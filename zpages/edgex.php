<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_EDGE;

$Setting = $_POST['SETTING'];
$Reverse = $_POST['REVERSE'];
$current = $_POST['CURRENTFILE'];


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$outputFileName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

if ($Reverse == 'on')
	$command = "convert +raise $Setting"."x"."$Setting $inputFileDir $outputFileDir";
else
	$command = "convert -raise $Setting"."x"."$Setting $inputFileDir $outputFileDir";

RecordCommand("$command");
RecordCommand("FINAL $outputFilePath ");
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

RecordAndComplete("EDGE",$outputFilePath,FALSE);
?>
