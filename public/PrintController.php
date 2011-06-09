<?php

class PrintController extends Zend_Controller_Action {
               function printplanAction(){
                   $this->_helper->layout->disableLayout();
                   //$this->_helper->viewRenderer->setNoRender(true);
                   $misc = new Application_Model_Misc();
                   $plans = new Application_Model_DbTable_Plan();
                   $lul = $misc->selectexercisesByPlanId($this->getRequest()->getParam("planid"));
                   $plan = $plans->getPlan($this->getRequest()->getParam("planid"));

                   $this->view->plan = $plan;

                   echo '';
                   echo '<img src="http://otd.naturallyartificial.net/img/smallwcl.gif" alt="" style="float:right;"/>
                                  <h2>Plan: '.str_replace("\\","",$plan["planname"]).'</h2>';
                   echo' <span class="red">Please fill empty boxes with the applied KGs or strikethrough which were not done.</span>
                                  ';
                   $exerciselist = new Application_Model_DbTable_Exercise();
                   $exlist = $exerciselist->fetchAll();
                   $exercisearray = array();

                   foreach($exlist as $exl){
                                  $exercisearray[$exl["exerciseid"]] = $exl["exercisename"];
                   }
                   unset($exerciselist, $exlist);

                   //dividing them into days
                   $days = array();
                   $startdate = $lul[0]["date"];
                   $firstindex = 0;
                   $cellsperrow = 14;
                   foreach($lul as $l){
                                  if($l["date"] != $startdate){
                                                 $firstindex++;
                                                 $startdate = $l["date"];
                                  }
                                  $days[$firstindex][] = $l;

                   }

                   //now they are divided into days
                   //for each day, we want a table
                   echo '<div id="print">';
                   foreach($days as $day){
                              $commentblock = "";
                                  echo '<div class="date">&raquo; '.date("l d-M-Y", strtotime($day[0]["date"])).'</div>';
                                  echo '<table>
                                                 ';
                                  $exid = 0;
                                 $count = 0;
                                 $cells = 0;
                                foreach($day as $d){
                                                
                                               if($d["exerciseid"] != $exid){
                                                              $exid = $d["exerciseid"];
                                                              if($count == 0){
                                                              echo '<tr><td class="exid">'.$d["exerciseid"].'</td>
                                                                                            ';
                                                              $count += 1;
                                                              }
                                                              else{
                                                                             if($cellsperrow > $cells){
                                                                                            for($ii=0;$ii < ($cellsperrow - $cells); $ii++){
                                                                                                           echo '<td></td>';
                                                                                            }
                                                                                            $cells = 0;
                                                                             }
                                                                               echo '</tr><tr><td class="exid">'.$d["exerciseid"].'</td>
                                                                                              ';
                                                              }
                                               }
                                               //normal mode
                                             /*echo '<td>'.$d["weight"].'/'.$d["reps"].'/'.$d["sets"].'</td>
                                                              ';
                                                             $cells++;
                                              */
                                               //or separated mode
                                               for($i=0;$i<$d["sets"];$i++){
                                                              echo '<td>'.$d["weight"].'/'.$d["reps"].'<br/></td>
                                                            ';
                                                              if($d["comment"]){
                                                                             $str = 'for ' . $exercisearray[$d["exerciseid"]] . ' ('.$d["weight"].'/'.$d["reps"].') - ';
                                                                             if(stristr($commentblock, $str) == false){
                                                                                          $commentblock  .= $str . str_replace("\\","",$d["comment"]) . '<br/>';
                                                                             }
                                                              }
                                                              $cells++;
                                               }
                                               //end modes
                                               
                                }
                                if($cellsperrow > $cells){
                                                                                            for($ii=0;$ii < ($cellsperrow - $cells); $ii++){
                                                                                                           echo '<td></td>';
                                                                                            }
                                                                                            $cells = 0;
                                                                             }
                                
                                echo '</tr>
                                               </table>
                                                            <table id="commentbox">
                                                                           <tr>
                                                                                          <td style="width:200px;">
                                                                                                         Body weight: _______kg.<br/>
                                                                                                         Notes:
                                                                                          </td>
                                                                                          <td style="width:240px;">
                                                                                          Exercise intensity rating 0 - 10:_____<br/>
                                                                                          <span style="font-size:8px;">Please record within 30 min of finishing training)</span>
                                                                                          </td>
                                                                                          <td>
                                                                                          Coach Notes:
                                                                                          </td>
                                                                           </tr>
                                                                           <tr>
                                                                                           <td>
                                                                                          </td>
                                                                                          <td>
                                                                                          </td>
                                                                                          <td>
                                                                                          <span class="commentblock">'.$commentblock.'</span>
                                                                                          </td>
                                                                           </tr>
                                                            </table>
                                                           
                                             ';
                   }
                   echo '';
               }
}

?>
