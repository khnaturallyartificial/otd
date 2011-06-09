<?php
/*class Application_Model_DbTable_Markedexercise extends Zend_Db_Table_Abstract{
	protected $_name = "markedexercise";
	
	function markExercisePlan($exerciseplanid,$mode,$userid=0){
		$done = ($mode == "done") ? "1" : "0";
		$sta = "INSERT INTO `".$this->_name."` VALUES (".$exerciseplanid.", ".$done.",".$userid.") ON DUPLICATE KEY UPDATE `done` = ".$done;
		
                                        try{
			if($this->_db->query($sql)){
			          return true;
			}
			//return $sql;
		}
		catch(Exception $e){
			return $sql;
		}
	}
}*/

class Application_Model_DbTable_Markedexercise extends Zend_Db_Table_Abstract{
	protected $_name = "markedexercise";

	function markExercisePlan($exerciseplanid,$mode,$userid=0,$actuallifted="",$actualreps="",$weight="",$actualrepscom="",$actualrepscom2="",$additionalcomment=""){
		$done = ($mode == "done") ? "1" : "0";
                                        //$date = date("Y-m-d",time());
		$ep = new Application_Model_DbTable_Exerciseplan();
		$eprow = $ep->fetchRow("exerciseplanid = ".$exerciseplanid);
		$date = $eprow["date"];
		$comment = $additionalcomment != "" ? "'$additionalcomment'" : "NULL";

		$sql = "INSERT INTO `".$this->_name."` VALUES (".$exerciseplanid.", ".$done.",".$userid.",'".$date."',".$actuallifted.",".$actualreps.",".$weight.",'".$actualrepscom."','".$actualrepscom2."',".$comment.") ON DUPLICATE KEY UPDATE `date` = '".$date."', `done` = ".$done.",
`actual_total_lifted` = ".$actuallifted.", `actual_total_reps` = ".$actualreps.", `actual_weight` = ".$weight.", `actual_reps_com` = '".$actualrepscom."', `actual_reps_com2` = '".$actualrepscom2."', `additionalcomment` = ".$comment;
		
                                        $result = $this->_db->query($sql);
                                        if($result){return true;}else{return false;}
	}
}

?>