<?php
include '../zcommon/common.inc';
$Title=$X_OILPAINT;

$DEFAULT="02.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/paint/";
$EX_PATH = "$BASE_PATH/wimages/examples/paint/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$v = array(1,2,3,4,5,6);
DisplayGenNumPicker($X_LEVEL,'PAINT',$v,2);

DisplaySep1();
$v = array(10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200);
DisplayGenNumPicker($X_BRIGHTNESS,'BRIGHTNESS',$v,130);

DisplaySep1();
$v = array(10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200);
DisplayGenNumPicker($X_SATURATION,'SATURATION',$v,150);

DisplayConvertButton();
DisplayFormEnd();
?>
