<?php
include '../zcommon/common.inc';

$Title = $X_COLOREDBORDER;


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayColorPicker($X_COLOR,'COLOR','COLOR1','#ff0000');
DisplaySep1();
DisplayNumPicker($X_WIDTH,'WIDTH',1,21,3);
DisplaySep1();
DisplayCheckBox($X_3D,'3D',false);
DisplayConvertButton();
DisplayFormEnd();
?>
