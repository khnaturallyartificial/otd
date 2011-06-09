<?php
function sd_square($x, $mean) { return pow($x - $mean,2); }
function sd($array) {
               return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
}
$userid = (int) $_GET["userid"];
$limit = (int) $_GET["limit"];
if(!$limit){$limit=10;}
$ini = parse_ini_file("../application/configs/application.ini");
mysql_connect($ini["resources.db.params.host"], $ini["resources.db.params.username"], $ini["resources.db.params.password"]);
mysql_select_db($ini["resources.db.params.dbname"]);
$tstamp = time();
$thisweek = (int) date("W",$tstamp);
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
               $result = mysql_query($query);
               $dailyload = array();
               while($row = mysql_fetch_array($result)){
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
               $sd = sd($dailyload);
               $mean =  $totalload / 7;
               $monotony = ($mean / $sd) == NULL ? 0 : ($mean / $sd);
               $strain = ($totalload * $monotony) == NULL ? 0 : ($totalload * $monotony);

               array_unshift($date_array, $fromdate);
               array_unshift($monotony_array, round($monotony,2));
               array_unshift($strain_array, round($strain));

}


/*
echo "<pre>";
var_dump($date_array);
var_dump($monotony_array);
var_dump($strain_array);
echo "</pre>";
exit;
 */

 // Standard inclusions
 include("pChart/pData.class");
 include("pChart/pChart.class");
  include("pChart/pCache.class");

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
  $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
  $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);
    $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");

  // Clear the scale
  $Test->clearScale();

  // Draw the 2nd graph
  $DataSet->RemoveSerie("Serie1");
  $DataSet->AddSerie("Serie2");
  $DataSet->SetYAxisName("STRAIN");
  $Test->drawRightScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_ADDALL,150,150,150,TRUE,90,2,true);
  //$Test->drawGrid(4,TRUE,230,230,230,10);
  $Test->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());
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


 ?>