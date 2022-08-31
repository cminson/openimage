<?php
include '../zcommon/common.inc';

$Title=$X_BLEACH;
$LastOperation=$X_BLEACHED;

$DEFAULT="03.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/bleached/";
$EX_PATH = "$BASE_PATH/wimages/examples/bleached/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
