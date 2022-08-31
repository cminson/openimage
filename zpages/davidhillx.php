#!/usr/bin/php
<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;


$X_DAVIDHILLEFFECT="David Hill Effect";
$LastOperation = $X_DAVIDHILLEFFECT;

$ArgContrast = $_POST['CONTRAST'];
$ArgGain = $_POST['GAIN'];


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$outputFileName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

$command = "../zshells/davidhill.sh -c $ArgContrast -g $ArgGain  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");
$outputFilePath = CheckFileSize($outputFileDir);


RecordCommand(" FINAL $outputFilePath ");
RecordAndComplete("DAVIDHILL",$outputFilePath,FALSE);

?>
