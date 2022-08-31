<?php
include '../zcommon/common.inc';

$Title = $X_SPIN;
DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySpeedPicker($X_SPEED,'TIME');
DisplaySep1();
$v= array('Forward','Backward','Broken');
$s= array('&rarr;','&larr;','&rarr; &larr;');
DisplayGenStringPicker('','ORIENT',$v,$s,0);
DisplaySep1();
DisplayConvertButton();
DisplayFormEnd();
?>
