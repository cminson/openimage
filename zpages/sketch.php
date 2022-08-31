<?php
include '../zcommon/common.inc';

$X_GRAYSCALE="Grayscale";

$Title = $X_SKETCH;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();

DisplaySep1();
$v = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
DisplayGenNumPicker($X_LEVEL,'EDGE',$v,10);

DisplaySep1();
$v = array(10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200);
DisplayGenNumPicker($X_CONTRAST,'CONTRAST',$v,100);

DisplaySep1();
$v = array(10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200);
DisplayGenNumPicker($X_SATURATION,'SATURATION',$v,100);

DisplaySep1();
DisplayCheckBox($X_GRAYSCALE,'GRAYSCALE',false);

DisplayConvertButton();
DisplayFormEnd();
?>
