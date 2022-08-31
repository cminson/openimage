<?php
include '../zcommon/common.inc';

$X_SKEW="Skew";

$Title = $X_SKEW;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayDegrees($X_DEGREES,"DEGREES");
DisplaySep1();
$v= array('r','l');
$s= array('&rarr;','&larr;');
DisplayGenStringPicker('','DIRECTION',$v,$s,0);

DisplaySep1();


DisplayConvertButton();
DisplayFormEnd();
?>
