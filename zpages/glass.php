<?php
include '../zcommon/common.inc';

$Title=$X_STAINEDGLASS;

$current = $_POST['CURRENTFILE'];

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
//DisplayColorPicker($X_COLOR,'COLOR','COLOR1','#000000');
//DisplaySep1();
$v= array('square','hexagon','random');
DisplayGenStringPicker('','KIND',$v,$v,2);
DisplaySep1();
$v= array(2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,29,20,21,22,23,24,25,26,27,28,29,30);
$v= array(2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,29,20);
DisplayGenNumPicker($X_SIZE,'SIZE',$v,6);
DisplaySep1();
//$v= array(0,1,2);
//DisplayGenNumPicker('Thickness','THICK',$v,1);

DisplayConvertButton();
DisplayFormEnd();
DisplaySlowNote();
?>
