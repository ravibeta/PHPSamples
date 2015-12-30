<?php

/**
 * Yii CURL Component
 *
 * Work with remote servers via cURL much easier than using the native PHP bindings.
 *
 * */
class CURL extends CComponent {

    private $response = '';    // Contains the cURL response for debug
    private $session;     // Contains the cURL handler for a session
    private $url;      // URL of the session
    private $options = array(); // Populates curl_setopt_array
    private $headers = array(); // Populates extra HTTP headers
    public $error_code;   // Error code returned as an int
    public $error_string;    // Error message returned as a string
    public $info;      // Returned after request (elapsed time, etc)

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

    function __construct($url = '') {
        Yii::log('debug', 'cURL Class Initialized');

        if (!$this->is_enabled()) {
            Yii::log('error', 'cURL Class - PHP was not built with cURL enabled. Rebuild PHP with --with-curl to use cURL.');
        }

        $url AND $this->create($url);
    }

    function __call($method, $arguments) {
        if (in_array($method, array('simple_get', 'simple_post', 'simple_put', 'simple_delete'))) {
            // Take off the "simple_" and past get/post/put/delete to _simple_call
            $verb = str_replace('simple_', '', $method);
            array_unshift($arguments, $verb);
            return call_user_func_array(array($this, '_simple_call'), $arguments);
        }
    }

    /* =================================================================================
     * SIMPLE METHODS
     * Using these methods you can make a quick and easy cURL call with one line.
     * ================================================================================= */

    public function _simple_call($method, $url, $params = array(), $options = array()) {
        // Get acts differently, as it doesnt accept parameters in the same way
        if ($method === 'get') {
            // If a URL is provided, create new session
            $this->create($url . ($params ? '?' . http_build_query($params) : ''));
        } else {
            // If a URL is provided, create new session
            $this->create($url);

            $this->{$method}($params);
        }

        // Add in the specific options provided
        $this->options($options);

        return $this->execute();
    }

    public function simple_ftp_get($url, $file_path, $username = '', $password = '') {
        // If there is no ftp:// or any protocol entered, add ftp://
        if (!preg_match('!^(ftp|sftp)://! i', $url)) {
            $url = 'ftp://' . $url;
        }

        // Use an FTP login
        if ($username != '') {
            $auth_string = $username;

            if ($password != '') {
                $auth_string .= ':' . $password;
            }

            // Add the user auth string after the protocol
            $url = str_replace('://', '://' . $auth_string . '@', $url);
        }

        // Add the filepath
        $url .= $file_path;

        $this->option(CURLOPT_BINARYTRANSFER, TRUE);
        $this->option(CURLOPT_VERBOSE, TRUE);

        return $this->execute();
    }

    /* =================================================================================
     * ADVANCED METHODS
     * Use these methods to build up more complex queries
     * ================================================================================= */

    public function post($params = array(), $options = array()) {
        // If its an array (instead of a query string) then format it correctly
        if (is_array($params)) {
            $params = http_build_query($params, NULL, '&');
        }

        // Add in the specific options provided
        $this->options($options);

        $this->http_method('post');

        $this->option(CURLOPT_POST, TRUE);
        $this->option(CURLOPT_POSTFIELDS, $params);
    }

