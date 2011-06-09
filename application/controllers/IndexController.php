<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
                  
       $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $user = $auth->getIdentity();
            if($user->role == "A"){
                $this->_helper->redirector('index','athlete');
            }
            elseif($user->role == "C"){
                $this->_helper->redirector('index','coach');
            }
            else{
                $this->_helper->redirector('index','super');
            }
        }
        else{
                        $this->_helper->redirector('login','auth');
        }
        	//var_dump($_SERVER["HTTP_USER_AGENT"]);exit;
        	$this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
        	$this->view->headScript()->appendFile('/js/roundcorner.js');
        	$this->view->headScript()->appendFile('/js/script.js');
        	if(preg_match("/(MSIE 5.5|MSIE 6.0)/",$_SERVER["HTTP_USER_AGENT"])){
        		//$this->view->headScript()->appendFile('/js/pngfix.js');
        	}
    }

    public function indexAction()
    {
        $news = new Application_Model_DbTable_News();
        $this->view->news = $news->readyNewsForFooter(4);
    }

    public function submitmessageAction(){
              $this->_helper->layout()->disableLayout();
              $this->_helper->viewRenderer->setNoRender(true);
              if($this->getRequest()->isPost()){
	    $data = $this->getRequest()->getPost();
	    echo $data["message"];
	    exit;

              }
    }


}

