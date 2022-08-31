<?php
include '../zcommon/common.inc';

$Title = $X_MELTIMAGE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$v= array('15','10','6','4','2');
$s = array('1','2','3','4','5');
DisplayGenStringPicker($X_SETTING,'SETTING',$v,$s,0);
DisplayConvertButton();
DisplayFormEnd();

?>
