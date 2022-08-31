<?php
include '../zcommon/common.inc';

$Title= $X_COOKIECUTTERIMAGE;

$EX_DIR = "$BASE_DIR/wimages/examples/cutters/";
$EX_PATH = "$BASE_PATH/wimages/examples/cutters/";

$DEFAULT="leaf-maple.gif";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,11,30,70,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
