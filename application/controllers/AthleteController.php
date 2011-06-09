<?php

class AthleteController extends Zend_Controller_Action{
               protected $user = null;
               function init(){
                              $auth = Zend_Auth::getInstance();
                              if($auth->hasIdentity()){
                                             $user = $auth->getIdentity();
                                             if($user->role != "A"){
                                                            $this->_helper->redirector('index','index');
                                             }
                                             else{
                                                            $this->user = $user;
                                             }
                              }
                              else{
                              	$this->_helper->redirector('index','index');
                              }
                              $this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
                    $this->view->headScript()->appendFile('/js/jqueryUI.js');
	$this->view->headScript()->appendFile('/js/roundcorner.js');
	$this->view->headScript()->appendFile('/js/script.js');
	$this->view->headScript()->appendFile('/js/athlete.js');
                    $this->view->headLink()->appendStylesheet('/css/jqueryUI.css');

                    $this->view->menu = Application_Model_DbTable_Coach::makeAthleteMenu();

	
	$misc = new Application_Model_Misc();
	$this->view->history = $misc->getplanhistory($this->user->userid);
	//Application_Model_DbTable_TestLog::log($this->view->history);

               }
               
               
               function indexAction(){
                              $this->view->headTitle("Dashboard [Athlete]");
                              $users = new Application_Model_DbTable_User();
	          $this->view->users = $users->getAllUsersForRem();
                              $ep = new Application_Model_DbTable_Exerciseplan();
                              //$this->view->todotoday = $ep->makeExercisePlansToday($this->user->planid,null,true);
                              //$this->view->todoyesterday = $ep->makeExercisePlansToday($this->user->planid,time() - 86400,true);
                              $this->view->todo3monthsago = $ep->makeExercisePlansToday($this->user->planid,null,true,time()-17280000);
                              $mexp = new Application_Model_DbTable_Markedexerciseplanview();
                              $this->view->custom1weekago = $mexp->getmarkedexercisecustom($this->user->userid);
                              $this->view->customform = new Application_Form_CustomExercise();
                              $this->view->userid = $this->user->userid;
                              $this->view->planid = $this->user->planid;
                              $exercise = new Application_Model_DbTable_Exercise();
                              $this->view->exercises = $exercise->getAllExercise();
                              $exercise = new Application_Model_DbTable_Exercise();
	          if($this->user->olym_or_power == "o"){
		$type = "ol";
	          }
	          else{
		$type = "po";
	          }
                              $this->view->customform->exerciseid->addMultiOptions($exercise->makeExerciseSelection($type));
	          $this->view->customform->exerciseid_com->addMultiOptions(array(""=>""));
	          $this->view->customform->exerciseid_com2->addMultiOptions(array(""=>""));
	          $this->view->customform->exerciseid_com->addMultiOptions($exercise->makeExerciseSelection($type));
	          $this->view->customform->exerciseid_com2->addMultiOptions($exercise->makeExerciseSelection($type));
                              if(true){ //replace true with date("N",time()) ==1 for every monday
                                             $today = date("Y-m-d", strtotime(date("Y",time())  . "W"  . date("W",time())));
                                             /*$wrr = new Application_Model_DbTable_WeeklyReportRatio();
                                            $already = count($wrr->fetchAll("`userid` = '".$this->user->userid."' and`date` = '".$today."'"));
                                             if($already == 0){
                                                            $this->view->weeklyalert = true;
                                             }
                                             else{
                                                            $this->view->weeklyalert = false;
                                             }

		      */
                              }

                              $dr = new Application_Model_DbTable_Dailyreport();
	          $resultx=$dr->getDoneDailyReports($this->user->userid,20);
	          //$check = $dr->query($sq);
	          
	          
	          $daysarray = array();
	          for($i=0;$i<20;$i++){
		$daysarray[] = date("Y-m-d",time() - (86400 * $i));
		
	          }
	          $donearray = array();
	          foreach($resultx as $re){
		$donearray[] = $re["date"];
	          }
	          $redif = array_diff($daysarray,$donearray);
	          //Application_Model_DbTable_TestLog::log($redif);
	          $this->view->forgotdailyreport = $redif;
	          $already = count($dr->fetchAll("`userid` = '".$this->user->userid."' and `date` = '".date("Y-m-d", time()) ."'"));
                              if(!$already){
                                             $this->view->dailyalert = true;
                              }

                              if($this->getRequest()->isPost()){
                                             $data = $this->getRequest()->getPost();
                                             echo "Testing <br/><br/>";
                                             if($this->view->customform->isValid($data)){
                                                            $data["planid"] = "2147483647";
                                                            unset($data["submit"]);
                                                            error_reporting(E_ALL);
                                                            $ep = new Application_Model_DbTable_Exerciseplan();
                                                            $ep->insert($data);
                                                            $result = $ep->fetchLastRow();
                                                            $newdata = array(
                                                                "exerciseplanid"=>$result["exerciseplanid"],
                                                                "done"=>0,
                                                                "userid"=>$this->user->userid,
                                                                "date"=>$data["date"],
                                                                "actual_total_lifted"=>((int)$data["weight"] * (int)$data["reps"] * (int) $data["sets"]),
                                                                "actual_total_reps" => ((int)$data["reps"] * (int)$data["sets"]),
                                                                "actual_weight" => (int)$data["weight"]
                                                            );

                                                          
                                                            $mep = new Application_Model_DbTable_Markedexercise();
                                                            $mep->insert($newdata);
                                                            
                                                          

                                                           $system = new Zend_Session_Namespace("System");
                                                           $system->msg = "Custom exercise was added.";
                                                           $this->_helper->redirector("index","Message");
                                             }
                                             else{
                                                           $system = new Zend_Session_Namespace("System");
                                                           $system->msg = "There was an error while adding custom exercise. Please
                                                                          make sure that all data values are valid";
                                                           $this->_helper->redirector("index","Message");
                                             }
                                             
                              }
               }
               function addcustomexerciseAction(){
                              $this->_helper->layout->disableLayout();
                              $this->_helper->viewRenderer->setNoRender(true);
                              $customform = new Application_Form_CustomExercise();
                              if($this->getRequest()->isPost()){
                                             $data = $this->getRequest()->getPost();
                                             if($customform->isValid($data)){
                                                            $data["planid"] = "2147483647";
			$data["uid_for_custom"] = $this->user->userid;
                                                            unset($data["submit"]);
			$abf = $data["abf"];
			unset($data["abf"]);
                                                            error_reporting(E_ALL);
                                                            $ep = new Application_Model_DbTable_Exerciseplan();
			$sets = (int) $data["sets"];
			$data["sets"] = 1;
			$lastrowarray = array();
			for($i=0;$i<$sets;$i++){
			          $tmpdat = $data;
			          unset($tmpdat["additionalcomment"]);
			          $ep->insert($tmpdat);
			          $result = $ep->fetchLastRow();
			          $lastrowarray[] = $result["exerciseplanid"];
			}
                                                            
			error_reporting(0);
			$mep = new Application_Model_DbTable_Markedexercise();
			foreach($lastrowarray as $sl){
                                                            $newdata = array(
                                                                "exerciseplanid"=>$sl,
                                                                "done"=>2,
                                                                "userid"=>$this->user->userid,
                                                                "date"=>$data["date"],
                                                                "actual_total_lifted"=>((int)$data["weight"] * (int)$data["reps"] * (int) $data["sets"]),
                                                                "actual_total_reps" => ((int)$data["reps"] * (int)$data["sets"]),
                                                                "actual_weight" => (int)$data["weight"],
			    "actual_reps_com" => $data["reps_com"],
			    "actual_reps_com2" =>$data["reps_com2"],
			    "additionalcomment"=>$data["additionalcomment"]
                                                            );
			if($abf == 1){
			         $newdata["actual_weight"]=0;
			         $newdata["actual_total_reps"]=0;
			         $newdata["actual_total_lifted"]=0;
			         $newdata["done"]=0;
			}


                                                            
                                                            $mep->insert($newdata);
			}



                                                           echo "success";
                                             }
                                             else{
                                                           echo "failed";
                                             }

                              }
               }
               function markexerciseplanAction(){
               	$this->_helper->layout->disableLayout();
               	$this->_helper->viewRenderer->setNoRender(true);
               		if($this->getRequest()->isPost()){
               			$data = $this->getRequest()->getPost();
               			$me = new Application_Model_DbTable_Markedexercise();
                                                            $result=$me->markExercisePlan($data["exid"],$data["mode"],$this->user->userid,$data["actual_total_lifted"],$data["actual_total_reps"],$data["actual_weight"],$data["repscom"],$data["repscom2"]);
               			if($result == true){
                                                                           echo "success";
                                                            }
                                                            else{
			          var_dump ($result);
                                                                           //echo "Failed to mark exercise. Please contact Administrator";
                                                            }

               		}
               }

