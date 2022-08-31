<?php
include '../zcommon/common.inc';

$Title = $X_VIBRATE;
$LastOperation = $X_VIBRATED;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySpeedPicker($X_SPEED,'TIME');
DisplaySep1();
$v= array('L','M','H');
$s = array('1','2','3');
DisplayGenStringPicker($X_JUMPINESS,'AMPLITUDE',$v,$s,0);
DisplaySep1();
$v = array('X','Y');
$s= array('&larr; &rarr;','&uarr; &darr;');
DisplayGenStringPicker('','ORIENT',$v,$s,0);

DisplayConvertButton();
DisplayFormEnd();
?>
