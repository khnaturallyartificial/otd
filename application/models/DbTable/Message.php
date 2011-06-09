<?php
class Application_Model_DbTable_Message extends Zend_Db_Table_Abstract{
    protected $_name = "message";
    
    function newMessages(){
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()){
	    $id = $auth->getIdentity();
	    $userid = $id->userid;
	    $query ="SELECT COUNT(*) AS nummsg FROM `".$this->_name."` WHERE `read` = 0 AND `to_id` = ".$userid;
	    $result = $this->_db->fetchRow($query);
                        $this->_db->closeConnection();
	    return $result["nummsg"];
	}
    }
}
?>