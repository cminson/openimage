<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;


$LastOperation = $X_WAVED;

$height = $_POST['HEIGHT'];
$length = $_POST['LENGTH'];
if (isset($height) == FALSE)
$height = 3;
if (isset($length) == FALSE)
	$length = 200;

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$outputFileName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

$command = "convert -background white -wave %HEIGHTx%LENGTH +repage $inputFileDir $outputFileDir";
$command = str_replace("%HEIGHT", $height, $command);
$command = str_replace("%LENGTH", $length, $command);
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");
$outputFilePath = CheckFileSize($outputFileDir);


RecordCommand(" FINAL $outputFilePath ");
RecordAndComplete("WAVE",$outputFilePath,FALSE);

?>
