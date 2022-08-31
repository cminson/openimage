<?php
include '../zcommon/common.inc';

$DEFAULT="ariali.ttf.png";
$EX_DIR = "$BASE_DIR/wimages/fontimages/";
$EX_PATH = "$BASE_PATH/wimages/fontimages/";
$FONT_DIR = "$BASE_DIR/wimages/fonts/";

$LastOperation = "3D $X_LABEL";
$Title = "3D $X_LABEL";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayTextInput($X_LINE." 1",'LABEL1','text',60);
DisplayLineSep0();
DisplayTextInput($X_LINE." 2",'LABEL2','',60);
DisplayLineSep0();
DisplayLineSep0();
DisplayPositionPicker('','GRAVITY');
DisplaySep1();
DisplayFontSizePicker2($X_FONTSIZE,'FONTSIZE');
DisplaySep1();
$values = array(10,20,30,40,50,60,70,80,90,100);
DisplayGenNumPicker($X_LEVEL,'INTENSITY',$values,60);
DisplaySep1();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,5,110,110,TRUE,$DEFAULT);

DisplaySep1();
DisplayConvertButton();
DisplayFormEnd();
?>

