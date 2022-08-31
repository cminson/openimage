<?php
include '../zcommon/common.inc';

$X_GROOVYBORDERIMAGE = "Groovy";
$Title = $X_GROOVYBORDERIMAGE;

$EX_DIR = "$BASE_DIR/wimages/examples/groovyborders/";
$EX_PATH = "$BASE_PATH/wimages/examples/groovyborders/";
$DEFAULT="x11-heart.jpg";



DisplayMainPageReturn();
DisplayTitle($Title);
DisplaySlowNote();
DisplayFormStart();
DisplayCheckBox("Tile","TILE",TRUE);
DisplaySep1();
DisplayCheckBox($X_ANIMATE,"ANIMATE",FALSE);
DisplayLineSep0();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,70,70,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
