<?php
include '../zcommon/common.inc';
$Title = $X_FRACTAL;
DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayLMHExtPicker($X_SPREAD,'SPREAD');
DisplaySep1();
DisplayLMHExtPicker($X_DENSITY,'DENSITY');
DisplaySep1();
DisplayLMHExtPicker($X_CURVATURE,'CURVE');
DisplayConvertButton();
DisplayFormEnd();

?>
