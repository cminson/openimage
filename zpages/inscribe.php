<?php
include '../zcommon/common.inc';

$X_INSCRIBE="Inscribe";
$X_INTENSITY="Edge Intensity";
$X_MIX="MIX";

$Title = $X_INSCRIBE;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$v = array(0,1,2,3,4,5,6,7,8,9,10);
DisplayGenNumPicker($X_INTENSITY,'INTENSITY',$v,3);
DisplaySep1();
DisplayPercentPicker($X_MIX,'MIX');

DisplaySep1();
$v= array('over','vivid_light','pin_light','linear_light','color_dodge','linear_dodge','color_burn','darken','soft_light','difference');
$s= array('over','vivid_light','pin_light','linear_light','color_dodge','linear_dodge','color_burn','darken','soft_light','difference');
DisplayGenStringPicker($X_SETTING,'SETTING',$v,$s,1);
DisplaySep1();

DisplayConvertButton();
DisplayFormEnd();
?>
