<?php

class MiscController extends Zend_Controller_Action
{

    public function init()
    {
       
    }


    public function submitmessageAction(){
              $this->_helper->layout()->disableLayout();
              $this->_helper->viewRenderer->setNoRender(true);
              if($this->getRequest()->isPost()){
	    $data = $this->getRequest()->getPost();
	    $data["message"] = htmlentities($data["message"]);
	    $shout = new Application_Model_DbTable_Shout();
	    if($data["userid"]){
	              $userid = $data["userid"];
	              unset($data["userid"]);
	              $users = new Application_Model_DbTable_User();
	              $result = $users->fetchRow("userid = ".$userid);
	              $useremail = $result["email"];
	    }
	    $data2 = array("message"=>$data["message"],"userName"=>$data["userName"],"role"=>$data["role"]);
	    if($shout->insert($data2)){
	              echo "Y";
	    }
	    else{
	              echo "N";
	    }
	    $mail = new Zend_Mail();
	     $mail->setBodyHtml($data["message"]);
	     $mail ->setFrom("system@otd.com", "OTD system Message");
	     $mail->addTo("kh.naturallyartificial@gmail.com", "kh.naturallyartificial@gmail.com");
	     if($userid){
	               $mail->addTo($useremail,$useremail);
	     }
	     $mail->setSubject("OTD global chat msg: ".$data["message"]);
	     $mail->send();
	    exit;

              }
    }
    public function getmessagesAction(){
              $this->_helper->layout()->disableLayout();
              $this->_helper->viewRenderer->setNoRender(true);
              if($this->getRequest()->isPost()){
	    $shout = new Application_Model_DbTable_Shout();
	    $result = $shout->fetchAll(NULL, "shoutID DESC", 15);
	    $txt = array();
	    foreach($result as $msg){
	              $txt[] = '
		    <span class="msg-name fontcolor-'.$msg["role"].'" title="'.$msg["time"].'"><strong>'.$msg["userName"].'</strong></span><br/>
		    
		    <span class="msg-txt">&raquo; '.str_replace("\\'","'",$msg["message"]).'</span>
		             

		';
	    }
	    $txt = implode($txt,'<hr class="msgdiv"/>');
	    echo $txt;
	    exit;

              }
    }


}

