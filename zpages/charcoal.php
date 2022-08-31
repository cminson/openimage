<?php
include '../zcommon/common.inc';
$Title=$X_CHARCOAL;

$DEFAULT="2.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/charcoal/";
$EX_PATH = "$BASE_PATH/wimages/examples/charcoal/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
