<?php
include '../zcommon/common.inc';

$X_RADIATION = "Radiation Level";
$Title = $X_MUTATE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$values = array(1,2,3,4,5,6,7,8);
DisplayGenNumPicker($X_RADIATION,'SETTING',$values,1);
DisplayLineSep1();
DisplayConvertButton();
DisplayFormEnd();

?>
