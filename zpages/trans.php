<?php
include '../zcommon/common.inc';
$Title = $X_MAKETRANSPARENT;

DisplayLineSep0();
DisplayDivColorPicker();
DisplayLineSep0();
DisplayLineSep0();

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayLineSep0();
DisplayFormStart();
DisplayColorPicker($X_PICKCOLOR,'PICKCOLOR','COLOR1','');
DisplaySep1();
DisplayFuzzPicker($X_FUZZ,'FUZZ',1);
DisplayHiddenField('CLIENTX');
DisplayHiddenField('CLIENTY');
DisplayConvertButton();
DisplayLineSep0();
if (IsHandHeld() == FALSE)
{
print $TRANS_HELP_NEW;
}
DisplayFormEnd();
?>
