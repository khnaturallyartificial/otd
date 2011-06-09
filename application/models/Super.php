<?php
class Application_Model_Super extends Zend_Db_Table_Abstract{
	protected $_name = "user";
	function makeSuperMenu(){
		$data = array();
                             	 $data[] = array(
			'title' => 'Dashboard',
			'img' => '/img/dashboard.png',
			'link' => '/',
			'tooltip' => 'Dashboard - View everything at a glance'
			) ;
                                         $data[] = array(
			'title' => 'Insert News',
			'img' => '/img/dashboard.png',
			'link' => '/super/news',
			'tooltip' => 'You can post or delete system messages here.'
			) ;
			
		$data[] = array(
			'title' => 'Attach Athlete',
			'img' => '/img/athletes.png',
			'link' => '/super/attach',
			'tooltip' => 'Assign an athlete to a coach.'
		);
                                        $data[] = array(
			'title' => 'User removal',
			'img' => '/img/dashboard.png',
			'link' => '/super/remove',
			'tooltip' => 'Remove users from the system.'
		);

                                        $data[] = array(
			'title' => 'Export Data',
			'img' => '/img/dashboard.png',
			'link' => '/super/export',
			'tooltip' => 'Export raw data into CSV files'
		);

		$data[] = array(
			'title' => 'Check User Login',
			'img' => '/img/dashboard.png',
			'link' => '/super/checklogin',
			'tooltip' => 'Check the last login date for users'
		);


                              $menu = Application_Model_Misc::makeMenu('accordiontitle','accordionitems',$data);
                              return $menu;
	}

                    function makeUserSelection($role){
                                   $select = $this->_db->select()->from($this->_name)->where("role = ?", $role);
                                   $list = $this->_db->fetchAll($select);
                              $ret = '<select id="'.$role.'IDlist">';
                              foreach($list as $l){
                                             $ret .= '<option value="'.$role.$l["userid"].'">'.$l["name"].' '.$l["surname"].'</option>';
                              }
                              return $ret."</select>";
               }

               function attachathletetocoach($aid,$cid){
                              $b = $this->_db->query("DELETE FROM coachathlete WHERE athleteid = $aid AND coachid = $cid LIMIT 1");
                              $a = $this->_db->query("INSERT INTO coachathlete VALUES($cid,$aid)");
                              if($b && $a){
                                             return true;
                              }
                              else{
                                             return false;
                              }

               }

               function makeCoachAthleteList(){
                              $content = "";
                              $select = $this->_db->select()->from("user")->where("role = 'C'");
                              $result = $this->_db->fetchAll($select);
                              foreach($result as $coach){
                                             $content .= '<h3><img src="/img/coaches.png" alt="" id="coachheader'.$coach["userid"].'"/> Coach: '.$coach["name"]." ".$coach["surname"].'</h3>';
                                             $sel = $this->_db->select()->from("coachathleteview")->where("coachid = ?", $coach["userid"]);
                                             $res = $this->_db->fetchAll($sel);
                                             $content .= '<ul>';
                                             foreach($res as $r){
                                                            $content .= '<li id="detach'.$r["athleteid"].'">'.$r["name"]." ".$r["surname"].' <img src="/img/minus.png" class="unpin"/></li>';
                                             }
                                             $content.='</ul>';

                              }
                              return $content;


               }

               function detachAthlete($aid){
                              if($this->_db->query("DELETE FROM coachathlete WHERE athleteid = ".$aid)){
                                             return true;
                              }
                              else{
                                             return false;
                              }
               }
}
?>