    public function put($params = array(), $options = array()) {
        // If its an array (instead of a query string) then format it correctly
        if (is_array($params)) {
            $params = http_build_query($params, NULL, '&');
        }

        // Add in the specific options provided
        $this->options($options);

        $this->http_method('put');
        $this->option(CURLOPT_POSTFIELDS, $params);

        // Override method, I think this overrides $_POST with PUT data but... we'll see eh?
        $this->option(CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: PUT'));
    }

    public function delete($params, $options = array()) {
        // If its an array (instead of a query string) then format it correctly
        if (is_array($params)) {
            $params = http_build_query($params, NULL, '&');
        }

        // Add in the specific options provided
        $this->options($options);

        $this->http_method('delete');

        $this->option(CURLOPT_POSTFIELDS, $params);
    }

    public function set_cookies($params = array()) {
        if (is_array($params)) {
            $params = http_build_query($params, NULL, '&');
        }

        $this->option(CURLOPT_COOKIE, $params);
        return $this;
    }

    public function http_header($header, $content = NULL) {
        $this->headers[] = $content ? $header . ': ' . $content : $header;
    }

    public function http_method($method) {
        $this->options[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
        return $this;
    }

    public function http_login($username = '', $password = '', $type = 'any') {
        $this->option(CURLOPT_HTTPAUTH, constant('CURLAUTH_' . strtoupper($type)));
        $this->option(CURLOPT_USERPWD, $username . ':' . $password);
        return $this;
    }

    public function proxy($url = '', $port = 80) {
        $this->option(CURLOPT_HTTPPROXYTUNNEL, TRUE);
        $this->option(CURLOPT_PROXY, $url . ':' . $port);
        return $this;
    }

    public function proxy_login($username = '', $password = '') {
        $this->option(CURLOPT_PROXYUSERPWD, $username . ':' . $password);
        return $this;
    }

    public function ssl($verify_peer = TRUE, $verify_host = 2, $path_to_cert = NULL) {
        if ($verify_peer) {
            $this->option(CURLOPT_SSL_VERIFYPEER, TRUE);
            $this->option(CURLOPT_SSL_VERIFYHOST, $verify_host);
            $this->option(CURLOPT_CAINFO, $path_to_cert);
        } else {
            $this->option(CURLOPT_SSL_VERIFYPEER, FALSE);
            $this->option(CURLOPT_SSL_VERIFYHOST, 0);
        }
        return $this;
    }

    public function options($options = array()) {
        // Merge options in with the rest - done as array_merge() does not overwrite numeric keys
        foreach ($options as $option_code => $option_value) {
            $this->option($option_code, $option_value);
        }
        // Set all options provided
        curl_setopt_array($this->session, $this->options);

        return $this;
    }

    public function option($code, $value) {
        if (is_string($code) && !is_numeric($code)) {
            $code = constant('CURLOPT_' . strtoupper($code));
        }

        $this->options[$code] = $value;
        return $this;
    }

    // Start a session from a URL
    public function create($url) {
        // If no a protocol in URL, assume its a CI link
        if (!preg_match('!^\w+://! i', $url)) {
            $url = Yii::app()->baseUrl . '/' . ($url);
        }

        $this->url = $url;
        $this->session = curl_init($this->url);

        return $this;
    }

    // End a session and return the results
    public function execute() {
        // Set two default options, and merge any extra ones in
        if (!isset($this->options[CURLOPT_TIMEOUT])) {
            $this->options[CURLOPT_TIMEOUT] = 30;
        }
        if (!isset($this->options[CURLOPT_RETURNTRANSFER])) {
            $this->options[CURLOPT_RETURNTRANSFER] = TRUE;
        }
        if (!isset($this->options[CURLOPT_FAILONERROR])) {
            $this->options[CURLOPT_FAILONERROR] = TRUE;
        }

        // Only set follow location if not running securely
        if (!ini_get('safe_mode') && !ini_get('open_basedir')) {
            // Ok, follow location is not set already so lets set it to true
            if (!isset($this->options[CURLOPT_FOLLOWLOCATION])) {
                $this->options[CURLOPT_FOLLOWLOCATION] = TRUE;
            }
        }
        
        if (!empty($this->headers)) {
            $this->option(CURLOPT_HTTPHEADER, $this->headers);
        }

        $this->options();

        // Execute the request & and hide all output
        $this->response = curl_exec($this->session);
        $this->info = curl_getinfo($this->session);        
        // Request failed
        if ($this->response === FALSE) {
            $this->error_code = curl_errno($this->session);
            $this->error_string = curl_error($this->session);
            curl_close($this->session);
            $this->set_defaults();

            return FALSE;
        }

        // Request successful
        else {
            curl_close($this->session);
            $response = $this->response;
            $this->set_defaults();
            return $response;
        }
    }

    public function is_enabled() {
        return function_exists('curl_init');
    }

    public function debug() {
        $str = "=============================================\nCURL Test:\n=============================================\n".
		"Response:\n".nl2br($this->response) . "\n\n";

        if ($this->error_string) {
	    $str .= "=============================================\nErrors:".
		    "Code: " . $this->error_code . "\nMessage: " . $this->error_string . "\n";
        }

        $str .= "=============================================\nInfo:\n";
	$print = print_r($this->info, true);
	$str .= $print;
	return $str;
    }

    public function debug_request() {
        return array(
            'url' => $this->url
        );
    }

    private function set_defaults() {
        $this->response = '';
        $this->headers = array();
        $this->options = array();
        $this->error_code = NULL;
        $this->error_string = '';
        $this->session = NULL;
    }
    
    public static function multiCall($urls, $credentials=array(), $sslOptions=array()) {
        $res = array();
        // Create get requests for each URL
        $curlOptions = array(
            CURLOPT_RETURNTRANSFER => 1,
            //CURLOPT_TIMEOUT => 8,
        );
        if (isset($credentials['username']) && isset($credentials['password'])) {
            $curlOptions[CURLOPT_HTTPAUTH] = empty($credentials['auth']) ? CURLAUTH_ANY : $credentials['auth'];
            $curlOptions[CURLOPT_USERPWD] = $credentials['username'] . ':' . $credentials['password'];
        }
        if (empty($sslOptions)) {
            $curlOptions[CURLOPT_SSL_VERIFYPEER] = FALSE;
            $curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;
        }
        else if(isset($sslOptions['verify_host']) && isset($sslOptions['path_to_cert'])) {
            $curlOptions[CURLOPT_SSL_VERIFYPEER] = TRUE;
            $curlOptions[CURLOPT_SSL_VERIFYHOST] = $sslOptions['verify_host'];
            $curlOptions[CURLOPT_SSL_VERIFYHOST] = $sslOptions['path_to_cert'];
        }

        $mh = curl_multi_init();
        foreach ($urls as $i => $url) {
            $ch = curl_init();
            $curlOptions[CURLOPT_URL] = $url;
            curl_setopt_array($ch,$curlOptions);
            curl_multi_add_handle($mh, $ch);
        }
        //CPU FIX

        do {
        	while(($execReturnValue = curl_multi_exec($mh, $runningHandles)) == CURLM_CALL_MULTI_PERFORM);
        	if($execReturnValue != CURLM_OK)
            	break;
        	// a request was just completed -- find out which one
		while($done = curl_multi_info_read($mh)) {
			$info = curl_getinfo($done['handle']);
			if ($info['http_code'] == 200)  {
				$output = curl_multi_getcontent($done['handle']);
				// request successful.  process output using the callback function.
				$res[] = $output;
				/* For more then 10 requests
				// start a new request (it's important to do this before removing the old one)
				$ch = curl_init();
				$options[CURLOPT_URL] = $urls[$i++];  // increment i
				curl_setopt_array($ch, $curlOptions);
				curl_multi_add_handle($mh, $ch);*/
				// remove the curl handle that just completed
				curl_multi_remove_handle($mh, $done['handle']);
			} else {
				// request failed.  add error handling.
			}
		}
	    } while ($runningHandles);

	/*// Start performing the request
        do {
            $execReturnValue = curl_multi_exec($mh, $runningHandles);
        } while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);
        // Loop and continue processing the request
        while ($runningHandles && $execReturnValue == CURLM_OK) {
            // Wait forever for network
            $numberReady = curl_multi_select($mh);
            if ($numberReady != -1) {
                // Pull in any new data, or at least handle timeouts
                do {
                    $execReturnValue = curl_multi_exec($mh, $runningHandles);
                } while ($execReturnValue == CURLM_CALL_MULTI_PERFORM);
            }
        }

        // Check for any errors
        if ($execReturnValue != CURLM_OK) {
            return "Curl multi read error $execReturnValue\n";
        }

        // Extract the content
        foreach ($urls as $i => $url) {
            // Check for errors
            $curlError = curl_error($ch[$i]);
            if ($curlError == "") {
                $res[$i] = curl_multi_getcontent($ch[$i]);
            } else {
                return "Curl error on handle $i: $curlError\n";
            }
            // Remove and close the handle
            curl_multi_remove_handle($mh, $ch[$i]);
            curl_close($ch[$i]);
        }*/
        // Clean up the curl_multi handle
        curl_multi_close($mh);
        //echo '<pre>';        print_r($res);exit;
    	return $res;
    }

    public function patch($params = array(), $options = array()) {
        // If its an array (instead of a query string) then format it correctly
        if (is_array($params)) {
            $params = http_build_query($params, NULL, '&');
        }

        // Add in the specific options provided
        $this->options($options);

        $this->http_method('patch');

        $this->option(CURLOPT_POST, TRUE);
        $this->option(CURLOPT_POSTFIELDS, $params);
    }
}
