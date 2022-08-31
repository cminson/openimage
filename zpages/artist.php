<?php
include '../zcommon/common.inc';

$Title=$X_ARTGALLERY;
$DEFAULT="IMPRESSIONIST.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/art/";
$EX_PATH = "$BASE_PATH/wimages/examples/art/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,6,100,100,TRUE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();

?>
