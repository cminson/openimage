<?php
include '../zcommon/common.inc';

$Title = $X_SWIRL;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
$values = array(-360,-350,-340,-330,-320,-310,-300,-290,-280,-270,-260,-250,-240,-230,-220,-210,-200,-190,-180,-170,-160,-150,-140,-130,-120,-110,-100,-90,-80,-70,-60,-50,-40,-30,-20,-10,0,10,20,30,40,50,60,70,80,90,100,110,120,130,140,150,160,170,180,190,200,210,220,230,240,250,260,270,280,290,300,310,320,330,340,350,360);
DisplayGenNumPicker($X_SETTING,'SETTING',$values,60);
DisplayLineSep1();
DisplayConvertButton();
DisplayFormEnd();

?>
