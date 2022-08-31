<?php
include '../zcommon/common.inc';

$Title = $X_BEND;

$DEFAULT="01.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/bend/";
$EX_PATH = "$BASE_PATH/wimages/examples/bend/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$values = array(10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200,210,220,230,240,250,260,270,280,290,300,310,320,330,340,350,360);
DisplayGenNumPicker($X_SETTING,'SETTING',$values,60);
DisplaySep1();
$v= array('UP','DOWN');
$s = array('&uarr;','&darr;');
DisplayGenStringPicker('','ORIENT',$v,$s,1);
DisplayLineSep1();
DisplayConvertButton();
DisplayFormEnd();

?>
