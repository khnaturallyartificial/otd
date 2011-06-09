<?php
class Application_Model_DbTable_Markedexerciseplanview extends Zend_Db_Table_Abstract{
	protected $_name = "markedexerciseplanview";
	
	function getMarkedExerciseInPlan($planid){
                                   $auth = Zend_Auth::getInstance();
                                   $user = $auth->getIdentity();
		$select	= $this->_db->select()->from($this->_name, array("exerciseplanid", "done"))
		->where("planid = ?", (int) $planid)
                                        ->where("userid = ?", $user->userid);
		$result = $this->_db->fetchAll($select);
		$ding = array();
		$ref = array("skipped","done");
	                   foreach($result as $l){
	                   	$ding[$l["exerciseplanid"]] = $ref[$l["done"]];
	                   }
		return $ding;
	}
                    function getMarkedExerciseInPlan2($planid,$userid=null,$showcustom = false){
                                   if($userid == null){
                                                  $auth = Zend_Auth::getInstance();
                                                  $user = $auth->getIdentity();
                                                  $uid = $user->userid;
                                   }
                                   else{
                                                  $uid=$userid;
                                          }
		  $extrawhere = "";
		  if($showcustom != false){
		            $extrawhere = " OR (planid = 2147483647 AND userid = $uid)";
		  }
		$select	= $this->_db->select()->from("markedexerciseplanviewcomplete2")//from($this->_name)
		->where("planid = $planid".$extrawhere)
                                        ->where("userid = ?", $uid);
		$result = $this->_db->fetchAll($select);
		$ding = array();
                                        $dong = array();
		$ref = array("skipped","done","custom");
	                   foreach($result as $l){
	                   	$ding[$l["exerciseplanid"]] = $ref[$l["done"]];
                                        $dong[$l["exerciseplanid"]] = array("actual_total_lifted"=>$l["actual_total_lifted"],"actual_total_reps"=>$l["actual_total_reps"],
                                                                                                         "actual_weight"=>$l["actual_weight"], "actual_reps_com"=>$l["actual_reps_com"],
		          "actual_reps_com2"=>$l["actual_reps_com2"],"additionalcomment"=>$l["additionalcomment"]    );
	                   }
		return array($ding,$dong);
	}

	function getMarkedExerciseInPlan2pex($userid,$startdate,$enddate){

		$select= $this->_db->select()->from("markedexerciseplanviewcomplete2")//from($this->_name)
                                        ->where("userid = ".$userid." AND (date BETWEEN '$startdate' AND '$enddate')");
		        ;
		$result = $this->_db->fetchAll($select);
		$ding = array();
                                        $dong = array();
		$ref = array("skipped","done","custom");
	                   foreach($result as $l){
	                   	$ding[$l["exerciseplanid"]] = $ref[$l["done"]];
                                        $dong[$l["exerciseplanid"]] = array("actual_total_lifted"=>$l["actual_total_lifted"],"actual_total_reps"=>$l["actual_total_reps"],
                                                                                                         "actual_weight"=>$l["actual_weight"], "actual_reps_com"=>$l["actual_reps_com"],
		          "actual_reps_com2"=>$l["actual_reps_com2"],"additionalcomment"=>$l["additionalcomment"]    );
	                   }
		return array($ding,$dong);
	}

                    function getmarkedexercisecustom($userid){
                                   $select = $this->_db->select()->from($this->_name)
                                           ->where("planid = ?",2147483647)
                                           ->where("date > ?", date("Y-m-d",time()-604800))
                                           ->where("userid = ?", $userid)
                                           ->order("exerciseplanid DESC");
                          
                                   return $this->_db->fetchAll($select);
                    }

