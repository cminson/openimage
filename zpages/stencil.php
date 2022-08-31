<?php
include '../zcommon/common.inc';

$Title = $X_STENCIL;

$DEFAULT="01.gif";
$EX_DIR = "$BASE_DIR/wimages/examples/stencils/";
$EX_PATH = "$BASE_PATH/wimages/examples/stencils/";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayTextInput($X_LINE." 1",'LABEL1','A',20);
DisplayLineSep0();
DisplayTextInput($X_LINE." 2",'LABEL2','',20);
DisplayLineSep0();
DisplayLineSep0();
DisplayColorPicker($X_COLOR,'COLOR','COLOR1','#ffffff');
//DisplayLineSep0();
//DisplayFontPicker($X_FONT,'FONT');
DisplayLineSep0();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,7,60,60,FALSE,$DEFAULT);
DisplayLineSep0();
DisplayConvertButton();
DisplayFormEnd();


?>
