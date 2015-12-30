<?php

/**
 * Yii RESTClient Components
 *
 * Make REST requests to RESTful services with simple syntax.
 *
 */
class RESTClient extends CComponent {

    public $supported_formats = array(
        'xml' => 'application/xml',
        'json' => 'application/json',
        'serialize' => 'application/vnd.php.serialized',
        'php' => 'text/plain',
        'csv' => 'text/csv'
    );
    public $auto_detect_formats = array(
        'application/xml' => 'xml',
        'text/xml' => 'xml',
        'application/json' => 'json',
        'text/json' => 'json',
        'text/csv' => 'csv',
        'application/csv' => 'csv',
        'application/vnd.php.serialized' => 'serialize'
    );
    private $rest_server;
    private $format;
    private $mime_type;
    private $http_auth = null;
    private $http_user = null;
    private $http_pass = null;
    private $response_string;
    private $_curl;
    private $_headers = array();
    private $_sslOptions = array();

    /**
     * Logs a message.
     *
     * @param string $message Message to be logged
     * @param string $level Level of the message (e.g. 'trace', 'warning',
     * 'error', 'info', see CLogger constants definitions)
     */
    public static function log($message, $level = 'error') {
        Yii::log($message, $level, __CLASS__);
    }

    /**
     * Dumps a variable or the object itself in terms of a string.
     *
     * @param mixed variable to be dumped
     */
    protected function dump($var = 'dump-the-object', $highlight = true) {
        if ($var === 'dump-the-object') {
            return CVarDumper::dumpAsString($this, $depth = 15, $highlight);
        } else {
            return CVarDumper::dumpAsString($var, $depth = 15, $highlight);
        }
    }

    function __construct($config = array()) {
        Yii::log('REST Class Initialized');
        $this->_curl = new CURL();
        empty($config) OR $this->initialize($config);
    }

    function __destruct() {
        $this->_curl->set_default();
    }

    public function initialize($config) {
        $this->rest_server = @$config['server'];

        if (substr($this->rest_server, -1, 1) != '/') {
            $this->rest_server .= '/';
        }

        isset($config['http_auth']) && $this->http_auth = $config['http_auth'];
        isset($config['http_user']) && $this->http_user = $config['http_user'];
        isset($config['http_pass']) && $this->http_pass = $config['http_pass'];
        isset($config['ssl_options']) && $this->_sslOptions = $config['ssl_options'];
    }

    public function get($uri, $params = array(), $format = NULL) {
        if ($params) {
            $uri .= '?' . (is_array($params) ? http_build_query($params) : $params);
        }
        return $this->_call('get', $uri, NULL, $format);
    }

    public function post($uri, $params = array(), $format = NULL) {
        return $this->_call('post', $uri, $params, $format);
    }

    public function put($uri, $params = array(), $format = NULL) {
        return $this->_call('put', $uri, $params, $format);
    }

    public function patch($uri, $params = array(), $format = NULL) {
        return $this->_call('patch', $uri, $params, $format);
    }

    public function delete($uri, $params = array(), $format = NULL) {
        return $this->_call('delete', $uri, $params, $format);
    }

    public function api_key($key, $name = 'X-API-KEY') {
        $this->_curl->http_header($name, $key);
    }

    public function set_header($name, $value) {
        $this->_headers[$name] = $value;
    }

    public function reset_header() {
        $this->_headers = array();
    }

    public function language($lang) {
        if (is_array($lang)) {
            $lang = implode(', ', $lang);
        }

        $this->_curl->http_header('Accept-Language', $lang);
    }

