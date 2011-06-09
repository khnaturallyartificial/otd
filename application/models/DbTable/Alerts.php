<?php

class Application_Model_DbTable_Alerts extends Zend_Db_Table_Abstract {
               protected $_name = "alerts";
               protected $_primary = "alertid";

               function doAlert($alerthead,$alertcontent, $alertlevel,$athleteid,$smail=false){
	     if(is_array($alertlevel)){
	               if(!empty($alertlevel)){
		$max = max($alertlevel);
	               }
	               else{
		     $max = 1;
	               }
	     }
	     else{
	               $max = $alertlevel;
	     }
                              $data = array(
                                  "alertlevel" => $max,
                                  "alerthead" => $alerthead,
                                  "alertcontent" => $alertcontent,
                                  "athleteid" => $athleteid
                              );

                              $this->_db->insert($this->_name,$data);
                              $log = new Application_Model_DbTable_TestLog();
	          //$log->log(var_export($data,true));
                              if($smail == true){
		try{
                                             $user = new Application_Model_DbTable_User();
                                             $cav = new Application_Model_DbTable_Coachathleteview();
                                             $athrow = $cav->getCoachIdAlert($athleteid);
		    
                                             $coachid = $athrow;
		     $mails = array();
		     foreach($coachid as $cid){
			$athrow = $user->fetchRow("userid = ".$cid["coachid"]);
			$mails[] = $athrow["email"];
		     }
                                             
                                             $athrow = $user->fetchRow("userid = ".$athleteid);
                                             $athname = $athrow["name"]. " " . $athrow["surname"];
                                             $mail = new Zend_Mail();
                                             $mail->setBodyHtml($alertcontent . '<br/><img src="http://otd.naturallyartificial.net/img/smallwcl.gif" alt=""/>');
                                             $mail ->setFrom("system@otd.com", "OTD system Message");
		     foreach($mails as $cmail){
		               $mail->addTo($cmail, $cmail);
		     }
                                             $mail->setSubject($alerthead ." : ". $athname);
                                             $mail->send();
		     $log->log($mail);
		}
		catch(Exception $e){
		          $log->log($e);
		}
                              }

               }
}

?>
