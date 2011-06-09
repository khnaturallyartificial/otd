<?php
 /*
     Example1 : A simple line chart
 */
error_reporting(E_ALL);
$userid = (int) $_GET["userid"];
$ini = parse_ini_file("../application/configs/application.ini");
mysql_connect($ini["resources.db.params.host"], $ini["resources.db.params.username"], $ini["resources.db.params.password"]);
mysql_select_db($ini["resources.db.params.dbname"]);
if($_GET["sdate"] && $_GET["edate"]){
               $range = " and `date` between '".mysql_real_escape_string($_GET["sdate"])."' and '".  mysql_real_escape_string($_GET["edate"])."' ";
}
else{ $range = ""; }
$query = "select `date`,`sleep_quality`,`mental_recovery`,`physical_recovery`,`pre_training_energy`,`muscle_soreness`,`general_fatigue`
               from `dailyreport` where `userid` = ".$userid."
              ".$range."
order by `date` DESC
               ";
if($_GET["limit"]){
               $query .= " limit ".$_GET["limit"];
}
else{
               $query .= " limit 10";
}
$result = mysql_query($query);

$date_a = array();
$sleep_quality_a = array();
$mental_recovery_a = array();
$physical_recovery_a = array();
$pre_training_energy_a = array();
$muscle_soreness_a = array();
$general_fatigue_a = array();

while($row = mysql_fetch_array($result)){

              array_unshift($date_a , date("d M Y", strtotime($row["date"])));
              if($_GET["sq"]){array_unshift($sleep_quality_a, (float) $row["sleep_quality"]);}
              if($_GET["mr"]){array_unshift($mental_recovery_a, (float)$row["mental_recovery"]);}
              if($_GET["pr"]){array_unshift($physical_recovery_a, (float)$row["physical_recovery"]);}
              if($_GET["pte"]){array_unshift($pre_training_energy_a, (float)$row["pre_training_energy"]);}
              if($_GET["ms"]){array_unshift($muscle_soreness_a, (float)$row["muscle_soreness"]);}
              if($_GET["gf"]){array_unshift($general_fatigue_a, (float)$row["general_fatigue"]);}
}



 // Standard inclusions
 include("pChart/pData.class");
 include("pChart/pChart.class");
  include("pChart/pCache.class");

 // Dataset definition
 $DataSet = new pData;
if($_GET["sq"]){$DataSet->AddPoint($sleep_quality_a,"Serie1");}
if($_GET["mr"]){$DataSet->AddPoint($mental_recovery_a,"Serie2");}
if($_GET["pr"]){$DataSet->AddPoint($physical_recovery_a,"Serie3");}
if($_GET["pte"]){$DataSet->AddPoint($pre_training_energy_a,"Serie4");}
if($_GET["ms"]){$DataSet->AddPoint($muscle_soreness_a,"Serie5");}
if($_GET["gf"]){$DataSet->AddPoint($general_fatigue_a,"Serie6");}
 $DataSet->AddPoint($date_a, "Serie7");
 if($_GET["sq"]){$DataSet->AddSerie("Serie1");}
 if($_GET["mr"]){$DataSet->AddSerie("Serie2");}
 if($_GET["pr"]){$DataSet->AddSerie("Serie3");}
 if($_GET["pte"]){$DataSet->AddSerie("Serie4");}
 if($_GET["ms"]){$DataSet->AddSerie("Serie5");}
 if($_GET["gf"]){$DataSet->AddSerie("Serie6");}
 $DataSet->SetAbsciseLabelSerie("Serie7");
if($_GET["sq"]){ $DataSet->SetSerieName("Sleep Quality","Serie1");}
 if($_GET["mr"]){$DataSet->SetSerieName("Mental Recovery","Serie2");}
 if($_GET["pr"]){$DataSet->SetSerieName("Physical Recovery","Serie3");}
 if($_GET["pte"]){$DataSet->SetSerieName("Pre Training Energy","Serie4");}
 if($_GET["ms"]){$DataSet->SetSerieName("Muscle Soreness","Serie5");}
 if($_GET["gf"]){$DataSet->SetSerieName("General Fatigue","Serie6");}
 $DataSet->SetYAxisName("Scale");
 $DataSet->SetYAxisUnit("");

// Cache definition
$Cache = new pCache();
$Cache->GetFromCache("Graph2",$DataSet->GetData());

 // Initialise the graph
 $Test = new pChart(700,310);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);
 $Test->setGraphArea(70,30,580,240);
 $Test->drawFilledRoundedRectangle(7,7,593,303,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,595,305,5,230,230,230);
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
  if($_GET["sq"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");}
  if($_GET["mr"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie2");}
  if($_GET["pr"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");}
  if($_GET["pte"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie4");}
  if($_GET["ms"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie5");}
  if($_GET["gf"]){$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie6");}
 // Finish the graph
 $Test->setFontProperties("Fonts/tahoma.ttf",7);
 $Test->drawLegend(600,35,$DataSet->GetDataDescription(),255,255,255,true);
 $Test->setFontProperties("Fonts/tahoma.ttf",10);
 $Test->drawTitle(60,22,"Fatigue and Recovery",50,50,50,585);
 $Cache->WriteToCache("Graph2",$DataSet->GetData(),$Test);
 $Test->Stroke("example1.png");

?>