<?php
include '../zcommon/common.inc';
$Title = $X_3DSHAPE;

$EX_DIR = "$BASE_DIR/wimages/examples/3D/";
$EX_PATH = "$BASE_PATH/wimages/examples/3D/";
$DEFAULT="FLATBOX.gif";
$DEFAULT="CYLV.gif";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplaySlowNote();
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
