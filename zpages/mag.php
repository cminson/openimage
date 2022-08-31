<?php
include '../zcommon/common.inc';

$Title = $LastOperation = $X_MAGAZINEMIX;
$DEFAULT="ng.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/mags/";
$EX_PATH = "$BASE_PATH/wimages/examples/mags/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
