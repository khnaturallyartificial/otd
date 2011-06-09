<?php
 class TestController extends Zend_Controller_Action{
                function init(){
                               $this->_helper->layout->disableLayout();
                              $this->_helper->viewRenderer->setNoRender(true);
                }
                function indexAction(){

                  $log = new Application_Model_DbTable_TestLog();
                  $log->log("hay");
                }
 }
?>
