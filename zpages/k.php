<?php
include '../zcommon/common.inc';

$Title = $X_KALEIDOSCOPE;
$DEFAULT="01.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/kal/";
$EX_PATH = "$BASE_PATH/wimages/examples/kal/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,4,100,100,FALSE,$DEFAULT);
DisplayLineSep0();
DisplayCheckBox($X_ANIMATE,"ANIMATE",FALSE);
DisplaySlowNote();
DisplayConvertButton();
DisplayFormEnd();
?>
