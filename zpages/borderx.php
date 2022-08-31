<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_COLOREDBORDER;
$ErrorCode = 0;
$ConvertResultCode = -1;

$color = $_POST['COLOR'];
$width = $_POST['WIDTH'];
$threeD = $_POST['3D'];
$current = $_POST['CURRENTFILE'];

RecordCommand("$current $color $width $threeD");

	if (strlen($color) < 3)
		$color = "#FF0000";
	if (stristr($color,'#') == FALSE)
		$color = "#$color";


    $color = str_replace("#", "", $color);

	if ($threeD == 'on')
	{
		$width = str_replace("-","",$width);
		$command = "convert -mattecolor '%COLOR' -frame 13x13+5+5 $inputFileDir $outputFileDir";
	}
	else
	{
		$command = "convert -border %WIDTHx%HEIGHT -bordercolor '%COLOR'";
	}

	//build up the input and output paths
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";

	//animated PNGS have a problem so we just convert all PNG to JPG
	if (IsValidPNG($inputFileDir) == TRUE)
	{
		$inputFileDir = ConvertToJPG($inputFileDir);
	}

	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";
	RecordCommand("$inputFileDir $outputFileName");

    $hash = "";
    if (ctype_xdigit($color) == TRUE)
        $hash = "#";
	//execute the command
	$command = str_replace("%WIDTH", $width, $command);
	$command = str_replace("%HEIGHT", $width, $command);
	$command = str_replace("%COLOR", "$hash$color", $command);
	$command = "$command $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("FINAL $outputFilePath");

    RecordAndComplete("BORDER",$outputFilePath,FALSE);

?>
