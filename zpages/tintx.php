<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$Title = $X_TINT;
$LastOperation = $X_TINTED;


$TintColor = $_POST['COLOR'];
$TintLevel = $_POST['TINTLEVEL'];
RecordCommand("$TintColor");

if (strlen($TintColor) < 2)
{
	$TintColor = "FF0000";
}
$TintColor = str_replace("#", "", $TintColor);


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);

$targetName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";

$hash = "";
if (ctype_xdigit($TintColor) == TRUE)
{
	$hash = "#";
}
$TintLevel = 100 - $TintLevel;
$command = "convert -fill '$hash$TintColor' -tint $TintLevel $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");

$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand("FINAL $outputFilePath");
RecordAndComplete("TINT",$outputFilePath,FALSE);

?>
