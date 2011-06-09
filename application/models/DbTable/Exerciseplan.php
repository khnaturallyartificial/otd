<?php

class Application_Model_DbTable_Exerciseplan extends Zend_Db_Table_Abstract{
               protected $_name = "exerciseplan";

               function getExercisePlans($planid){
                              $plans = new Application_Model_DbTable_Plan();
                              $plan = $plans->getPlan($planid);
                              $plantime = new Zend_Date($plan["startdate"],"YYYY-MM-DD");
                              $e = new Application_Model_DbTable_Exercise();
                              $exercise = $e->getAllExercise();
                              for($i=0;$i<$plan["duration"];$i++){
                                             $select = $this->_db->select()->from($this->_name)->where('planid = ?',$plan["planid"])
                                                            ->where('date = ?',date('o-m-d', $plantime->getTimestamp()))->order("weight ASC");
                                             $result = $this->_db->fetchAll($select);
                                             if(count($result) > 0){
                                                            $ret.='<a id="'.date('o-m-d', $plantime->getTimestamp()).'"><img src="/img/calendar-today.png" alt="*"/> <span class="plantitle rounded3">&nbsp;Day '.($i+1).'('.date('D, j M Y', $plantime->getTimestamp()).')&nbsp;</span></a><ul>';
                                                            foreach($result as $r){
                                                                           $comment = ($r["comment"]) ? ' * '. str_replace("\\","",$r["comment"]) : '';
                                                                           $ret.= "<li id=\"epid".$r["exerciseplanid"]."\">".$exercise[$r["exerciseid"]]." - ".$r["weight"]."kg. x".$r["reps"]." : ".$r["sets"]." sets.".$comment."<img src=\"/img/cross.png\" alt=\"-\" class=\"deleteexplan\" /></li>";
                                                            }
                                                            $ret .= "</ul>";
                                             }
                                             $plantime->addDay(1);
                              }
                              return $ret;
               }


               function getExercisePlansDay($planid,$time,$range=null){
                              if($range){
                                             $select = $this->_db->select()->from($this->_name)
                                                     ->where("planid = ?", $planid)
                                                     ->where("date > ?", date("o-m-d",$range))
                                                     ->where("date < ?", date("o-m-d",time()))
                                                     ->order("exerciseplanid DESC");
                                                     //->order("weight ASC");
                              }
                              else{
                                             $select = $this->_db->select()->from($this->_name)
                                                     ->where("planid = ?", $planid)
                                                     ->where("date = ?", date("o-m-d",$time));
                                                     //->order("weight ASC");
                              }
                              //echo $select->__toString();exit;
                              $result = $this->_db->fetchAll($select);
                              return $result;
               }

               function makeExercisePlansToday($planid,$time = NULL,$fordashboard=false,$range=null){ //range is the earliest time stamp to now
               				if($time==null){$t = time();} else ($t = $time);
                                                                               
                              $ep = $this->getExercisePlansDay($planid, $t,$range);
                              $e = new Application_Model_DbTable_Exercise();
                              $exercise = $e->getAllExercise();
                              $markedex = new Application_Model_DbTable_Markedexerciseplanview();
                              $ce = $markedex->getMarkedExerciseInPlan($planid);
                              $ret = "";
                              if($fordashboard==false){$fdb = "";}
                              else{$fdb = '<img src="/img/tick.png" class="markdone" alt="" title="Mark as completed as prescribed" />
                              							<img src="/img/skip.png" class="markskipped" title="Mark as modified (done more/less exercise than prescribed)" alt="" />';}
                              foreach($ep as $r){
                              	$marked = ($ce[$r["exerciseplanid"]]) ? ' class="'.$ce[$r["exerciseplanid"]].'" ' : "";
                                             $ret.= "<li id=\"epid".$r["exerciseplanid"]."\" ".$marked.">";
                                             if($range){//if there is range value, echo date as well
                                                            $ret .= "[".$r["date"]."]";
                                             }
                                             $ret .= $exercise[$r["exerciseid"]]." - <span id=\"weightfor".$r["exerciseplanid"]."\">".$r["weight"]."</span>kg. x<span id=\"repsfor".$r["exerciseplanid"]."\">".$r["reps"]."</span> : <span id=\"setsfor".$r["exerciseplanid"]."\">".$r["sets"]."</span> sets.
                                                                           ".$fdb."<span id=\"".$r["exerciseplanid"]."-saved\">";
                                             if($ce[$r["exerciseplanid"]] == "skipped"){
                                                                 $auth = Zend_Auth::getInstance();
                                                                 $user = $auth->getIdentity();

                                                                 $select = $this->_db->select()->from("markedexerciseplanview", array("actual_total_lifted", "actual_total_reps"))
                                                                         ->where("exerciseplanid = ?",(int) $r["exerciseplanid"])
                                                                         ->where("userid = ?", $user->userid);
                                                                 $result = $this->_db->fetchAll($select);
                                                                 //var_dump($result);

                                                          $ret.= $result[0]["actual_total_reps"]." reps -> total ".$result[0]["actual_total_lifted"]. " kg.";
                                             }
                                             $ret .="</span></li>";
                              }
                              if(strlen($ret)>0){
                              return $ret;
                              }
                              else{
                              	return "<li>No exercise</li>";
                              }
               }

               function fetchLastRow(){
                              $select = $this->_db->select()->from($this->_name)
                                      ->order("exerciseplanid DESC")
                                      ->limit(1);
                              $result = $this->_db->fetchRow($select);
                              return $result;
               }
}


?>
