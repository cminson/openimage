<?php
include '../zcommon/common.inc';


$MAX_ANIM = 52;

$Title = $X_FRAMEANIMATION;
$LastOperation = $X_FRAMEANIMATION;

$current = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$current";
$IsAnimated = IsAnimatedGIF($inputFileDir);

//RecordCommand("ANIM $LN $current");
DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
if ($IsAnimated == TRUE)
	DisplayModifyTable($current,$MAX_ANIM);
else
	DisplayCreateTable($current,$MAX_ANIM);
DisplayLineSep0();
DisplaySpeedPicker($X_SPEED,"TIME");
DisplaySep1();
DisplayLoopPicker($X_LOOP,"LOOP");
DisplaySep1();
DisplayCheckBox($X_AUTORESIZE,"RESIZE",TRUE);
DisplayConvertButton();
DisplayAltNote($X_ANIMMAX);
DisplaySlowNote();


?>
