<?php

class Application_Model_DbTable_WeeklyReport extends Zend_Db_Table_Abstract {
               protected $_name = 'weekly_report';

               function getUserReport($userid){
                              $select = $this->_db->select()->from($this->_name)
                                      ->where("userid = ?", $userid)->limit(50)->order("date DESC");
                              $result = $this->_db->fetchAll($select);
                              return $result;
               }

               function getIllnessTypes(){
                              $select = $this->_db->select()->from('illnesstype');
                              $result = $this->_db->fetchAll($select);
                              $ret = array();
                              foreach($result as $r){
                                             $ret[(int) $r["id"]] = $r["name"];
                              }
                              return $ret;
               }

               function alreadyDoneThisWeek($userid){
                              $lastweek = date("Y-m-d",(strtotime(date("Y",time())."W". date("W",time()))));
                              $select = $this->_db->select()->from("weekly_report_ratio")
                                      ->where("userid = ?",$userid)->where("date = ?", $lastweek);
                              $result = $this->_db->fetchAll($select);
	          
                              if(count($result) > 0){
                                             return true;
                              }
                              else{
                                             return false;
                              }
               }
}

?>
