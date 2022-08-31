<?php
include '../zcommon/common.inc';

$Title=$X_HEATMAP;

$DEFAULT="50.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/heatmap/";
$EX_PATH = "$BASE_PATH/wimages/examples/heatmap/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
