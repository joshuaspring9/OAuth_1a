<?php
/**
* OAuth_1a
* 
* A PHP Library to interact with OAuth version 1.0a protected resources following
* PHP's PECL OAuth method of functioning as closely as possible
*
* @package OAuth_1a
* @author Joshua Zeitlinger <me@joshuazeitlinger.com>
* @link  https://github.com/joshuaspring9/Oauth_1a
* @version  0.1.1
*
*
* History:
* version 0.1 - first version
* version 0.1.1 - put OAuth_1a_Exception class in a separate file
*
*/

//import the custom exceptions class
require "OAuth_1a_Exception.php";

class OAuth_1a  {

	private $consumer_key;
	private $consumer_secret;
	private $oauth_token;
	private $oauth_token_secret;
	private $signature_method;
	private $auth_method;
	private $last_response;
	private $last_response_debug;
	
	
	public function __construct($consumer_key_public, $consumer_key_secret, $sig_method, $authentication_method)
	{
		$this->consumer_key = $consumer_key_public;
		$this->consumer_secret = $consumer_key_secret;
		$this->signature_method = $sig_method;
		$this->auth_method = $authentication_method;
		$this->last_response_debug = array();
		
	}
 
	
	public function getRequestToken($url, $callback, $type)
	{
		$time = time();
		$nonce = mt_rand();
		$oauth = array (
						"oauth_callback" => $callback,
						"oauth_consumer_key" => $this->consumer_key,
						"oauth_nonce" => mt_rand(),
						"oauth_signature_method" => $this->signature_method,
						"oauth_timestamp" => time(),
						"oauth_version" => "1.0", 
						);
		

		$curl = curl_init();
		
		if(strtoupper($this->auth_method) == "QUERY")
			$url = $this->_setupHeader($url, $oauth, null, false, $type);
		else
			curl_setopt($curl, CURLOPT_HTTPHEADER, array($this->_setupHeader($url, $oauth, null, true, $type)));
		
		curl_setopt($curl, CURLOPT_URL, $url);					
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_HEADER, 1);

		$type = strtoupper($type);
		
		switch ($type)
		{
			case 'POST'  :
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
			case 'GET':
				break;
			default:
				throw new OAuth_1a_Exception("The verb $type is not a RESTful verb or not supported", 4, false, false, false, false);

		}

		$response = curl_exec($curl);
		$response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$response_header = substr($response, 0, $header_size);
		$response_body = substr($response, $header_size);

		if ($response_code != 200)
		{
			throw new OAuth_1a_Exception("An error occurred while fetching the access token", 2, $response_header, $response_body, $response_code, $header_size);
		}

		// Uncomment next four lines to see/debug full cURL response
		// $curl_info = curl_getinfo($curl);
		// var_dump($curl_info);
		// var_dump($response_header);
		// var_dump($response_body);
		
		if(!$response)
			throw new OAuth_1a_Exception("Connection to the server failed", 0, false, false, false, false);
		
		//store these for debugging purposes
		$this->last_response_debug["code"] = $response_code;
		$this->last_response_debug["headers"] = $response_header;
		$this->last_response_debug["body"] = $response_code;
		
		$data = array();
		
		parse_str($response_body, $data);
		
		$this->last_response = $data;
		
