<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$Title = "HSL";
$LastOperation = "HSL";
$H = $_POST['H'];
$S = $_POST['S'];
$L = $_POST['L'];

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);

$targetName = NewName($inputFileName);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";

$command = "../zshells/tintilize.sh -m \"hsl($H,$S%,$L%)\" $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("FINAL $command");

RecordCommand("FINAL $outputFilePath command");

RecordAndComplete("HSL",$outputFilePath,FALSE);
?>