                    function getAvLoadRepsPercent30days($userid){
                                   $today = date("Y-m-d", time());
                                   $lastmonth = date("Y-m-d", time90 - 2592000 );
                                   $select = $this->_db->query("select SUM(`pres_total_lifted`) as `sptl`, SUM(`actual_total_lifted`) as `satl`,
                                                  SUM(`pres_reps`) as `spr`, SUM(`actual_total_reps`) as `satr` from `markedexerciseplanview` where
                                                  `userid` = '".$userid."' and `date` between '".$lastmonth."' and '".$today."'");
                                   $result = $select->fetchAll();

                                   $satl = (int)  $result[0]["satl"];
                                   $sptl = (int) $result[0]["sptl"];

                                   $satr = (int) $result[0]["satr"];
                                   $spr = (int) $result[0]["spr"];

                                   $load = @round($satl / $sptl * 100);
                                   $reps = @round($satr / $spr * 100);
                                   return array($load,$reps);

                    }
	function getmaxtabledata($coachid){
	          $query1 = "SELECT `userid`, MAX(`actual_weight`)
		          FROM `markedexerciseplanviewcompletetest`
		          WHERE `exerciseid` = 54
		          AND `userid` IN (
		          SELECT `coachathlete`.`athleteid` FROM `coachathlete`,`user`
		          WHERE `coachathlete`.`coachid` = $coachid
		          AND `user`.`userid` = `coachathlete`.`athleteid`
		          AND `user`.`olym_or_power` = 'o'
		          ) AND `userid` NOT IN (14)
		          GROUP BY `userid`";
	          $query2 = "SELECT `userid`, MAX(`actual_weight`)
		FROM `markedexerciseplanviewcompletetest`
		WHERE `exerciseid` = 3
		AND `userid` IN (
		          SELECT `coachathlete`.`athleteid` FROM `coachathlete`,`user`
		          WHERE `coachathlete`.`coachid` = $coachid
		          AND `user`.`userid` = `coachathlete`.`athleteid`
		          AND `user`.`olym_or_power` = 'o'
		          )
		AND `userid` NOT IN (14)
		GROUP BY `userid`";
	          $query3 = "SELECT `coachathleteview`.`athleteid`,`coachathleteview`.`name`,
		`coachathleteview`.`surname` FROM `coachathleteview`,`user` WHERE `coachid` = $coachid AND `coachathleteview`.`athleteid` = `user`.`userid`
		AND `user`.`olym_or_power` = 'o'
	          AND `coachathleteview`.`athleteid` != 14
		ORDER BY `name` ASC";
	          $query4 = "SELECT `userid`, MAX(`actual_weight`)
		          FROM `markedexerciseplanviewcompletetest`
		          WHERE `exerciseid` = 1
		          AND `userid` IN (
		          SELECT `coachathlete`.`athleteid` FROM `coachathlete`,`user`
		          WHERE `coachathlete`.`coachid` = $coachid
		          AND `user`.`userid` = `coachathlete`.`athleteid`
		          AND `user`.`olym_or_power` = 'o'
		          ) AND `userid` NOT IN (14)
		          GROUP BY `userid`";

	          $usrs = $this->_db->query($query3)->fetchAll();
	          $ms = $this->_db->query($query1)->fetchAll();
	          $mcj	 = $this->_db->query($query2)->fetchAll();
	          $msa = $this->_db->query($query4)->fetchAll();

	          $maxsquats = array();
	          $maxCJ = array();
	          $maxsnatch = array();

	          foreach($ms as $m){
	          $maxsquats[$m["userid"]] = $m["MAX(`actual_weight`)"];
	               }
	               foreach($mcj as $m_cj){
		     $maxCJ[$m_cj["userid"]] = $m_cj["MAX(`actual_weight`)"];
	               }
	               foreach($msa as $m_sa){
		     $maxsnatch[$m_sa["userid"]] = $m_sa["MAX(`actual_weight`)"];
	               }
	               $finalarray = array();
	               foreach($usrs as $us){
		     $finalarray[] = array(
		       "name" => ucfirst($us["name"]).' '.ucfirst($us["surname"]),
		       "squat"=> $maxsquats[$us["athleteid"]],
		       "cj"=> $maxCJ[$us["athleteid"]],
		       "snatch"=> $maxsnatch[$us["athleteid"]],
		     );
	               }
	         return $finalarray;




	}
}

?>