<?php

class Application_Model_DrawChart extends Zend_Db_Table_Abstract {
               protected $_name = "user";

               function cleanDate($date){
	     if(preg_match('/^[0-9\-]{10}$/',$date)){
	               return $date;
	     }
	     else{
	               return date("Y-m-d",time());
	     }
               }

               function drawchart_correlation($data){
	     //$coachid = $data["coachid"];
	     $auth = Zend_Auth::getInstance();
	     $usr = $auth->getIdentity();
	     $coachid = $usr->userid;
	     $mode = $data["type"];
	     $ex1 = "Squats";
	     $ex2 = "";
	     //get the data first
	     if($mode == "SCJ"){
	               $ex2 = "C&J";
	               $graphname = "Squats vs Clean&Jerk";
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
		          ) AND `userid` NOT IN (14)
		          GROUP BY `userid`";
	               $query3 = "SELECT `athleteid` FROM `coachathlete`,`user` WHERE `coachid` = $coachid AND
	               `user`.`userid` = `coachathlete`.`athleteid` AND `user`.`olym_or_power` = 'o'
	               AND `coachathlete`.`athleteid` != 14";
	     }
	     else{
	               $ex2 = "Snatch";
	               $graphname = "Squats vs Snatch";
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
		          WHERE `exerciseid` = 1
		          AND `userid` IN (
		          SELECT `coachathlete`.`athleteid` FROM `coachathlete`,`user`
		          WHERE `coachathlete`.`coachid` = $coachid
		          AND `user`.`userid` = `coachathlete`.`athleteid`
		          AND `user`.`olym_or_power` = 'o'
		          ) AND `userid` NOT IN (14)
		          GROUP BY `userid`";
	               $query3 = "SELECT `athleteid` FROM `coachathlete`,`user` WHERE `coachid` = $coachid AND
	               `user`.`userid` = `coachathlete`.`athleteid` AND `user`.`olym_or_power` = 'o'
	               AND `coachathlete`.`athleteid` != 14";
	     }
	     $al = $this->_db->query($query3)->fetchAll();
	     $result1 = $this->_db->query($query1)->fetchAll();
	     $result2 = $this->_db->query($query2)->fetchAll();
	     error_reporting(0);
	     //got all the data, time to arrange them into a manageable format
	     $firstmax = array();
	     $secondmax = array();
	     foreach($result1 as $r){
	               $firstmax[$r["userid"]] = $r["MAX(`actual_weight`)"];
	     }
	     foreach($result2 as $r){
	               $secondmax[$r["userid"]] = $r["MAX(`actual_weight`)"];
	     }
	     
	     $serie1 = array();
	     $serie2 = array();
	     $serie3 = array();
	     foreach($al as $a){
	               if($firstmax[$a["athleteid"]]){
		     $serie1[] = $firstmax[$a["athleteid"]];
	               }
	               else{
		     $serie1[] = 0;
	               }
	               if($secondmax[$a["athleteid"]]){
		     $serie2[] = $secondmax[$a["athleteid"]];
	               }
	               else{
		     $serie2[] = 0;
	               }
	     }

	    



	          $DataSet = new pData;
	          $DataSet->AddPoint($serie1,"Serie1");
	          $DataSet->AddPoint($serie2,"Serie2");
	          $DataSet->SetSerieName("Yay","Serie1");
	          $DataSet->AddSerie("Serie1");
	          $DataSet->AddSerie("Serie2");
	          $DataSet->SetXAxisName($ex2);
	          $DataSet->SetYAxisName($ex1);


	          $Test = new pChart(880,370);
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->setGraphArea(70,30,780,300);
                               $Test->drawFilledRoundedRectangle(7,7,793,363,5,240,240,240);
                               $Test->drawRoundedRectangle(5,5,795,365,5,230,230,230);
                               $Test->drawGraphArea(237,237,237,true);
                               $Test->drawXYScale($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1","Serie2",0,0,0); 
                               $Test->drawGrid(4,true,228,228,228);

	           
                               // Draw the 0 line
                               $Test->setFontProperties("Fonts/tahoma.ttf",6);
                               //$Test->drawTreshold(180,162,162,162,true,false,3);

                               // Draw the line graph
                              
                               //$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawXYPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1","Serie2",0,4,3);
                                
                               $Test->setFontProperties("Fonts/tahoma.ttf",10);
                               $Test->drawTitle(60,22,$graphname,50,50,50,585);
                              
	            
                               $Test->Stroke("corellationthingy.png");
	          

               }

               function drawchart($data){ //data in associative array
                              error_reporting(0);
                              $userid = (int) $data["userid"];
	          $expand = 0;

                              if($data["sdate"] && $data["edate"]){
                                             $range = " and `date` between '".$this->cleanDate($data["sdate"])."' and '".  $this->cleanDate($data["edate"])."' ";
		     $edate = $data["edate"];
                              }
                              else{ $range = "";$edate = date("Y-m-d",time()); }
                              $query = "select `date`, `session1_duration`,`session2_duration`,`session2_intensity` , `session1_intensity` from `dailyreport` where `userid` = ".$userid."
                                            ".$range."
                              order by `date` DESC
                                             ";
                              if($data["limit"]){
                                             $query .= " limit ".$data["limit"];
		     $timelim = (int)$data["limit"];
		     if((int)$data["limit"]>10){
		               $expand = ((int)$data["limit"]-10)*10;
		     }
                              }
                              else{
                                             $query .= " limit 10";
		     $timelim = 10;
                              }
	          //echo $query;
	          
                              $result = $this->_db->query($query)->fetchAll();
	          
	          
                              $datear = array();
                              $dataar = array();
	          $dataar2 = array();
	          $firstdatetimestamp = strtotime($edate);
	          $globalcount = 1;
	          //$logcontent = "";
                             //Zend_Debug::dump($result);
	          
                              foreach($result as $row){
		if($globalcount > $timelim){break;}
		for($p=1;$p<$timelim;$p++){
		         //$logcontent .= $row["date"]." -- ". date("Y-m-d",$firstdatetimestamp)."\n";
		          if($row["date"] != date("Y-m-d",$firstdatetimestamp)){
			array_unshift($dataar,0);
			array_unshift($dataar2,0);
			array_unshift($datear,date("d M Y",$firstdatetimestamp));

			$firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
			//$firstdatetimestamp -= 86400;

			if($globalcount > $timelim){break;}else{$globalcount++;}
		          }
		          else{
			break;
		          }
		}
		if($globalcount > $timelim){break;}
                                            array_unshift($dataar , $row["session1_duration"] * $row["session1_intensity"]);
		    array_unshift($dataar2 ,max($row["session2_duration"] * $row["session2_intensity"],0));
                                            array_unshift($datear , date("d M Y", strtotime($row["date"])));
		    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
		    $globalcount++;
                              }
	          if(count($result)==0){
		$datear[]=0;
		$dataar[]=0;
		$dataar2[]=0;
	          }
	          //Application_Model_DbTable_TestLog::log($datear);

	        
                               // Dataset definition
                               $DataSet = new pData;
                               $DataSet->AddPoint($dataar,"Serie1");
	           $DataSet->AddPoint($dataar2,"Serie2");
                               $DataSet->AddPoint($datear, "Serie3");
                               $DataSet->AddSerie("Serie1");
	           $DataSet->AddSerie("Serie2");
                               $DataSet->SetAbsciseLabelSerie("Serie3");
                               $DataSet->SetSerieName("Session 1","Serie1");
	           $DataSet->SetSerieName("Session 2","Serie2");
                               $DataSet->SetYAxisName("AU");

                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph1",$DataSet->GetData());

                               // Initialise the graph
                               $Test = new pChart(780+$expand,270);
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->setGraphArea(70,30,680+$expand,200);
                               $Test->drawFilledRoundedRectangle(7,7,693+$expand,263,5,240,240,240);
                               $Test->drawRoundedRectangle(5,5,695+$expand,265,5,230,230,230);
                               $Test->drawGraphArea(237,237,237,true);
                               $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,true,45,0);
                               $Test->drawGrid(4,true,228,228,228);
                               $Test->setFixedScale(0,1000);

                               // Draw the 0 line
                               $Test->setFontProperties("Fonts/tahoma.ttf",6);
                               //$Test->drawTreshold(180,162,162,162,true,false,3);

