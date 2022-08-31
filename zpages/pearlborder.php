<?php
include '../zcommon/common.inc';

RecordCommand("ENTER");
$Title = "$X_PEARL";

$DEFAULT="black-black.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/pearl/";
$EX_PATH = "$BASE_PATH/wimages/examples/pearl/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayNumPicker($X_WIDTH,'WIDTH',6,100,25);
DisplaySep1();
DisplayCheckBox($X_ANIMATE,"ANIMATE",FALSE);
DisplaySep1();
//DisplayCheckBox("3d","INSET",FALSE);
//DisplayLineSep0();
DisplaySelectionTable("SETTING",$EX_DIR,$EX_PATH,5,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
