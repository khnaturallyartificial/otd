<?php

class Application_Model_DbTable_Injuriesreport extends Zend_Db_Table_Abstract {
               protected $_name = "injuries_report";
               function insertnewreportrow($data){
                              $date = $data["date"];
                              $userid = $data["userid"];
                              $select = $this->_db->select()->from($this->_name)
                                      ->where("date = ?", $date)
                                      ->where("userid = ?", $userid);
                              $result = $this->_db->fetchAll($select);
                             if(count($result) == 0){
                                            $this->insert($data);
                             }
               }
}

?>
