<?php   
 /*
     Example1 : A simple line chart
 */
$userid = (int) $_GET["userid"];
$ini = parse_ini_file("../application/configs/application.ini");
mysql_connect($ini["resources.db.params.host"], $ini["resources.db.params.username"], $ini["resources.db.params.password"]);
mysql_select_db($ini["resources.db.params.dbname"]);
if($_GET["sdate"] && $_GET["edate"]){
               $range = " and `date` between '".mysql_real_escape_string($_GET["sdate"])."' and '".  mysql_real_escape_string($_GET["edate"])."' ";
}
else{ $range = ""; }
$query = "select `date`, `session1_duration` , `session1_intensity` from `dailyreport` where `userid` = ".$userid."
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
$datear = array();
$dataar = array();

while($row = mysql_fetch_array($result)){
              array_unshift($dataar , $row["session1_duration"] * $row["session1_intensity"]);
              array_unshift($datear , date("d M Y", strtotime($row["date"])));
}


 // Standard inclusions      
 include("pChart/pData.class");   
 include("pChart/pChart.class");
  include("pChart/pCache.class");
  
 // Dataset definition    
 $DataSet = new pData;   
 $DataSet->AddPoint($dataar,"Serie1");
 $DataSet->AddPoint($datear, "Serie3");
 $DataSet->AddSerie("Serie1");
 $DataSet->SetAbsciseLabelSerie("Serie3");
 $DataSet->SetSerieName("Session 1","Serie1");
 $DataSet->SetYAxisName("AU");

// Cache definition
$Cache = new pCache();
$Cache->GetFromCache("Graph1",$DataSet->GetData());
  
 // Initialise the graph   
 $Test = new pChart(700,270);
 $Test->setFontProperties("Fonts/tahoma.ttf",8);   
 $Test->setGraphArea(70,30,680,200);   
 $Test->drawFilledRoundedRectangle(7,7,693,263,5,240,240,240);
 $Test->drawRoundedRectangle(5,5,695,265,5,230,230,230);
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
 // Finish the graph   
 //$Test->setFontProperties("Fonts/tahoma.ttf",8);
 //$Test->drawLegend(75,35,$DataSet->GetDataDescription(),255,255,255);
 $Test->setFontProperties("Fonts/tahoma.ttf",10);   
 $Test->drawTitle(60,22,"Training load",50,50,50,585);
 $Cache->WriteToCache("Graph1",$DataSet->GetData(),$Test);
 $Test->Stroke("example1.png");

?>