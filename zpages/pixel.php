<?php
include '../zcommon/common.inc';

$Title = $X_PIXELLATE;

$DEFAULT="01.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/pixel/";
$EX_PATH = "$BASE_PATH/wimages/examples/pixel/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,4,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();

?>
