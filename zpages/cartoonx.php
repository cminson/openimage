<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;
RecordCommand("Cartoon X Start");

$LastOperation=$X_CARTOON;
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);

$ArgLevel = $_POST['LEVEL'] + 1;
$ArgEdge = $_POST['EDGE'];
$ArgBrightness = $_POST['BRIGHTNESS'];
$ArgSaturation = $_POST['SATURATION'];

$command = "/var/www/christopherminson/httpdocs/openimage/zshells/cartoon2.sh -n $ArgLevel -e $ArgEdge -b $ArgBrightness -s $ArgSaturation";

$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "$command $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command $lines[0] $lines[1]");

$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("CARTOON",$outputFilePath,FALSE);
?>
