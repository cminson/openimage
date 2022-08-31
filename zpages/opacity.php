<?php
include '../zcommon/common.inc';

$Title = $X_MAKETRANSLUCENT;
DisplayMainPageReturn($current);
DisplayTitle($Title);
DisplayFormStart();
DisplayColorPicker($X_PICKCOLOR,'PICKCOLOR','COLOR1','');
DisplaySep1();
DisplayFuzzPicker($X_FUZZ,'FUZZ',20);
DisplaySep1();
DisplayTransPicker($X_TRANSLUCENCY,'LEVEL','COLOR2','');
DisplayConvertButton();
DisplayLineSep0();
if (IsHandHeld() == FALSE)
{
print $TRANSLUCENT_HELP;
}
DisplayHiddenField('CLIENTX');
DisplayHiddenField('CLIENTY');
DisplayFormEnd();

?>
