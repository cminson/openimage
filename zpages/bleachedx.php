<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation=$X_BLEACHED;

	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$targetName = NewName($inputFileDir);

	switch ($Setting)
	{
	case '00':
		$command = "convert -white-threshold 8000";
    break;
	case '01':
		$command = "convert -white-threshold 10000";
    break;
	case '02':
		$command = "convert -white-threshold 20000";
    break;
	case '03':
		$command = "convert -white-threshold 30000";
    break;
	case '04':
		$command = "convert -white-threshold 40000";
    break;
	case '05':
		$command = "convert -white-threshold 50000";
    break;
	}

	$targetName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");


    RecordCommand("FINAL $outputFilePath");

	RecordAndComplete("BLEACHED",$outputFilePath,FALSE);
?>
