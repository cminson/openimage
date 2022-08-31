<?php
include '../zcommon/common.inc';

$current = $_POST['CURRENTFILE'];	//Reminder: need to set current now so post will work!

$Title = $X_MAKETRANSPARENT." - BATCHED -";
$LastOperation = $X_TRANSPARENCYADDED." BATCHED ";
$MAX_ANIM = 16;
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
DisplayFuzzPicker($X_FUZZ,'FUZZ',0);
DisplayLineSep1();
DisplayCreateTable($current,$MAX_ANIM);
DisplayConvertButton();
DisplayLineSep1();
DisplayHiddenField('CLIENTX');
DisplayHiddenField('CLIENTY');
if (IsHandHeld() == FALSE)
{
print $TRANS_HELP_NEW;
}
DisplayFormEnd();

?>
