<?php
include '../zcommon/common.inc';

$Title = $X_ARTISTICFRAME;
$LastOperation =$X_ARTISTICFRAME;

$DEFAULT="ART-7.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/frames/";
$EX_PATH = "$BASE_PATH/wimages/examples/frames/";

DisplayMainPageReturn($current);
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable("SETTING",$EX_DIR,$EX_PATH,5,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();

?>
