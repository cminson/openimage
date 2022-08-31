<?php
include '../zcommon/common.inc';

$Title=$X_BLACKANDWHITE;

$DEFAULT="50.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/blackwhite/";
$EX_PATH = "$BASE_PATH/wimages/examples/blackwhite/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
