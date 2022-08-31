<?php
include '../zcommon/common.inc';

$Title=$X_COLORSWAP;

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayColorPicker($X_OLDCOLOR,'PICKCOLOR','COLOR1','');
DisplaySep1();
DisplayColorPicker($X_NEWCOLOR,'NEWCOLOR','COLOR2','');
DisplaySep1();
DisplayFuzzPicker($X_FUZZ,'FUZZ',10);
DisplayHiddenField('CLIENTX');
DisplayHiddenField('CLIENTY');
DisplayConvertButton();
DisplayFormEnd();
DisplayLineSep0();
print $SWAPPER_HELP;
?>
