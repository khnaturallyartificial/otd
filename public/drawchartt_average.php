<?php
 /*
     Example1 : A simple line chart
 */
$userid = (int) $_GET["userid"];
$limit = (int) $_GET["limit"];
if(!$limit){$limit=10;}
$ini = parse_ini_file("../application/configs/application.ini");
mysql_connect($ini["resources.db.params.host"], $ini["resources.db.params.username"], $ini["resources.db.params.password"]);
mysql_select_db($ini["resources.db.params.dbname"]);
$tstamp = time();
$thisweek = (int) date("W",$tstamp);
$thisyear = (int) date("Y", $tstamp);
$stime = strtotime($thisyear."W".$thisweek); //this is the time

$datearray = array();
$totalliftarray = array();
$averageloadarray = array();

$result = mysql_query("select SUM(`actual_total_lifted`) as tl, SUM(`actual_total_reps`) as tr from `markedexerciseplanview` where
               `userid` = '".$userid."' and
               `date` between
               '".date("Y-m-d",$stime)."' and '".date("Y-m-d",$stime + 604800 - 1)."'");
while($row = mysql_fetch_array($result)){
               $datearray[] = date("d M Y",$stime);
               $totalliftarray[] = (int) $row["tl"];
               $averageloadarray[] =(int) ((int) $row["tl"] / (int) $row["tr"]);
}

for($i = 0; $i<=($limit-2); $i++){

               $result = mysql_query("select SUM(`actual_total_lifted`) as tl, SUM(`actual_total_reps`) as tr from `markedexerciseplanview` 
                              where `userid` = '".$userid."' and
                              `date` between
               '".date("Y-m-d",$stime - (604800 * ($i + 1)) )."' and '".date("Y-m-d",$stime - 1 - (604800 * $i))."'");
               while($row = mysql_fetch_array($result)){
                              array_unshift($datearray,date("d M Y",$stime - (604800 * ($i + 1)) ) );
                              array_unshift($totalliftarray, (int) $row["tl"] / 1000);
                              array_unshift($averageloadarray, (int) ((int) $row["tl"] / (int) $row["tr"]));
               }

}

/*
echo '<pre>';
var_dump($datearray);
var_dump($totalliftarray);
var_dump($averageloadarray);

echo '</pre>';
*/


 // Standard inclusions
 include("pChart/pData.class");
 include("pChart/pChart.class");
  include("pChart/pCache.class");

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
  $Test->clearScale();

  // Draw the 2nd graph
  $DataSet->RemoveSerie("Serie1");
  $DataSet->AddSerie("Serie2");
  $DataSet->SetYAxisName("Average Load (kg)");
  $Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,true);
  //$Test->drawGrid(4,TRUE,230,230,230,10);
  $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
  $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
  $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");

  // Write the legend (box less)
  $Test->setFontProperties("Fonts/tahoma.ttf",8);
  $Test->drawLegend(530,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,150,150,150,FALSE);

  // Write the title
  $Test->setFontProperties("Fonts/MankSans.ttf",10);
  $Test->drawTitle(0,0,"Total Lifted & Average Load (Weekly)",150,150,150,660,30);

  // Render the picture
   $Cache->WriteToCache("Graph5",$DataSet->GetData(),$Test);
  $Test->Stroke();

  /*

// Initialise the graph
  $Test = new pChart(700,350);
  $Test->setFontProperties("Fonts/tahoma.ttf",8);
  $Test->setGraphArea(50,30,680,270);
  $Test->drawFilledRoundedRectangle(7,7,693,346,5,240,240,240);
  $Test->drawRoundedRectangle(5,5,695,348,5,230,230,230);
  $Test->drawGraphArea(255,255,255,TRUE);
  $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90,2,TRUE);
  $Test->drawGrid(4,TRUE,230,230,230,50);

  // Draw the 0 line
  $Test->setFontProperties("Fonts/tahoma.ttf",7);
  $Test->drawTreshold(0,143,55,72,TRUE,TRUE);

  // Draw the bar graph
  $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),TRUE);
  $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");

$Test->clearScale(); 
  // Draw the 2nd graph
  $DataSet->RemoveSerie("Serie1");
  $DataSet->AddSerie("Serie2");
  $DataSet->SetYAxisName("Average Load");
  $Test->setFontProperties("Fonts/tahoma.ttf",8);
  $Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,90);

  $Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),true);




  // Finish the graph
  $Test->setFontProperties("Fonts/tahoma.ttf",10);
  $Test->drawTitle(50,22,"Example 12",50,50,50,585);
  $Test->Stroke("example12.png");
   * */

 ?>