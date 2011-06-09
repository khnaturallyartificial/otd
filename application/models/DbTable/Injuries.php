<?php

class Application_Model_DbTable_Injuries extends Zend_Db_Table_Abstract {
               protected $_name = "injuries";

               function countinjuries($userid){
                              $sql = "SELECT COUNT(*) AS cri FROM ".$this->_name." WHERE userid = '".$userid."'";
                              $result = $this->_db->fetchRow($sql);
                              return $result["cri"];
               }

               function getinjuries($userid){
                              $select = $this->_db->select()->from($this->_name)
                                      ->where("userid = ?", $userid)->limit(50)->order("date DESC");
                              $result = $this->_db->fetchAll($select);
                              return $result;
               }

               function getTrafficLightCurrent($athleteid){
                              $today = date("Y-m-d", time());
                              $status = "";
                              $yesterday = date("Y-m-d", time()-86400);
                              $sql = "SELECT COUNT(*) as injcount FROM ".$this->_name . " WHERE userid =  '".$athleteid."' and date BETWEEN '$yesterday' AND '$today'";
                              $result = $this->_db->fetchRow($sql);
                              if($result["injcount"] == 0){
                                             $status = "green";
                              }
                              else{
                                             $sql = "SELECT COUNT(*) as injcount FROM ".$this->_name . " WHERE (training_modified = 'b' OR training_modified = 'c' ) AND userid =  '".$athleteid."'  and date BETWEEN '$yesterday' AND '$today'";
                                             $result = $this->_db->fetchRow($sql);
                                             if($result["injcount"] > 0){
                                                            $status = "amber";
                                             }

                                             $sql = "SELECT COUNT(*) as injcount FROM ".$this->_name . " WHERE training_modified = 'd' AND userid =  '".$athleteid."'  and date BETWEEN '$yesterday' AND '$today'";
                                             $result = $this->_db->fetchRow($sql);
                                             if($result["injcount"]>0){
                                                            $status = "red";
                                             }
                              }
                              return $status;
               }

               function getInjuries30days($athleteid){
                              $today = date("Y-m-d" , time());
                              $lastmonth = date("Y-m-d", time() - 2592000);

                              $select = $this->_db->select()->from($this->_name)->where("userid = ?", $athleteid)
                                      ->where("date BETWEEN '$lastmonth' AND '$today'");
                              $result = $this->_db->fetchAll($select);
                              //got result, begins categorization!
                              $data = array(
                                  "a" => array(),
                                  "b" => array(),
                                  "c" => array(),
                                  "d" => array()
                              );
                              foreach($result as $inj){
                                             $data[$inj["training_modified"]][] = $inj;
                              }
                              //Zend_Debug::dump($data);

                              $ambercount = count(array_merge($data["b"],$data["c"]));
                              $redcount = count($data["d"]);

                              //echo $ambercount;
                              //echo $redcount;

                              //amber dealio~
                              $amberstring = "Within the last 30 days, athlete reported $ambercount ambers, on:  ";
                              foreach($data["b"] as $dat_b){
                                             $amberstring .= "<br/>".$dat_b["date"];
                              }
                              foreach($data["c"] as $dat_c){
                                             $amberstring .= "<br/>".$dat_c["date"];
                              }

                              $redstring = "Within the last 30 days, athlete reported $redcount reds, on:  ";
                              
                              foreach($data["d"] as $dat_d){
                                             $redstring .= "<br/>".$dat_d["date"];
                              }

                              return array($amberstring,$redstring);

                              
                             
               }
}

?>
