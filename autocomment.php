<?php

define('ROOTPATH', dirname(__FILE__).'/');
//echo ROOTPATH;exit;
require_once ROOTPATH.'src/facebook.php';
require_once ROOTPATH.'config.php';

$dyfile = ROOTPATH.'dynamic_config.php';
if( !file_exists($dyfile)){
  die("\n===== $dyfile is not exists! Please you first exec authorize.php file to authorize! =====\n");
}
require_once $dyfile;


// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook($app_config);

if( !isset($dynamic_config['access_token'])){
   die("\n===== access_token invalid! ======\n");
}

$facebook->setAccessToken($dynamic_config['access_token']);

$page_comment_list = $facebook->api('/'.$static_config['page_id'].'/feed');



