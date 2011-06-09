<?php
class CoachController extends Zend_Controller_Action{
               public $coachid = null;
               protected $coach = null;
    function init(){
	$auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
	    $user = $auth->getIdentity();
	   if($user->role != "C"){
	    $this->_helper->redirector('index','index');
	    }
                        else{
                                       $this->coachid = $user->userid;
                                       $this->coach = $user;
                        }

	}
                    else{
                                   $this->_helper->redirector('index','index');
                    }
	$this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
                    $this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js');
	$this->view->headScript()->appendFile('/js/roundcorner.js');
	$this->view->headScript()->appendFile('/js/script.js');
                    $this->view->headLink()->appendStylesheet('/css/jqueryUI.css');
	$this->view->headScript()->appendFile('/js/coach.js');
	$this->view->menu = Application_Model_DbTable_Coach::makeCoachMenu();
	
    }
    
    function indexAction(){
	$this->view->headTitle('Dashboard [Coach]');
                    $plan = new Application_Model_DbTable_Plan();
                    $cav = new Application_Model_DbTable_Coachathleteview();
                    $this->view->plancount = $plan->countPlans($this->coachid);
                    $this->view->athletecount = $cav->countAthleteUnderCoach($this->coachid);
	$users = new Application_Model_DbTable_User();
	$this->view->users = $users->getAllUsersForRem();
	
    }

    function deleteexerciseplanAction(){
                   $this->_helper->layout->disableLayout();
                                  $this->_helper->viewRenderer->setNoRender(true);
                   if($this->getRequest()->isPost()){
                                  $data = $this->getRequest()->getPost();
                                  $deleteid = $data["deleteid"];
                                  $exerciseplans = new Application_Model_DbTable_Exerciseplan();
                                  try{
                                                 $exerciseplans->delete('exerciseplanid = '.$deleteid);
                                                 echo "Exercise has been deleted"; //must always contain the word deleted
                                  }
                                  catch(Exeption $e){
                                                 echo "Failed to delete exerciseplan"; //must never have the word deleted
                                  }

                   }
    }

    function addexerciseplanAction(){
                   $this->_helper->layout->disableLayout();
                                  $this->_helper->viewRenderer->setNoRender(true);
                   if($this->getRequest()->isPost()){
                                  $data = $this->getRequest()->getPost();
                                  $form = new Application_Form_Exerciseplan();
                                  if($form->isValid($data)){
                                               $exerciseplan = new Application_Model_DbTable_Exerciseplan();
                                               try{
		                 if($data["reps_com"] == ""){$data["reps_com"]=NULL;}
		                 if($data["reps_com2"]==""){$data["reps_com2"]=NULL;}
			$sets = $data["sets"];
			$data["sets"] = 1;
			$planorder = new Application_Model_DbTable_Planorder();
			for($i=0;$i<$sets;$i++){
                                                              $exerciseplan->insert($data);
			  $planorder->addnewexerciseID($data["planid"],$data["exerciseid"],$data["am_or_pm"]);
			}
			  
			  
			  
                                                              $epid = $exerciseplan->fetchAll($exerciseplan->select()->from("exerciseplan",array('exerciseplanid'))
                                                                      ->order('exerciseplanid DESC')->limit(1));
                                                              $epid = $epid[0]["exerciseplanid"];
                                                               echo $epid."||success||<img src=\"/img/thumbup.png\" alt=\"\" /> Successfully added to the plan.";
                                               }
                                               catch(Exception $e){
                                                              echo '<img src="/img/thumbdown.png" alt="" /> Failed to add plan';
                                               }

                                  }
                                  else{
                                                echo 'x||failed||<img src="/img/thumbdown.png" alt="" /> An error has occured. Please make sure that weight, repititions and sets
                                                               must all be numbers.';
                                                var_dump($data);
                                  }
                   }
    }

    function createplanAction(){
                   $this->view->headTitle('Create a new plan');
                   $this->view->form = new Application_Form_Newplan();

                   if($this->getRequest()->isPost()){
                                  $data = $this->getRequest()->getPost();
                                  if($this->view->form->isValid($data)){
                                                 $plans = new Application_Model_DbTable_Plan();
                                                 unset($data["submit"]);
                                                 $sd = strtotime($data["startdate"]);
                                                 $ed = strtotime($data["enddate"]);
                                                 $data["duration"] = $data["enddate"];// ($ed - $sd + 86400) / 86400;
                                                 unset($data["enddate"]);
                                                 unset($data["planid"]);
                                                 $session = new Zend_Session_Namespace('System');
		         $addedplanid = $plans->addPlan($data);
                                                 if($addedplanid > 0){
			    $planorder = new Application_Model_DbTable_Planorder();
			    $planorder->insert(array("planid"=>$addedplanid));
                                                                $session->msg = "Plan `".$data['planname']."` has been added.";
                                                                $session->bot = "happy";
                                                 }
                                                 else{
                                                                $session->msg = "Failed to add plan. Probably because a plan of
                                                                               the same name already exist, please try again.";
                                                                $session->bot = "sad";
                                                 }
                                                 $this->_helper->redirector('index','message');
                                  }
                                  else{
                                                 $this->view->form->populate($data);
                                  }
                   }
    }
    function delexAction(){
                   $this->_helper->layout->disableLayout();
                   $this->_helper->viewRenderer->setNoRender(true);
                   if($this->getRequest()->isPost()){
                                  $data = $this->getRequest()->getPost();
                                  $exercise = new Application_Model_DbTable_Exercise();
                                  if($exercise->delete("exerciseid = ".$data["id"])){
                                                 echo "Exercise ID ".$data["id"]. " has been removed.";
                                  }
                                  else{
                                                echo "Exercise ID ".$data["id"]. " has NOT been removed.";
                                  }
                   }
    }
    function exerciseAction(){
	$this->view->headTitle(' Exercise types');
                    $exercises = new Application_Model_DbTable_Exercise();
                    $this->view->exerciselist = $exercises->makeExerciseList();
                     if($this->getRequest()->isPost()){
                                   $this->_helper->layout->disableLayout();
                                  $this->_helper->viewRenderer->setNoRender(true);
                                  $data = $this->getRequest()->getPost();
                                  if($data["e"] && $data["id"] && $data["type"]){
                                                 $result =  $exercises->addExercise($data["e"],$data["id"],$data["type"]);
                                                 echo $result;
                                  }
                                  elseif($data["newexerciselist"]){
                                                 echo $this->view->exerciselist;
                                  }
                                  
                   }
    }
    function archiveplanAction(){
                   $this->_helper->layout->disableLayout();
                   $this->_helper->viewRenderer->setNoRender(true);
                   if($this->getRequest()->isPost()){
                                  $data = $this->getRequest()->getPost();
                                  $plans = new Application_Model_DbTable_Plan();
                                  $planrow = $plans->fetchRow("planid = ".$data["apid"]);
                                  $planrow->archived = "1";
                                  if($planrow->save()){
                                                 echo "success";
                                  }
                                  else{
                                                 echo "failed";
                                  }
                   }
    }

    function plansAction(){
                   $this->view->headTitle('Plans');
                   

                   if($this->getRequest()->getParam('planid')){
                                  $this->view->planid = $this->getRequest()->getParam('planid');
                                  $cav = new Application_Model_DbTable_Coachathleteview();
                                  $this->view->athleteselection = '<select id="athleteselection" name="athleteselection">'.
                                          $cav->makeAthleteSelect($this->coachid, $this->getRequest()->getParam('planid'))
                                                  .'</select>';
                                  $plans = new Application_Model_DbTable_Plan();
                                  $exerciseplans = new Application_Model_DbTable_Exerciseplan();
                                  $this->view->exerciseplans = $exerciseplans->getExercisePlans($this->getRequest()->getParam('planid'));
                                  $this->view->form = new Application_Form_Newplan();
	             
                                  $form2 = new Application_Form_Exerciseplan();
                                  $form2->planid->setValue($this->getRequest()->getParam('planid'));
	              $planinfo = $plans->getPlan($this->getRequest()->getParam('planid'));

	               if($planinfo["powerlift"] == 1){
		    $this->view->forpowerlift = true;
	              }
                                  $exercise = new Application_Model_DbTable_Exercise();
                                  $form2->date->addMultiOptions(
                                                  $plans->makeDateSelection($this->getRequest()->getParam('planid'))
                                                          );
                                  $form2->exerciseid->addMultiOptions(
                                          $exercise->makeExerciseSelection());
                                  $form2->exerciseid_com->addMultiOptions(array(""=>""));
                                  $form2->exerciseid_com->addMultiOptions(
                                          $exercise->makeExerciseSelection());
	              $form2->exerciseid_com2->addMultiOptions(array(""=>""));
                                  $form2->exerciseid_com2->addMultiOptions(
                                          $exercise->makeExerciseSelection());

                                  $planid = $this->getRequest()->getParam("planid");
                                   $cav = new Application_Model_DbTable_Coachathleteview();
                                   $ret = "<ul>";
                                   $list = $cav->listAthletes($this->coachid, $planid);
                                   foreach($list as $at){
                                                  $ret .= '<li id="at'.$at["athleteid"].'">'.$at["name"].' '.$at["surname"].'  <img src="/img/cross.png" class="removeathfrmlst" alt="" /></li>';
                                   }
                                   $ret .= '</ul>';
                                   $this->view->athletelist = $ret;




                                  $this->view->addexerciseform = $form2;
                                  $plandata = new Application_Model_DbTable_Plan();
                                  $data = $plandata->getPlan($this->getRequest()->getParam('planid'));
                                  $session = new Zend_Session_Namespace('System');
                                  $sd = strtotime($data["startdate"]);
                                  $et = $data["duration"]*86400;
                                  $data["enddate"] =  $data["duration"]; //date("Y-m-d",($sd + $et - 86400));
                                  unset($data["duration"]);
                                  unset($data["creator"]);
                                  if($data == null){
                                                 $session->msg = 'Failed to get plan information. Either the plan does not exist,
                                                                or you are not the creator of this form.';
                                                 $session->bot = 'sad';
                                                 $this->_helper->redirector('index','message');
                                  }
                                  $this->view->form->populate($data);
                   }
    }

    function updateplanAction(){
                   $this->_helper->layout->disableLayout();
                   $this->_helper->viewRenderer->setNoRender(true);
                   if($this->getRequest()->isPost()){
                                  $plans = new Application_Model_DbTable_Plan();
                                  $data = $this->getRequest()->getPost();
                                  if($data["getplanlist"]){
                                                 echo  $plans->getPlans("id");
                                                 exit;
                                  }
                             $intval = new Zend_Validate_Int();
	         $dateval = new Zend_validate_Regex('/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/');
	         $stringval = new Zend_Validate_StringLength(1,200);
                                  if(
		  $intval->isValid($data["enddate"])  &&
		  $intval->isValid($data["planid"]) &&
		  $dateval->isValid($data["startdate"]) &&
		  $stringval->isValid($data["planname"])
	                  ){
                                                 $sd = strtotime($data["startdate"]);
                                                 //$ed = strtotime($data["enddate"]);
                                                 $data["duration"] = (int) $data["enddate"]; //($ed-$sd+86400)/86400;
                                                 unset($data["enddate"]);
                                                 if($plans->update($data, "planid = ".$data["planid"])){
                                                                echo "Plans info successfully changed.";
                                                 }
                                                 else{
                                                                echo "Failed to change plan info.";
                                                 }
                                  }
                   }
    }

    function planathletelistAction(){
                   $this->_helper->layout->disableLayout();
                   $this->_helper->viewRenderer->setNoRender(true);
                   $planid = $this->getRequest()->getParam("planid");
                    $cav = new Application_Model_DbTable_Coachathleteview();
                    $ret = "<ul>";
                    $list = $cav->listAthletes($this->coachid, $planid);
                    foreach($list as $at){
                                   $ret .= '<li id="at'.$at["athleteid"].'">'.$at["name"].' '.$at["surname"].' <img src="/img/cross.png" class="removeathfrmlst" alt="" /></li>';
                    }
                    $ret .= '</ul>';
                    echo $ret;

    }
    function attachathleteAction(){
                   $this->_helper->layout->disableLayout();
                   $this->_helper->viewRenderer->setNoRender(true);
                   if($this->getRequest()->isPost()){
                                  try{
                                                 $adata = $this->getRequest()->getPost();
                                                 $data = array("planid"=>$adata["plan"]);
                                                 $where = "athleteid = ".$adata["ath"];
                                                 $cav = new Application_Model_DbTable_Coachathleteview();
                                                 $cav->update($data, $where);
		         $history = new Application_Model_DbTable_Planhistory();
		         $history->insert(array("userid"=>$adata["ath"],"planid"=>$adata["plan"]));
                                                 echo "success";
                                  }
                                  catch(Exception $e){
                                                 echo $e->getMessage();
                                  }
                   }
    }

    function detachathleteAction(){
                   if($this->getRequest()->isPost()){
                                  $adata = $this->getRequest()->getPost();
                                  $data = array("planid" => 0);
                                  $where = "athleteid = ".$adata["aid"];
                                  $cav = new Application_Model_DbTable_Coachathleteview();
                                  $cav->update($data, $where);
                                  $this->_helper->layout->disableLayout();
                   $this->_helper->viewRenderer->setNoRender(true);
                   }
    }

    function viewathAction(){
                   $this->view->headTitle("Athletes");
                   $cav = new Application_Model_DbTable_Coachathleteview();
                   $this->view->athletelist = $cav->getAthleteUnderCoach($this->coachid);
                   //$this->view->ua = $cav->getUnassignedAthlete();
                   $this->view->ua = $cav->getAllAthletes();
    }

    function doattachAction(){
                                   $this->_helper->layout->disableLayout();
                                  $this->_helper->viewRenderer->setNoRender(true);
                                   if($this->getRequest()->isPost()){
                                                  $data = $this->getRequest()->getPost();
                                                  $sp = new Application_Model_Super();

                                                  if($sp->attachathletetocoach($data["aid"], $this->coachid)){
                                                                 echo "success";
                                                  }
                                                  else{
                                                                 echo "failed";
                                                  }
                                   }
                    }
     function monitorAction(){
                    $this->view->headTitle("Monitor Athletes");
                    $this->view->headLink()->appendStylesheet('/css/graph.css');
                    $cav = new Application_Model_DbTable_Coachathleteview();
                    $this->view->athletes = $cav->getAthleteUnderCoach($this->coachid);
     }

     function detachfrommeAction(){
                    $this->_helper->layout->disableLayout();
                   $this->_helper->viewRenderer->setNoRender(true);
         if($this->getRequest()->isPost()){
             $data =$this->getRequest()->getPost();
             $ca = new Application_Model_DbTable_CoachAthlete();

             if($ca->detachfromcoach($data["aid"])){
                 echo "success";
             }
             else{
                 echo "failed";
             }
         }
     }


     function athsumAction(){
                    $this->view->headTitle("Athlete Summary");
                    $this->view->headLink()->appendStylesheet('/css/athsum.css');
                    $cav = new Application_Model_DbTable_Coachathleteview();
                    $this->view->athletes = $cav->getAthleteUnderCoach($this->coachid);
                    $this->view->form = new Application_Form_Uploadfile();

                    if($this->getRequest()->isPost()){
                                $data = $this->getRequest()->getPost();
                                //print getcwd();
                                $session = new Zend_Session_Namespace('System');
                                if($this->view->form->isValid($data)){
                                               $upload = new Zend_File_Transfer_Adapter_Http();
                                               $upload->setDestination("userpicture");
                                               try{
                                                              $upload->receive();
                                               }
                                               catch(Zend_File_Transfer_Exception $e){
                                                              $e->getMessage();
                                               }

                                               $uploadedData = $this->view->form->getValues();
                                               Zend_Debug::dump($uploadedData, 'Form Data: ');

                                               $name = $upload->getFileName('file_path');
                                               $upload->setOptions(array('useByteString' => false));
                                               $size = $upload->getFileSize('file_path');
                                               $mimeType = $upload->getMimeType('file_path');

                                               print "name: ". $name;
                                               print "File size: " . $size;

                                               $renamefile = time() . '.jpg';
                                               $fullrenamepath = 'userpicture/'.$renamefile;

                                               $filterFileRename = new Zend_Filter_File_Rename(array('target'=> $fullrenamepath, 'overwrite' => true));
                                               $filterFileRename->filter($name);

                                               $session->msg = "Image saved. [Size: ".round ((int) $size / 1024) . " KB.]";

                                               $usr = new Application_Model_DbTable_User();
                                               try{
                                                              $dataa = array(
                                                                  "picture" => $renamefile
                                                              );
                                                              $usr->update($dataa, "userid = '". $data["useridh"]."'");
                                               }
                                               catch(Exception $e){
                                                              echo $e->getMessage();
                                                              $session->msg = "Error Inserting data into the database...";
                                               }
                                               $this->_helper->redirector('index','Message');
                                }

                    }
     }

     function fetchathleterowAction(){
                    $this->_helper->layout->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);
                    if($this->getRequest()->isPost()){
                                   $data = $this->getRequest()->getPost();
                                   $users = new Application_Model_DbTable_User();
                                   $user = $users->fetchRow("userid = ".$data["aid"]);
                                   $dailyreports = new Application_Model_DbTable_Dailyreport();
                                   $mepv = new Application_Model_DbTable_Markedexerciseplanview();
                                   $dailyreport = $dailyreports->fetchAllForUser($data["aid"]);
                                   $recentcomment = " ";
                                   $latestweight = 0;
                                             foreach($dailyreport as $d){
                                                            $recentcomment .= ($d["comment"]) ?  $d["date"]." &raquo; ".$d["comment"] . "<br/>"  : "";
                                                            $latestweight = $d["bw_morning"];
                                             }
                                   
                                   $dobarray = explode("-",$user["date_of_birth"]);
                                   $dobtimestamp = mktime(0,0,0,$dobarray[1],$dobarray[2],$dobarray[0]);
                                   $agestamp = time() - $dobtimestamp;
                                   $age = floor($agestamp / 31556926);

                                   $inj = new Application_Model_DbTable_Injuries();
                                   $injcount = $inj->countinjuries($data["aid"]);

                                   $statustoday = $inj->getTrafficLightCurrent($data["aid"]);
                                   $amberandred = $inj->getInjuries30days($data["aid"]);

                                   echo $user["name"] . " " . $user["surname"] . "||" . $age . "|| ||" . date("j M Y",time())
                                             . "||" . $user["height"] . "||" . $latestweight . "||" . $recentcomment . "||" . $user["current_max_snatch"]
                                              . "||" . $user["current_max_cj"]. "||" . $user["body_fat"];
                                 $percent =  $mepv->getAvLoadRepsPercent30days($data["aid"]);
                                 echo '||'.$percent[0].'||'.$percent[1].'||'.$user["picture"] . '||'.$injcount.'||'.$statustoday
                                 .'||'.$amberandred[0].'||'.$amberandred[1];
                                   
                    }
     }

     function historyAction(){
                    $cav = new Application_Model_DbTable_Coachathleteview();
                    $this->view->athletelist = $cav->getAthleteUnderCoach($this->coachid);
                    
                    if($this->getRequest()->getParam("atid")){
                              $wr = new Application_Model_DbTable_WeeklyReport();
                              $id = $this->getRequest()->getParam("atid");
                              $users = new Application_Model_DbTable_User();
                              $user = $users->fetchRow("userid = ".$id);
                              $this->view->username = $user["name"]." ".$user["surname"];
                              $this->view->illnesslist = $wr->getUserReport($id);
                              $this->view->illnesstype = $wr->getIllnessTypes();

                              $inj = new Application_Model_DbTable_Injuries();
                              $this->view->injurylist = $inj->getinjuries($id);
                    }
     }

     function updateathsumAction(){
                    $this->_helper->layout->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);
                    if($this->getRequest()->isPost()){
                                   $data = $this->getRequest()->getPost();
                                 
                                   $updatedata = array(
                                     "current_max_snatch"  => (int) $data["maxsnatch"],
                                     "current_max_cj" => (int) $data["maxcj"],
                                       "body_fat" => (int) $data["bf"]
                                   );
                                   $user = new Application_Model_DbTable_User();
                                   try{
                                                  $user->update($updatedata, "userid = ".(int) $data["aid"]);
                                                  echo "success";
                                   }
                                   catch(SQLException $e){
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
                                                  $user->update($data,"userid = ".$this->coachid);
                                                  echo "New email saved! Please logout and login again to see the change.";

                                   }
                                   else{
                                                  echo "Invalid email address!";
                                   }
                    }
     }

     function alertAction(){
                    $cav = new Application_Model_DbTable_Coachathleteview();
                    $athlist = $cav->fetchAll("coachid = ".$this->coachid);
                    $alert = new Application_Model_DbTable_Alerts();
                    $retdata = array();
                    foreach($athlist as $athlete){
                                   $id = $athlete["athleteid"];
                                   $name = $athlete["name"] . " " . $athlete["surname"];
                                   $arraytoadd = array();
                                   $alertsforthisath = $alert->fetchAll($alert->select()->where("athleteid = ".$id)->limit(5)->order("date DESC"));
                                   foreach($alertsforthisath as $afts){
                                                  $arraytoadd[] = array("date"=>$afts["date"], "alerthead"=>$afts["alerthead"], "alertcontent" => 
                                                      str_replace("<h3>This is an auto alert generated by an athlete's input</h3><br/><br/>","",$afts["alertcontent"]));
                                   }
                                   $retdata[]= array("name"=> $name, "data" => $arraytoadd);
                    }
                    $this->view->alertdata = $retdata;
     }

     function importAction(){
	
                    if($this->getRequest()->isPost()){
	          $this->_helper->layout->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);
                                   $data = $this->getRequest()->getPost();
                                   $planname = $data["planname"];
                                   $data = explode("\r\n",$data["rawdata"]);
	               $duration = 1;
	               $daterow=null;
	               $lastdate=null;//unused
                                   foreach($data as $row){
                                                  if(preg_match('/Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday/i',$row)){
                                                            //$duration++;
			preg_match('/^[0-9]{1,2}/',$row,$lastdate);
                                                  }
		          if($daterow == null && preg_match('/[0-9]{4}\.[0-9]{2}\.[0-9]{2}/',str_replace(" ","",$row))){
			$daterow = $row;
		          }
                                   }
                                   
                                   $daterow = str_replace(array(" ","\t"),"",$daterow);
                                   preg_match('/[0-9]{4}\.[0-9]{2}\.[0-9]{2}/',$daterow,$date);
                                   $startdate = strtotime(str_replace(".","-",$date[0]));
                                   array_shift($data);
	               //echo $startdate;
	               for($i=0;$i<31;$i++){
		     if(date("d",$startdate+($i*86400)) != $lastdate[0]){
		               $duration++;
		     }
		     else{
		               break;
		     }
	               }
	               //echo "<br/>";
	               //echo $duration;
                                   //exit;
                                   $creator = $this->coachid;
                                   //need to insert a new plan here
//uncomment this                 
  	              $plan = new Application_Model_DbTable_Plan();
                                  $planid = $plan->insert(array("planname"=>$planname,"startdate"=>date("Y-m-d",$startdate),"duration"=>$duration,"creator"=>$creator));
                                   echo date("Y-m-d",$startdate);
	               echo $duration;
	              echo "planid: ".$planid;
                                   //----------------
                                   $rowdate = $startdate - 86400;
                                   $datestring = "";
                                   $final = "INSERT INTO `exerciseplan` VALUES ";
                                   $finalcount = 0;

                                   //Zend_Debug::dump($data);
				   $currentrow = 0;
				   $first = true;

                                   foreach($data as $row){ 
                                                  if(preg_match('/Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday/i',$row)){
						      $lineforpm = false;
						      $currentrow = 0;
                                                      $tmprow = $row;
						      $tmprow2 = $row;
						      $line = preg_match("/line:[0-9\s]+/i", $tmprow2,$matches);
						      if($line>0){
							$lineforpm = (int) preg_replace("/[^0-9]/i","",$matches[0]);
						      }
                                                      $datenumberonplan = ereg_replace("[^0-9]","",substr(trim($tmprow),0,5));
                                                               $rowdate = strtotime("+1 day",$rowdate);
                                                               $c = 0;
                                                               while((int)date("d",$rowdate) != (int)$datenumberonplan && $c<15){
                                                                 $rowdate = strtotime("+1 day",$rowdate);
                                                                 $c++;

                                                               }
                                                               echo "<br/>$rowdate<br/>";
                                                               $datestring = date("Y-m-d",$rowdate);
                                                               echo $datestring;
							       echo "<br/>";
							       echo "pm line:".$lineforpm;echo "<br/>";
                                                  }
						  elseif(preg_match('/^[0-9\t\s]+$/',$row)){
						      //do nothing
						            
						  }
                                                  elseif(preg_match('/^[0-9\/\t\s\-]{3,}$/',$row)){ //if this row is full of exercises
                                                                 //echo "exercise row<br/>";
			    
								 //echo $currentrow . "<br/>";
								$currentrow += 1;
								$pm = 0;
								
								if((int)$lineforpm < (int)$currentrow && (int)$lineforpm != 0){
								    $pm = 1;
								}
								//echo "Line for pm is ".(int)$lineforpm.", and current row is $currentrow. So PM is $pm<br/>";
								$exerciserow = str_replace('/\s/',"",$row);
                                                                 $exerciserow = explode("\t",$exerciserow);
                                                                 $eid = explode("/",$exerciserow[0]);
                                                                 $exerciseid = $eid[0];
                                                                 $exerciseid_com = $eid[1];
			     $exerciseid_com2 = $eid[2]; //****
                                                                 array_shift($exerciserow);
                                                                 //echo "ex:$exerciseid, exid_com:$exerciseid_com<br/>";

                                                                 foreach($exerciserow as $exitem){
			               if(preg_match('/^[0-9\/]+$/',$exitem)){
				     //echo $exitem . "<br/>";
				          $tmp = explode("/",$exitem);
				          $weight = $tmp[0];
				          if(!$weight){
					     $weight = '0';
				          }
				          $reps = $tmp[1];
				          $reps_com = $tmp[2];
				          $reps_com2 = $tmp[3];//****
				          if($exerciseid_com){
					     $exidcom = "/".$exerciseid_com;
				          }
				          if(strlen($reps)){
					     if($finalcount!=0){
						$final .= ",";
					     }
					     $toprintexerciseidcom2 = $exerciseid_com2 ? "'$exerciseid_com2'" : "NULL";
					     $toprintrepscom2 = $reps_com2 ? "'$reps_com2'" : "NULL";
					$final .= "('','$planid','$datestring','$exerciseid',$weight,$reps,1,'','$exerciseid_com','$reps_com','$pm',NULL,$toprintexerciseidcom2,$toprintrepscom2)";//****
					//echo "('','$planid','$datestring','$exerciseid',$weight,$reps,1,'','$exerciseid_com','$reps_com','$pm')";


					$finalcount++;
				          }
			          }
                                                                 }
								 
                                                  }
		          
						  

                                   }
				
	               Application_Model_DbTable_TestLog::log($final);
                                   $exerciseplan = new Application_Model_DbTable_Exerciseplan();
                                   $session = new Zend_Session_Namespace("System");
                                   $exe = $exerciseplan->getAdapter()->prepare($final);

	               
                                   if($exe->execute()){
                                                  $session->msg = "Imported successfully!<br/> <a href=\"/coach/plans/planid/".$planid."\">Take me to the imported plan</a>";
                                                  $session->bot = "happy";
                                   }
                                   else{
                                                  $session->msg = "Failed to import plan. Please check that the plan's name is not already exist";
                                                  $session->bot = "sad";
                                   }
	               

                                   $this->_helper->redirector("index","Message");
                    }
     }

     function excompAction(){

                    $cav = new Application_Model_DbTable_Coachathleteview();
                    $cavfetch = $cav->fetchAll("coachid = ".$this->coachid, "name ASC");
                    $this->view->users = $cavfetch->toArray();
                   

     }

     function deleteplanAction(){
                    $this->_helper->layout->disableLayout();
                    $this->_helper->viewRenderer->setNoRender(true);
                    $id = $this->getRequest()->getParam("id");
                    $plans = new Application_Model_DbTable_Plan();
                    $system = new Zend_Session_Namespace("System");
                    if($plans->delete("planid = ".(int)$id)){
                                   $system->bot = "happy";
                                   $system->msg = "Plan deleted.";
                    }
                    else{
                                   $system->bot = "sad";
                                   $system->msg = "Something went wrong. Please try again...";
                    }
                    $system->redir = "/coach/plans";
                    $system->redirtext = "Back to plans";
                    $this->_helper->redirector("index","Message");
     }

     function pastexcompAction(){
	$cav = new Application_Model_DbTable_Coachathleteview();
                    $cavfetch = $cav->fetchAll("coachid = ".$this->coachid,"name ASC");
                    $this->view->users = $cavfetch->toArray();

     }

     function checkdailyreportcompletionAction(){
               $cav = new Application_Model_DbTable_Coachathleteview();
               $allathundrecoach = $cav->fetchAll($cav->select()->where("coachid = ".$this->coachid)
	   ->order("name ASC"));
               $allathundrecoach = $allathundrecoach->toArray();
               $athlist = array();
               foreach($allathundrecoach as $auc){
	     $athlist[$auc["athleteid"]] = ucfirst($auc["name"]) . " " .  ucfirst($auc["surname"]);
               }
               $this->view->athletelist = $athlist;
               //Zend_Debug::dump($athlist);
               $dailyreports = new Application_Model_DbTable_Dailyreport();
               $date = time();
               $length = 30; //days
               $donelist = array();
               for($i=0;$i<$length;$i++){
	     $tmp = $dailyreports->fetchAll($dailyreports->select()->from("dailyreport",array("userid"))->where("date = '".date("Y-m-d",($date)-($i*86400))."'"));
	     $tmp=$tmp->toArray();
	     
	     if(empty($tmp)){$donelist[$i]["noone"]=1;}
	     foreach($tmp as $t){
	               $donelist[$i][]= (int) $t["userid"];
	               
	     }

               }

               $this->view->donelist = $donelist;
               //Zend_Debug::dump($donelist);
     }

     function getathinfoAction(){
               $this->_helper->layout->disableLayout();
               $this->_helper->viewRenderer->setNoRender(true);
               if($this->getRequest()->isPost()){
	     $data = $this->getRequest()->getPost();
	     $users = new Application_Model_DbTable_User();
	     $user = $users->fetchRow("userid = ".(int)$data["uid"]);
	     $user = $user->toArray();
	     echo ucfirst($user["name"])." ".ucfirst($user["surname"])."|".$user["email"]."|".$user["picture"];
               }
     }

     function makemaxtableAction(){
               $this->_helper->layout->disableLayout();
               $mepv = new Application_Model_DbTable_Markedexerciseplanview();
               $this->view->table = $mepv->getmaxtabledata($this->coachid);
     }

}
?>