<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$Title=$X_COLORIZE;
$LastOperation=$X_COLORIZE;

	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$targetName = NewName($inputFileDir);

	$command = "convert -colorize $Setting";

	$targetName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("COLORIZE $command");


    RecordCommand("COLORIZE FINAL $outputFilePath");

	RecordAndComplete("COLORIZE",$outputFilePath,FALSE);
?>
