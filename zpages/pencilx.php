<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation=$X_PENCILIMAGE;

$ArgEdge = $_POST['LEVEL'];
$ArgBrightness = $_POST['BRIGHTNESS'];
$ArgSaturation = $_POST['SATURATION'];
$mod = "$ArgBrightness,$ArgSaturation";

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);

$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert -modulate $mod -edge $ArgEdge $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");

$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("PENCIL",$outputFilePath,FALSE);
?>
