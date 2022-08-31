#!/usr/bin/php
<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;


$X_SKETCH="Sketch";
$LastOperation = $X_SKETCH;

//$ArgKind = $_POST['KIND'];
$ArgEdge = $_POST['EDGE'];
$ArgContrast = $_POST['CONTRAST'];
$ArgSaturation = $_POST['SATURATION'];
$ArgGrayScale = $_POST['GRAYSCALE'];

$opt = "";
if ($ArgGrayScale == 'on') $opt = "-g";

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$outputFileName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

//$command = "../zshells/sketch.sh -k $ArgKind -e $ArgEdge -c $ArgContrast -s $ArgSaturation $opt $inputFileDir $outputFileDir";
$command = "../zshells/sketch.sh  -e $ArgEdge -c $ArgContrast -s $ArgSaturation $opt $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");
$outputFilePath = CheckFileSize($outputFileDir);


RecordCommand(" FINAL $outputFilePath ");
RecordAndComplete("SKETCH",$outputFilePath,FALSE);

?>
