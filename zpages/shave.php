<?php
include '../zcommon/common.inc';

$Title=$X_SHAVE;


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayNumPicker("",'SETTING',1,100,10);
DisplaySep1();
$v= array('NORTH','SOUTH','NORTHSOUTH','WEST','EAST','WESTEAST','ALL');
$s = array('Top','Bottom','Top & Bottom','Left','Right','Left & Right','All Sides');
DisplayGenStringPicker('','GRAVITY',$v,$s,0);
DisplayConvertButton();
DisplayFormEnd();

?>
