<?php
error_reporting(9);
include("pChart/pData.class");
include("pChart/pChart.class");

//data set
$dataset = new pData();
$dataset->AddPoint(array(30,15,20,2,36,38,35,34,34,31,20,21,35,36,33,31,35));
$dataset->AddSerie();
$dataset->SetAbsciseLabelSerie();
$dataset->SetSerieName("Random dots", "Dots 1");

$dataset->SetYAxisName("AU");
$dataset->SetYAxisUnit("au");

//Initialise graph
$chart = new pChart(700,230);
$chart->setFontProperties("Fonts/tahoma.ttf", 10);
$chart->setGraphArea(70,30,680,200);
$chart->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);
$chart->drawRoundedRectangle(5,5,695,225,5,230,230,230);
$chart->drawGraphArea(255,255,255,true);
$chart->drawScale($dataset->GetData(),$dataset->GetDataDescription(),SCALE_NORMAL,150,150,150,true,0,2);
$chart->drawGrid(4,true,230,230,230,50);

// Draw the 0 line
 $chart->setFontProperties("Fonts/tahoma.ttf",6);
 $chart->drawTreshold(0,143,55,72,TRUE,TRUE);

// draw line
$chart->drawLineGraph($dataset->GetData(),$dataset->GetDataDescription());
$chart->drawPlotGraph($dataset->GetData(),$dataset->GetDataDescription(),4,2,183,147,104);

//finish it

$chart->setFontProperties("Fonts/tahoma.ttf", 8);
$chart->drawLegend(45,35,$dataset->GetDataDescription(),255,255,255);
$chart->setFontProperties("Fonts/tahoma.ttf", 10);
$chart->drawTitle(60,22,"Test Graph",50,50,50,585);
$chart->Stroke("Graph.png");
echo "asdf";
?>
