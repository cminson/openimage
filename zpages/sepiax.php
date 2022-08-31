<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$Title=$X_SEPIA;
$LastOperation=$X_SEPIA;
$DEFAULT="80.jpg";

$Setting = $_POST['SETTING'];
$inputFileDir = $_POST['CURRENTFILE'];
if (strlen($Setting) < 2)
	$Setting = $DEFAULT;
$Setting = str_replace(".jpg","",$Setting);

$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);


$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert -sepia-tone $Setting% $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("SEPIA $command");

$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("SEPIA",$outputFilePath,FALSE);

?>
