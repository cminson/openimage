<?php
include '../zcommon/common.inc';


$Title = $LastOperation = $X_WORLDMIX;

$DEFAULT="MIXMAG1.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/mix/";
$EX_PATH = "$BASE_PATH/wimages/examples/mix/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,5,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
