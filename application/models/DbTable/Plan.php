<?php

class Application_Model_DbTable_Plan extends Zend_Db_Table_Abstract{
               protected $_name = "plan";
               function getPlan($id){
                              $auth = Zend_Auth::getInstance();
                              $user = $auth->getIdentity();
                              $select = $this->_db->select()->from($this->_name)
                                      ->where('planid = ?', $id)->where('creator = ?', $user->userid);
                              $result = $this->_db->fetchAll($select);
                              return $result[0];
               }
               

               function getAthletesInPlan($planid){
                              
               }

               function countPlans($coachid){
                              $sql = "SELECT COUNT(*) as countno FROM ". $this->_name . " WHERE creator = ". $coachid;
                              $result = $this->_db->fetchRow($sql);
                              return $result["countno"];
               }

               function makeDateSelection($planid){
                              $select = $this->_db->select()->from($this->_name, array('duration','startdate'))
                                      ->where('planid = ?',$planid);
                              $result = $this->_db->fetchAll($select);
                              $length = $result[0]["duration"];
                              $startdate = $result[0]["startdate"];
                              $startdateobj = new Zend_Date;
                              $startdateobj->setDate($startdate, "YYYY-MM-DD");
                              
                              $ret = array();
                              for($i=0;$i<$length;$i++){
                                             $ret[date('o-m-d', $startdateobj->getTimestamp())] = "Day " . ($i+1). " - " .date('D, j M Y', $startdateobj->getTimestamp());
                                             $startdateobj->addDay(1);
                              }
                              return $ret;
                              //return $startdateobj;
                             // return array(array(1,'2010-06-09'),array(2,'2010-06-10'),array(3,'2010-06-11'),array(4,'2010-06-12'));
               }

               function getPlans($href = null){
                              $auth = Zend_Auth::getInstance();
                              $user = $auth->getIdentity();

                              $select = $this->_db->select()->from($this->_name)
                                      ->where('creator = ?',$user->userid)->limit(30)->order("startdate DESC");
                              $result = $this->_db->fetchAll($select);
                              $menu = "";
                              foreach($result as $plan){
                                             if($href == null){
                                                            $menu .= '<span title="'.$plan["startdate"].'" class="tipleft">
                                                                           <a class="planlist" id="'.$plan["planid"].'" href="#">'.$plan["planname"].'</a>
                                                                                          </span><br/>';
                                             }
                                             if($href== "id"){
                                                            $menu .= '<span title="'.$plan["startdate"].'" class="tipleft">
                                                                           <a class="planlist" id="'.$plan["planid"].'" href="/coach/plans/planid/'.$plan["planid"].'">'.$plan["planname"].'</a>
                                                                                          </span><br/>';
                                             }
                              }
                              return $menu;
               }

               function getActivePlans($href = null){
                              $auth = Zend_Auth::getInstance();
                              $user = $auth->getIdentity();

                              $select = $this->_db->select()->from($this->_name)
                                      ->where('creator = ?',$user->userid)->where("archived = '0'")->limit(30)->order("planid DESC");
                              $result = $this->_db->fetchAll($select);
                              $menu = "";
                              foreach($result as $plan){
                                             if($href == null){
                                                            $menu .= '<span title="'.$plan["startdate"].'" class="tipleft">
                                                                           <a class="planlist" id="'.$plan["planid"].'" href="#">'.$plan["planname"].'</a>
                                                                                          </span><br/>';
                                             }
                                             if($href== "id"){
                                                            $menu .= '<span title="'.$plan["startdate"].'" class="tipleft">
                                                                           <a class="planlist" id="'.$plan["planid"].'" href="/coach/plans/planid/'.$plan["planid"].'">'.$plan["planname"].'</a>
                                                                                          </span><img src="/img/skip.png" alt="'.$plan["planid"].'" class="archiveit" title="archive this plan"/><br/>';
                                             }
                              }
                              return $menu;
               }

               function getArchivedPlans($href = null){
                              $auth = Zend_Auth::getInstance();
                              $user = $auth->getIdentity();

                              $select = $this->_db->select()->from($this->_name)
                                      ->where('creator = ?',$user->userid)->where("archived = '1'")->limit(30)->order("startdate DESC");
                              $result = $this->_db->fetchAll($select);
                              $menu = "";
                              foreach($result as $plan){
                                             if($href == null){
                                                            $menu .= '<span title="'.$plan["startdate"].'" class="tipleft">
                                                                           <a class="planlist" id="'.$plan["planid"].'" href="#">'.$plan["planname"].'</a>
                                                                                          </span><br/>';
                                             }
                                             if($href== "id"){
                                                            $menu .= '<span title="'.$plan["startdate"].'" class="tipleft">
                                                                           <a class="planlist" id="'.$plan["planid"].'" href="/coach/plans/planid/'.$plan["planid"].'">'.$plan["planname"].'</a>
                                                                                          </span><img src="/img/cross.png" class="deleteplanpermanent"/><br/>';
                                             }
                              }
                              return $menu;
               }

               function addPlan($data){
                              $isunique = $this->uniquePlanName($data["planname"]);

                              if($isunique){
                                             $planid = $this->insert($data);
		     return $planid;
                                             //return true;
                              }
                              else{
                                             return false;
                              }                              
               }

               function uniquePlanName($planname){
                              $select = $this->_db->select()->from($this->_name)
                                      ->where('planname = ?', $planname);
                              $result = $this->_db->fetchAll($select);
                              if(count($result)>0){
                                             return false;
                              }
                              else{
                                             return true;
                              }
               }
}

?>
