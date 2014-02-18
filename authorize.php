<?php
ini_set('error_display',1);
error_reporting(E_ALL);
 session_start();
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
  if($_REQUEST['state'] == $_SESSION['state']){
    $access_token = $facebook->getAccessTokenFromCode($code, $redirect_uri = null);
    $access_token || exit('code is invalid!');
    //save token
    $contents = "<?php\r\n\$dynamic_config=array('access_token'=>'$access_token');\r\n?>";
    file_put_contents($dyfile, $contents);
    chmod($dyfile,0777);
  }
  header('Location: '.$_SERVER["PHP_SELF"]);
}else{
  $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
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
  $loginOutUrl = $facebook->getLogoutUrl();
  echo '<a href="'.$loginOutUrl.'">退出</a> | ';
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.

$loginUrl = sprintf('https://www.facebook.com/dialog/oauth?client_id=%s&redirect_uri=%s&scope=%s&state=%s',$app_config['appId'],urlencode($static_config['redirect_uri']),$static_config['scope'],$_SESSION['state']);



?>


<a href="<?php echo $loginUrl;?>">開始授權</a>

<?php
echo "<pre>";
var_dump($user);
echo "<br />access_token: <br />";
var_dump($dynamic_config);

try {
//$page_comment_list = $facebook->api('/'.$static_config['page_id'].'/feed');
//$page_comment_list = $facebook->api('/me/inbox');
$page_comment_list = $facebook->api('/'.$static_config['page_id'].'/conversations');
//$page_comment_list = $facebook->api('/me?fields=id,name,inbox.limit(10)');
//$page_comment_list = $facebook->api('/me/permissions');
    // Proceed knowing you have a logged in user who's authenticated.
  } catch (FacebookApiException $e) {
    var_dump($e);
    $user = null;
  }
var_dump($page_comment_list);
echo "<hr>1";
?>
