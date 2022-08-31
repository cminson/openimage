<?php
include '../zcommon/common.inc';
$Title = $X_EMBOSS;

$DEFAULT="16.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/emboss/";
$EX_PATH = "$BASE_PATH/wimages/examples/emboss/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
