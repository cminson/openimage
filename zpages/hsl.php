<?php
include '../zcommon/common.inc';

$Title = "HSL";
$LastOperation = "HSL";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$v = array();
$s = array();
for ($i=0; $i <= 360; $i += 10)
{
    $s[]  = $i;

}
DisplayGenStringPicker('H','H',$s,$s,3);
DisplaySep1();
DisplayPercentPicker('S','S');
DisplaySep1();
DisplayPercentPicker('L','L');
DisplaySep1();
DisplayConvertButton();
DisplayFormEnd();

?>
