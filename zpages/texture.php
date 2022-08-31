<?php
include '../zcommon/common.inc';

$Title = $X_TEXTURE;
$DEFAULT="wetclay.jpg";

$EX_DIR = "$BASE_DIR/wimages/examples/textures/";
$EX_PATH = "$BASE_PATH/wimages/examples/textures/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable("SETTING",$EX_DIR,$EX_PATH,6,80,80,TRUE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