		return true;
		
	}
	
	public function setToken($token, $secret)
	{
		$this->oauth_token = $token;
		$this->oauth_token_secret = $secret;
	}
	
	
	public function getAccessToken($url, $oauth_verifier, $type)
	{
		$time = time();
		$nonce = mt_rand();
		$oauth = array (
						"oauth_consumer_key" => $this->consumer_key,
						"oauth_nonce" => mt_rand(),
						"oauth_signature_method" => $this->signature_method,
						"oauth_timestamp" => time(),
						"oauth_token" => $this->oauth_token,
						"oauth_verifier" => $oauth_verifier,
						"oauth_version" => "1.0", 
						);

		$curl = curl_init();
		
		if(strtoupper($this->auth_method) == "QUERY")
			$url = $this->_setupHeader($url, $oauth, null, false, $type);
		else
			curl_setopt($curl, CURLOPT_HTTPHEADER, array($this->_setupHeader($url, $oauth, null, true, $type)));
		
		curl_setopt($curl, CURLOPT_URL, $url);					
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_HEADER, 1);

		$type = strtoupper($type);
		
		switch ($type)
		{
			case 'POST'  :
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
			case 'GET':
				break;
			default:
				throw new OAuth_1a_Exception("The verb $type is not a RESTful verb or not supported", 4, false, false, false, false);
		
		}

		$response = curl_exec($curl);
		$response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$response_header = substr($response, 0, $header_size);
		$response_body = substr($response, $header_size);

		
		if ($response_code != 200)
		{
			throw new OAuth_1a_Exception("An error occurred while fetching the access token", 2, $response_header, $response_body, $response_code, $header_size);
		}

		

		// Uncomment next four lines to see/debug full cURL response
		// $curl_info = curl_getinfo($curl);
		// var_dump($curl_info);
		// var_dump($response_header);
		// var_dump($response_body);
		
		if(!$response)
			throw new OAuth_1a_Exception("Connection to the server failed", 0, false, false, false, false);
		
		
		//store these for debugging purposes
		$this->last_response_debug["code"] = $response_code;
		$this->last_response_debug["headers"] = $response_header;
		$this->last_response_debug["body"] = $response_code;
		
		$data = array();
		
		parse_str($response_body, $data);
		
		$this->last_response = $data;
		
		return true;
		
	}
	
	public function fetch($url, $extra_parameters, $type, $headers = null)
	{
		$time = time();
		$nonce = mt_rand();
		$oauth = array (
						"oauth_consumer_key" => $this->consumer_key,
						"oauth_nonce" => mt_rand(),
						"oauth_signature_method" => $this->signature_method,
						"oauth_timestamp" => time(),
						"oauth_token" => $this->oauth_token,
						"oauth_version" => "1.0", 
						);
		
		if($extra_parameters != null)
			$with_extras = array_merge($oauth, $extra_parameters);
		else
			$with_extras = $oauth;
		$curl = curl_init();
		
		
		if(strtoupper($this->auth_method) == "QUERY")
			$url = $this->_setupHeader($url, $oauth, $with_extras, false, $type);
		else
		{
			$heads = array($this->_setupHeader($url, $oauth, $with_extras, true, $type));
			if($headers != null)
				$heads = array_merge($heads, $headers);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $heads);
		}
		
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($curl, CURLOPT_URL, $url);					
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_HEADER, 1);

		$type = strtoupper($type);
		
		switch ($type)
		{
			case 'POST'  :
			case 'PUT' :
			case 'PATCH' :
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $extra_parameters);
				break;
			case 'DELETE':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
				break;
			case 'GET':
				break;
			default:
				throw new OAuth_1a_Exception("The verb $type is not a RESTful verb or not supported", 4, false, false, false, false);

		}

		$response = curl_exec($curl);
		$response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		$response_header = substr($response, 0, $header_size);
		$response_body = substr($response, $header_size);

		if ($response_code != 200)
		{
			throw new OAuth_1a_Exception("An error occurred while fetching the access token", 2, $response_header, $response_body, $response_code, $header_size);
		}

		// Uncomment next four lines to see/debug full cURL response
		// $curl_info = curl_getinfo($curl);
		// var_dump($curl_info);
		// var_dump($response_header);
		// var_dump($response_body);
		
		if(!$response)
			throw new OAuth_1a_Exception("Connection to the server failed", 0, false, false, false, false);
		
		//store these for debugging purposes
		$this->last_response_debug["code"] = $response_code;
		$this->last_response_debug["headers"] = $response_header;
		$this->last_response_debug["body"] = $response_code;
		
		$this->last_response = $response_body;
		
		return true;
		
		
	}
	
	public function getLastResponse()
	{
		return $this->last_response;
	}
	
	public function getLastResponseDebug()
	{
		return $this->last_response_debug;
	}
	
	private function _get_base($url, $method, $params) {
		$holder = array();
		ksort($params);
		foreach($params as $key=>$value){
			$holder[] = "$key=" . rawurlencode($value);
		}
		return $method ."&". rawurlencode($url) ."&". rawurlencode(implode("&", $holder));
	}
	
	private function _setupHeader($url, $oauth, $with_extras, $auth_type, $type)
	{
		if(empty($this->oauth_token_secret))
			$this->oauth_token_secret = null;
		if($this->signature_method == "HMAC-SHA1")
		{
			if($with_extras == null)
				$with_extras = $oauth;
			$base = $this->_get_base($url, $type, $with_extras);
			$key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->oauth_token_secret);
			$oauth_signature = base64_encode(hash_hmac('sha1', $base, $key, true));
			$oauth['oauth_signature'] = $oauth_signature;
		} else if($this->signature_method == "PLAINTEXT")
		{
			$oauth['oauth_signature'] = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->oauth_token_secret);
		} else
		{
			throw new OAuth_1a_Exception("The signature method ".$this->signature_method." is currently not supported", 3, false, false, false, false);
		}
		
		if($auth_type)
		{
			$oauthString = "Authorization: OAuth   " ;
			foreach($oauth as $key=>$value) {
				$stringKey = rawurlencode($key);
				$stringValue = rawurlencode($value);
				$oauthString .= "$stringKey=\"$stringValue\", ";
			}
			
			$oauthString = rtrim($oauthString,", ");

		}
		else
		{
			$oauthString = "";
			foreach($oauth as $key=>$value) 
			{
				$stringKey = rawurlencode($key);
				$stringValue = rawurlencode($value);
				if(strlen($oauthString) == 0)
					$oauthString .= "?";
				else
					$oauthString .= "&";
				$oauthString .= "$stringKey=$stringValue";
			}
			$oauthString = $url.$oauthString;
		}

		return $oauthString;
		
	}
}