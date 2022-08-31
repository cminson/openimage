<?php
include '../zcommon/common.inc';

$Title = $X_MAGNIFY;


$DISPLAY="";
DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayLineSep0();

print "<a href=\"#home\" onclick=\"toggleImageAreaSelect(true)\">$X_BOUNDBOX</a>\n";
DisplayLineSep0();
DisplayLineSep1();
DisplayNumPicker($X_SETTING,'MAG',2,4,2);
/*
DisplaySep1();
DisplayNumPicker($X_WIDTH,'WIDTH',0,5,0);
DisplaySep1();
DisplayColorPicker($X_COLOR,'PICKCOLOR','COLOR1','');
DisplaySep1();
DisplayCheckBox($X_RECTANGULAR,"RECT",FALSE);
*/

DisplayLineSep0();
DisplayLineSep0();
$x1 = $y1 = $x2 = $y2 = 0;	//DEV CJM
DisplayReadOnlyText("X1","X1",4,$x1);
DisplaySep1();
DisplayReadOnlyText("Y1","Y1",4,$y1);
DisplaySep1();
DisplayReadOnlyText("X2","X2",4,$x2);
DisplaySep1();
DisplayReadOnlyText("Y2","Y2",4,$y2);
DisplaySep1();
DisplayReadOnlyText("W","w",4,$y2);
DisplaySep1();
DisplayReadOnlyText("H","h",4,$y2);

DisplayConvertButton();
DisplayFormEnd();
//DebugDisplay();
?>
