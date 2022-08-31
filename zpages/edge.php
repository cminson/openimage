<?php
include '../zcommon/common.inc';

$Title = $X_EDGE;
$LastOperation = $X_EDGE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$values = array(3,4,5,6,7,8,9,10,15,20,25,30,40,50);
DisplayGenNumPicker($X_SETTING,'SETTING',$values,5);
DisplaySep1();
DisplayCheckBox($X_REVERSE,'REVERSE',false);
DisplayConvertButton();
DisplayFormEnd();


?>
