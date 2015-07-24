<?php
//show all errors while we debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require "OAuth_1a.php";

$s = new OAuth_1a("consumer","consumer_sercret","HMAC-SHA1", "AUTHORIZATION");

$result = $s->getRequestToken("http://api.shapeways.com/oauth1/request_token/v1", "http://yoursite.com/curl-example-geToken.php", "GET");

$data = $s->getLastResponse();

$_SESSION['secret'] = $data['oauth_token_secret'];


header("Location: ".$data['authentication_url']);