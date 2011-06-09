<?php

error_reporting(E_ALL);
$userid = (int) $_GET["userid"];
$limit = $_GET["limit"] ? (int) $_GET["limit"] : 10;
$ini = parse_ini_file("../application/configs/application.ini");
mysql_connect($ini["resources.db.params.host"], $ini["resources.db.params.username"], $ini["resources.db.params.password"]);
mysql_select_db($ini["resources.db.params.dbname"]);

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
                              $result = mysql_query($sql);
                              $ech = array();
                              while($row = mysql_fetch_array($result)){
                                             $holder[(int) $row["exerciseid"]] = $row["maw"] ;
                              }
                              array_unshift($snatch,$holder[1] ? $holder[1] : 0);
                              array_unshift($powersnatch, $holder[11] ? $holder[11] : 0);
                              array_unshift($cj, $holder[3] ? $holder[3] : 0);
                              array_unshift($pc, $holder[31] ? $holder[31] : 0);
                              array_unshift($squat, $holder[54] ? $holder[54] : 0);
                              array_unshift($pjfr, $holder[39] ? $holder[39] : 0);
                              array_unshift($date, date("Y-m-d",$endingstamp));



                              $holder = array();
                              $beginningstamp -= 604800;
                              $endingstamp -= 604800;
}


 // Standard inclusions
 include("pChart/pData.class");
 include("pChart/pChart.class");
  include("pChart/pCache.class");

  $DataSet = new pData;

  if($_GET["snatch"]){$DataSet->AddPoint($snatch, "Serie1");}
  if($_GET["psnatch"]){$DataSet->AddPoint($powersnatch, "Serie2");}
  if($_GET["cj"]){$DataSet->AddPoint($cj, "Serie3");}
  if($_GET["pc"]){$DataSet->AddPoint($pc, "Serie4");}
  if($_GET["squat"]){$DataSet->AddPoint($squat, "Serie5");}
 if($_GET["pjfr"]){$DataSet->AddPoint($pjfr, "Serie6");}
 $DataSet->AddPoint($date,"Serie7");

   if($_GET["snatch"]){$DataSet->AddSerie("Serie1");}
  if($_GET["psnatch"]){$DataSet->AddSerie("Serie2");}
  if($_GET["cj"]){$DataSet->AddSerie("Serie3");}
  if($_GET["pc"]){$DataSet->AddSerie("Serie4");}
  if($_GET["squat"]){$DataSet->AddSerie("Serie5");}
 if($_GET["pjfr"]){$DataSet->AddSerie("Serie6");}
 $DataSet->SetAbsciseLabelSerie("Serie7");

    if($_GET["snatch"]){$DataSet->SetSerieName("Snatch","Serie1");}
  if($_GET["psnatch"]){$DataSet->SetSerieName("Power Snatch","Serie2");}
  if($_GET["cj"]){$DataSet->SetSerieName("Clean & Jerk","Serie3");}
  if($_GET["pc"]){$DataSet->SetSerieName("Power Clean","Serie4");}
  if($_GET["squat"]){$DataSet->SetSerieName("Squat","Serie5");}
 if($_GET["pjfr"]){$DataSet->SetSerieName("Power Jerk (From racks)", "Serie6");}
 $DataSet->SetYAxisName("Weight (kg.)");
 $DataSet->SetYAxisUnit("");


// Cache definition
$Cache = new pCache();
$Cache->GetFromCache("Graph9",$DataSet->GetData());

 // Initialise the graph
 $Test = new pChart(700,310);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->setGraphArea(70,30,580,240);
 $Test->drawFilledRoundedRectangle(7,7,593,303,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,595,305,5,230,230,230);
 $Test->drawGraphArea(237,237,237,true);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,150,150,150,true,45,1);
 $Test->drawGrid(4,true,228,228,228);


 // Draw the 0 line
 //$Test->setFontProperties("Fonts/tahoma.ttf",6);
 //$Test->drawTreshold(3,162,162,162,true,false,3);

 // Draw the line graph
 //$Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
 $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
  $Test->setFontProperties("Fonts/tahoma.ttf",7);
  $Test->setColorPalette(0,112,55,46);
  if($_GET["snatch"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");}
  if($_GET["psnatch"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");}
  if($_GET["cj"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");}
  if($_GET["pc"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie4");}
  if($_GET["squat"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie5");}
  if($_GET["pjfr"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie6");}
 // Finish the graph
 $Test->setFontProperties("Fonts/tahoma.ttf",7);
 $Test->drawLegend(600,35,$DataSet->GetDataDescription(),255,255,255,true);
 $Test->setFontProperties("Fonts/tahoma.ttf",10);
 $Test->drawTitle(60,22,"Current max per exercise",50,50,50,585);
 $Cache->WriteToCache("Graph9",$DataSet->GetData(),$Test);
 $Test->Stroke("example9.png");


?>
