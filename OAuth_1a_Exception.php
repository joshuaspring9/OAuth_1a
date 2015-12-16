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
* @version  0.2
*
*
* History:
* version 0.1.1 - file added
* version 0.2 - added "$this->" for instance variables
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
		return $this->curl_body;
	}
	public function getLastHeaders()
	{
		return $this->curl_headers;
	}
	public function getLastCode()
	{
		return $this->curl_code;
	}
	public function getLastSize()
	{
		return $this->curl_size;
	}
	public function getAll()
	{
		return array(   "headers" => $this->curl_headers,
						"body" => $this->curl_body,
						"code" => $this->curl_code,
						"size" => $this->curl_size,
						"message" => $this->getMessage(),
					);
	}
	public function decodeResponse()
	{
		$this->json = json_decode($this->curl_body);
		return $this->json;
	}

}
