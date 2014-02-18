<?php

require_once ROOTPATH.'/db.class.php';

class Model{
  public $db = null;
  public function __construct(){
    global $dbconfig;
    $this->db = new Dbstuff($dbconfig);
    $this->db->connect($dbconfig['dbhost'], $dbconfig['dbuser'], $dbconfig['dbpw'], $dbconfig['dbname']);
  }
  /**
   * mid  message id
   * uid  comment uid
   */
  public function getCommentUidByid($mid,$uid){
    if( !$mid || !$uid){
       return false;
    }
    $sql = sprintf("SELECT `id` FROM `robotreply` WHERE `mid`=%d AND `uid`=%d LIMIT 1", $mid, $uid);
    $row = $this->db->fetch_first($sql);
    return isset($row['id']) ? $row['id'] : 0;
  }
  public function addCommentUid($data = array()){
    if( !isset($data['mid'])){
      return false;
    }
    $sql = $this->db->insert_string('robotreply', $data);
    $this->db->query($sql);
    return $this->db->insert_id();
  }
}

function check_message_reply($message){
  return 1;
}

?>
