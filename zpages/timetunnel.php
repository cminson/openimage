<?php
include '../zcommon/common.inc';
$Title=$X_TIMETUNNEL;

$DEFAULT="01.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/timetunnel/";
$EX_PATH = "$BASE_PATH/wimages/examples/timetunnel/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,4,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
