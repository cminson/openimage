<?php
include '../zcommon/common.inc';

$Title = $X_DAVIDHILL;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$v = array(-10,-9,-8,-7,-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,6,7,8,9,10);
DisplayGenNumPicker($X_CONTRAST,'CONTRAST',$v,0);
DisplaySep1();
DisplayPercentPicker($X_ADJUST,'GAIN');
DisplayConvertButton();
DisplayFormEnd();
?>
