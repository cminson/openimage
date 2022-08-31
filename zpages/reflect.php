<?php
include '../zcommon/common.inc';

$Title = $X_REFLECT;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$v= array('100','50','25','10','1');
$s= array('1 Second','1/2 Second','1/4 Second','1/5 Second','1/10 Second','1/100 Second');
DisplaySep1();
DisplayGenStringPicker($X_SPEED,'TIME',$v,$s,3);
DisplaySep1();
$v= array('4x4','6x6','9x9');
$s= array('Low','Medium','High');
DisplayGenStringPicker($X_RIPPLESIZES,'AMPLITUDE',$v,$s,1);
DisplaySep1();
$v= array('NONE','CLEAR','MURKY');
$s= array('Clear Water','Pool Water','Dark Water');
DisplayGenStringPicker($X_SPECIALEFFECTS,'EFFECTS',$v,$s,1);
DisplayLineSep0();
//DisplayCheckBox($X_MIRRORIMAGE,'MIRROR',FALSE);
DisplayConvertButton();
DisplaySlowNote();
?>
