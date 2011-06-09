<?php

class Application_Model_DbTable_Coachathleteview extends Zend_Db_Table_Abstract{
               protected $_name = "coachathleteview";
               protected $_primary = "athleteid";

               function getAthletes($coachid,$planid){ // athletes under this coach, and NOT in this plan
                              try{
                              $select = $this->_db->select()->from($this->_name)
                                             ->where('coachid = ?', $coachid)->order("name ASC");

                              return $this->_db->fetchAll($select);
                              }
                              catch(Exception $e){
                                            echo $e->getMessage();
                              }


               }

               function countAthleteUnderCoach($coachid){
                              $sql = "select count(*) as athletecount from ".$this->_name." where coachid = ".$coachid;
                              $result = $this->_db->fetchRow($sql);
                              return $result["athletecount"];
               }

               function listAthletes($coachid,$planid){ 
                              try{
                              $select = $this->_db->select()->from($this->_name)
                                             ->where('coachid = ?', $coachid)
                                             ->where('planid = ?', $planid)
		     ->order('name ASC');
                              return $this->_db->fetchAll($select);
                              }
                              catch(Exception $e){
                                            echo $e->getMessage();
                              }


               }

               function makeAthleteSelect($coachid,$planid){
                              $list = $this->getAthletes($coachid, $planid);
                              $ret = "";
                              foreach($list as $l){
                                             $planname = "none";
                                             $result = $this->_db->fetchAll($this->_db->select()->from("plan",array("planname"))
                                                     ->where("planid = ?", $l["planid"]));
                                             if(count($result) == 1){$planname = $result[0]["planname"];}
                                             $ret .= '<option value="athlete'.$l["athleteid"].'">'.$l["name"].' '.$l["surname"].' [currently on: '.$planname.']</option>';
                              }
                              return $ret;
               }

               function getAthleteUnderCoach($coachid){
                              $select = $this->_db->select()->from($this->_name)
                                             ->where("coachid = ?",$coachid)
		     ->order("name ASC");
                              $result = $this->_db->fetchAll($select);
                              return $result;
               }

               function getUnassignedAthlete(){
                              $select ="select * from user as u left join coachathlete as c on u.userid = c.athleteid where c.athleteid is null and u.role = 'A'";
                              $result = $this->_db->fetchAll($select);
                              return $result;

               }

               function getAllAthletes(){
                   $select ="select * from user as u left join coachathlete as c on u.userid = c.athleteid where u.role = 'A'";
                              $result = $this->_db->fetchAll($select);
                              return $result;
               }

               function getCoachId($athleteid){
                              $select = $this->_db->select()->from($this->_name)->where("athleteid = ?",$athleteid)->order("name ASC");
                              $result = $this->_db->fetchRow($select);
                              return $result["coachid"];
               }

               function getCoachIdAlert($athleteid){
	     $select = $this->_db->select()->from($this->_name, array("coachid"))->where("athleteid = ?",$athleteid)->order("name ASC");
                              $result = $this->_db->fetchAll($select);
                              return $result;
               }


}

?>
