<?php
class MessageController extends Zend_Controller_Action{
	function init(){
		$this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
        	$this->view->headScript()->appendFile('/js/roundcorner.js');
        	$this->view->headScript()->appendFile('/js/script.js');
                    $this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js');
	}
    function indexAction(){
	$this->view->headTitle('System Message');
	$system = new Zend_Session_Namespace('System');
	$this->view->message =$system->msg;
	$this->view->bot = $system->bot;
                    $this->view->redir = $system->redir;
                    $this->view->redirtext = $system->redirtext;
	$system->msg = "No message.";
	$system->bot = "normal";
                    $system->redir = NULL;
                    $system->redirtext = NULL;
    }
}
?>