<?php
include '../zcommon/common.inc';

$Title=$X_SEPIA;
$LastOperation=$X_SEPIA;


$DEFAULT="80.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/sepia/";
$EX_PATH = "$BASE_PATH/wimages/examples/sepia/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
