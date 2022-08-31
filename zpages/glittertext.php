<?php
include '../zcommon/common.inc';

$Title = $X_GLITTERTEXT;
$DEFAULT="gold14.gif";

$TRANSCOLOR="#0400A0";
$TRANSCOLOR="#00FE01";

$EX_DIR = "$BASE_DIR/wimages/examples/glitters/";
$EX_PATH = "$BASE_PATH/wimages/examples/glitters/";
$FONT_DIR = "$BASE_DIR/wimages/fonts/";


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();

DisplayTextInput($X_LINE." 1",'LABEL1','text',60);
print  "<br>\n";
DisplayTextInput($X_LINE." 2",'LABEL2','',60);
print "<br><p>\n";
DisplayPositionPicker($X_POSITION,'POSITION');
print "<br><p>\n";
DisplayFontSizePicker($X_FONTSIZE,'FONTSIZE',80);
DisplaySep1();
DisplayColorPicker($X_PICKCOLOR,'LABELCOLOR','COLOR1','#ff0000');
DisplaySep1();

print "<br><p>\n";
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,15,30,100,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();


?>



