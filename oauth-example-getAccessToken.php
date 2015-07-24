<?php
//show all errors while we debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require "OAuth_1a.php";

$s = new OAuth_1a("consumer","consumer_secret","HMAC-SHA1","AUTHORIZATION");

$s->setToken($_GET['oauth_token'], $_SESSION['secret']);

$result = $s->getAccessToken("http://api.shapeways.com/oauth1/access_token/v1",$_GET['oauth_verifier'], "GET");

$data = $s->getLastResponse();

print_r($data);
