<?php
include '../zcommon/common.inc';

$Title = $X_BLEND;


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayFilePicker($X_BLENDIMAGE,'BLEND');
DisplayLineSep0();
DisplayPercentPicker($X_BLENDLEVEL, 'DISSOLVE');
DisplaySep1();
$v=array("OVERLAY","TILED","BORDER");
$s=array('Automatic Full Area','Tiled','Bordered');
DisplayGenStringPicker($X_EFFECT,'EFFECT',$v,$s,0);
DisplayLineSep0();

$x1 = $y1 = $x2 = $y2 = 0;  //DEV CJM
DisplayHiddenText("X1","X1",4,$x1);
DisplaySep1();
DisplayHiddenText("Y1","Y1",4,$y1);
DisplaySep1();
DisplayHiddenText("X2","X2",4,$x2);
DisplaySep1();
DisplayHiddenText("Y2","Y2",4,$y2);
DisplaySep1();
DisplayHiddenText("W","w",4,$y2);
DisplaySep1();
DisplayHiddenText("H","h",4,$y2);

DisplayConvertButton();
DisplayFormEnd();
?>
