<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation=$X_OILPAINT;

$ArgPaint = $_POST['PAINT'];
$ArgBrightness = $_POST['BRIGHTNESS'];
$ArgSaturation = $_POST['SATURATION'];
$mod = "$ArgBrightness,$ArgSaturation";


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);

$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert -modulate $mod -paint $ArgPaint $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");


RecordCommand("FINAL $outputFilePath");

RecordAndComplete("PAINT",$outputFilePath,FALSE);
?>
