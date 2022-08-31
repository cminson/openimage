<?php
include '../zcommon/common.inc';
$Title = $X_3DSHAPEANIMATIONS;
$LastOperation = $X_3DANIMATED;

$DEFAULT="HBOXBW.gif";
$EX_DIR = "$BASE_DIR/wimages/examples/3DAnim/";
$EX_PATH = "$BASE_PATH/wimages/examples/3DAnim/";

$MAX_ANIM = 6;
$current = $_POST['CURRENTFILE'];

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,5,80,100,FALSE,$DEFAULT);
DisplaySlowNote();
DisplayConvertButton();
DisplayFormEnd();
?>
