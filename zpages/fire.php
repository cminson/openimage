<?php
include '../zcommon/common.inc';

$Title = $X_FIREANDRAIN;
$DEFAULT="fire.gif";
$EX_PATH = "$BASE_PATH/wimages/examples/firerain/";
$EX_DIR = "$BASE_DIR/wimages/examples/firerain/";
$FIRERAIN_DIR = "$BASE_DIR/wimages/firerain/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,TRUE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
