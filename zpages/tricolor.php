<?php
include '../zcommon/common.inc';

$Title = $X_BLACKLIGHT;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayColorPicker($X_PICKCOLOR,'C1','COLOR1','#ff0000');
DisplaySep1();
DisplayColorPicker($X_PICKCOLOR,'C2','COLOR2','#00ff00');
DisplaySep1();
DisplayColorPicker($X_PICKCOLOR,'C3','COLOR3','#0000ff');
DisplayConvertButton();
DisplayFormEnd();
?>
