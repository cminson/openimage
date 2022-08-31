<?php
include '../zcommon/common.inc';

$Title = $X_BUTTON;

$EX_DIR = "$BASE_DIR/wimages/examples/buttons/";
$EX_PATH = "$BASE_PATH/wimages/examples/buttons/";
$TARPOST_DIR = "$BASE_DIR/wimages/buttons/";

$DEFAULT = 'square-A.png';



DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
