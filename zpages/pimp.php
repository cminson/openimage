<?php
include '../zcommon/common.inc';

$Title = $X_PIMPIMAGE;

$EX_DIR = "$BASE_DIR/wimages/glitters/";
$EX_PATH = "$BASE_PATH/wimages/glitters/";
$DEFAULT = "sil04.gif";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,13,40,100,FALSE,$DEFAULT);
DisplayLineSep0();
DisplayPercentPicker('','DISSOLVE');
DisplayConvertButton();
DisplayFormEnd();
?>
