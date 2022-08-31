#!/usr/bin/php
<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;


$X_INSCRIBE="Inscribe";
$LastOperation = $X_INSCRIBE;

$ArgIntensity = $_POST['INTENSITY'];
$ArgMix = $_POST['MIX'];
$ArgSetting = $_POST['SETTING'];


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$outputFileName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

$command = "../zshells/edgefx.sh -s $ArgIntensity -c $ArgSetting -m $ArgMix  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");
$outputFilePath = CheckFileSize($outputFileDir);


RecordCommand(" FINAL $outputFilePath ");
RecordAndComplete("INSCRIBE",$outputFilePath,FALSE);

?>