               function getreportforeditAction(){
	     $this->_helper->layout->disableLayout();
	     $this->_helper->viewRenderer->setNoRender(true);
	     if($this->getRequest()->isPost()){
	               $data = $this->getRequest()->getPost();
	               $dr = new Application_Model_DbTable_Dailyreport();
	               $result = $dr->fetchRow("date = '".$data["date"]."' AND userid = ".$this->user->userid);
	               try{
		     $jsonstring =  json_encode($result->toArray());
		     echo str_replace('"',"", $jsonstring);
	               }
	               catch(Exception $e){
		     echo "false";
		     
	               }
	               
	     }

               }

               function deletecustomexerciseAction(){
	     $this->_helper->layout->disableLayout();
	     $this->_helper->viewRenderer->setNoRender(true);
	     if($this->getRequest()->isPost()){
	               $data = $this->getRequest()->getPost();
	               $exerciseplans = new Application_Model_DbTable_Exerciseplan();
	               $markedexerciseplan = new Application_Model_DbTable_Markedexercise();

	               try{
		     $exerciseplans->delete("`exerciseplanid` = ".$data["delid"]);
		     $markedexerciseplan->delete("`exerciseplanid` = ".$data["delid"]);
		     echo "success";
	               }
	               catch(MySQLException $e){
		     echo "false";
		     var_dump($e);
	               }
	     }
               }

