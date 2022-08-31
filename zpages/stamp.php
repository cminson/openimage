<?php
include '../zcommon/common.inc';

$Title = $X_STAMPTEXT;

$FONT_DIR = "$BASE_DIR/wimages/fonts/";

$DEFAULT="ariali.ttf.png";
$EX_DIR = "$BASE_DIR/wimages/fontimages/";
$EX_PATH = "$BASE_PATH/wimages/fontimages/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayTextInput($X_LINE." 1",'LABEL1','text',60);
DisplayLineSep1();
DisplayTextInput($X_LINE." 2",'LABEL2','',60);
DisplayLineSep1();
DisplayTextInput($X_LINE." 3",'LABEL3','',60);
DisplayLineSep1();
DisplayTextInput($X_LINE." 4",'LABEL4','',60);
DisplayLineSep1();
DisplayTextInput($X_LINE." 5",'LABEL5','',60);
DisplayLineSep1();
DisplayTextInput($X_LINE." 6",'LABEL6','',60);
DisplayLineSep1();
DisplayPositionPicker($X_POSITION,'POSITION');
DisplayLineSep1();
DisplayColorPicker($X_COLOR,'LABELCOLOR','COLOR1','#ff0000');
DisplayLineSep1();
DisplayFontSizePicker($X_FONTSIZE,'FONTSIZE',20);
DisplaySep1();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,5,110,110,TRUE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();


?>



