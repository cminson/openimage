<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation=$X_CHARCOALED;

$Setting = $_POST['SETTING'];
if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
$Setting = str_replace(".jpg","",$Setting);

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);

$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert -charcoal $Setting $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");

$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("CHARCOAL",$outputFilePath,FALSE);
?>
