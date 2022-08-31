<?php
include '../zcommon/common.inc';

$Title = $X_PHOTOTOCARTOON;
$X_EDGE="Edge";
$DEFAULT="01.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/cartoon/";
$EX_PATH = "$BASE_PATH/wimages/examples/cartoon/";
$LastOperation = $X_PHOTOTOCARTOON;


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();

$v = array(1,2,3,4,5,6,7,8,9,10);
DisplayGenNumPicker($X_LEVEL,'LEVEL',$v,6);

DisplaySep1();
$v = array(1,2,3,4,5,6,7,8,9,10);
DisplayGenNumPicker($X_EDGE,'EDGE',$v,4);


DisplaySep1();
$v = array(10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200);
DisplayGenNumPicker($X_BRIGHTNESS,'BRIGHTNESS',$v,130);

DisplaySep1();
$v = array(10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200);
DisplayGenNumPicker($X_SATURATION,'SATURATION',$v,150);

//DisplayImageSelectionTable('SETTING',$EX_DIR,$EX_PATH,6,120,$DEFAULT);

DisplayConvertButton();
DisplayFormEnd();
?>
