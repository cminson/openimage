<?php
include '../zcommon/common.inc';


$Title = $X_THEMATRIX;;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$v= array('2x2','3x3','4x4');
$s=array('2x2','3x3','4x4');
DisplayGenStringPicker($X_SIZE,'SIZE',$v,$s,1);
DisplaySep1();
DisplaySpeedPicker($X_SPEED,'TIME');
DisplaySep1();
$v= array('0','25','50','75');
$s=array('0%','25%','50%','75%');
DisplayGenStringPicker($X_HOLES,'HOLES',$v,$s,0);
DisplaySep1();
DisplayCheckBox($X_FIRSTIMAGEWHOLE,'BASE',TRUE);
DisplaySlowNote();
DisplayConvertButton();
DisplayFormEnd();
?>
