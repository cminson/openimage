<?php
include '../zcommon/common.inc';

$LastOperation = $X_MOTIVATIONALPOSTER;
$Title = $X_MOTIVATIONALPOSTER;

$DEFAULT="ariali.ttf.png";
$EX_DIR = "$BASE_DIR/wimages/fontimages/";
$EX_PATH = "$BASE_PATH/wimages/fontimages/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayLineSep1();
DisplayTextInput($X_TITLE,'TITLE','title',60);
DisplayLineSep1();
DisplayTextInput($X_LINE." 1",'TEXT1','text',60);
DisplayLineSep1();
DisplayTextInput($X_LINE." 2",'TEXT2','',60);
DisplayLineSep1();
DisplayOrientationPicker($X_ORIENTATION,'ORIENTATION');
DisplaySep1();
DisplayBorderPicker($X_BORDER,'BORDER');
DisplayLineSep0();
DisplayColorPicker($X_COLORTITLEANDBORDER,'TITLECOLOR','COLOR1','#ff0000');
DisplaySep1();
DisplayColorPicker($X_COLORTEXT,'TEXTCOLOR','COLOR2','#ff0000');
DisplaySep1();
DisplayColorPicker($X_COLORBACKGROUND,'BACKGROUNDCOLOR','COLOR3','#000000');
DisplayLineSep1();
DisplaySep1();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,5,110,110,TRUE,$DEFAULT);
DisplayLineSep1();
DisplayConvertButton();
DisplayFormEnd();
?>



