<?php
//show all errors while we debug
error_reporting(E_ALL);
ini_set('display_errors', 1);


require "OAuth_1a.php";

$s = new OAuth_1a("consumer","consumer_secret","HMAC-SHA1","AUTHORIZATION");

$s->setToken('oauth_token', 'oauth_token_secret');

$result = $s->fetch("http://api.shapeways.com/models/3629769/info/v1", null, "GET", array("Accept" => "application/json"));

print $s->getLastResponse();
