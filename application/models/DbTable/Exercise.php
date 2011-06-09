<?php

class Application_Model_DbTable_Exercise extends Zend_Db_Table_Abstract{
               protected $_name = "exercise";
               public function getAllExercise(){
                               $select = $this->_db->select()->from($this->_name);
                              $result = $this->_db->fetchAll($select);
                              $content = array();
                              foreach($result as $r){
                                             $content[$r["exerciseid"]] = $r["exercisename"];
                              }
                              return $content;
               }
               public function makeExerciseSelection($type=NULL){
                              $select = $this->_db->select()->from($this->_name)->order("exerciseid ASC");
	          if($type != NULL){
		$select->where("ol_or_po = '".(string)$type."'");
	          }
                              $result = $this->_db->fetchAll($select);
                              $ret = array();
                              foreach($result as $r){
                                             $ret[$r["exerciseid"]] = $r["exerciseid"]." - ".$r["exercisename"];
                              }
                              return $ret;
               }
               public function makeExerciseList(){
                              $select = $this->_db->select()->from($this->_name)->order('exerciseid ASC');
                              $result = $this->_db->fetchAll($select);
                              $this->_db->closeConnection();
                              $data = "<ul>";
                              foreach($result as $r){
                                             $data .= '     <li class="'.$r["ol_or_po"].'">('.$r["exerciseid"].') '.$r["exercisename"].'<img src="/img/cross.png" alt="" class="deleteexercise" id="'.$r["exerciseid"].'"/></li>
                                                            ';
                              }
                              $data .= '</ul>';
                              return $data;
               }

               public function addExercise($en,$id,$type){
                              $validator = new Zend_validate_Regex('/^[a-zA-Z0-9\s\.\-\(\)]{1,50}$/');
                              $int_validator = new Zend_Validate_Int();
                              if($validator->isValid($en) && $int_validator->isValid($id)){
                                             $data = array("exercisename" => $en,"exerciseid"=>$id,"ol_or_po"=>$type);
                                             if($this->insert($data)){
                                                            return "`$en` added to the list";
                                             }
                                             else{
                                                            return "Failed to insert data. The data is either invalid or Exercise Number
                                                                           already exist.";
                                             }
                              }
                              else{
                                             return "Invalid Character. Alphabets, numbers, spaces, dot and brackets allowed";
                              }
               }
}

?>
