<?php
include '../zcommon/common.inc';

$Title=$X_PSYCHEDELIC;

$DEFAULT="01.gif";
$EX_DIR = "$BASE_DIR/wimages/examples/lsd/";
$EX_PATH = "$BASE_PATH/wimages/examples/lsd/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
