<?php

define('ROOTPATH', dirname(__FILE__).'/');
//echo ROOTPATH;exit;
require_once ROOTPATH.'src/facebook.php';
require_once ROOTPATH.'config.php';
require_once ROOTPATH.'function.php';

$dyfile = ROOTPATH.'dynamic_config.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook($app_config);

$code = isset($_GET['code']) ? $_GET['code']: '';
//var_dump($code);exit;
if($code){
  $access_token = $facebook->getAccessTokenFromCode($code, $redirect_uri = null);
  $access_token || exit('code is invalid!');
  //save token
  $contents = "<?php\r\n\$dynamic_config=array('access_token'=>'$access_token');\r\n?>";
  file_put_contents($dyfile, $contents);
  chmod($dyfile,0777);
}

$dynamic_config = array();
if( file_exists($dyfile)){
  require_once $dyfile;
}

if(isset($dynamic_config['access_token'])){
  $facebook->setAccessToken($dynamic_config['access_token']);
}

// Get User ID
$user = $facebook->getUser();


if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $statusUrl = $facebook->getLoginStatusUrl();
//  $loginUrl = $facebook->getLoginUrl();
}
  $loginUrl = sprintf('https://www.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&scope=%s',$app_config['appId'],urlencode($static_config['redirect_uri']),$static_config['scope']);



?>


<a href="<?php echo $loginUrl;?>">開始授權</a>

<?php
echo "<pre>";
var_dump($user);
echo "<br />";
var_dump($dynamic_config);
echo "<hr>page comment list:<br />";
/**/
$page_comment_list = $facebook->api('/'.$static_config['page_id'].'/feed');

foreach($page_comment_list as $comment_array){
  foreach($comment_array as $comment_list){
    if( !isset($comment_list['comments'])){
       echo "Info ID $comment_list[id] is not comment!\n";continue;
    }
    foreach($comment_list['comments']['data'] as $comment){
       // check message
       $status = check_message_reply($comment['message']);
       if( !$status){
          echo "\n== ==\n";continue;
       }
       //  filter some userid example admin uid
/*
       // add reply
       $status = $facebook->api("/$comment[id]/comments",
       "POST",
       array (
        'message' => $reply_message[$status],
      ));
      // add comment_id   info_id + comment_id or info_id + userid 
      var_dump($status);exit;
*/
    } 
    var_dump($comment_list);echo "<hr>|<hr>";
  }
  var_dump($comment_array);echo "<hr>";
}

var_dump($page_comment_list);


//$page_note_list = $facebook->api('/'.$static_config['page_id'].'/notes');
//$page_note_list = $facebook->api('/me/inbox');
//$page_note_list = $facebook->api('/'.$static_config['page_id'].'/inbox');
//$page_note_list = $facebook->api('/'.$static_config['page_id'].'/milestones');
//var_dump($page_note_list);

echo "<br /> platform";
/* get User info 动态
$platform = $facebook->api('/me/feed');
var_dump($platform);
*/
?>
