<?php
include '../zcommon/common.inc';

$Title = $X_MONTAGE;
$current = $_POST['CURRENTFILE'];

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayAnimInputList($current,9);
DisplayLineSep0();

$v= array('1x2','2x1','2x2','3x3','4x4');
$s= array('1x2','2x1','2x2','3x3','4x4');
DisplayGenStringPicker($X_SIZE,'SIZE',$v,$s,4);

$v= array('+0+0','+1+1','+2+2','+3+3','+4+4','+5+5','+10+10','+15+15','+20+20','+25+25','+30+30');
DisplaySep1();
$s = array('None','1','2','3','4','5','10','15','20','25','30');
DisplayGenStringPicker($X_SEPARATION,'SEPARATION',$v,$s,0);

DisplaySep1();
DisplayColorPicker($X_COLOR,'COLOR','COLOR1','#ff0000');

$v= array('NONE','POLAROID','HEAP');
$s = array('NONE','Photo Spread','Random Photo Heap');
DisplaySep1();
DisplayGenStringPicker($X_EFFECT,'EFFECTS',$v,$s,0);

DisplaySep1();
DisplayCheckBox($X_RANDOMLYANIMATE,'ANIMATE',FALSE);
DisplaySlowNote();

DisplayConvertButton();
DisplayFormEnd();
?>
