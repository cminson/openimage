<?php
include '../zcommon/common.inc';

$Title = $X_GRITTY;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();

DisplaySep1();
$v = array(-10,-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9,10);
DisplayGenNumPicker($X_CONTRAST,'CONTRAST',$v,5);

DisplaySep1();
$v = array(10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200);
DisplayGenNumPicker($X_SATURATION,'SATURATION',$v,120);

DisplayConvertButton();
DisplayFormEnd();
?>
