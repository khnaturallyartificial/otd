<?php
 /*
     Example1 : A simple line chart
 */
error_reporting(E_ALL);
$userid = (int) $_GET["userid"];
$ini = parse_ini_file("../application/configs/application.ini");
mysql_connect($ini["resources.db.params.host"], $ini["resources.db.params.username"], $ini["resources.db.params.password"]);
mysql_select_db($ini["resources.db.params.dbname"]);
/*$query = "select `date`, `session1_duration` , `session1_intensity` from `dailyreport` where `userid` = ".$userid." order by `date` DESC";
if($_GET["limit"]){
               $query .= " limit ".$_GET["limit"];
}*/
if($_GET["sdate"] && $_GET["edate"]){
               $range = "`date` between '".mysql_real_escape_string($_GET["sdate"])."' and '".  mysql_real_escape_string($_GET["edate"])."' and";
}
else{ $range = ""; }

$query = "select `date` , SUM(`pres_reps`) as `stl`, SUM(`actual_total_reps`) as `satl`
               from `markedexerciseplanview`
               where ".$range."

                             `userid` = ".$userid."
               group by `date`
               order by `date` DESC
               "; //removed `done` = 1

if($_GET["limit"]){
               $query .= " limit ".$_GET["limit"];
}
else{
               $query .= " limit 10";
}
$result = mysql_query($query) or die(mysql_error());

$datear = array();
$dataar = array();
$act_dataar = array();

while($row = mysql_fetch_array($result)){
              array_unshift($dataar , $row["stl"]);
              array_unshift($act_dataar,$row["satl"]);
              array_unshift($datear , date("d M Y", strtotime($row["date"])));
}


 // Standard inclusions
 include("pChart/pData.class");
 include("pChart/pChart.class");
   include("pChart/pCache.class");

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
$Cache->GetFromCache("Graph4",$DataSet->GetData());

 // Initialise the graph
 $Test = new pChart(730,370);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->setGraphArea(70,30,620,300);
 $Test->drawFilledRoundedRectangle(7,7,693,363,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,365,5,230,230,230);
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
 $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),0.1);
 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
  $Test->setFontProperties("Fonts/tahoma.ttf",7);
  $Test->setColorPalette(0,112,55,46);
  $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie1", -10);
  $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie2",10);

  //$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(), "Serie2");
 // Finish the graph
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->drawLegend(630,35,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("Fonts/tahoma.ttf",10);
 $Test->drawTitle(60,22,"Prescribed vs Actual total reps done",50,50,50,585);

  //$Cache->WriteToCache("Graph4",$DataSet->GetData(),$Test);
 $Test->Stroke("example3.png");

?>