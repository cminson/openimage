<?php
include '../zcommon/common.inc';

$Title = $X_POPART;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplaySlowNote();
DisplayFormStart();

$v= array('1x2','2x1','2x2','3x3','4x4','5x5','6x6','7x7','8x8');
$s= array('1x2','2x1','2x2','3x3','4x4','5x5','6x6','7x7','8x8');
DisplayGenStringPicker($X_SIZE,'SIZE',$v,$s,4);
DisplaySep1();
DisplaySpeedPicker($X_SPEED,'TIME');
DisplayLineSep0();
$v= array('+0+0','+1+1','+2+2','+3+3','+4+4','+5+5','+10+10','+15+15','+20+20','+25+25','+30+30');
$s = array('None',1,2,3,4,5,10,15,20,25,30);
DisplayGenStringPicker($X_SEPARATION,'SEPARATION',$v,$s,0);
DisplaySep1();
DisplayColorPicker($X_COLOR,'COLOR','COLOR1','#ff0000');
DisplaySep1();
DisplayCheckBox("Abstract","ABSTRACT",TRUE);
print "<p><br>\n";
$v= array('STATIC','ANIM1','ANIM3','ANIM2');
$s = array('Static Warhol','Animated Warhol','Spotted Warhol','Animated Single');
DisplayGenStringPicker($X_ARTTYPE,'TYPE',$v,$s,0);
DisplayConvertButton();
DisplayFormEnd();
?>
