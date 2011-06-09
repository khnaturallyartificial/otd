<?php
class Application_Model_DbTable_User extends Zend_Db_Table_Abstract{
    protected $_name = 'user';
    
    function getTableName(){
	return $this->_name;
    }

    function getCoachEmail($coachid){
                   $coachrow = $this->_db->fetchRow("select email from ".$this->_name." where userid = ".$coachid);
                   return $coachrow["email"];

    }
    
    function isUnique($username){
	$select = $this->_db->select()->from($this->_name,'username')
			->where('username = ?', $username);
	$result = $this->_db->fetchAll($select);
                    $this->_db->closeConnection();
	if(count($result)!=0){
	    return false;
	}
	else{
	    return true;
	}
    }

    function getAllUsersForRem(){
                   $select = $this->_db->select()->from($this->_name,array("name","surname","userid","role"))->order("name ASC");
                   $result = $this->_db->fetchAll($select);

                   return $result;
    }

    function getUserForUpdate($userid){
                   $select = $this->_db->select()->from($this->_name,array('name','surname','contactno','email'))->where("userid = ?",$userid);
                   $result = $this->_db->fetchRow($select);
                   return $result;
    }

}
?>