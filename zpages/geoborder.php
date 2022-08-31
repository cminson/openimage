<?php
include '../zcommon/common.inc';

$Title = $X_GEOMETRIC;

$values = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayGenNumPicker($X_SPREAD,'SPREAD',$values,5);
DisplaySep1();
DisplayGenNumPicker($X_DENSITY,'DENSITY',$values,5);
DisplaySep1();
DisplayGenNumPicker($X_CURVATURE,'CURVE',$values,5);
DisplaySep1();
DisplayColorPicker($X_COLOR,'NEWCOLOR','COLOR1','');

DisplayConvertButton();
DisplayFormEnd();

?>
