#!/usr/bin/php
<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;


$LastOperation = $X_GRITTY;
$ArgContrast = $_POST['CONTRAST'];
$ArgSaturation = $_POST['SATURATION'];

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$outputFileName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

$command = "../zshells/draganeffect.sh -b 1 -c $ArgContrast -s $ArgSaturation $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command $lines[0]");
$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand(" FINAL $outputFilePath ");
RecordAndComplete("GRIT",$outputFilePath,FALSE);

?>
