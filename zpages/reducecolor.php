<?php
include '../zcommon/common.inc';

$Title=$X_REDUCECOLOR;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$values = array(64,32,16,8);
DisplayGenNumPicker($X_SETTING,'SETTING',$values,32);

DisplayConvertButton();
DisplayFormEnd();

?>
