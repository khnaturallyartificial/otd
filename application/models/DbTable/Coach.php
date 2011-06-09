<?php
class Application_Model_DbTable_Coach extends Zend_Db_Table_Abstract{
               static function makeAthleteMenu(){
                              $data = array();
                              $data[] = array(
			'title' => 'Dashboard',
			'img' => '/img/dashboard.png',
			'link' => '/',
			'tooltip' => 'Dashboard - View everything at a glance'
			) ;
	          $data[] = array(
			'title' => 'Daily Report',
			'img' => '/img/dailyreport.png',
			'link' => '/athlete/dailyreport',
			'tooltip' => 'This report should be filled in daily. You may also fill in reports on previous days.'
			) ;
                              $data[] = array(
			'title' => 'Injuries and niggles',
			'img' => '/img/bandaid.png',
			'link' => '/athlete/injuries',
			'tooltip' => 'If you have any injuries or niggles, please report them here'
			) ;
	          $data[] = array(
			'title' => 'Illness',
			'img' => '/img/ill.png',
			'link' => '/athlete/dailyillness',
			'tooltip' => 'If you have any illnesses, please report them here'
			) ;
                              $data[] = array(
			'title' => 'Illness & Injuries history',
			'img' => '/img/envelope.png',
			'link' => '/athlete/history',
			'tooltip' => 'The last 50 illness/injuries reports.'
			) ;
                               $data[] = array(
			'title' => 'My Progress',
			'img' => '/img/progress.png',
			'link' => '/athlete/myprogress',
			'tooltip' => 'View graphs of your performance.'
			) ;
                               $data[] = array(
			'title' => 'My Info.',
			'img' => '/img/info.png',
			'link' => '/athlete/profile',
			'tooltip'=>'View alert (Athlete\'s incompleted exercises, high tiredness, etc.)'
			) ;

                                


                              $menu = Application_Model_Misc::makeMenu('accordiontitle','accordionitems',$data);
                              return $menu;

               }


    static function makeCoachMenu(){
	$data = array();
	$data[] = array(
			'title' => 'Dashboard',
			'img' => '/img/dashboard.png',
			'link' => '/',
			'tooltip' => 'Dashboard - View everything at a glance'
			) ;
	$data[] = array(
			'title' => 'Plans',
			'img' => '/img/plans.png',
			'link' => '/',
			'tooltip' => 'Manage exercise plans',
			'menuitems' => array(
				array('text' => 'View/Edit plans', 'link' =>'/coach/plans','tooltip'=>'View existing plans and make modifications'),
				array('text' => 'Create new plan', 'link' =>'/coach/createplan','tooltip'=>'Create a new (blank) plan.'),
				array('text' => 'Exercise types', 'link' =>'/coach/exercise','tooltip'=>'View/Add exercise types'),
                                                                                array('text' => 'Import Plans', 'link' =>'/coach/import','tooltip'=>'Automatically create a plan.')
							)
			) ;
	$data[] = array(
			'title' => 'Athletes',
			'img' => '/img/athletes.png',
			'link' => '/',
			'tooltip' => 'Manage athletes currently training under you',
			'menuitems' => array(
				 array('text' => 'View athletes', 'link' =>'/coach/viewath','tooltip'=>'A list of all athletes training under you.'),
                                                                                 array('text' => 'Athlete Summary', 'link' =>'/coach/athsum','tooltip'=>'Detailed information about athletes.'),
                                                                                 array('text' => 'Injuries History', 'link' =>'/coach/history','tooltip'=>'Athlete\'s injuries and illness history')
					)
			) ;
	
	/*$data[] = array(
			'title' => 'Messages',
			'img' => '/img/mail.png',
			'link' => '/',
			'tooltip' => 'Send and recieve messages to athletes and other coaches',
			'menuitems' => array(
							    array('text' => 'Compose Message', 'link' =>'www.ex.com', 'tooltip'=>'Compose a new message'),
							    array('text' => 'Inbox', 'link' =>'www.ex.com', 'tooltip'=>'View recieved messages'),
							    array('text' => 'Sent', 'link' =>'www.ex.com', 'tooltip'=>'View sent messages'),
							    array('text' => 'View Contacts', 'link' =>'www.ex.com', 'tooltip'=>'A list of your contacts.')
							)
			) ;*/
	$data[] = array(
			'title' => 'Monitor',
			'img' => '/img/monitor.png',
			'link' => '/',
			'tooltip'=>'Monitor the performance of your athletes',
			'menuitems' => array(
							    array('text' => 'Athlete analysis', 'link' =>'/coach/monitor', 'tooltip'=>'Analysis graphs for your athletes.'),
							    array('text' => 'Completion', 'link' =>'/coach/excomp', 'tooltip'=>'View the plan attached to the athletes, and its completion.'),
							    array('text' => 'Past Completion', 'link' =>'/coach/pastexcomp', 'tooltip'=>'View the exercise completion in the past'),
							    array('text' => 'Daily report comp.', 'link' =>'/coach/checkdailyreportcompletion', 'tooltip'=>'View daily report completion for your athlets')
							)
			) ;
	$data[] = array(
			'title' => 'Alert',
			'img' => '/img/alerts.png',
			'link' => '/coach/alert',
			'tooltip'=>'View alert (Athlete\'s incompleted exercises, high tiredness, etc.)'
			) ;
                    $data[] = array(
			'title' => 'My Info.',
			'img' => '/img/info.png',
			'link' => '/coach/profile',
			'tooltip'=>'View and update your profile.'
			) ;
	$menu = Application_Model_Misc::makeMenu('accordiontitle','accordionitems',$data);
	return $menu;
    }
}
?>