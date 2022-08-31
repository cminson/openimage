<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_SKETCH;

$Setting = $_POST['SETTING'];
RecordCommand("Setting = $Setting");

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);

$ArgGrayScale = "";
switch ($Setting)
{
case 1:
	$ArgEdge = 10; $ArgContrast = 100; $ArgSaturation = 100;
	break;
case 2:
	$ArgEdge = 40; $ArgContrast = 140; $ArgSaturation = 100;
	break;
case 3:
	$ArgEdge = 10; $ArgContrast = 100; $ArgSaturation = 100;
	break;
case 4:
	$ArgEdge = 10; $ArgContrast = 100; $ArgSaturation = 100;
	break;
case 5:
	$ArgEdge = 10; $ArgContrast = 100; $ArgSaturation = 100;
	break;
case 6:
	$ArgEdge = 10; $ArgContrast = 100; $ArgSaturation = 100;
	$ArgGrayScale = "-g";
	break;
}

$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";

$command = "../zshells/sketch.sh  -e $ArgEdge -c $ArgContrast -s $ArgSaturation $opt $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("SKETCH",$outputFilePath,FALSE);
?>
