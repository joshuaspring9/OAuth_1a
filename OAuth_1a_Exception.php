<?php
/**
* OAuth_1a_Exception
* 
* A PHP class to extend the functionality of the regular Exception class by providing 
* OAuth specific diagnostic/debugging tools
*
* @package OAuth_1a_Exception
* @author Joshua Zeitlinger <me@joshuazeitlinger.com>
* @link  https://github.com/joshuaspring9/Oauth_1a
* @version  0.1.1
*
*
* History:
* version 0.1.1 - file added
*
*/


class OAuth_1a_Exception extends Exception
{
	private $curl_headers;
	private $curl_body;
	private $curl_code;
	private $curl_size;
	private $json;
	
	public function __construct($message, $code, $curl_headers, $curl_body, $curl_code, $curl_size)
	{
		parent::__construct($message, $code);
	
		$this->curl_headers = $curl_headers;
		$this->curl_body = $curl_body;
		$this->curl_code = $curl_code;
		$this->curl_size = $curl_size;
	}
	
	public function getLastResponse()
	{
		return $curl_body;
	}
	public function getLastHeaders()
	{
		return $curl_headers;
	}
	public function getLastCode()
	{
		return $curl_code;
	}
	public function getLastSize()
	{
		return $curl_size;
	}
	public function getAll()
	{
		return array(   "headers" => $curl_headers,
						"body" => $curl_body,
						"code" => $curl_code,
						"size" => $curl_size,
					);
	}
	public function decodeResponse()
	{
		$this->json = json_decode($curl_body);
		return $this->json;
	}
	
}