<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_REDUCECOLOR;

$Setting = $_POST['SETTING'];
$inputFileDir = $_POST['CURRENTFILE'];

$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);
$targetName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert +dither -colors $Setting $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");

RecordAndComplete("REDUCECOLOR",$outputFilePath,FALSE);

?>
