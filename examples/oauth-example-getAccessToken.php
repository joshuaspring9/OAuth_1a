<?php
/**
* oauth-example-getAccessToken
*
* Demonstrate the use of the getAccessToken() function from the OAuth_1a class
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

session_start();

require "../OAuth_1a.php";

try{

  $s = new OAuth_1a("consumer","consumer_secret","HMAC-SHA1","AUTHORIZATION");

  $s->setToken($_GET['oauth_token'], $_SESSION['secret']);

  $result = $s->getAccessToken("http://api.shapeways.com/oauth1/access_token/v1",$_GET['oauth_verifier'], "GET");

  $data = $s->getLastResponse();

  print_r($data);

} catch (OAuth_1a_Exception $e){

  print_r($e->getAll());

}
