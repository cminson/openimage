<?php
include '../zcommon/common.inc';

$LastOperation = $X_ACCENT;
if (CompleteWithNoAction()) return;


$current = $_POST['CURRENTFILE'];
$Color = $_POST['COLOR'];
$Fuzz = $_POST['FUZZ'];

RecordCommand(" Color=$Color Fuzz=$Fuzz");
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

$inputFileDir = ConvertToJPG($inputFileDir);

$targetName = NewNamePNG();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$inputFileDir = $outputFileDir;
$inputFileName = basename($inputFileDir);

$targetName = TMPName($inputFileName);
$grayFileDir = "$CONVERT_DIR$targetName";
$command = "convert -type Grayscale $inputFileDir $grayFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$targetName = TMPName($inputFileName);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert $inputFileDir -fuzz $Fuzz -fill white  +opaque '$Color' $outputFileDir";
//$command = "convert $inputFileDir -fuzz $Fuzz -fill white  +opaque 'pink' $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand(" $command");
$inputFileDir = $outputFileDir;

$targetName = TMPName($inputFileName);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -transparent white $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$inputFileDir = $outputFileDir;

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -composite $grayFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$inputFileDir = $outputFileDir;

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert  -modulate 100,130 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand(" $command");

$Color = str_replace("#","",$Color);
$LastOperation .= ": $Color";

$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand(" FINAL $outputFilePath");


RecordAndComplete("ACCENT",$outputFilePath,FALSE);

?>
