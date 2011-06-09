<?php

class PrintController extends Zend_Controller_Action {
               function printplan2Action(){
                   $this->_helper->layout->disableLayout();
                   $misc = new Application_Model_Misc();
                   $plans = new Application_Model_DbTable_Plan();
                   $auth = Zend_Auth::getInstance();
	                   $user = $auth->getIdentity();
	                   if($this->getRequest()->getParam("hidetick")){
		         $this->view->hidetick = true;
	                   }
                   if($this->getRequest()->getParam("custom")){
	         $useridwoot = 0;
	         if($this->getRequest()->getParam("userid")){
	                   $useridwoot = $this->getRequest()->getParam("userid");
	         }
	         else{
	                   
	                   $useridwoot = $user->userid;
	         }
	         if($this->getRequest()->getParam("past")){
	                   $exerciseitems = $misc->selectexercisesByPlanIdPRINTcustomAll($this->getRequest()->getParam("startdate"),$this->getRequest()->getParam("enddate"),$useridwoot);
	         }
	         else{
		$exerciseitems = $misc->selectexercisesByPlanIdPRINTcustom($this->getRequest()->getParam("startdate"),$this->getRequest()->getParam("enddate"),$useridwoot);
		$logs = new Application_Model_DbTable_TestLog();
		//$logs->log(var_export($exerciseitems,true));
	         }
                   }
                   else{
	          if($this->getRequest()->getParam("showcustom")){
		$exerciseitems = $misc->selectexercisesByPlanIdPRINT($this->getRequest()->getParam("planid"),true,$this->getRequest()->getParam("userid"));
	          }
	          else{
		$exerciseitems = $misc->selectexercisesByPlanIdPRINT($this->getRequest()->getParam("planid"));
	          }
                   }
                   $this->view->userid = $this->getRequest()->getParam("userid");
                   $this->view->userrole = $user->role;
                   $this->view->hascss = $this->getRequest()->getParam("hascss");
                   $plan = $plans->getPlan($this->getRequest()->getParam("planid"));
                   $exerciselist = new Application_Model_DbTable_Exercise();
                   $mex = new Application_Model_DbTable_Markedexerciseplanview();
                   if($this->getRequest()->getParam("formon")){
	         if($this->getRequest()->getParam("showcustom")){
	                   $dolist = $mex->getMarkedExerciseInPlan2($this->getRequest()->getParam("planid"),$this->view->userid,true);
	         }
	         else{
		$dolist = $mex->getMarkedExerciseInPlan2($this->getRequest()->getParam("planid"),$this->view->userid);
	         }
                   }
                   else{
	         if($this->getRequest()->getParam("showcustom")){
	                   $dolist = $mex->getMarkedExerciseInPlan2($this->getRequest()->getParam("planid"),null,true);
	         }
	         else{
		if($this->getRequest()->getParam("past")){
		          $dolist = $mex->getMarkedExerciseInPlan2pex($this->getRequest()->getParam("userid"),
		                  $this->getRequest()->getParam("startdate"),
		                  $this->getRequest()->getParam("enddate"));
		}
		else{
		          $dolist = $mex->getMarkedExerciseInPlan2($this->getRequest()->getParam("planid"));
		}
	          
	         }
                   }
                   $this->view->donelist = $dolist[0];
                   //var_dump($this->view->donelist);exit;
                   //Application_Model_DbTable_TestLog::log($this->view->donelist);
                   $this->view->detaillist = $dolist[1];
                   //Application_Model_DbTable_TestLog::log($this->view->detaillist);
                   $exlist = $exerciselist->fetchAll();
                   $exercisearray = array();
                   $columnlimit = 0;

                   foreach($exlist as $exl){
                                  $exercisearray[$exl["exerciseid"]] = $exl["exercisename"];
                   }
                   unset($exerciselist, $exlist);

                   $this->view->exercisearray = $exercisearray;
                   $mainarray = array();
                   $secondary = array();
                   //drop the results into mainarray in order of dates
                   foreach($exerciseitems as $ei){
                                  $mainarray[$ei["date"]][] = $ei;
                   }
                   foreach($mainarray as $key => $m){
                                  
                                  $temparray = array();
	              $customtemp = array();
                                  foreach($m as $item){
		    if($item["planid"]==2147483647){
		          $customtemp[]=$item;
		          continue;
		    }
                                                 $id = $item["exerciseid"];
                                                 if($item["exerciseid_com"]){
                                                                $id.= "/".$item["exerciseid_com"];
                                                 }
		         //third combined exercise
		         if($item["exerciseid_com2"]){
		                   $id.= "/".$item["exerciseid_com2"];
		         }
		           if($item["am_or_pm"]!= 0 && $item["am_or_pm"]!=NULL){
		               $id .= "pm";
		          }
		          // if($item["planid"]==2147483647){
			//$customtemp[]=$item;
		          //}
		         // else{
			$temparray[(String)$id][]=$item;
		          //}

                                  }

	              //next sort for customs
	              foreach($customtemp as $item){

                                                 $id = $item["exerciseid"];
                                                 if($item["exerciseid_com"]){
                                                                $id.= "/".$item["exerciseid_com"];
                                                 }
		         //third combined exercise
		         if($item["exerciseid_com2"]){
		                   $id.= "/".$item["exerciseid_com2"];
		         }
		           if($item["am_or_pm"]!= 0 && $item["am_or_pm"]!=NULL){
		               $id .= "pm";
		          }

			$temparray[(String)$id][]=$item;


                                  }
	              


                                  $secondary[$key] = $temparray;
                   }
                   //Application_Model_DbTable_TestLog::log($secondary);
                   $this->view->alldata=$secondary;
                   
                   $this->view->plandata=$plan;
                   if($this->getRequest()->getParam("printpage")){
                                  $this->view->printpage = true;
                   }
                   $fullplandata = $plans->fetchRow("planid = ". $this->getRequest()->getParam("planid"));
                   if($fullplandata["powerlift"] == 1){//($this->getRequest()->getParam("planid") == 116){
	         $this->view->hascustom = $this->getRequest()->getParam("custom");
	         $this->view->hasprintpage = $this->getRequest()->getParam("printpage");
	         $this->render('printplan2powerlift');
                   }

               }
               function printplanAction(){
                   $this->_helper->layout->disableLayout();
                   //$this->_helper->viewRenderer->setNoRender(true);
                   $misc = new Application_Model_Misc();
                   $plans = new Application_Model_DbTable_Plan();
                   $lul = $misc->selectexercisesByPlanIdPRINT($this->getRequest()->getParam("planid"));
                   $plan = $plans->getPlan($this->getRequest()->getParam("planid"));

                   $this->view->plan = $plan;

                   $exerciselist = new Application_Model_DbTable_Exercise();
                   $exlist = $exerciselist->fetchAll();
                   $exercisearray = array();

                   foreach($exlist as $exl){
                                  $exercisearray[$exl["exerciseid"]] = $exl["exercisename"];
                   }
                   unset($exerciselist, $exlist);

                   $this->view->exercisearray = $exercisearray;

                   //dividing them into days
                   $days = array();
                   $startdate = $lul[0]["date"];
                   $firstindex = 0;
                   $this->view->cellsperrow = 14;
                   foreach($lul as $l){
                                  if($l["date"] != $startdate){
                                                 $firstindex++;
                                                 $startdate = $l["date"];
                                  }
                                  $days[$firstindex][] = $l;

                   }
                   $this->view->days = $days;

               }
}

?>