               function dailyreportAction (){
                              /*$wr = new Application_Model_DbTable_WeeklyReport();
                              $result = $wr->alreadyDoneThisWeek($this->user->userid);
                              if($result == false){
                                             $session = new Zend_Session_Namespace("System");
                                             $session->msg = "You have not completed the weekly illness report, please do that first.";
                                             $session->redir = "/athlete/illness";
                                             $session->redirtext = "Take me to weekly illness form.";
                                             $this->_helper->redirector("index", "message");
                              }
	           * 
	           *
                              $dr = new Application_Model_DbTable_Dailyreport();
                              $result = $dr->alreadyDoneToday($this->user->userid);
                              if($result == true){
                                             $session = new Zend_Session_Namespace("System");
                                             $session->msg = "You have already completed the daily report
                                                            <br/>If you have any injuries and/or niggles, please click on the link below.";
                                             $session->redir = "/athlete/injuries";
                                             $session->redirtext = "Take me to injury & niggle form";
                                             $this->_helper->redirector("index", "message");
                              }
	           *
	           */
                              $this->view->headTitle('Daily Report');
                              $this->view->headLink()->appendStylesheet('/css/dailyreport.css');
                              $this->view->form = new Application_Form_DailyReportForm();
	          if($this->getRequest()->getParam("presetdate") && preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$this->getRequest()->getParam("presetdate"))){
		$this->view->form->date->setValue($this->getRequest()->getParam("presetdate"));
		$this->view->alreadyhasdate = true;
	          }
                              $dr = new Application_Model_DbTable_Dailyreport();
                              $ath = $dr->selectlastentry($this->user->userid);
                              $dat = array(
                                "bw_morning"=>$ath[0]["bw_morning"],
                                  "bw_evening"=>$ath[0]["bw_evening"]
                              );
                              //$this->view->form->populate($dat);
                              $this->view->sex = $this->user->sex;

