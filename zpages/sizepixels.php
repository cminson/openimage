<?php
include '../zcommon/common.inc';

$Title = $X_RESIZEPIXELS;
DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayTextInput($X_WIDTH,'CLIENTX','',5);
DisplaySep1();
DisplayTextInput($X_HEIGHT,'CLIENTY','',5);
DisplaySep1();
DisplayCheckBox($X_PRESERVEASPECTRATIO,'ASPECT',false);
print "<br><p>\n";
DisplayConvertButton();
DisplayFormEnd();
?>
