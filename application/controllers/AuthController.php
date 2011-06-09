<?php
class AuthController extends Zend_Controller_Action{
    function init(){
	$this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
                    $this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js');
        	$this->view->headScript()->appendFile('/js/roundcorner.js');
        	$this->view->headScript()->appendFile('/js/script.js');
                    $this->view->headLink()->appendStylesheet('/css/jqueryUI.css');
    }
    function logoutAction(){
	$auth = new Zend_Auth_Storage_Session();
	$auth->clear();
	$system = new Zend_Session_Namespace('System');
	$system->msg = "Logged out successfully";
	$this->_helper->redirector('index','message');
    }

    function registerAction(){
	$auth=Zend_Auth::getInstance();if($auth->hasIdentity()){$this->_helper->redirector('index','index');}
	
	$this->view->headTitle('Register');
	$this->view->form = new Application_Form_Register();
	
	if($this->getRequest()->isPost()){
	    $data = $this->getRequest()->getPost();
	    if($this->view->form->isValid($data)){
		if($data["password"]==$data["cpassword"]){
		    $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/config.ini','live');
		    $session = new Zend_Session_Namespace('System');
		    if(strlen($data["coachcode"])>0){
			if($data["coachcode"] != $config->coachcode){
			    $session->msg = "Wrong coachcode!";
			    $session->bot = "sad";
			    $this->_helper->redirector('index','message');
			}
			else{
			    $data["role"] = "C";
			}
		    }
		    $users = new Application_Model_DbTable_User();
		    if($users->isUnique($data["username"]) == false){
			$session->msg = "This username already exist";
			$session->bot = "sad";
			$this->_helper->redirector('index','message');
		    }
		    unset($data["cpassword"],$data["submit_x"],$data["submit_y"],$data["coachcode"]);	 
		    if($users->insert($data)){
			$session->msg = "You have been registered.";
			$session->bot = "happy";
			$this->_helper->redirector('index','message');
		    }
		    else{
			$session->msg = "An error has occured. Failed to register.";
			$session->bot = "sad";
			$this->_helper->redirector('index','message');
		    }
		}
	    }
	    else{
		$this->view->form->populate($data);
	    }
	}
    }
    function loginAction(){
	$auth=Zend_Auth::getInstance();if($auth->hasIdentity()){$this->_helper->redirector('index','index');}
	$this->view->headTitle("Login");
	$this->view->form = new Application_Form_Login();
	Zend_Layout::getMvcInstance()->assign("cp",true);
	
	if($this->getRequest()->isPost()){
	    $data = $this->getRequest()->getPost();
	    if($this->view->form->isValid($data)){
		$logintable = new Application_Model_DbTable_User();
		$authAdapter = new Zend_Auth_Adapter_DbTable($logintable->getAdapter());
		$authAdapter->setTableName($logintable->getTableName())
				    ->setIdentityColumn('username')->setCredentialColumn('password');
		$authAdapter->setIdentity($data["username"])->setCredential($data["password"]);
		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($authAdapter);
		$system = new Zend_Session_Namespace('System');
		if($result->isValid()){
		    $storage = new Zend_Auth_Storage_Session();
		    $storage->write($authAdapter->getResultRowObject(null,'password'));
		    $obj = $storage->read();
		    $userid = $obj->userid;
		    $loginlog = new Application_Model_DbTable_Loginlog();
		    $loginlog->insert(array("userid"=>$userid,"time"=>date("Y-m-d",time())));
		    $system->msg = "Logged in successfully.";
		    $system->bot = "happy";
		}
		else{
		    $system->msg = "Failed to login.<br/>Please make sure that your username and password are correct";
		    $system->bot = "sad";
		}
		$this->_helper->redirector('index','Message');
		
	    }
	    else{
		$this->view->form->populate($data);
	    }
	}
    }

    function forgotpassAction(){
                   $auth=Zend_Auth::getInstance();if($auth->hasIdentity()){$this->_helper->redirector('index','index');}
                   if($this->getRequest()->isPost()){
                                  $data = $this->getRequest()->getPost();
                                  $users = new Application_Model_DbTable_User();
                                  $result = $users->fetchRow("email = '".$data["forgotemail"]."'");
                                  $session = new Zend_Session_Namespace("System");
                                  if($result["password"]){
                                  $mail = new Zend_Mail();
                                             $mail->setBodyHtml("<h3>Your password</h3>Password: ".$result["password"] . '<br/>
                                                            <br/>
                                                           
                                                            <img src="http://otd.naturallyartificial.net/img/smallwcl.gif" alt=""/>');
                                             $mail ->setFrom("system@otd.com", "OTD system Message");
                                             $mail->addTo($data["forgotemail"], $data["forgotemail"]);
                                             $mail->setSubject("Your password.");
                                             $mail->send();

                                  $session->msg = "You password has been sent to your email address.";
                                  $session->bot = "happy";
                                  }
                                  else{
                                              $session->msg = "This email does not exist";
                                              $session->bot = "bad";
                                  }
                                  $this->_helper->redirector("index","message");
                   }

    }
}
?>