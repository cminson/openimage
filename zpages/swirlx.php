<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$Title = $X_SWIRLIMAGE;

$Setting = $_POST['SETTING'];
$AreaSelect = $_POST['AREASELECT'];
$inputFileDir = $_POST['CURRENTFILE'];

foreach ($_POST as $k => $v)
{
	RecordCommand("$k $v");
}

$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);
RecordCommand("AreaSelect =  $AreaSelect $inputFileDir");
RecordCommand("$Setting $AreaSelect $inputFileDir");

$outputFileName = SwirlImage($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

$inputFileDir = $outputFileDir;
GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
if ($size > 500000)
{
	if (($real_width > 400) || ($real_height > 400))
	{
		$inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
		$targetName = basename($inputFileDir);
		$outputFilePath = "$CONVERT_PATH$targetName";
	}
}

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("SWIRL",$outputFilePath,FALSE);



function SwirlImage($inputFileDir)
{
global $CONVERT_DIR,  $Setting, $AreaSelect;
global $HEIGHT_IMAGE;

	if ($AreaSelect == 'on')
	{
		$clientX1 = $_POST['X1'];
		$clientX2 = $_POST['X2'];
		$clientY1 = $_POST['Y1'];
		$clientY2 = $_POST['Y2'];
		GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
		$display_height = $HEIGHT_IMAGE;
		$display_width = (int)(($display_height/$real_height)*$real_width);

		$clientX1 = (int)(($real_width/$display_width) * $clientX1);
		$clientY1 = (int)(($real_height/$display_height) * $clientY1);
		$clientX2 = (int)(($real_width/$display_width) * $clientX2);
		$clientY2 = (int)(($real_height/$display_height) * $clientY2);
		$w= $clientX2 - $clientX1;
		$h= $clientY2 - $clientY1;
		$region = $w."x".$h."+$clientX1+$clientY1";
		$command = "convert -region $region -swirl $Setting";
	}
	else
	{
		$command = "convert -swirl $Setting";
	}

	$targetName = NewName($inputFileDir);

	$outputFileDir = "$CONVERT_DIR$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
	return $targetName;
}


?>
