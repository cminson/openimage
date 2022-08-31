<?php
include '../zcommon/common.inc';

$Title = $X_STRETCHIMAGE;

$DEFAULT="01.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/stretch/";
$EX_PATH = "$BASE_PATH/wimages/examples/stretch/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayLineSep1();
DisplayConvertButton();
DisplayFormEnd();
?>
