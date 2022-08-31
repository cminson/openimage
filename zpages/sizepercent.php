<?php
include '../zcommon/common.inc';

$Title = $X_RESIZEPERCENT;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplaySizePicker($X_RESIZELEVEL,'SETTING');
DisplayLineSep0();
DisplayConvertButton();
DisplayFormEnd();
?>
