<?php
include '../zcommon/common.inc';

$FONT_DIR = "$BASE_DIR/wimages/fonts/";
$Title = $X_BORDERTEXT;

$DEFAULT="ariali.ttf.png";
$EX_DIR = "$BASE_DIR/wimages/fontimages/";
$EX_PATH = "$BASE_PATH/wimages/fontimages/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayTextInput($X_LINE." 1",'LABEL1','text',60);
DisplayLineSep0();
DisplayTextInput($X_LINE." 2",'LABEL2','',60);
DisplayLineSep0();
DisplayColorPicker($X_PICKCOLOR,'LABELCOLOR','COLOR1','#ff0000');
DisplaySep1();
DisplayColorPicker($X_PICKBACKGROUNDCOLOR,'BACKGROUNDCOLOR','COLOR2','#000000');
DisplayLineSep0();
DisplayFontSizePicker($X_FONTSIZE,'FONTSIZE',20);
DisplayLineSep0();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,5,110,110,TRUE,$DEFAULT);
DisplayLineSep0();
DisplayConvertButton();
DisplayFormEnd();

?>
