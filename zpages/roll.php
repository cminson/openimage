<?php
include '../zcommon/common.inc';

$Title = $X_ROLLIMAGE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();

$v= array('XLEFT','XRIGHT','YUP','YDOWN');
$s = array('X &larr;','X &rarr;','Y &uarr;', 'Y &darr;');
DisplayGenStringPicker('','DIRECTION',$v,$s,0);
DisplaySep1();
DisplayPercentPicker2('','AMOUNT',10);
DisplaySep1();
DisplayCheckBox('Wrap Image','WRAP',true);
DisplayConvertButton();
DisplayFormEnd();
?>
