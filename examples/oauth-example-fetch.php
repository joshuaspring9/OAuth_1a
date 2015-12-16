<?php
/**
* oauth-example-fetch
*
* Demonstrate the use of the fetch() function from the OAuth_1a class
*
* @package OAuth_1a
* @author Joshua Zeitlinger <me@joshuazeitlinger.com>
* @link  https://github.com/joshuaspring9/Oauth_1a
* @version  0.2
*
*
* History:
* version 0.1.1 - file created
* version 0.2 - add try/catch block for exceptions
*
*/

//show all errors while we debug
error_reporting(E_ALL);
ini_set('display_errors', 1);


require "../OAuth_1a.php";

try {

  $s = new OAuth_1a("consumer","consumer_secret","HMAC-SHA1","AUTHORIZATION");

  $s->setToken('oauth_token', 'oauth_token_secret');

  $result = $s->fetch("http://api.shapeways.com/models/3629769/info/v1", null, "GET", array("Accept" => "application/json"));

  print $s->getLastResponse();

} catch (OAuth_1a_Exception $e){

  print_r($e->getAll());

}
