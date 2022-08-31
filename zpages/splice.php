<?php
include '../zcommon/common.inc';

$Title = $X_SPLICE;
$current = $_POST['CURRENTFILE'];

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();

$values = array(1,2,3,4,5,6,7);
DisplayGenNumPicker($X_SETTING,'SETTING',$values,2);
DisplaySep0();
$v= array('x','y');
$s = array('<','^');
DisplayGenStringPicker('','DIRECTION',$v,$s,0);

DisplayConvertButton();
DisplayFormEnd();

?>
