<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation=$X_PSYCHEDELIC;


$DEFAULT="01.gif";
	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".gif","",$Setting);
	RecordCommand("Setting=$Setting");

    $inputFileDir = $_POST['CURRENTFILE'];
	$inputFileDir = "$BASE_DIR$inputFileDir";
	$inputFileDir = ConvertToJPG($inputFileDir);

	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 20000)
	{
		if (($real_width > 300) || ($real_height > 300))
		{
        $inputFileDir = ResizeImage($inputFileDir,300,300,FALSE);
		RecordCommand("LSD: resized $inputFileDir");
		}
	}

	$targetName = TMPJPG();
	$outputFileDir = "$CONVERT_DIR$targetName";

	switch ($Setting)
	{
	case '01':
		$outputFileDir = $inputFileDir;
		break;
	case '02':
		$command = "convert -paint 1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		break;
	case '03':
		$command = "convert -swirl 30 -paint 2 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		break;
	case '04':
		$command = "convert -charcoal 1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		break;
	case '05':
		$command = "convert -charcoal 2 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		break;
	case '06':
		$command = "convert -swirl 45 -charcoal 2 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		break;
	}
	RecordCommand("$command");
	$inputFileDir = $outputFileDir;

	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
	$command = "../zshells//pseudocolor.sh -i 20 $inputFileDir $outputFileDir";

	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$lines[0] $command");


    RecordCommand("FINAL $outputFilePath");

RecordAndComplete("LSD",$outputFilePath,FALSE);
?>
