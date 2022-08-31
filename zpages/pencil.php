<?php
include '../zcommon/common.inc';
$Title=$X_PENCIL;

$DEFAULT="03.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/pencil/";
$EX_PATH = "$BASE_PATH/wimages/examples/pencil/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$v = array(1,2,3,4,5,6);
DisplayGenNumPicker($X_LEVEL,'LEVEL',$v,2);

DisplaySep1();
$v = array(80,100,120,140,160,180);
$s = array('1','2','3','4','5','6');
DisplayGenStringPicker($X_BRIGHTNESS,'BRIGHTNESS',$v,$s,2);

DisplaySep1();
$v = array(90,110,130,150,170,190);
$s = array('1','2','3','4','5','6');
DisplayGenStringPicker($X_SATURATION,'SATURATION',$v,$s,2);
//DisplayImageSelectionTable('SETTING',$EX_DIR,$EX_PATH,6,120,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
