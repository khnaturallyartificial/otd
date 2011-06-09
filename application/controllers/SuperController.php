<?php
 class SuperController extends Zend_Controller_Action {
                protected $user = null;
 	function init(){
                              $auth = Zend_Auth::getInstance();
                               if($auth->hasIdentity()){
                              $user = $auth->getIdentity();
                              if($user->role != "S"){
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
                                   $this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js');
                                   $this->view->headScript()->appendFile('/js/roundcorner.js');
                                   $this->view->headScript()->appendFile('/js/script.js');
                                   $this->view->headScript()->appendFile('/js/super.js');
                                   $this->view->headLink()->appendStylesheet('/css/jqueryUI.css');
                                   $this->view->headLink()->appendStylesheet('/css/super.css');
                                   $this->view->menu = Application_Model_Super::makeSuperMenu();
 	}
 	
 	function indexAction(){
                                   $this->view->headTitle("Dashboard - Super User");
	               $users = new Application_Model_DbTable_User();
	          $this->view->users = $users->getAllUsersForRem();
                    }

                    function attachAction(){
                                   $this->view->headTitle("Attach Athlete");
                                   $cav = new Application_Model_Super();
                                   $this->view->athleteselect = $cav->makeUserSelection("A");
                                   $this->view->coachselect = $cav->makeUserSelection("C");
                                   $this->view->coachathletelist = $cav->makeCoachAthleteList();
                    }

                    function dodetachAction(){
                                   $this->_helper->layout->disableLayout();
                                  $this->_helper->viewRenderer->setNoRender(true);
                                   if($this->getRequest()->isPost()){
                                                  $data = $this->getRequest()->getPost();
                                                  $s = new Application_Model_Super();
                                                  if($s->detachAthlete($data["aid"])){
                                                                 echo "success";
                                                  }
                                                  else{
                                                                 echo "fail";
                                                  }
                                   }
                    }

                    function newsAction(){
                                   $news = new Application_Model_DbTable_News();
                                  $this->view->newslist = $news->getNews(30);
                    }

                    function doattachAction(){
                                   $this->_helper->layout->disableLayout();
                                  $this->_helper->viewRenderer->setNoRender(true);
                                   if($this->getRequest()->isPost()){
                                                  $data = $this->getRequest()->getPost();
                                                  $sp = new Application_Model_Super();
                                                 
                                                  if($sp->attachathletetocoach($data["aid"], $data["cid"])){
                                                                 echo "success";
                                                  }
                                                  else{
                                                                 echo "failed";
                                                  }
                                   }
                    }

                    function addnewsAction(){
                                   $this->_helper->layout->disableLayout();
                                  $this->_helper->viewRenderer->setNoRender(true);
                                  
                                   if($this->getRequest()->isPost()){
                                                  $data = $this->getRequest()->getPost();
                                                  $news = new Application_Model_DbTable_News();
                                                  try{
                                                                 $news->insert($data);
                                                                 echo "success";
                                                  }
                                                  catch(Exception $e){
                                                                 echo "failed";
                                                  }
                                   }
                    }

	function checkloginAction(){
	          $loginlog = new Application_Model_DbTable_Loginlog();
	          $result = $loginlog->getAll();
	          $this->view->list = $result;
	}

                    function removeAction(){
                                   $users = new Application_Model_DbTable_User();
                                   $this->view->userlist = $users->getAllUsersForRem();
                                  
                    }
                    function removeuserAction(){
                                   $this->_helper->layout->disableLayout();
                                   $this->_helper->viewRenderer->setNoRender(true);
                                   if($this->getRequest()->isPost()){
                                                  $data = $this->getRequest()->getPost();
                                                  $users = new Application_Model_DbTable_User();
                                                  $users_backup = new Application_Model_DbTable_Userbackup();
                                                  $user = $users->fetchRow("userid = ".$data["userid"]);
                                                  $user = $user->toArray();
                                                  if($users_backup->insert($user)){
                                                                 if($users->delete("userid = ".$data["userid"])){
                                                                                $ca = new Application_Model_DbTable_CoachAthlete();
                                                                                $ca->delete("coachid = ".$data["userid"]);
                                                                                $ca->delete("athleteid = ".$data["userid"]);
                                                                                echo "The user has been removed.";
                                                                 }
                                                  }
                                                  else{
                                                                 echo "An error occured during user deletion. Please contact the administrator.
                                                                                All user information is still intact.";
                                                  }

                                   }
                    }

                    function removenewsAction(){
                                   $this->_helper->layout->disableLayout();
                                  $this->_helper->viewRenderer->setNoRender(true);
                                   if($this->getRequest()->isPost()){
                                                  $data = $this->getRequest()->getPost();
                                                  $news = new Application_Model_DbTable_News();
                                                  try{
                                                            $news->delete("newsid = ". (int) $data["newsid"]);
                                                            echo "success";
                                                  }
                                                  catch(SQLException $e){
                                                                 echo "failed";
                                                  }

                                   }
                    }

                    function exportAction(){
                             
                                   if($this->getRequest()->isPost()){
                                          require_once '../application/models/PHPExcel.php';
                                          $data = $this->getRequest()->getParam("tablename");
                                          $excelobj = new PHPExcel();
                                          $excelobj->getProperties()->setCreator("OTD")
                                                  ->setTitle("Data Export")
                                                  ->setSubject("Database Dump")
                                                  ->setDescription("Raw Data Dump from database")
                                                  ->setKeywords("Database raw data dump")
                                                  ->setCategory("OTD Datadump");

                                          if($data== "dr"){
                                                         //$excelobj->
                                                         $excelobj->setActiveSheetIndex(0)
                                                                 ->setCellValue('A1', "Daily report data dump")
                                                                 ->setCellValue('A3','Report ID')
                                                                 ->setCellValue('B3','User ID')
                                                                 ->setCellValue('C3','Date')
                                                                 ->setCellValue('D3','Body Weight (Morning)')
                                                                 ->setCellValue('E3','Body Weight (Evening)')
                                                                 ->setCellValue('F3','Urine (Morning)')
                                                                 ->setCellValue('G3','Urine (Evening)')
                                                                 ->setCellValue('H3','MHR')
                                                                 ->setCellValue('I3','Fluid Consumption')
                                                                 ->setCellValue('J3','Sleep Quality')
                                                                 ->setCellValue('K3','Sleep Quantity')
                                                                 ->setCellValue('L3','Mental Recovery')
                                                                 ->setCellValue('M3','Physical Recovery')
                                                                 ->setCellValue('N3','Pre Training Energy')
                                                                 ->setCellValue('O3','Muscle Soreness')
                                                                 ->setCellValue('P3','General Fatigue')
                                                                 ->setCellValue('Q3','Session 1 Duration')
                                                                 ->setCellValue('R3','Session 2 Duration')
                                                                 ->setCellValue('S3','Session 1 Intensity')
                                                                 ->setCellValue('T3','Session 2 Intensity')
                                                                 ->setCellValue('U3','Comments');

                                                         //$excelobj->getActiveSheet()->getStyle("A3")->getNumberFormat()->
                                                         $excelobj->getActiveSheet()->getStyle("A1")->applyFromArray(
                                                                 array(
                                                                     'font' => array('name' => 'Trebuchet MS', 'size' => 18, 'bold' => true)
                                                                 ));
                                                         $excelobj->getActiveSheet()->getStyle("A3:T3")->getAlignment()->setTextRotation(90);
                                                         $excelobj->getActiveSheet()->getColumnDimension('A')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('B')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('C')->setWidth(15);
                                                         $excelobj->getActiveSheet()->getColumnDimension('D')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('E')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('F')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('G')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('H')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('I')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('J')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('K')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('L')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('M')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('N')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('O')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('P')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('Q')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('R')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('S')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('T')->setWidth(5);
                                                         $excelobj->getActiveSheet()->getColumnDimension('U')->setWidth(35);

                                                         $dreport = new Application_Model_DbTable_Dailyreport();
                                                         $result = $dreport->fetchAll();
                                                         $rstart = 4;
                                                         foreach($result as $row){
                                                                        $row2 = $row->toArray();
                                                                                       $excelobj->setActiveSheetIndex(0)
                                                                                               ->setCellValue('A'.$rstart,$row2["reportid"])
                                                                                               ->setCellValue('B'.$rstart,$row2["userid"])
                                                                                               ->setCellValue('C'.$rstart,$row2["date"])
                                                                                               ->setCellValue('D'.$rstart,$row2["bw_morning"])
                                                                                               ->setCellValue('E'.$rstart,$row2["bw_evening"])
                                                                                               ->setCellValue('F'.$rstart,$row2["urine_morning"])
                                                                                               ->setCellValue('G'.$rstart,$row2["urine_evening"])
                                                                                               ->setCellValue('H'.$rstart,$row2["mhr"])
                                                                                               ->setCellValue('I'.$rstart,$row2["fluid"])
                                                                                               ->setCellValue('J'.$rstart,$row2["sleep_quality"])
                                                                                               ->setCellValue('K'.$rstart,$row2["sleep_quantity"])
                                                                                               ->setCellValue('L'.$rstart,$row2["mental_recovery"])
                                                                                               ->setCellValue('M'.$rstart,$row2["physical_recovery"])
                                                                                               ->setCellValue('N'.$rstart,$row2["pre_training_energy"])
                                                                                               ->setCellValue('O'.$rstart,$row2["muscle_soreness"])
                                                                                               ->setCellValue('P'.$rstart,$row2["general_fatigue"])
                                                                                               ->setCellValue('Q'.$rstart,$row2["session1_duration"])
                                                                                               ->setCellValue('R'.$rstart,$row2["session2_duration"])
                                                                                               ->setCellValue('S'.$rstart,$row2["session1_intensity"])
                                                                                               ->setCellValue('T'.$rstart,$row2["session2_intensity"])
                                                                                               ->setCellValue('U'.$rstart,$row2["comment"]);
                                                                                       $rstart++;
                                                         }

                                          }

                                          $excelobj->setActiveSheetIndex(0);


                                             header('Content-Type: application/vnd.ms-excel');
                                             header('Content-Disposition: attachment;filename="DataExport.xls"');
                                             header('Cache-Control: max-age=0');

                                             $objWriter = PHPExcel_IOFactory::createWriter($excelobj, 'Excel5');
                                             $objWriter->save('php://output');
                                             exit;



                                   }



                    }
 }


?>