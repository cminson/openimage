<?php
include '../zcommon/common.inc';

$Title = "$X_GLITTER";
$EX_DIR = "$BASE_DIR/wimages/glitters/";
$EX_PATH = "$BASE_PATH/wimages/glitters/";
$DEFAULT = "sil04.gif";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayNumPicker($X_WIDTH,'WIDTH',6,100,25);
DisplaySep1();
DisplayCheckBox($X_ANIMATE,"ANIMATE",FALSE);
DisplaySep1();
DisplaySelectionTable("SETTING",$EX_DIR,$EX_PATH,13,40,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
