<?php

class Application_Model_DbTable_Dailyreport extends Zend_Db_Table_Abstract {
               protected $_name = 'dailyreport';

               function insertdata($data){
                              try{
                              $this->insert($data);
                              return "success";
                              }
                              catch(Exception $e){
                                             return $e;//'failed';
                              }
               }

               function getDoneDailyReports($userid,$limit=20){

	     $select = $this->_db->select()->from($this->_name,array("date"))->where("userid = ?",$userid)
	             ->where("date > ?",date("Y-m-d",time()-(86400*$limit)))
	             ->where("date <= ?",date("Y-m-d",time()))->order("date DESC");
	     $result = $this->_db->fetchAll($select);
	     return $result;
               }

               function selectlastentry($aid){
                   $select = $this->_db->select()->from($this->_name)
                           ->where("userid = ?", $aid)
                           ->limit(1)
                           ->order("date DESC");
                   return $this->_db->fetchAll($select);
               }

               function fetchAllForUser($userid){
                              $select = $this->_db->select()->from($this->_name)->where("userid = ?", $userid)
                                      ->order('date DESC')->limit(30);
                              $result = $this->_db->fetchAll($select);
                              return $result;
               }

               function alreadyDoneToday($userid){
                              $today = date("Y-m-d", time());
                              $select = $this->_db->select()->from($this->_name)
                                      ->where("userid = ?",$userid)->where("date = ?",$today);
                              $result = $this->_db->fetchAll($select);
                              if(count($result)>0){
                                              return true;
                              }
                              else{
                                             return false;
                              }
               }
}

?>
