#!/usr/bin/php
<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;


$X_SKEW="Inscribe";
$LastOperation = $X_SKEW;

$ArgDegrees = $_POST['DEGREES'];
$ArgDirection = $_POST['DIRECTION'];
$opt = "b2$ArgDirection";


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$outputFileName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

$command = "../zshells/skew.sh  -v background -a $ArgDegrees -d $opt $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");
$outputFilePath = CheckFileSize($outputFileDir);


RecordCommand(" FINAL $outputFilePath ");
RecordAndComplete("SKEW",$outputFilePath,FALSE);

?>
