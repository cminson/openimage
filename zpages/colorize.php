<?php
include '../zcommon/common.inc';

$Title=$X_COLORIZE;
$DEFAULT="230.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/colorize/";
$EX_PATH = "$BASE_PATH/wimages/examples/colorize/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
