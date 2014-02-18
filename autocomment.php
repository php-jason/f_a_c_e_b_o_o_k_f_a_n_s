<?php

define('ROOTPATH', dirname(__FILE__).'/');
//echo ROOTPATH;exit;
require_once ROOTPATH.'src/facebook.php';
require_once ROOTPATH.'config.php';
require_once ROOTPATH.'model.php';

$dyfile = ROOTPATH.'dynamic_config.php';
if( !file_exists($dyfile)){
  die("\n===== $dyfile is not exists! Please you first exec authorize.php file to authorize! =====\n");
}
require_once $dyfile;


// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook($app_config);
$model = new Model();

if( !isset($dynamic_config['access_token'])){
   die("\n===== access_token invalid! ======\n");
}

$facebook->setAccessToken($dynamic_config['access_token']);

$page_comment_list = $facebook->api('/'.$static_config['page_id'].'/feed');

foreach($page_comment_list as $comment_array){
  foreach($comment_array as $comment_list){
    if( !isset($comment_list['comments'])){
       echo "Info ID $comment_list[id] not have comment!\n";continue;
    }
    if( !isset($comment_list['comments']['data'])){
       continue;
    }
    foreach($comment_list['comments']['data'] as $comment){
       // check message
       $status = check_message_reply($comment['message']);
       if( !$status){
          echo "\n== ==\n";continue;
       }
       //  filter some userid example admin uid
       $uid = $comment['from']['id'];
       if(in_array($uid, $filterUser)){
          continue;
       } 
       // add reply
       $mid = $comment_list['id'];
       $id = $model->getCommentUidByid($mid,$uid);
       if($id){
         echo "\n== Message id:$mid Uid:$uid reply ==\n";continue;
       }
       $status = $facebook->api("/$comment[id]/comments",
       "POST",
       array (
        'message' => $reply_message[$status],
      ));
      // add comment_id   info_id + comment_id or info_id + userid
      $id = $model->addCommentUid($data = array('mid'=> $mid,'uid'=> $uid));
      echo "\n +++++ robot reply id:$id Message id:$mid ++++\n";
    }
  }
}

