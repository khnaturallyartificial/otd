<?php
class Application_Model_DbTable_Loginlog extends Zend_Db_Table_Abstract{
          protected $_name = "loginlog";
          function getAll(){
	$sql = "SELECT loginlog.ID,loginlog.userid,MAX(loginlog.time),user.name, user.surname,user.role FROM `loginlog`,`user` where loginlog.userid = user.userid group by userid order by user.name";
	return $this->_db->fetchAll($sql);
          }
}
?>
