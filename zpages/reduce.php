<?php
include '../zcommon/common.inc';

$Title=$X_REDUCE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayPercentPicker($X_REDUCEBYAPPROXIMATE,'SETTING');
DisplayConvertButton();
DisplayFormEnd();

?>
