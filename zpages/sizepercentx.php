<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$current = $_POST['CURRENTFILE'];

	$setting = $_POST['SETTING'];
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    // enlarging heavy images will choke the system, so filter this case
    $ErrorTooBig = FALSE;
    GetImageAttributes($inputFileDir,$width1,$height1,$size);
    if ($size > 250000)
    {
        if ($setting > '100')
        {
            $setting = '100';
	        RecordCommand("XSIZE FILTERED $inputFileDir:  $size");
            $ErrorTooBig = TRUE;
        }
    }


    $setting = "$setting%";

	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";

	//execute the command
	$command = "convert -resize $setting $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XSIZE $command");
	RecordCommand("XSIZE FINAL $outputFilePath");

    GetImageAttributes($outputFileDir,$width2,$height2,$size);
    if ($ErrorTooBig == TRUE)
    {
        $psize = round(($size / 1000),0);
        $psize = $psize."kb";
        $LastOperation = "NOT Resized: file too large ($psize) $width1"."x"."$height1";
    }
    else
    {
        $LastOperation = "Resized $setting from $width1"."x"."$height1 to $width2"."x"."$height2";
    }

	RecordCommand("XSIZE DEV $outputFilePath");
	RecordAndComplete("RESIZE",$outputFilePath,FALSE);
?>