                               // Draw the line graph
                               $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                               //$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->setFontProperties("Fonts/tahoma.ttf",7);
                                $Test->setColorPalette(0,112,55,46);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie1");
	            $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie2");
                               // Finish the graph
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->drawLegend(690+$expand,35,$DataSet->GetDataDescription(),255,255,255);
                               $Test->setFontProperties("Fonts/tahoma.ttf",10);
                               $Test->drawTitle(60,22,"Training load",50,50,50,585);
                               $Cache->WriteToCache("Graph1",$DataSet->GetData(),$Test);
                               $Test->Stroke("example1.png");
               }

               function drawchart_average($data){
                              error_reporting(0);
                              $userid = (int) $data["userid"];
	          $expand = 0;
                              $limit = (int) $data["limit"];
                              if(!$limit){$limit=10;}
	          else{
		if($limit > 10){
		          $expand = ($limit -10)*10;
		}
	          }
                              $tstamp = time();
                              $thisweek = date("W",$tstamp);
                              $thisyear = (int) date("Y", $tstamp);
                              $stime = strtotime($thisyear."W".$thisweek); //this is the time

                              $datearray = array();
                              $totalliftarray = array();
                              $averageloadarray = array();

                              $sql = "select SUM(`actual_total_lifted`) as tl, SUM(`actual_total_reps`) as tr from `markedexerciseplanview` where
                                             `userid` = '".$userid."' and
                                             `date` between
                                             '".date("Y-m-d",$stime)."' and '".date("Y-m-d",$stime + 604800 - 1)."'";

                              $result = $this->_db->query($sql)->fetchAll();


                              foreach($result as $row){
                                             $datearray[] = date("d M Y",$stime);
                                             $totalliftarray[] = round((int) $row["tl"] / 1000, 2); //(int) $row["tl"];
                                             $averageloadarray[] =(int) ((int) $row["tl"] / (int) $row["tr"]);
                              }


                              for($i = 0; $i<=($limit-2); $i++){

                                             $sql = "select SUM(`actual_total_lifted`) as tl, SUM(`actual_total_reps` + IFNULL(`actual_reps_com`,0) + IFNULL(`actual_reps_com2`,0)) as tr from `markedexerciseplanview`
                                                            where `userid` = '".$userid."' and
                                                            `date` >=
                                             '".date("Y-m-d",$stime - (604800 * ($i + 1)) )."' and `date` < '".date("Y-m-d",$stime - 1 - (604800 * $i))."'";
                                             $result = $this->_db->query($sql)->fetchAll();

		     //Zend_Debug::dump($result);

                                             foreach($result as $row){
                                                            array_unshift($datearray,date("d M Y",$stime - (604800 * ($i + 1)) ) );
                                                            array_unshift($totalliftarray, round((int) $row["tl"] / 1000, 2));
                                                            array_unshift($averageloadarray, (int)((int) $row["tl"] / (int) $row["tr"]));
                                             }

                              }
                              //exit;

                               // Dataset definition
                               $DataSet = new pData;
                               $DataSet->AddPoint($totalliftarray,"Serie1");
                                $DataSet->AddPoint($averageloadarray,"Serie2");
                               $DataSet->AddPoint($datearray, "Serie3");
                               $DataSet->AddSerie("Serie1");
                               $DataSet->SetAbsciseLabelSerie("Serie3");
                               $DataSet->SetSerieName("Total Lifted (tonne)","Serie1");
                                $DataSet->SetSerieName("Average Load (kg.)","Serie2");
                                $DataSet->SetYAxisName("Total Lifted (tonne)");

                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph5",$DataSet->GetData());
                                // Initialise the graph
                                $Test = new pChart(660+$expand,390);

                                // Prepare the graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->setGraphArea(60,40,595+$expand,290,true);

                                  $Test->drawFilledRoundedRectangle(7,7,656+$expand,383,5,240,240,240);
                                $Test->drawRoundedRectangle(5,5,658+$expand,385,5,230,230,230);
                              $Test->drawGraphArea(255,255,255,TRUE);

                                // Initialise graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);

                                // Draw the SourceForge Rank graph
                                $DataSet->SetYAxisName("Total Lifted (tonne)");
                                $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
                                $Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),true);
                                  $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");

                                // Clear the scale
                                $Test->clearScale();

                                // Draw the 2nd graph
                                $DataSet->RemoveSerie("Serie1");
                                $DataSet->AddSerie("Serie2");
                                $DataSet->SetYAxisName("Average Load (kg)");
                                $Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
                                //$Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                                $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");

                                // Write the legend (box less)
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->drawLegend(530+$expand,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,150,150,150,FALSE);

                                // Write the title
                                $Test->setFontProperties("Fonts/MankSans.ttf",10);
                                $Test->drawTitle(0,0,"Total Lifted & Average Load (Weekly)",150,150,150,660,30);

                                // Render the picture
                                 $Cache->WriteToCache("Graph5",$DataSet->GetData(),$Test);
                                
                                $Test->Stroke();
               }

               function drawchart_averagedaily($data){
                              error_reporting(0);
                              $userid = (int) $data["userid"];
	          $expand = 0;
                              $limit = (int) $data["limit"];
                              if(!$limit){$limit=10;}
	          else{
		$limit = max(array($limit-2,0));
		$expand = $limit > 10 ? ($limit - 10)*10 : 0;
	          }
                              $tstamp = time();
                              $thisweek = date("W",$tstamp);
                              $thisyear = (int) date("Y", $tstamp);
                              $stime = $data["edate"] ? (int) strtotime($data["edate"]) : (int) time(); //this is the time

                              $datearray = array();
                              $totalliftarray = array();
                              $averageloadarray = array();

	          if($data["sdate"] && $data["edate"]){
		$limit = ceil((strtotime($data["edate"]) - strtotime($data["sdate"])) / 86400);
		$limit--;
	          }

	          

                              /*$sql = "select SUM(`actual_total_lifted`) as tl, SUM(`actual_total_reps` + IFNULL(`actual_reps_com`,0) + IFNULL(`actual_reps_com2`,0)) as tr from `markedexerciseplanview` where
                                             `userid` = '".$userid."' and
                                             `date` between
                                             '".$data["sdate"]."' and '".$data["edate"]."'";
	          $sql .= $limit ? " LIMIT ".$limit : " LIMIT 10";

                              $result = $this->_db->query($sql)->fetchAll();


                              foreach($result as $row){
                                             $datearray[] = date("d M Y",$stime);
                                             $totalliftarray[] = round((int) $row["tl"] / 1000, 2); //(int) $row["tl"];
                                             $averageloadarray[] =(int) ((int) $row["tl"] / (int) $row["tr"]);
                              }
	          */
	          $lolsql = "";

	         
                              for($i = -1; $i<=($limit); $i++){

		/*
		 * changes to query
		 * between
                                             '".date("Y-m-d",$stime - (86400 * ($i + 1)) )."' and
		 *
		 * changed to =
		 */
                                             $sql = "select SUM(`actual_total_lifted`) as tl, SUM(`actual_total_reps` + IFNULL(`actual_reps_com`,0) + IFNULL(`actual_reps_com2`,0)) as tr from `markedexerciseplanview`
                                                            where `userid` = '".$userid."' and
                                                            (`date` = '".date("Y-m-d",$stime - (86400 * ($i + 1)))."')";
                                             $sql .= $limit ? " limit ".$limit : " limit 10";
	           	          $result = $this->_db->query($sql)->fetchAll();
		          $lolsql .=$sql." \n";
		     //Zend_Debug::dump($result);
		     

                                             foreach($result as $row){
		               
                                                            array_unshift($datearray,date("d M Y",$stime - (86400 * ($i + 1)) ) );
                                                            array_unshift($totalliftarray, round((int) $row["tl"] / 1000, 2));
                                                            array_unshift($averageloadarray, (int)((int) $row["tl"] / (int) $row["tr"]));
                                             }

                              }
	          
	          //Application_Model_DbTable_TestLog::log($lolsql);
                              //exit;

                               // Dataset definition
                               $DataSet = new pData;
                               $DataSet->AddPoint($totalliftarray,"Serie1");
                                $DataSet->AddPoint($averageloadarray,"Serie2");
                               $DataSet->AddPoint($datearray, "Serie3");
                               $DataSet->AddSerie("Serie1");
                               $DataSet->SetAbsciseLabelSerie("Serie3");
                               $DataSet->SetSerieName("Total Lifted (tonne)","Serie1");
                                $DataSet->SetSerieName("Average Load (kg.)","Serie2");
                                $DataSet->SetYAxisName("Total Lifted (tonne)");

                              // Cache definition
                              $Cache = new pCache();
                              //$Cache->GetFromCache("Graph5",$DataSet->GetData());
                                // Initialise the graph
                                $Test = new pChart(660+$expand,390);

                                // Prepare the graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->setGraphArea(60,40,595+$expand,290,true);

                                  $Test->drawFilledRoundedRectangle(7,7,656+$expand,383,5,240,240,240);
                                $Test->drawRoundedRectangle(5,5,658+$expand,385,5,230,230,230);
                              $Test->drawGraphArea(255,255,255,TRUE);

                                // Initialise graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);

                                // Draw the SourceForge Rank graph
                                $DataSet->SetYAxisName("Total Lifted (tonne)");
                                $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
                                $Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),true);
                                  $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");

                                // Clear the scale
                                $Test->clearScale();

                                // Draw the 2nd graph
                                $DataSet->RemoveSerie("Serie1");
                                $DataSet->AddSerie("Serie2");
                                $DataSet->SetYAxisName("Average Load (kg)");
                                $Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
                                //$Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                                $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");

                                // Write the legend (box less)
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->drawLegend(530+$expand,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,150,150,150,FALSE);

                                // Write the title
                                $Test->setFontProperties("Fonts/MankSans.ttf",10);
                                $Test->drawTitle(0,0,"Total Lifted & Average Load (Daily)",150,150,150,660,30);

                                // Render the picture
                                 $Cache->WriteToCache("Graph30",$DataSet->GetData(),$Test);

                                $Test->Stroke();
               }

               function drawchart_volumeindex($data){
                              error_reporting(0);
                              $userid = (int) $data["userid"];
	          $dr = new Application_Model_DbTable_Dailyreport();
	          $res = $dr->fetchRow($dr->select()->where("userid = ".$userid)->where("bw_morning > 0")->order("date DESC"));
	          $mass = $res["bw_morning"];
                              $limit = (int) $data["limit"];
                              if(!$limit){$limit=10;}
	          else{
		$limit = max(array($limit-2,0));
	          }
                              $tstamp = time();
                              $thisweek = date("W",$tstamp);
                              $thisyear = (int) date("Y", $tstamp);
                              $stime = $data["edate"] ? (int) strtotime($data["edate"]) : (int) time(); //this is the time

                              $datearray = array();
                              $totalliftarray = array();
                              $averageloadarray = array();

	          if($data["sdate"] && $data["edate"]){
		$limit = ceil((strtotime($data["edate"]) - strtotime($data["sdate"])) / 86400);
		$limit--;
	          }



                              /*$sql = "select SUM(`actual_total_lifted`) as tl, SUM(`actual_total_reps` + IFNULL(`actual_reps_com`,0) + IFNULL(`actual_reps_com2`,0)) as tr from `markedexerciseplanview` where
                                             `userid` = '".$userid."' and
                                             `date` between
                                             '".$data["sdate"]."' and '".$data["edate"]."'";
	          $sql .= $limit ? " LIMIT ".$limit : " LIMIT 10";

                              $result = $this->_db->query($sql)->fetchAll();


                              foreach($result as $row){
                                             $datearray[] = date("d M Y",$stime);
                                             $totalliftarray[] = round((int) $row["tl"] / 1000, 2); //(int) $row["tl"];
                                             $averageloadarray[] =(int) ((int) $row["tl"] / (int) $row["tr"]);
                              }
	          */
	          $lolsql = "";


                              for($i = -1; $i<=($limit); $i++){

                                             $sql = "select SUM(`actual_total_lifted`) as tl from `markedexerciseplanview`
                                                            where `userid` = '".$userid."' and
                                                            (`date` between
                                             '".date("Y-m-d",$stime - (86400 * ($i + 1)) )."' and '".date("Y-m-d",$stime - (86400 * $i))."')";
                                             $sql .= $limit ? " limit ".$limit : " limit 10";
	           	          $result = $this->_db->query($sql)->fetchAll();
		          $lolsql .=$sql." \n";
		     //Zend_Debug::dump($result);

                                             foreach($result as $row){
                                                            array_unshift($datearray,date("d M Y",$stime - (86400 * ($i + 1)) ) );
                                                            array_unshift($totalliftarray, round((int) $row["tl"] / $mass, 2));
                                                            //array_unshift($averageloadarray, (int)((int) $row["tl"] / (int) $row["tr"]));
                                             }

                              }
	          //Application_Model_DbTable_TestLog::log($lolsql);
                              //exit;

                               // Dataset definition
                               $DataSet = new pData;
                               $DataSet->AddPoint($totalliftarray,"Serie1");
                                //$DataSet->AddPoint($averageloadarray,"Serie2");
                               $DataSet->AddPoint($datearray, "Serie3");
                               $DataSet->AddSerie("Serie1");
                               $DataSet->SetAbsciseLabelSerie("Serie3");
                               $DataSet->SetSerieName("Total Lifted (tonne)","Serie1");
                                //$DataSet->SetSerieName("Average Load (kg.)","Serie2");
                                $DataSet->SetYAxisName("Volume Index");

                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph40",$DataSet->GetData());
                                // Initialise the graph
                                $Test = new pChart(660,390);

                                // Prepare the graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->setGraphArea(60,40,595,290,true);

                                  $Test->drawFilledRoundedRectangle(7,7,656,383,5,240,240,240);
                                $Test->drawRoundedRectangle(5,5,658,385,5,230,230,230);
                              $Test->drawGraphArea(255,255,255,TRUE);

                                // Initialise graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);

                                // Draw the SourceForge Rank graph
                                $DataSet->SetYAxisName("Total Lifted (tonne)");
                                $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
                                $Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),true);
                                  $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");

                                // Clear the scale
	            /*
                                $Test->clearScale();

                                // Draw the 2nd graph
                                $DataSet->RemoveSerie("Serie1");
                                $DataSet->AddSerie("Serie2");
                                $DataSet->SetYAxisName("Average Load (kg)");
                                $Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
                                //$Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                                $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");

                                // Write the legend (box less)
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->drawLegend(530,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,150,150,150,FALSE);
	            */
                                // Write the title
                                $Test->setFontProperties("Fonts/MankSans.ttf",10);
                                $Test->drawTitle(0,0,"Volume Index",150,150,150,660,30);

                                // Render the picture
                                 $Cache->WriteToCache("Graph40",$DataSet->GetData(),$Test);

                                $Test->Stroke();
               }

               

               function drawchart_geninfo($data){
                              error_reporting(0);
                              $userid = (int) $data["userid"];
	          $expand = 0;

                              if($data["sdate"] && $data["edate"]){
                                             $range = " and `date` between '".$this->cleanDate($data["sdate"])."' and '".  $this->cleanDate($data["edate"])."' ";
		     $edate = $data["edate"];
                              }
                              else{ $range = ""; $edate = date("Y-m-d",time()); }
                              $query = "select `date`,`sleep_quality`,`mental_recovery`,`physical_recovery`,`pre_training_energy`,`muscle_soreness`,`general_fatigue`
                                             from `dailyreport` where `userid` = ".$userid."
                                            ".$range."
                              order by `date` DESC
                                             ";
                              if($data["limit"]){
                                             $query .= " limit ".$data["limit"];
		     $timelim = (int)$data["limit"];
		     $expand = $timelim > 10? ($timelim - 10)*10:0;
                              }
                              else{
                                             $query .= " limit 10";
		     $timelim = 10;
		     
                              }
                              $sql = $query;
                              $result = $this->_db->query($sql)->fetchAll();

                              $date_a = array();
                              $sleep_quality_a = array();
                              $mental_recovery_a = array();
                              $physical_recovery_a = array();
                              $pre_training_energy_a = array();
                              $muscle_soreness_a = array();
                              $general_fatigue_a = array();
	          $firstdatetimestamp = strtotime($edate);
	          $globalcount = 1;
                              foreach($result as $row){
		if($globalcount > $timelim){break;}

		for($p=1;$p<$timelim;$p++){

		          if($row["date"] != date("Y-m-d",$firstdatetimestamp)){
			array_unshift($date_a , date("d M Y", $firstdatetimestamp));
			    if($data["sq"]){array_unshift($sleep_quality_a, 0);}
			    if($data["mr"]){array_unshift($mental_recovery_a,0);}
			    if($data["pr"]){array_unshift($physical_recovery_a, 0);}
			    if($data["pte"]){array_unshift($pre_training_energy_a,0);}
			    if($data["ms"]){array_unshift($muscle_soreness_a, 0);}
			    if($data["gf"]){array_unshift($general_fatigue_a, 0);}
			    //$firstdatetimestamp -= 86400;
			    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
			    if($globalcount > $timelim){break;}else{$globalcount++;}
		          }
		          else{
			break;
		          }
		}
		if($globalcount > $timelim){break;}

                                            array_unshift($date_a , date("d M Y", strtotime($row["date"])));
                                            if($data["sq"]){array_unshift($sleep_quality_a, (float) $row["sleep_quality"]);}
                                            if($data["mr"]){array_unshift($mental_recovery_a, (float)$row["mental_recovery"]);}
                                            if($data["pr"]){array_unshift($physical_recovery_a, (float)$row["physical_recovery"]);}
                                            if($data["pte"]){array_unshift($pre_training_energy_a, (float)$row["pre_training_energy"]);}
                                            if($data["ms"]){array_unshift($muscle_soreness_a, (float)$row["muscle_soreness"]);}
                                            if($data["gf"]){array_unshift($general_fatigue_a, (float)$row["general_fatigue"]);}

		    //$firstdatetimestamp -= 86400;
		    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
		    $globalcount++;
                              }

                               // Dataset definition
                               $DataSet = new pData;
                              if($data["sq"]){$DataSet->AddPoint($sleep_quality_a,"Serie1");}
                              if($data["mr"]){$DataSet->AddPoint($mental_recovery_a,"Serie2");}
                              if($data["pr"]){$DataSet->AddPoint($physical_recovery_a,"Serie3");}
                              if($data["pte"]){$DataSet->AddPoint($pre_training_energy_a,"Serie4");}
                              if($data["ms"]){$DataSet->AddPoint($muscle_soreness_a,"Serie5");}
                              if($data["gf"]){$DataSet->AddPoint($general_fatigue_a,"Serie6");}
                               $DataSet->AddPoint($date_a, "Serie7");
                               if($data["sq"]){$DataSet->AddSerie("Serie1");}
                               if($data["mr"]){$DataSet->AddSerie("Serie2");}
                               if($data["pr"]){$DataSet->AddSerie("Serie3");}
                               if($data["pte"]){$DataSet->AddSerie("Serie4");}
                               if($data["ms"]){$DataSet->AddSerie("Serie5");}
                               if($data["gf"]){$DataSet->AddSerie("Serie6");}
                               $DataSet->SetAbsciseLabelSerie("Serie7");
                              if($data["sq"]){ $DataSet->SetSerieName("Sleep Quality","Serie1");}
                               if($data["mr"]){$DataSet->SetSerieName("Mental Recovery","Serie2");}
                               if($data["pr"]){$DataSet->SetSerieName("Physical Recovery","Serie3");}
                               if($data["pte"]){$DataSet->SetSerieName("Pre Training Energy","Serie4");}
                               if($data["ms"]){$DataSet->SetSerieName("Muscle Soreness","Serie5");}
                               if($data["gf"]){$DataSet->SetSerieName("General Fatigue","Serie6");}
                               $DataSet->SetYAxisName("Scale");
                               $DataSet->SetYAxisUnit("");

                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph2",$DataSet->GetData());

                               // Initialise the graph
                               $Test = new pChart(700+$expand,310);
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->setGraphArea(70,30,580+$expand,240);
                               $Test->drawFilledRoundedRectangle(7,7,593+$expand,303,5,240,240,240);
                               $Test->drawRoundedRectangle(5,5,595+$expand,305,5,230,230,230);
                               $Test->drawGraphArea(237,237,237,true);
                                $Test->setFixedScale(0,6,12);
                               $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,true,45,1);
                               $Test->drawGrid(4,true,228,228,228);


                               // Draw the 0 line
                               $Test->setFontProperties("Fonts/tahoma.ttf",6);
                               $Test->drawTreshold(3,162,162,162,true,false,3);

                               // Draw the line graph
                               //$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->setFontProperties("Fonts/tahoma.ttf",7);
                                $Test->setColorPalette(0,112,55,46);
                                if($data["sq"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");}
                                if($data["mr"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");}
                                if($data["pr"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");}
                                if($data["pte"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie4");}
                                if($data["ms"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie5");}
                                if($data["gf"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie6");}
                               // Finish the graph
                               $Test->setFontProperties("Fonts/tahoma.ttf",7);
                               $Test->drawLegend(595+$expand,35,$DataSet->GetDataDescription(),255,255,255,true);
                               $Test->setFontProperties("Fonts/tahoma.ttf",10);
                               $Test->drawTitle(60,22,"Fatigue and Recovery",50,50,50,585);
                               $Cache->WriteToCache("Graph2",$DataSet->GetData(),$Test);
                               $Test->Stroke("example1.png");
               }

               function drawchart_monotony($data){
	     exit;
                              error_reporting(0);
                              $userid = (int) $data["userid"];
                              $limit = (int) $data["limit"];
                              if(!$limit){$limit=10;}
                              $tstamp = time();
                              $thisweek = date("W",$tstamp);
                              $thisyear = (int) date("Y", $tstamp);
                              $stime = strtotime($thisyear."W".$thisweek); //this is the timestamp for Monday of this week

                              $date_array = array();
                              $monotony_array = array();
                              $strain_array = array();
                              $joojoo = 0;

                              for($i = 0; $i < $limit; $i++){
                                             $fromdate = date("Y-m-d",$stime - (604800 * $i));
                                             $todate = date("Y-m-d", $stime - (604800 * $i) + 604800);

                                             $query = "select ((`session1_duration` * `session1_intensity`)+(`session2_duration` * `session2_intensity`)) as `dl`
                                             from `dailyreport`
                                             where `userid` = '".$userid."' and
                                                            `date` between '".$fromdate."'  and '".$todate."'
                                             order by `date` DESC
                                             "; //this will give us the total load per day. Result will have multiple rows, 1 for each day
                                             $result = $this->_db->query($query)->fetchAll();
                                             $dailyload = array();
                                             foreach($result as $row){
                                                            array_push($dailyload, $row["dl"]);
                                             }

                                             $count = count($dailyload);
                                             $left = 7 - $count;
                                             if($left > 0){
                                                            for($ii=0; $ii < $left; $ii++){
                                                                            array_push($dailyload,0);
                                                            }
                                             }

                                             $totalload = array_sum($dailyload);
                                             $sd = $this->standard_deviation_population($dailyload);
                                             $mean =  $totalload / 7;
                                             $monotony = ($mean / $sd) == NULL ? 0 : ($mean / $sd);
                                             $strain = ($totalload * $monotony) == NULL ? 0 : ($totalload * $monotony);

                                             array_unshift($date_array, date("j M Y",strtotime($fromdate)));
                                             array_unshift($monotony_array, round($monotony,2));
                                             array_unshift($strain_array, round($strain));

                              }

                               // Dataset definition
                               $DataSet = new pData;
                               $DataSet->AddPoint($monotony_array,"Serie1");
                                $DataSet->AddPoint($strain_array,"Serie2");
                               $DataSet->AddPoint($date_array, "Serie3");
                               $DataSet->AddSerie("Serie1");
                               $DataSet->SetAbsciseLabelSerie("Serie3");
                               $DataSet->SetSerieName("Monotony","Serie1");
                                $DataSet->SetSerieName("Strain","Serie2");
                                $DataSet->SetYAxisName("Monotony");

                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph6",$DataSet->GetData());
                                // Initialise the graph
                                $Test = new pChart(660,390);

                                // Prepare the graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->setGraphArea(60,40,595,290,true);

                                  $Test->drawFilledRoundedRectangle(7,7,656,383,5,240,240,240);
                                $Test->drawRoundedRectangle(5,5,658,385,5,230,230,230);
                              $Test->drawGraphArea(255,255,255,TRUE);

                                // Initialise graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);

                                // Draw the SourceForge Rank graph
                                $DataSet->SetYAxisName("MONOTONY");
                                $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALL,150,150,150,TRUE,90,2,true);
                                $Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                                $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                  $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");

                                // Clear the scale
                                $Test->clearScale();

                                // Draw the 2nd graph
                                $DataSet->RemoveSerie("Serie1");
                                $DataSet->AddSerie("Serie2");
                                $DataSet->SetYAxisName("STRAIN");
                                $Test->setFixedScale(0, 1000);
                                $Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALL,150,150,150,TRUE,90,2,true);
                                //$Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                                $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");

                                // Write the legend (box less)
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->drawLegend(530,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,150,150,150,FALSE);

                                // Write the title
                                $Test->setFontProperties("Fonts/MankSans.ttf",10);
                                $Test->drawTitle(0,0,"Training Monotony & Strain",150,150,150,660,30);

                                // Render the picture
                                 $Cache->WriteToCache("Graph6",$DataSet->GetData(),$Test);
                                $Test->Stroke();
               }

              function standard_deviation_population ($a)
                              {
                                //variable and initializations
                                $the_standard_deviation = 0.0;
                                $the_variance = 0.0;
                                $the_mean = 0.0;
                                $the_array_sum = array_sum($a); //sum the elements
                                $number_elements = count($a); //count the number of elements

                                //calculate the mean
                                $the_mean = $the_array_sum / $number_elements;

                                //calculate the variance
                                for ($i = 0; $i < $number_elements; $i++)
                                {
                                  //sum the array
                                  $the_variance = $the_variance + ($a[$i] - $the_mean) * ($a[$i] - $the_mean);
                                }

                                $the_variance = $the_variance / $number_elements;

                                //calculate the standard deviation
                                $the_standard_deviation = pow( $the_variance, 0.5);

                                //return the variance
                                return $the_standard_deviation;
                              }

           function drawchart_movements($data){
                              error_reporting(0);
                              $userid = (int) $data["userid"];
	          $expand = 0;
                              $limit = $data["limit"] ? (int) $data["limit"] : 10;
	          $expand = $limit > 10 ? ($limit -10)*10 : 0;
	          $timelim = $limit;

                              $beginningstamp = (int) strtotime(date("Y",time())."W".date("W", time()));
                              $endingstamp = $beginningstamp;
                              $beginningstamp +=  604800;

                              //temp container var
                              $holder = array();
                              $snatch = array();
                              $powersnatch = array();
                              $cj = array();
                              $pc = array();
                              $squat = array();
                              $pjfr = array();
                              $date = array();


                              for($i=0;$i<$limit;$i++){//loop for number of weeks

                                                            $sql = "SELECT `exerciseid`, MAX(`actual_weight`) AS `maw`
                                                                           FROM `markedexerciseplanview`
                                                                           WHERE (`userid` = $userid)
                                                                                          AND (`date` BETWEEN '".date("Y-m-d",$endingstamp)."' AND '".date("Y-m-d",$beginningstamp)."')
                                                                                          AND (`exerciseid` IN (1,11,3,31,54,39))
                                                                          GROUP BY `exerciseid`";
                                                            $result = $this->_db->query($sql)->fetchAll();
                                                            $ech = array();
                                                            foreach($result as $row){
                                                                           $holder[(int) $row["exerciseid"]] = $row["maw"] ;
                                                            }
                                                            array_unshift($snatch,$holder[1] ? $holder[1] : 0);
                                                            array_unshift($powersnatch, $holder[11] ? $holder[11] : 0);
                                                            array_unshift($cj, $holder[3] ? $holder[3] : 0);
                                                            array_unshift($pc, $holder[31] ? $holder[31] : 0);
                                                            array_unshift($squat, $holder[54] ? $holder[54] : 0);
                                                            array_unshift($pjfr, $holder[39] ? $holder[39] : 0);
                                                            array_unshift($date, date("j M Y",$endingstamp));



                                                            $holder = array();
                                                            $beginningstamp -= 604800;
                                                            $endingstamp -= 604800;
                              }

                                $DataSet = new pData;

                                if($data["snatch"]){$DataSet->AddPoint($snatch, "Serie1");}
                                if($data["psnatch"]){$DataSet->AddPoint($powersnatch, "Serie2");}
                                if($data["cj"]){$DataSet->AddPoint($cj, "Serie3");}
                                if($data["pc"]){$DataSet->AddPoint($pc, "Serie4");}
                                if($data["squat"]){$DataSet->AddPoint($squat, "Serie5");}
                               if($data["pjfr"]){$DataSet->AddPoint($pjfr, "Serie6");}
                               $DataSet->AddPoint($date,"Serie7");

                                 if($data["snatch"]){$DataSet->AddSerie("Serie1");}
                                if($data["psnatch"]){$DataSet->AddSerie("Serie2");}
                                if($data["cj"]){$DataSet->AddSerie("Serie3");}
                                if($data["pc"]){$DataSet->AddSerie("Serie4");}
                                if($data["squat"]){$DataSet->AddSerie("Serie5");}
                               if($data["pjfr"]){$DataSet->AddSerie("Serie6");}
                               $DataSet->SetAbsciseLabelSerie("Serie7");

                                  if($data["snatch"]){$DataSet->SetSerieName("Snatch","Serie1");}
                                if($data["psnatch"]){$DataSet->SetSerieName("Power Snatch","Serie2");}
                                if($data["cj"]){$DataSet->SetSerieName("Clean & Jerk","Serie3");}
                                if($data["pc"]){$DataSet->SetSerieName("Power Clean","Serie4");}
                                if($data["squat"]){$DataSet->SetSerieName("Squat","Serie5");}
                               if($data["pjfr"]){$DataSet->SetSerieName("Power Jerk \r\n(From racks)", "Serie6");}
                               $DataSet->SetYAxisName("Weight (kg.)");
                               $DataSet->SetYAxisUnit("");


                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph9",$DataSet->GetData());

                               // Initialise the graph
                               $Test = new pChart(700+$expand,310);
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->setGraphArea(70,30,580+$expand,240);
                               $Test->drawFilledRoundedRectangle(7,7,593+$expand,303,5,240,240,240);
                               $Test->drawRoundedRectangle(5,5,595+$expand,305,5,230,230,230);
                               $Test->drawGraphArea(237,237,237,true);
                               $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,true,45,1);
                               $Test->drawGrid(4,true,228,228,228);


                               // Draw the 0 line
                               //$Test->setFontProperties("Fonts/tahoma.ttf",6);
                               //$Test->drawTreshold(3,162,162,162,true,false,3);

                               // Draw the line graph
                               $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                               //$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->setFontProperties("Fonts/tahoma.ttf",7);
                                $Test->setColorPalette(0,112,55,46);
                                if($data["snatch"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");}
                                if($data["psnatch"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");}
                                if($data["cj"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");}
                                if($data["pc"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie4");}
                                if($data["squat"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie5");}
                                if($data["pjfr"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie6");}
                               // Finish the graph
                               $Test->setFontProperties("Fonts/tahoma.ttf",7);
                               $Test->drawLegend(600+$expand,35,$DataSet->GetDataDescription(),255,255,255,true);
                               $Test->setFontProperties("Fonts/tahoma.ttf",10);
                               $Test->drawTitle(60,22,"Current max per exercise",50,50,50,585);
                               $Cache->WriteToCache("Graph9",$DataSet->GetData(),$Test);
                               $Test->Stroke("example9.png");
           }

           function drawchart_movementspl($data){
                              error_reporting(0);
                              $userid = (int) $data["userid"];
	          $expand = 0;
                              $limit = $data["limit"] ? (int) $data["limit"] : 10;
	          $expand = $limit > 10 ? ($limit - 10) * 10 : 0;
	          $timelim = $limit;

                              $beginningstamp = (int) strtotime(date("Y",time())."W".date("W", time()));
                              $endingstamp = $beginningstamp;
                              $beginningstamp +=  604800;

                              //temp container var
                              $holder = array();
                              $snatch = array();
                              $powersnatch = array();
                              $cj = array();
                              $pc = array();
                              $squat = array();
                              $pjfr = array();
	          $fbp = array();
                              $date = array();
	          $q = "";

                              for($i=0;$i<$limit;$i++){//loop for number of weeks

                                                            $sql = "SELECT `exerciseid`, MAX(`actual_weight`) AS `maw`
                                                                           FROM `markedexerciseplanview`
                                                                           WHERE (`userid` = $userid)
                                                                                          AND (`date` BETWEEN '".date("Y-m-d",$endingstamp)."' AND '".date("Y-m-d",$beginningstamp)."')
                                                                                          AND (`exerciseid` IN (237,205,233,234,231,201))
                                                                          GROUP BY `exerciseid`";
			$q .= "\n".$sql;
                                                            $result = $this->_db->query($sql)->fetchAll();
                                                            $ech = array();
                                                            foreach($result as $row){
                                                                           $holder[(int) $row["exerciseid"]] = $row["maw"] ;
                                                            }
                                                            array_unshift($snatch,$holder[237] ? $holder[237] : 0);
                                                            array_unshift($powersnatch, $holder[205] ? $holder[205] : 0);
                                                            array_unshift($cj, $holder[233] ? $holder[233] : 0);
                                                            array_unshift($pc, $holder[234] ? $holder[234] : 0);
                                                            array_unshift($squat, $holder[231] ? $holder[231] : 0);
			array_unshift($fbp, $holder[201] ? $holder[201]:0);
                                                            //array_unshift($pjfr, $holder[39] ? $holder[39] : 0);
                                                            array_unshift($date, date("j M Y",$endingstamp));



                                                            $holder = array();
                                                            $beginningstamp -= 604800;
                                                            $endingstamp -= 604800;
                              }
	          Application_Model_DbTable_TestLog::log($q);

                                $DataSet = new pData;

                                if($data["snatch"]){$DataSet->AddPoint($snatch, "Serie1");}
                                if($data["psnatch"]){$DataSet->AddPoint($powersnatch, "Serie2");}
                                if($data["cj"]){$DataSet->AddPoint($cj, "Serie3");}
                                if($data["pc"]){$DataSet->AddPoint($pc, "Serie4");}
                                if($data["squat"]){$DataSet->AddPoint($squat, "Serie5");}
                               if($data["fbp"]){$DataSet->AddPoint($fbp, "Serie6");}
                               $DataSet->AddPoint($date,"Serie7");

                                 if($data["snatch"]){$DataSet->AddSerie("Serie1");}
                                if($data["psnatch"]){$DataSet->AddSerie("Serie2");}
                                if($data["cj"]){$DataSet->AddSerie("Serie3");}
                                if($data["pc"]){$DataSet->AddSerie("Serie4");}
                                if($data["squat"]){$DataSet->AddSerie("Serie5");}
                               if($data["fbp"]){$DataSet->AddSerie("Serie6");}
                               $DataSet->SetAbsciseLabelSerie("Serie7");

                                  if($data["snatch"]){$DataSet->SetSerieName("Holds","Serie1");}
                                if($data["psnatch"]){$DataSet->SetSerieName("Doubles","Serie2");}
                                if($data["cj"]){$DataSet->SetSerieName("Jamaican Press","Serie3");}
                                if($data["pc"]){$DataSet->SetSerieName("Board Press","Serie4");}
                                if($data["squat"]){$DataSet->SetSerieName("Bottoms","Serie5");}
                               if($data["fbp"]){$DataSet->SetSerieName("Formal B. Press", "Serie6");}
                               $DataSet->SetYAxisName("Weight (kg.)");
                               $DataSet->SetYAxisUnit("");


                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph90",$DataSet->GetData());

                               // Initialise the graph
                               $Test = new pChart(700+$expand,310);
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->setGraphArea(70,30,580+$expand,240);
                               $Test->drawFilledRoundedRectangle(7,7,593+$expand,303,5,240,240,240);
                               $Test->drawRoundedRectangle(5,5,595+$expand,305,5,230,230,230);
                               $Test->drawGraphArea(237,237,237,true);
                               $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,true,45,1);
                               $Test->drawGrid(4,true,228,228,228);


                               // Draw the 0 line
                               //$Test->setFontProperties("Fonts/tahoma.ttf",6);
                               //$Test->drawTreshold(3,162,162,162,true,false,3);

                               // Draw the line graph
                               $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                               //$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->setFontProperties("Fonts/tahoma.ttf",7);
                                $Test->setColorPalette(0,112,55,46);
                                if($data["snatch"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");}
                                if($data["psnatch"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");}
                                if($data["cj"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");}
                                if($data["pc"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie4");}
                                if($data["squat"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie5");}
                                if($data["fbp"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie6");}
                               // Finish the graph
                               $Test->setFontProperties("Fonts/tahoma.ttf",7);
                               $Test->drawLegend(600+$expand,35,$DataSet->GetDataDescription(),255,255,255,true);
                               $Test->setFontProperties("Fonts/tahoma.ttf",10);
                               $Test->drawTitle(60,22,"Current max per exercise",50,50,50,585);
                               $Cache->WriteToCache("Graph9",$DataSet->GetData(),$Test);
                               $Test->Stroke("example90.png");
           }

           function drawchart_ratio($data){
	 /*
                          error_reporting(0);
                              $userid = (int) $data["userid"];
                              $query = "select * from `weekly_report_ratio` where `userid` = '".$userid."'";
                              if($data["limit"]){
                                             $query .= " limit ".$data["limit"];
                              }
                              else{
                                             $query .= " limit 10";
                              }
                              $result = $this->_db->query($query)->fetchAll();
                              $datear = array();
                              $dataar = array();

                              foreach($result as $row){
                                            array_unshift($dataar ,round( (($row["max_snatch"] + $row["max_cj"]) / $row["bw"]),2));
                                            array_unshift($datear , date("d M Y", strtotime($row["date"])));
                              }

                               // Dataset definition
                               $DataSet = new pData;
                               $DataSet->AddPoint($dataar,"Serie1");
                               $DataSet->AddPoint($datear, "Serie3");
                               $DataSet->AddSerie("Serie1");
                               $DataSet->SetAbsciseLabelSerie("Serie3");
                               $DataSet->SetSerieName("S/W Ratio","Serie1");
                               $DataSet->SetYAxisName("S/W Ratio");
                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph8",$DataSet->GetData());

                               // Initialise the graph
                               $Test = new pChart(700,270);
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->setGraphArea(70,30,680,200);
                               $Test->drawFilledRoundedRectangle(7,7,693,263,5,240,240,240);
                               $Test->drawRoundedRectangle(5,5,695,265,5,230,230,230);
                               $Test->drawGraphArea(237,237,237,true);
                               $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,true,45,2,true);
                               $Test->drawGrid(4,true,228,228,228);


                               // Draw the 0 line
                               $Test->setFontProperties("Fonts/tahoma.ttf",6);
                               //$Test->drawTreshold(180,162,162,162,true,false,3);

                               // Draw the line graph
                               $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                               //$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->setFontProperties("Fonts/tahoma.ttf",7);
                                $Test->setColorPalette(0,112,55,46);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie1");
                               // Finish the graph
                               //$Test->setFontProperties("Fonts/tahoma.ttf",8);
                               //$Test->drawLegend(75,35,$DataSet->GetDataDescription(),255,255,255);
                               $Test->setFontProperties("Fonts/tahoma.ttf",10);
                               $Test->drawTitle(60,22,"Strength / Weight Ratio",50,50,50,585);
                               $Cache->WriteToCache("Graph8",$DataSet->GetData(),$Test);
                               $Test->Stroke("example1.png");
	  */
           }

           function drawchart_sleepquan($data){
                          error_reporting(0);
                              $userid = (int) $data["userid"];
	          $expand =0;

                              if($data["sdate"] && $data["edate"]){
                                             $range = " and `date` between '".$this->cleanDate($data["sdate"])."' and '".  $this->cleanDate($data["edate"])."' ";
		     $edate = $data["edate"];
                              }
                              else{ $range = ""; $edate = date("Y-m-d",time()); }
                              $query = "select `date`, `sleep_quantity` from `dailyreport` where `userid` = ".$userid."
                                            ".$range."
                              order by `date` DESC
                                             ";
                              if($data["limit"]){
                                             $query .= " limit ".$data["limit"];
		     $timelim = $data["limit"];
		     $expand = $timelim > 10 ? ($timelim - 10)*10: 0;
                              }
                              else{
                                             $query .= " limit 10";
		     $timelim = 10;
                              }
                              $result = $this->_db->query($query)->fetchAll();
                              $datear = array();
                              $dataar = array();
	          $firstdatetimestamp = strtotime($edate);
	          $globalcount = 1;

                              foreach($result as $row){
		if($globalcount > $timelim){break;}
		for($p=1;$p<$timelim;$p++){

		          if($row["date"] != date("Y-m-d",$firstdatetimestamp)){
			array_unshift($dataar , 0);
			array_unshift($datear , date("d M Y", $firstdatetimestamp));
			$firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
			//$firstdatetimestamp -= 86400;
			if($globalcount > $timelim){break;}else{$globalcount++;}
		          }


		          else{
			break;
		          }
		}
		if($globalcount > $timelim){break;}
                                            array_unshift($dataar , $row["sleep_quantity"]);
                                            array_unshift($datear , date("d M Y", strtotime($row["date"])));
		    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
		    //$firstdatetimestamp -= 86400;
		    $globalcount++;
                              }
                              

                               // Dataset definition
                               $DataSet = new pData;
                               $DataSet->AddPoint($dataar,"Serie1");
                               $DataSet->AddPoint($datear, "Serie3");
                               $DataSet->AddSerie("Serie1");
                               $DataSet->SetAbsciseLabelSerie("Serie3");
                               $DataSet->SetSerieName("Sleep quantity","Serie1");
                               $DataSet->SetYAxisName("Hrs");

                              // Cache definition
                              $Cache = new pCache();
                              $Cache->GetFromCache("Graph7",$DataSet->GetData());

                               // Initialise the graph
                               $Test = new pChart(700+$expand,270);
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->setGraphArea(70,30,680+$expand,200);
                               $Test->drawFilledRoundedRectangle(7,7,693+$expand,263,5,240,240,240);
                               $Test->drawRoundedRectangle(5,5,695+$expand,265,5,230,230,230);
                               $Test->drawGraphArea(237,237,237,true);
                               $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,true,45,0);
                               $Test->drawGrid(4,true,228,228,228);
                               $Test->setFixedScale(0,1000);

                               // Draw the 0 line
                               $Test->setFontProperties("Fonts/tahoma.ttf",6);
                               $Test->drawTreshold(180,162,162,162,true,false,3);

                               // Draw the line graph
                               $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                               //$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->setFontProperties("Fonts/tahoma.ttf",7);
                                $Test->setColorPalette(0,112,55,46);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie1");
                               // Finish the graph
                               //$Test->setFontProperties("Fonts/tahoma.ttf",8);
                               //$Test->drawLegend(75,35,$DataSet->GetDataDescription(),255,255,255);
                               $Test->setFontProperties("Fonts/tahoma.ttf",10);
                               $Test->drawTitle(60,22,"Sleep quantity",50,50,50,585);
                               $Cache->WriteToCache("Graph7",$DataSet->GetData(),$Test);
                               $Test->Stroke();
           }

           function drawchart_totalreps($data){
                              error_reporting(0);
                              $userid = (int) $data["userid"];
	          $expand = 0;

                              /*$query = "select `date`, `session1_duration` , `session1_intensity` from `dailyreport` where `userid` = ".$userid." order by `date` DESC";
                              if($data["limit"]){
                                             $query .= " limit ".$data["limit"];
                              }*/
                              if($data["sdate"] && $data["edate"]){
                                             $range = "`date` between '".$this->cleanDate($data["sdate"])."' and '".  $this->cleanDate($data["edate"])."' and";
		     $edate = $data["edate"];
                              }
                              else{ $range = "";$edate = date("Y-m-d",time()); }

                              $query = "select `date` , SUM(IFNULL(`actual_total_reps`,0)) + SUM(IFNULL(`actual_reps_com`,0)) + SUM(IFNULL(`actual_reps_com2`,0)) as `satl`
                                             from `markedexerciseplanviewcompletetest`
                                             where ".$range."

                                                           `userid` = ".$userid."
                                             group by `date`
                                             order by `date` DESC
                                             "; //removed `done` = 1
	          $getplanid = "select `planid` from `user` where `userid` = '$userid' limit 1";
	          $uid = $this->_db->query($getplanid)->fetchAll();
	          $query2 = "select `date`, SUM(IFNULL(`reps`,0)) + SUM(IFNULL(`reps_com`,0)) + SUM(IFNULL(`reps_com2`,0)) as `stl`
                                             from `exerciseplan`
                                             where ".$range." `planid` IN (
				SELECT `planid` FROM `planhistory` WHERE `userid` = '".$userid."'
				)
                                             group by `date`
                                             order by `date` DESC
                                             ";

	          

                              if($data["limit"]){
                                             $query .= " limit ".$data["limit"];
		     $timelim = (int)$data["limit"];
		     $expand = $timelim >10? ($timelim - 10)*10: 0;
                              }
                              else{
                                             $query .= " limit 10";
		     $timelim = 10;
                              }
                              $result = $this->_db->query($query)->fetchAll();
	          $result2 = $this->_db->query($query2)->fetchAll();
	          $pres_row_array = array();
	          foreach($result2 as $presrow){
		$pres_row_array[$presrow["date"]] = $presrow["stl"];
	          }
	          
                              $datear = array();
                              $dataar = array();
                              $act_dataar = array();
	          $firstdatetimestamp = strtotime($edate);
	          $globalcount = 1;
                              foreach($result as $row){
		if($globalcount > $timelim){break;}
		for($p=1;$p<$timelim;$p++){

		          if($row["date"] != date("Y-m-d",$firstdatetimestamp)){
			    array_unshift($dataar , $pres_row_array[date("Y-m-d",$firstdatetimestamp)]);
			    array_unshift($act_dataar,0);
			    array_unshift($datear , date("d M Y", $firstdatetimestamp));
			    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
			    //$firstdatetimestamp -= 86400;
			if($globalcount > $timelim){break;}else{$globalcount++;}
			}
			else{
			          break;
			}
		          }
		          if($globalcount > $timelim){break;}
                                            array_unshift($dataar , $pres_row_array[$row["date"]]);
                                            array_unshift($act_dataar,$row["satl"]);
                                            array_unshift($datear , date("d M Y", strtotime($row["date"])));
		    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
		    //$firstdatetimestamp -= 86400;
		    $globalcount++;
                              }
	          //for setting pres reps
	          /*$firstdatetimestamp = strtotime($edate);
	          $globalcount = 1;
                              foreach($result as $row){
		if($globalcount > $timelim){break;}
		for($p=1;$p<$timelim;$p++){

		          if($row["date"] != date("Y-m-d",$firstdatetimestamp)){
			array_unshift($dataar , 0);
			    array_unshift($act_dataar,0);
			    array_unshift($datear , date("d M Y", $firstdatetimestamp));
			    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
			    //$firstdatetimestamp -= 86400;
			if($globalcount > $timelim){break;}else{$globalcount++;}
			}
			else{
			          break;
			}
		          }
		          if($globalcount > $timelim){break;}
                                            array_unshift($dataar , $row["stl"]);
                                            array_unshift($act_dataar,$row["satl"]);
                                            array_unshift($datear , date("d M Y", strtotime($row["date"])));
		    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
		    //$firstdatetimestamp -= 86400;
		    $globalcount++;
                              }

	           */
                               // Dataset definition
                               $DataSet = new pData;
                               $DataSet->AddPoint($dataar,"Serie1");
                               $DataSet->AddPoint($act_dataar,"Serie2");
                               $DataSet->AddPoint($datear, "Serie3");
                               $DataSet->AddSerie("Serie1");
                               $DataSet->AddSerie("Serie2");
                               $DataSet->SetAbsciseLabelSerie("Serie3");
                               $DataSet->SetSerieName("Prescribed","Serie1");
                                $DataSet->SetSerieName("Actual","Serie2");
                               $DataSet->SetYAxisName("Reps");

                               // Cache definition
                              $Cache = new pCache();
                              //$Cache->GetFromCache("Graph4",$DataSet->GetData());

                               // Initialise the graph
                               $Test = new pChart(730+$expand,370);
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->setGraphArea(70,30,620+$expand,300);
                               $Test->drawFilledRoundedRectangle(7,7,693+$expand,363,5,240,240,240);
                               $Test->drawRoundedRectangle(5,5,695+$expand,365,5,230,230,230);
                               $Test->drawGraphArea(237,237,237,true);
                                //$Test->setFixedScale(0,10,20);
                               $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,true,45,2,true);
                               $Test->drawGrid(4,true,228,228,228);

                               // Draw the 0 line
                               $Test->setFontProperties("Fonts/tahoma.ttf",6);
                               $Test->drawTreshold(900,162,162,162,true,false,3);

                               // Draw the line graph
                              // $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                               //$Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                               $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                $Test->setFontProperties("Fonts/tahoma.ttf",7);
                                $Test->setColorPalette(0,112,55,46);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie1", -10);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie2",10);

                                //$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie2");
                               // Finish the graph
                               $Test->setFontProperties("Fonts/tahoma.ttf",8);
                               $Test->drawLegend(630+$expand,35,$DataSet->GetDataDescription(),255,255,255);
                               $Test->setFontProperties("Fonts/tahoma.ttf",10);
                               $Test->drawTitle(60,22,"Prescribed vs Actual total reps done",50,50,50,585);

                                //$Cache->WriteToCache("Graph4",$DataSet->GetData(),$Test);
                               $Test->Stroke("example3.png");
           }

           function drawchart_weightHydration($data){
                              error_reporting(E_ALL^E_NOTICE);
                              $userid = (int) $data["userid"];
	          $expand = 0;

                              if($data["sdate"] && $data["edate"]){
                                             $range = " and `date` between '".addslashes($data["sdate"])."' and '". addslashes($data["edate"])."' ";
		     $edate = $data["edate"];
                              }
                              else{ $range = "";$edate = date("Y-m-d",time());}
                              $query = "select `date`, `bw_morning`,`bw_evening`,`urine_morning`,`urine_evening`,`fluid`,`tom`
                                             from `dailyreport` where `userid` = ".$userid."
                                            ".$range."
                              order by `date` DESC
                                             ";
                              if($data["limit"]){
                                             $query .= " limit ".$data["limit"];
		     $timelim = (int)$data["limit"];
		     $expand = $timelim > 10 ? ($timelim - 10)*10 : 0;
                              }
                              else{
                                             $query .= " limit 10";
		     $timelim = 10;
                              }
                              $sql = $query;
                              $result = $this->_db->query($sql)->fetchAll();

	     
	          
	          $date_a = array();
	          $bw_morning_a = array();
	          $bw_evening_a = array();
	          $urine_morning_a = array();
	          $urine_evening_a = array();
	          $fluid_a = array();
	          $tom_a = array();
	          $tom_val = array();

	          $firstdatetimestamp = strtotime($edate);
	          $globalcount = 1;


                              /*$date_a = array();
                              $sleep_quality_a = array();
                              $mental_recovery_a = array();
                              $physical_recovery_a = array();
                              $pre_training_energy_a = array();
                              $muscle_soreness_a = array();
                              $general_fatigue_a = array();*/

                              foreach($result as $row){
		if($globalcount > $timelim){break;}
		for($p=1;$p<$timelim;$p++){

		          if($row["date"] != date("Y-m-d",$firstdatetimestamp)){
			array_unshift($date_a , date("d M Y",$firstdatetimestamp));
                                            if($data["bwm"]){array_unshift($bw_morning_a, 0);}
                                            if($data["bwe"]){array_unshift($bw_evening_a,0);}
                                            if($data["um"]){array_unshift($urine_morning_a,0);}
                                            if($data["ue"]){array_unshift($urine_evening_a,0);}
		    array_unshift($tom_a,0);
		    array_unshift($tom_val,"");
		    array_unshift($fluid_a,0);
		    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
		    //$firstdatetimestamp -= 86400;
			if($globalcount > $timelim){break;}else{$globalcount++;}
		          }
		          else{
			break;
		          }
		          }
		          if($globalcount > $timelim){break;}
                                            array_unshift($date_a , date("d M Y", strtotime($row["date"])));
                                            if($data["bwm"]){array_unshift($bw_morning_a, (float) $row["bw_morning"]);}
                                            if($data["bwe"]){array_unshift($bw_evening_a, (float)$row["bw_evening"]);}
                                            if($data["um"]){array_unshift($urine_morning_a, (float)$row["urine_morning"]);}
                                            if($data["ue"]){array_unshift($urine_evening_a, (float)$row["urine_evening"]);}
		    $tomval = $row["tom"]=="n"? 0:8;
		    $tomval2 = $row["tom"]=="n"? "":"TOM!";
		    array_unshift($tom_a,$tomval);
		    array_unshift($tom_val,$tomval2);
		    array_unshift($fluid_a,(float)$row["fluid"]);
		    $firstdatetimestamp = strtotime(date("Y-m-d", $firstdatetimestamp) . " -1 day");
		    //$firstdatetimestamp -= 86400;
		    $globalcount++;
                              }

                               // Dataset definition
                               $DataSet = new pData;
                              if($data["bwm"]){$DataSet->AddPoint($bw_morning_a,"Serie1");}
                              if($data["bwe"]){$DataSet->AddPoint($bw_evening_a,"Serie2");}
	          $DataSet->AddPoint($fluid_a,"Serie5");
	          $DataSet->AddPoint($tom_a,"Serie6");
	          $DataSet->AddPoint($tom_val,"Serie8");


                               $DataSet->AddPoint($date_a, "Serie7");
                               if($data["bwm"]){$DataSet->AddSerie("Serie1");}
                               if($data["bwe"]){$DataSet->AddSerie("Serie2");}



                               $DataSet->SetAbsciseLabelSerie("Serie7");
                              if($data["bwm"]){ $DataSet->SetSerieName("Body weight (m)","Serie1");}
                               if($data["bwe"]){$DataSet->SetSerieName("Body weight (e)","Serie2");}
	           


                               $DataSet->SetYAxisName("Scale");
                               $DataSet->SetYAxisUnit("");

                              // Cache definition
                              //$Cache = new pCache();
                              //$Cache->GetFromCache("Graph20",$DataSet->GetData());
                                // Initialise the graph
                                $Test = new pChart(720+$expand,390);

                                // Prepare the graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->setGraphArea(60,40,595+$expand,290,true);

                                  $Test->drawFilledRoundedRectangle(7,7,656+$expand,383,5,240,240,240);
                                $Test->drawRoundedRectangle(5,5,658+$expand,385,5,230,230,230);
                              $Test->drawGraphArea(255,255,255,TRUE);

                                // Initialise graph area
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);

                                // Draw the SourceForge Rank graph
                                $DataSet->SetYAxisName("Body weight (kg.)");
	            $Test->setFixedScale(0,200,10);
	            if($data["bwm"] || $data["bwe"]){
                                $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
                                $Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),true);
                                $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1",-10);
	            $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2",10);
	            }

                                // Clear the scale
                                $Test->clearScale();

	            if($data["um"]){$DataSet->AddPoint($urine_morning_a,"Serie3");}
                               if($data["ue"]){$DataSet->AddPoint($urine_evening_a,"Serie4");}
	           if($data["um"]){$DataSet->AddSerie("Serie3");}
                               if($data["ue"]){$DataSet->AddSerie("Serie4");}
	           if($data["um"]){$DataSet->SetSerieName("Urine (m)","Serie3");}
                               if($data["ue"]){$DataSet->SetSerieName("Urine (e)","Serie4");}

                                // Draw the 2nd graph
                                if($data["bwm"]){$DataSet->RemoveSerie("Serie1");}
	            if($data["bwe"]){$DataSet->RemoveSerie("Serie2");}
	            $DataSet->SetSerieName("Fluid Consumption","Serie5");
	            $DataSet->SetSerieName("Time Of Month","Serie6");
                                $DataSet->AddSerie("Serie3");
	            $DataSet->AddSerie("Serie4");
	            $DataSet->AddSerie("Serie5");
	            
                                $DataSet->SetYAxisName("Hydration");
	            $Test->setFixedScale(0,10,10);
                                $Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
                                //$Test->drawGrid(4,TRUE,230,230,230,10);
                                $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
                                $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
                                //$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3",-10);
	            //$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie4",10);
	            //$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie5",0);
	            $DataSet->RemoveSerie("Serie3");
	            $DataSet->RemoveSerie("Serie4");
	            $DataSet->RemoveSerie("Serie5");
	            $DataSet->AddSerie("Serie6");
	            $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
	            $DataSet->RemoveSerie("Serie6");
	            $DataSet->AddSerie("Serie8");
	            $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie8",0);
                                // Write the legend (box less)
                                $Test->setFontProperties("Fonts/tahoma.ttf",8);
                                $Test->drawLegend(620+$expand,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,150,150,150,FALSE);

                                // Write the title
                                $Test->setFontProperties("Fonts/MankSans.ttf",10);
                                $Test->drawTitle(0,0,"Body weight & hydration",150,150,150,660,30);

                                // Render the picture
                                 //$Cache->WriteToCache("Graph20",$DataSet->GetData(),$Test);
	          $Test->Stroke();
	           

	 }

               

}

?>
