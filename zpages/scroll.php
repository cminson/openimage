<?php
include '../zcommon/common.inc';

$Title = $X_SCROLLIMAGE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySpeedPicker($X_SPEED,'TIME');
DisplaySep1();
$v= array('NONE','BEND','SWIRL','WAVE','SWIRLWAVE');
$s = array('None','Arc','Twist','Wave','Twist & Wave');
DisplayGenStringPicker($X_EFFECT,'EFFECT',$v,$s,0);
DisplaySep1();
$v= array('LHORIZONTAL','RHORIZONTAL','UVERTICAL', 'DVERTICAL','RDIAG','LDIAG');
$s = array('&rarr;','&larr;','&uarr;','&darr;','&rarr; &darr;','&larr; &darr;');
DisplayGenStringPicker('','ORIENT',$v,$s,0);
DisplaySep1();
DisplayConvertButton();
DisplayFormEnd();
?>
