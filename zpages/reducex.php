<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation=$X_REDUCEFILESIZE;

	$setting = $_POST['SETTING'];
    $setting = str_replace("%","",$setting);
    $setting = 100 - $setting;

	//build up the input and output paths
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

/*
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $ErrorCode = 3;
    }
    */

    if ($ErrorCode == 0)
    {

    $oldSize = filesize($inputFileDir);
    $oldSize /= 1000;
    $oldSize = round($oldSize,0);

	//execute the command
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
		$outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -layers Optimize $inputFileDir $outputFileDir";
        $lastOperation = "Animation";
    }
    else
    {
		$outputFileName = NewNameJPG(); 
		$outputFileDir = "$CONVERT_DIR$outputFileName";
	    $outputFilePath = "$CONVERT_PATH$outputFileName";
	    $command = "convert -quality $setting $inputFileDir $outputFileDir";
    }
	RecordCommand("$command");
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

    $newSize = filesize($outputFileDir);
    $newSize /= 1000;
    $newSize = round($newSize,0);

    if ($newSize >= $oldSize)
    {
        $lastOperation = "$lastOperation NOT reduced - file is already smallest size at $oldSize"."kb";
        //$outputFilePath = "$CONVERT_PATH$inputFileName";
    }
    else
    {
        $lastOperation = "$lastOperation reduced file size from $oldSize"."kb"." to $newSize"."kb";
    }

	RecordCommand("FINAL $outputFilePath");
    $lastOperation = trim($lastOperation);
	RecordAndComplete("REDUCE",$outputFilePath,FALSE);
    }
?>
