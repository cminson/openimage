<?php
include '../zcommon/common.inc';

$Title = $X_PUZZLE;
$LastOperation = $X_PUZZLE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$values = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
DisplayGenNumPicker($X_SETTING,'SETTING',$values,8);
DisplaySep1();
DisplayConvertButton();
DisplayFormEnd();


?>
