<?php
function cURLcheckBasicFunctions() 
{ 
  if( !function_exists("curl_init") && 
      !function_exists("curl_setopt") && 
      !function_exists("curl_exec") && 
      !function_exists("curl_close") ) return false; 
  else return true; 
} 

// declare
if( !cURLcheckBasicFunctions() ) print_r('UNAVAILABLE: cURL Basic Functions');
$apikey = 'your_api_key_here';
$clientId = 'your_client_id_here';
$clientSecret = 'your_client_secret_here';
$url = 'https://test.openapi.starbucks.com/v1/oauth/token?api_key='.$apikey;
$ch = curl_init($url);
$fields = array(
'grant_type' => urlencode('client_credentials'),
'client_id' => urlencode($clientId),
'client_secret' => urlencode($clientSecret),
'scope' => urlencode('test_scope'),
'state' => urlencode('some_state'));

//url-ify the data for the POST
$fields_string = '';
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');	
curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
// curl_setopt($ch, CURLOPT_USERPWD, $credentials);
// curl_setopt($ch, CURLOPT_SSLVERSION, 3); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);			
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

//execute post
if(curl_exec($ch) === false)
{
    echo 'Curl error: ' . curl_error($ch);
}
else
{
    echo 'Operation completed without any errors';
}

//close connection
curl_close($ch);
?> 
