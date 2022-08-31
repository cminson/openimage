<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$Title = $LastOperation = $X_MAGAZINEMIX;
$DEFAULT="ng.jpg";
$effectFileDir = "$BASE_DIR/wimages/mags/ng.jpg";


	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg",".gif",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	//$inputFileDir = ConvertToJPG($inputFileDir);
    $inputFileName = basename($inputFileDir);

    $isAnimated = FALSE;
    RecordCommand("$inputFileDir");
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
    }

    if ($isAnimated == TRUE)
    {
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
            $outputFileName = MagImage($imageFileDir);
            $outputFileDir = "$CONVERT_DIR$outputFileName";
            RecordCommand("ANIM $outputFileDir");
            $AnimateString .= "$outputFileDir ";
        }

        // rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    }
    else
    {
        $outputFileName = MagImage($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
    }


$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand("FINAL $outputFilePath");
RecordAndComplete("MAG",$outputFilePath,FALSE);


function MagImage($inputFileDir)
{
global $BASE_DIR, $CONVERT_DIR, $Setting;

	$inputFileName = basename($inputFileDir);

	$effectFileDir = "$BASE_DIR/wimages/mags/$Setting";
	$targetName = TMPName($inputFileName);
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -resize 300x400! $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

    $inputFileDir = $outputFileDir;
    $outputFileName = NewName($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $outputFilePath = "$CONVERT_PATH$outputFileName";
	//RecordCommand("OUTPUT $outputFileDir");
	$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");

	return $outputFileName;
}

?>
