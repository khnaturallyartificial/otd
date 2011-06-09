<?php

class Application_Model_DbTable_TestLog extends Zend_Db_Table_Abstract {
          protected $_name = "testlog";

        function log ($content){
	//$this->insert(array("logid"=>time(),"log"=>$content));
                  
	   $writer = new Zend_Log_Writer_Stream('log/log.txt',"w");
	    $logger = new Zend_Log($writer);
	    ob_start();
	    var_dump($content);
	    $result = ob_get_clean();
	   $logger->info($result);

          } 


          
}
?>
