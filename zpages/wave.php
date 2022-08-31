<?php
include '../zcommon/common.inc';

$Title = $X_WAVE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$values = array(0,1,2,3,4,5,10,15,20,25,30);
DisplayGenNumPicker($X_HEIGHT,'HEIGHT',$values,5);
DisplaySep1();
$values = array(10,20,30,40,50,60,70,80,90,100,125,150,175,200);
DisplayGenNumPicker($X_LENGTH,'LENGTH',$values,50);
DisplayConvertButton();
DisplayFormEnd();
?>
