<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;
RecordCommand("Puzzle X Start");

$LastOperation=$X_CARTOON;
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);

$ArgCount = $_POST['SETTING'];
$seed = rand(1000,9999);

$command = "../zshells/puzzle.sh -m $ArgCount -n $seed";

$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "$command $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command $lines[0] $lines[1]");

$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("PUZZLE",$outputFilePath,FALSE);
?>
