<?php
include '../zcommon/common.inc';

$Title = "$X_SHAPE";

$EX_DIR = "$BASE_DIR/wimages/examples/shapes/";
$WORK_DIR = "$BASE_DIR/wimages/examples/shapes/";
$EX_PATH = "$BASE_PATH/wimages/examples/shapes/";
$DEFAULT="square.jpg";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplaySlowNote();
DisplayFormStart();
DisplayColorPicker($X_PICKCOLOR,'COLOR','COLOR1','#000000');
DisplayLineSep0();
$s=array('none','90%','80%','70%','60%','50%','40%','30%','20%','10%');
$v=array('none','.9','.8','.7','.6','.5','.4','.3','.2','.1');
DisplayGenStringPicker($X_COMBINE,'COMBINE',$v,$s,7);
DisplaySep1();
DisplayPercentPicker($X_BLENDLEVEL, 'BLEND');
DisplaySep1();
DisplayCheckBox($X_VIGNETTE,'VIGNETTE',true);
DisplayLineSep1();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,7,60,60,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
