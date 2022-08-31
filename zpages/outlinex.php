<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation=$X_OUTLINE;
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);

if (IsAnimatedGIF($inputFileDir) == TRUE)
{
	$imageList = GetAnimatedImages($inputFileDir);
	$AnimateString = "";
	foreach ($imageList as $imageFileDir)
	{
		$targetName = TMPJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$command = "convert $imageFileDir -colorspace gray \( +clone -blur 0x2 \) +swap -compose divide -composite -linear-stretch 5%x0% $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XOUTLINE ANIM $outputFileDir");
		$AnimateString .= "$outputFileDir ";
	}

	// rebuild animation
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
}
else
{
	$targetName = NewName($inputFileName);
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert $inputFileDir -colorspace gray \( +clone -blur 0x2 \) +swap -compose divide -composite -linear-stretch 5%x0% $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XOUTLINE $command");
}
$lastOperation = $X_OUTLINE;
RecordCommand("FINAL $outputFilePath ");

RecordAndComplete("OUTLINE",$outputFilePath,TRUE);

?>
