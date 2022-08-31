<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_MAGNIFIED;

$clientX1 = $_POST['X1'];
$clientY1 = $_POST['Y1'];
$clientX2 = $_POST['X2'];
$clientY2 = $_POST['Y2'];
$ArgMag = $_POST['MAG'];
$ArgWidth = $_POST['WIDTH'];
$ArgColor = $_POST['PICKCOLOR'];
$ArgRect = $_POST['RECT'];

/*
$opt = "";
if ($ArgRect == 'on')
	$opt = "-s square";
else
	$opt = "-s circle";
*/
$ArgWidth = 0;
$opt = "-s circle";


if (($clientX1 == 0) && ($clientX2 == 0) && ($clientY1 == 0) && ($clientY2 == 0))
{
    RecordCommand("No Selection Seen");
    ReportError("No target area selected for crop. Click the 'Activate Image Selection' link below");
}
    $ArgEllipse = $_POST['ELLIPSE'];

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	RecordCommand("$ArgType $clientX1 $clientY1 $clientX2 $clientY2 $real_width $real_height");
	$display_height = $HEIGHT_IMAGE;
	$display_width = (int)(($display_height/$real_height)*$real_width);

	if ($clientX1 < 0) $clientX1 = 0;
	if ($clientY1 < 0) $clientY1 = 0;
	if ($clientX2 < 0) $clientX2 = 0;
	if ($clientY2 < 0) $clientY2 = 0;

	if (($clientX1 == 0) && ($clientY1 == 0)
		&& ($clientX2 == 0) && ($clientY2 == 0))
	{
		$ErrorCode = 1;
	}
	else if (($clientX2 == 0) || ($clientY2 == 0))
	{
		$ErrorCode = 2;
	}
	else if ($clientX2 < $clientX1) 
	{
		$ErrorCode = 3;
	}
	else if ($clientY2 < $clientY1) 
	{
		$ErrorCode = 4;
	}

	if ($ErrorCode != 0)
	{
		$x = intval($display_width / 10);
		$y = intval($display_height / 10);
		$clientX1 = $x;
		$clientY1 = $y;
		$clientX2 = $display_width - $x;
		$clientY2 = $display_height - $y;
	}


	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";

	RecordCommand("$clientX1 $real_width $display_width");
	RecordCommand("$clientY1 $real_height $display_height");

	$clientX1 = (int)(($real_width/$display_width) * $clientX1);
	$clientY1 = (int)(($real_height/$display_height) * $clientY1);
	$clientX2 = (int)(($real_width/$display_width) * $clientX2);
	$clientY2 = (int)(($real_height/$display_height) * $clientY2);

	$new_real_width = $real_width - $clientX1 - ($real_width - $clientX2);
	$new_real_height = $real_height - $clientY1 - ($real_height - $clientY2);

	$radius = round($new_real_width / 2);
	$centx = $clientX1 + $radius;
	$centy = $clientY1 + $radius;

	$command = "../zshells/magnify.sh $centx,$centy $opt -m $ArgMag -c '$ArgColor' -b $ArgWidth -l $radius $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");


RecordCommand("FINAL $outputFilePath");
RecordAndComplete("MAGNIFY",$outputFilePath,FALSE);
?>