    private function _call($method, $uri, $params = array(), $format = NULL) {
        $this->_set_headers();

        // Initialize cURL session
        $this->_curl->create($this->rest_server . $uri);

        // If authentication is enabled use it
        if ($this->http_auth != '' && $this->http_user != '') {
            $this->_curl->http_login($this->http_user, $this->http_pass, $this->http_auth);
        }
        
        if (empty($this->_sslOptions)) {
            $this->_curl->ssl(false);
        }
        else if(isset($this->_sslOptions['verify_host']) && isset($this->_sslOptions['path_to_cert'])) {
            $this->_curl->ssl(true, $this->_sslOptions['verify_host'], $this->_sslOptions['path_to_cert']);
        }

        // We still want the response even if there is an error code over 400
        $this->_curl->option('failonerror', FALSE);

        // Call the correct method with parameters
        $this->_curl->{$method}($params);

        // Execute and return the response from the REST server
        $response = $this->_curl->execute();

        // Format and return
        if ($format !== NULL) {
            $this->format($format);
            return $this->_format_response($response);
        } else
            return $response;
    }

    // If a type is passed in that is not supported, use it as a mime type
    public function format($format) {
        if (array_key_exists($format, $this->supported_formats)) {
            $this->format = $format;
            $this->mime_type = $this->supported_formats[$format];
        } else {
            $this->mime_type = $format;
        }

        return $this;
    }

    public function debug() {
        $debug = $this->_curl->debug();
        $request = $this->_curl->debug_request();

        $str = "=============================================\nREST Test:\n=============================================\n".
		"Request:\n".$request['url'] . "\n=============================================\nResponse:\n";

        if ($this->response_string) {
            $str .= nl2br($this->response_string) . "\n\n";
        } else {
            $str .= "No response\n\n";
        }

        $str .= "=============================================\n";

        if ($this->_curl->error_string) {
            $str .= "Errors:Code:" . $this->_curl->error_code . "\nMessage:" . $this->_curl->error_string . "\n=============================================\n";
        }

        $str .= "Call details\n";
        $print = print_r($this->_curl->info, true);
        $str .= $print;
	self::log($debug.$str);	
    }

    // Return HTTP status code
    public function status() {
        return $this->info('http_code');
    }

    // Return curl info by specified key, or whole array
    public function info($key = null) {
        return $key === null ? $this->_curl->info : @$this->_curl->info[$key];
    }

    // Set custom options
    public function option($code, $value) {
        $this->_curl->option($code, $value);
    }

    private function _set_headers() {
        if (!array_key_exists("Accept", $this->_headers))
            $this->set_header("Accept", $this->mime_type);
        foreach ($this->_headers as $k => $v) {
            $this->_curl->http_header(sprintf("%s: %s", $k, $v));
        }
    }

    private function _format_response($response) {
        $this->response_string = & $response;

        // It is a supported format, so just run its formatting method
        if (array_key_exists($this->format, $this->supported_formats)) {
            return $this->{"_" . $this->format}($response);
        }

        // Find out what format the data was returned in
        $returned_mime = @$this->_curl->info['content_type'];

        // If they sent through more than just mime, stip it off
        if (strpos($returned_mime, ';')) {
            list($returned_mime) = explode(';', $returned_mime);
        }

        $returned_mime = trim($returned_mime);

        if (array_key_exists($returned_mime, $this->auto_detect_formats)) {
            return $this->{'_' . $this->auto_detect_formats[$returned_mime]}($response);
        }

        return $response;
    }

    // Format XML for output
    private function _xml($string) {
        return $string ? (array) simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA) : array();
    }

    // Format HTML for output
    // This function is DODGY! Not perfect CSV support but works with my REST_Controller
    private function _csv($string) {
        $data = array();

        // Splits
        $rows = explode("\n", trim($string));
        $headings = explode(',', array_shift($rows));
        foreach ($rows as $row) {
            // The substr removes " from start and end
            $data_fields = explode('","', trim(substr($row, 1, -1)));

            if (count($data_fields) == count($headings)) {
                $data[] = array_combine($headings, $data_fields);
            }
        }

        return $data;
    }

    // Encode as JSON
    private function _json($string) {
        return json_decode(trim($string));
    }

    // Encode as Serialized array
    private function _serialize($string) {
        return unserialize(trim($string));
    }
    
    public static function multiCall($urls, $credentials=array(), $sslOptions=array()) {
        return CURL::multiCall($urls, $credentials, $sslOptions);        
    }        

}
