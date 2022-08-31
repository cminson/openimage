<?php

include '../zcommon/common.inc';

$Title=$X_ACCENT;

$current = $_POST['CURRENTFILE'];

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayCoreColorPicker($X_COLOR,'COLOR');
DisplayFuzzPicker($X_FUZZ,'FUZZ',40);
DisplayConvertButton();
DisplayFormEnd();

?>
