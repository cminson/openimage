<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_EXPLODED;

$Setting = $_POST['SETTING'];
$ArgReverse = $_POST['REVERSE'];


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);

$outputFileName = ImplodeImage($inputFileDir,$ArgReverse);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("EXPLODE",$outputFilePath,FALSE);


function ImplodeImage($inputFileDir,$reverse)
{
global $CONVERT_DIR, $Setting;
global $HEIGHT_IMAGE, $AreaSelect;



	$v = $Setting / 10;

	if ($reverse == 'on')
		$command = "convert -implode $v";
	else
		$command = "convert -implode -$v";

	$targetName = NewName($inputFileDir);

	$outputFileDir = "$CONVERT_DIR$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("IMPLODE $v $command");
	return $targetName;
}


?>
