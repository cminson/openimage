<?php
include '../zcommon/common.inc';
/*
print "<script type=\"text/javascript\">\n";
print "var RequiresCrop = TRUE;";
print "</script>\n";
*/

$Title = $X_CROP;


$DISPLAY="";
DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayLineSep0();

//print "<a href=\"#home\" onclick=\"toggleImageAreaSelect(false)\">$X_BOUNDBOX</a>\n";
DisplayCheckBox($X_ELLIPSE,'ELLIPSE',false);
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
