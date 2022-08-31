<?php
include '../zcommon/common.inc';

$Title= $X_EXPLODEIMAGE;

$DEFAULT="01.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/implode/";
$EX_PATH = "$BASE_PATH/wimages/examples/implode/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$values = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,25,30,35,40,45,50);
DisplayGenNumPicker($X_SETTING,'SETTING',$values,5);
DisplayLineSep1();
DisplayCheckBox($X_REVERSE,"REVERSE",FALSE);
DisplayConvertButton();
DisplayFormEnd();
?>
