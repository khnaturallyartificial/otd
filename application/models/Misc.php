<?php
class Application_Model_Misc extends Zend_Db_Table_Abstract {
          function runQuery($query){
	return $this->_db->query($query);
          }

          function getplanhistory($userid){
	$select = $this->_db->select()->from("planhistory",array("planid"))
	        ->join("plan","planhistory.planid = plan.planid", array("planname"))
	        ->where("userid = ?",$userid)
	        ->order("planhistory.historyID DESC")
	        ->limit(10);
	return $this->_db->fetchAll($select);
          }
    function makeMenu($menutitleclass,$menuitemclass,$data){
	$content = "";
	foreach($data as $m){
	    $s = '<span';
	    if($m["menuitems"]){ $s .= ' class="'.$menutitleclass.'" ';}
	    if(isset($m["tooltip"])){ $s .= ' title="'.$m["tooltip"].'" ';}
	    $s .= '><a href="'.$m["link"].'" class="none"><img src="'.$m["img"].'" alt="" /> '.$m["title"].'</a></span>
	    ';
	    if(count($m["menuitems"])){
		$t = '<div class="'.$menuitemclass.'">
		';
		foreach($m["menuitems"] as $mi){
		    $t .= '<a href="'.$mi["link"].'"';
		    if(isset($mi["tooltip"])){ $t .= ' title="'.$mi["tooltip"].'" ';}
		    $t .= '><img src="/img/node.png" alt=""/> '.$mi["text"].'</a><br/>
		    ';
		}
		$t .= '</div>
		';
		$s .= $t;
	    }
	    else{
		$s.= '<div></div>
		';
	    }
	    if($data[count($data)-1] != $m){$content .= $s . '<div class="linedotted"></div>
	    ';}
	    else{
		$content .= $s;
	    }
	}
	 return $content.'<div class="clear"></div>';
    }

    function selectexercisesByPlanId($planid){
                   $select = $this->_db->select()->from("exerciseplan")->where("planid = ?", $planid)
                                  ->order("date ASC")->order("exerciseid ASC")->order("weight ASC");
                   $result = $this->_db->fetchAll($select);
                   return $result;
    }

    function selectexercisesByPlanIdPRINT($planid,$showcustom=false,$uid=false){
                   $plans = new Application_Model_DbTable_Plan();
                   $plan = $plans->fetchRow("planid = ".$planid);
                   $plandat = $plan->toArray();
                   $startdate = $plandat["startdate"];
                   $extrawhere = "";
                   if($showcustom != false && $uid != false){
	         $extrawhere = " OR (planid = 2147483647 AND uid_for_custom = $uid)";
                   }
                   $enddate = date("Y-m-d",strtotime($plandat["startdate"] . " +".($plandat["duration"]-1)." day"));
                   $select = $this->_db->select()->from("exerciseplan")->where("planid = $planid".$extrawhere)
                           ->where("date BETWEEN '$startdate' AND '$enddate'")
                                  ->order("date ASC")->order("am_or_pm ASC")->order("exerciseplanid ASC")
	       ;
                  
                   $result = $this->_db->fetchAll($select);
                   
                   
                   return $result;
    }

    function selectexercisesByPlanIdPRINTpex($showcustom=false,$uid=false,$startdate=null,$enddate=null){
                   $extrawhere = "";
                   if($showcustom != false && $uid != false){
	         $extrawhere = " OR (planid = 2147483647 AND uid_for_custom = $uid)";
                   }
                   
                   $select = $this->_db->select()->from("exerciseplan")->where($extrawhere)
                           ->where("date BETWEEN '$startdate' AND '$enddate'")
                                  ->order("date ASC")->order("am_or_pm ASC")->order("exerciseplanid ASC")
	       ;

                   $result = $this->_db->fetchAll($select);


                   return $result;
    }

    function selectexercisesByPlanIdPRINTcustom($startdate,$enddate,$userid){
                   //$plans = new Application_Model_DbTable_Plan();
                   //$plan = $plans->fetchRow("planid = ".$planid);
                   //$plandat = $plan->toArray();
                   //$startdate = $plandat["startdate"];
                   //$enddate = date("Y-m-d",strtotime($plandat["startdate"] . " +".($plandat["duration"]-1)." day"));
              if($startdate == null || $enddate == null){
	    $startdate = date("Y-m-d",time()-604800);
	    $enddate = date("Y-m-d",time());
              }
                   $select = $this->_db->select()->from("markedexerciseplanviewcomplete2")->where("planid = ?", 2147483647)
                           ->where("date BETWEEN '$startdate' AND '$enddate'")->where("userid = ?",$userid)
                                  ->order("date ASC")->order("am_or_pm ASC")->order("exerciseplanid ASC");
                   $result = $this->_db->fetchAll($select);
                   return $result;
    }

        function selectexercisesByPlanIdPRINTcustomAll($startdate,$enddate,$userid){
                   //$plans = new Application_Model_DbTable_Plan();
                   //$plan = $plans->fetchRow("planid = ".$planid);
                   //$plandat = $plan->toArray();
                   //$startdate = $plandat["startdate"];
                   //$enddate = date("Y-m-d",strtotime($plandat["startdate"] . " +".($plandat["duration"]-1)." day"));
              if($startdate == null || $enddate == null){
	    $startdate = date("Y-m-d",time()-604800);
	    $enddate = date("Y-m-d",time());
              }
                   $select = $this->_db->select()->from("markedexerciseplanviewcomplete2")
                           ->where("date BETWEEN '$startdate' AND '$enddate'")->where("userid = ?",$userid)
                                  ->order("date ASC")->order("am_or_pm ASC")->order("exerciseplanid ASC");

                   $sql = "
	         SELECT * FROM `exerciseplan` WHERE (`date` BETWEEN '$startdate'
	         AND '$enddate') AND (( `planid` IN (
	         SELECT `planid` FROM `planhistory` WHERE `userid` = $userid
	          )) OR (`planid` = 2147483647 AND `uid_for_custom` = $userid))
	          ORDER BY `date` ASC, `am_or_pm` ASC, `exerciseplanid` ASC
	          ";
                   
                   $result = $this->_db->query($sql)->fetchAll();
                   Application_Model_DbTable_TestLog::log($sql);
                   return $result;
    }

}
?>