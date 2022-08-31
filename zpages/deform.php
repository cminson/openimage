<?php
include '../zcommon/common.inc';

$Title = $X_DEFORM;
$DEFAULT="FLAG1.gif";
$EX_DIR = "$BASE_DIR/wimages/examples/anfun/";
$EX_PATH = "$BASE_PATH/wimages/examples/anfun/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,5,60,100,TRUE,$DEFAULT);
DisplayConvertButton();
DisplaySlowNote();
DisplayFormEnd();
?>
