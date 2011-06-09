<?php

class ChartController extends Zend_Controller_Action {
               function init(){
                              
                              $this->_helper->layout->disableLayout();
                              $this->_helper->viewRenderer->setNoRender(true);
               }
               function indexAction(){
                              error_reporting(E_ALL);
                              include("pChart/pData.class");
                              include("pChart/pChart.class");
                              include("pChart/pCache.class");
                              $data = $this->getRequest()->getparams();
                              $graph = new Application_Model_DrawChart();
                              $graph->$data["mode"]($data);
                              exit;

               }
}

?>
