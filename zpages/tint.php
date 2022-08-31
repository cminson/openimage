<?php
include '../zcommon/common.inc';

$Title = $X_TINT;
$LastOperation = $X_TINTED;

$current = $_POST['CURRENTFILE'];

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayColorPicker($X_COLOR,'COLOR','COLOR1','#ff0000');
DisplaySep1();
DisplayPercentPicker($X_TINTLEVEL,'TINTLEVEL');
DisplayConvertButton();
DisplayFormEnd();
?>
