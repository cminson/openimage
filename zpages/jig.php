<?php
include '../zcommon/common.inc';

$Title = $X_JIGSAW;

$EX_DIR = "$BASE_DIR/wimages/examples/jigsaws/";
$EX_PATH = "$BASE_PATH/wimages/examples/jigsaws/";
$DEFAULT="jigsaw1-A.png";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,4,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
