<?php
include '../zcommon/common.inc';

$Title = $X_ROTATEIMAGE;
$LastOperation = $X_ROTATED;
RecordCommand("ENTER");

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();

$v= array('CLOCKWISE','COUNTERCLOCKWISE');
$s= array('&rarr;','&larr;');
DisplayGenStringPicker('','DIRECTION',$v,$s,1);
DisplaySep1();
$v = array();
$s = array();
for ($i=1; $i < 360; $i += 1)
{
	$v[] = $i;
	$s[] = "$i%";

}
DisplayGenStringPicker('','DEGREES',$v,$s,15);
DisplaySep1();
DisplayCheckBox($X_ADJUST,'ADJUST',false);
DisplayConvertButton();
DisplayFormEnd();
?>