                              if($this->getRequest()->isPost()){
                                             $data = $this->GetRequest()->getPost();
                                             if($this->view->form->isValid($data)){
                                                            $dr = new Application_Model_DbTable_Dailyreport();
                                                            $data["userid"] = $this->user->userid;
			$session = new Zend_Session_Namespace('System');
			$already = $dr->fetchAll("`userid` = ".$this->user->userid." and `date` = '".$data["date"]."'");
			//$logs = new Application_Model_DbTable_TestLog();
			//$logs->log($already);
			$ar = $already->toArray();
			//var_dump(empty($already));
			
			if(count($ar) > 0){
			          $row = $ar[0];
			          $dr->update($data,"`reportid` = " . $row["reportid"]); //didn't use comparison...
			          $session->msg = "You have made changes to report on ".$data["date"];
                                                                           $session->redir = "/athlete/dailyreport";
                                                                           $session->redirtext = "Go back to daily report";
                                                                           $this->_helper->redirector('index','message');
			               exit;
			}
                                                            $result = $dr->insertdata($data);
			//var_dump($result);
			//echo "end";exit;
                                                            
                                                            if($result == "success"){
                                                                           $alerthead = "OTD System Message - High HRrest ";
                                                                           $alertcontent = "This athlete seems to have HRrest outside the healthy range (40 - 90)<br/>
                                                                                          <br/>
                                                                                          HRrest: ".$data["mhr"]."";
                                                                           $alertlevel = 1;
                                                                           $athleteid = $this->user->userid;
                                                                           $smail = true;
                                                                           $alerts = new Application_Model_DbTable_Alerts();
                                                                           if(!empty($data["mhr"])){
                                                                                          if($data["mhr"]<40 || $data["mhr"]>90){
                                                                                                         $alerts->doAlert($alerthead,$alertcontent, $alertlevel,$athleteid,$smail);
                                                                                          }
                                                                           }

                                                                           $session->msg = "Report saved successfully.";
                                                                           $session->redir = "/athlete";
                                                                           $session->redirtext = "Take me to dashboard";
                                                                           $this->_helper->redirector('index','message');
                                                            }

                                             }
                                             else{
                                                            //$this->view->form->populate($data);
		               /*$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
			Zend_Debug::dump($data);
			exit;*/
		               $this->view->failedvalidate = true;
                                             }
                              }
               }

               function myprogressAction(){
                              $this->view->headTitle('My Progress');
                               $this->view->headLink()->appendStylesheet('/css/graph.css');
                              $this->view->images = array();

                              $this->view->userid = $this->user->userid;
                              $this->view->images[] = '/chart/index/mode/drawchart/userid/'.$this->user->userid;
                              $this->view->images[] = '/chart/index/mode/drawchart_average/userid/'.$this->user->userid;
                              $this->view->images[] = '/chart/index/mode/drawchart_geninfo/userid/'.$this->user->userid .'/sq/1/mr/1/pr/1/pte/1/ms/1/gf/1';
               }

               function illnessAction(){
                              $wr = new Application_Model_DbTable_WeeklyReport();
                              $result = $wr->alreadyDoneThisWeek($this->user->userid);
                              if($result == true){
                                             $session = new Zend_Session_Namespace("System");
                                             $session->msg = "You have already completed this week's illness report. Please proceed to daily report";
                                             $session->redir = "/athlete/dailyreport";
                                             $session->redirtext = "Take me to daily report form.";
                                             $this->_helper->redirector("index", "message");
                              }
                              $this->view->headLink()->appendStylesheet('/css/weeklyreport.css');
                              $illtypes = new Application_Model_DbTable_Illnesstype();
                              $this->view->illtypes = $illtypes->fetchAll();
                              $this->view->userid = $this->user->userid;
                              $this->view->thisweekstartdate = date("Y-m-d", strtotime(date("Y",time())  . "W"  . date("W",time())));
                              $this->view->lastweekstartdate = date("Y-m-d", strtotime(date("Y",time())  . "W"  . date("W",time())) - 604800);
               }

               function addweeklyformAction(){
                              $this->_helper->layout->disableLayout();
                              $this->_helper->viewRenderer->setNoRender(true);
                              $wr = new Application_Model_DbTable_WeeklyReport();
                              $wrr = new Application_Model_DbTable_WeeklyReportRatio();
                              if($this->getRequest()->isPost()){
                                             $data = $this->getRequest()->getPost();
                                             try{
                                                            $wrrdata = array(
                                                                "userid" => $this->user->userid,
                                                                "date" => $data["thisweek"],
                                                                "max_snatch" => $data["snatch"],
                                                                "max_cj" => $data["pcj"],
                                                                "bw" => $data["bw"]
                                                            );
                                                            $wrr->insert($wrrdata);

                                                            $illdays = 0;
                                                            if($data["ill"]){
                                                            $illlist = explode(",",$data["ill"]);
                                                            $alert = "";
                                                            $alertlevel = 1;
                                                            $illtype = new Application_Model_DbTable_Illnesstype();
                                                           
                                                                          foreach( $illlist as $illness){
                                                                                         $explosion = explode("x",$illness);
                                                                                         $illnessname = $illtype->fetchRow("id = ".$explosion[0]);
                                                                                         $alert .= '<i>Illness</i> : '. $illnessname["name"] . ' - '. $explosion[2] . "days
                                                                                                         (".$explosion[1] . ")<br/>" ;
                                                                                         $wrdata = array(
                                                                                            "date" => $data["lastweek"],
                                                                                             "userid" => $this->user->userid,
                                                                                             "illnesstypeid" => $explosion[0],
                                                                                             "severity" => $explosion[1],
                                                                                             "duration" => $explosion[2],
                                                                                             "activity_level" => $explosion[3]
                                                                                         );
                                                                                         $illdays += (int) $explosion[2];
                                                                                         $wr->insert($wrdata);

                                                                          }
                                                            }
                                                           if($alert != ""){
                                                                           $alerts = new Application_Model_DbTable_Alerts();
                                                                           $alerts->doAlert("New Illness reported", $alert, $alertlevel, $this->user->userid,true);
                                                           }
                                                            $msg = "All data has been recorded. You had ". $illdays . " ill day(s)";
                                                            $session = new Zend_Session_Namespace('System');
                                                             $session->msg = $msg;
                                                             $session->redir = "/athlete/dailyreport";
                                                             $session->redirtext = "Proceed to next step (Daily report)";
                                                             echo "success";
                                             }
                                             catch(Exception $e){
                                                            $msg = $e->getMessage();
                                                            $session = new Zend_Session_Namespace('System');
                                                            $session->msg = $msg;
                                                            echo $msg;//" Failed to save data. You may have entered the data for this week already.";
                                             }

                                             
                              }

               }
               function injuriesAction(){
                              $this->view->headLink()->appendStylesheet('/css/injuries.css');
               }

               function historyAction(){
                              $wr = new Application_Model_DbTable_WeeklyReport();
                              $id = $this->user->userid;
                              $this->view->illnesslist = $wr->getUserReport($id);
                              $this->view->illnesstype = $wr->getIllnessTypes();

                              $inj = new Application_Model_DbTable_Injuries();
                              $this->view->injurylist = $inj->getinjuries($id);
               }
               function saveinjuriesAction(){
                              $this->_helper->layout->disableLayout();
                              $this->_helper->viewRenderer->setNoRender(true);
	          
                              if($this->getRequest()->isPost()){
                                             $data = $this->getRequest()->getPost();
                                             $date = $data["date"];
                                             $indata = trim($data["data"], "[");
                                             $indata = trim($indata, "]");
                                             $injuries = explode("][", $indata);
                                             //Zend_Debug::dump($date);
                                             //Zend_Debug::dump($injuries);
                                             $injuryreport = new Application_Model_DbTable_Injuriesreport();
                                             $injuryentry = new Application_Model_DbTable_Injuriesentry();
                                             try{
                                                            $injuryreport->insertnewreportrow(array(
                                                                "date" => $date,
                                                                "userid" => $this->user->userid
                                                            ));

                                                            $reportrow = $injuryreport->fetchRow("`date` = '".$date."' and `userid` = '".$this->user->userid."'");
                                                            $reportid = $reportrow["reportid"];

                                                            $alert = "";
                                                            $alertlevel = array();
                                                            $dup = "";

                                                            foreach($injuries as $injury){
                                                                           $realdata = explode("***", $injury);
                                                                           if((int) $realdata[4] == 1){
                                                                                          $alert .= '<i>New Injury</i>: '.$realdata[1].' (pain rating:'.$realdata[2].')<br/>';
                                                                           }

                                                                           if($realdata[5] == "c" || $realdata[5] == "d"){
                                                                                          $alertlevel[] = 2;
                                                                                          $alert .= '<i>Training modified</i>: '.$realdata[1].' (pain rating:'.$realdata[2].') Type '.$realdata[5].'
                                                                                                         (unable to complete prescribed exercise or unable to train.)<br/>';
                                                                           }
                                                                            $injview = new Application_Model_DbTable_InjuriesView();
			                $select = $injview->select()->where('date = ?',$date)->where('muscle_group = ?',$realdata[1])->where("userid = ?",$this->user->userid);
                                                                            $injrow = $injview->fetchRow($select );
			                //$log = new Application_Model_DbTable_TestLog();
				//$log->log("cow");
				

                                                                            if($injrow == NULL){
                                                                                          $injuryentry->insert(array(
                                                                                              "reportid" => $reportid,
                                                                                              "inj_or_nig" => $realdata[0],
                                                                                              "muscle_group" => $realdata[1],
                                                                                              "pain_rating" => $realdata[2],
                                                                                              "tissue" => $realdata[3],
                                                                                              "type" => $realdata[4],
                                                                                              "training_modified" => $realdata[5],
                                                                                              "other_info" =>  $realdata[6]
                                                                                          ));
                                                                            }
                                                                            else{
                                                                                           $dup .=  $realdata[1]." niggle/injury has already been reported on ".$date."<br/>";
                                                                            }
                                                                            

                                                                           

                                                            }
			
                                                            if($alert != ""){ //if there are alert data
                                                                           $alert = "<h3>This is an auto alert generated by an athlete's input on ".$date."</h3><br/><br/>".$alert;
                                                                                          $alerthead = "Injuries & Niggle alerts";
                                                                                          $alerts = new Application_Model_DbTable_Alerts();
                                                                                          $alerts->doAlert($alerthead, $alert, $alertlevel,$this->user->userid,true);
                                                                           }
                                                            $session = new Zend_Session_Namespace('System');
                                                            if($dup != ""){
                                                                           $dup = "<br/><br/>but: ".$dup."Duplicate injuries have not been saved";
                                                            }
                                                            $session->msg = "Successfully reported injuries/niggles info for " . $date. "<br/>
                                                                           You have reported " . count($injuries) . " injuries/niggles in total.";
                                                            if($dup!=""){
                                                                           $session->msg .= $dup;
                                                            }
                                                            echo "success";


                                             }
                                             catch(SQLException $e){
                                                            $session = new Zend_Session_Namespace('System');
                                                            $session->msg = $e->getMessage();
			
                                                            echo "failed";
                                             }
                              }
               }
               function profileAction(){
                              //$this->view->email = $this->user->email;
                              $this->view->form = new Application_Form_Updateinfo();
                              if($this->getRequest()->isPost()){
                                             $data = $this->getRequest()->getPost();
                                             $system = new Zend_Session_Namespace("System");
                                             if($this->view->form->isValid($data) && ($data["password"]==$data["cpassword"]) && strlen($data["password"]) > 0 ){
                                                          unset($data["cpassword"]);
                                                          unset($data["submit"]);
                                                          $users = new Application_Model_DbTable_User();
                                                          $users->update($data, "userid = ".$this->user->userid);
                                                          $system->msg = "Your details has been saved successfully. You may need to re-login to see the changes.";
                                                          $system->bot = "happy";
                                             }
                                             else{
                                                            $system->msg = "Failed to save.<br/>Please check that all info are entered correctly, and both
                                                                           password and confirm password text field are completed and matched.";
                                                            $system->bot = "sad";
                                             }
                                             $this->_helper->redirector("index","message");
                              }
                              else{
                                             $users = new Application_Model_DbTable_User();
                                             $user = $users->getUserForUpdate($this->user->userid);
                                             $this->view->form->populate($user);

                              }
               }

               function saveemailAction(){
                    $this->_helper->layout->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);
                    if($this->getRequest()->isPost()){
                                   $data = $this->getRequest()->getPost();
                                   $emailval = new Zend_Validate_EmailAddress();
                                   if($emailval->isValid($data["email"])){
                                                  $user = new Application_Model_DbTable_User();
                                                  $user->update($data,"userid = ".$this->user->userid);
                                                  echo "New email saved! Please logout and login again to see the change.";

                                   }
                                   else{
                                                  echo "Invalid email address!";
                                   }
                    }
     }

     function dailyillnessAction(){
               $illtypes = new Application_Model_DbTable_Illnesstype();
               $this->view->illtypes = $illtypes->fetchAll();
     }

     function adddailyillnessAction(){
               $this->_helper->layout->disableLayout();
               $this->_helper->viewRenderer->setNoRender(true);
               if($this->getRequest()->isPost()){
	     $data = $this->getRequest()->getPost();
	     $wr = new Application_Model_DbTable_WeeklyReport();
	     $select = $wr->select()->where("date = ?",$data["date"])
	             ->where("userid = ?",$this->user->userid)
	             ->where("illnesstypeid = ?",$data["type"]);
	     
	     $row = $wr->fetchAll($select);
	    if(count($row)>0){
	              echo "This illness has already been reported on ".$data["date"];
	    }
	    else{
	            
	              $dat = array(
	                  "date"=>$data["date"],
	                  "userid"=>$this->user->userid,
	                  "illnesstypeid"=> $data["type"],
	                  "severity"=>$data["sev"],
	                  "type"=>$data["dur"],
	                  "activity_level"=>$data["act"],
	                  "comment"=>str_replace("[AMP]","&amp;",$data["com"])
	              );
	              if($wr->insert($dat)){
		    echo "Illness report has been saved.";
		    $alerthead = "New Illness report ";
		    $typear = array(NULL,"New","Recurrent","Ongoing","Chronic");
		    $actar = array(NULL,"Normal","Reduced","Modified","Unable to train");
		    $illtypes = new Application_Model_DbTable_Illnesstype();
		    $illtypes = $illtypes->fetchRow("id = ".$data["type"]);
		    $alertcontent = $this->user->name." ".$this->user->surname." has reported the following illness on ".$data["date"].":<br/>
		              <br/>[".$typear[(int)$data["dur"]]."] ".$illtypes["name"]." - ".$actar[(int)$data["act"]]." (Severity Lv. ".$data["sev"].")";
		    $alerts = new Application_Model_DbTable_Alerts();
		    $alertlevel = 1;
		    $athleteid = $this->user->userid;
		    $alerts->doAlert($alerthead,$alertcontent, $alertlevel,$athleteid,true);
	              }
	              else{
		    echo "An error occured while saving illness report.";
	              }
	    }
               }
     }
}

?>
