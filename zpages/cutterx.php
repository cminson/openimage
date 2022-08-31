<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_COOKIECUTTER;

$EX_DIR = "$BASE_DIR/wimages/examples/cutters/";
$EX_PATH = "$BASE_PATH/wimages/examples/cutters/";
$DEFAULT="leaf-maple.gif";


    $rand = MakeRandom();

    //get the command parameters
    $cutter = $_POST['SETTING'];
	if (isset($cutter) == FALSE)
		$cutter = 'leaf-maple.gif';
    $pcutter = StripSuffix($cutter);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    //get image dimensions
    GetImageAttributes($inputFileDir, $real_width, $real_height, $size);

    //resize if too large
    if ($size > 300000)
    {
        $real_width = $real_height = 400;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
        GetImageAttributes($inputFileDir, $real_width, $real_height, $size);
        $inputFileName = basename($inputFileDir);
    }

    $imageFileDir = $inputFileDir;

    //resize cutter image to target dimensions
    $cutterFileDir = "$EX_DIR$cutter";
    $outputFileName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "convert -resize %ARG $cutterFileDir $outputFileDir";
    $command = "convert -resize %ARG $cutterFileDir -threshold 50% $outputFileDir";
    $command = str_replace("%ARG","$real_width"."x"."$real_height"."\!",$command);
    RecordCommand("XCUTTER Resize $command");
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $inputFileDir = $outputFileDir;

    //make sure the cutter image is fully black where it should be black
/*
    $outputFileName = TMPGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "convert -black-threshold 65500 $inputFileDir $outputFileDir";
    $command = "convert -black-threshold 55555 $inputFileDir $outputFileDir";
    RecordCommand("XCUTTER BLACKEN $command");
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $inputFileDir = $outputFileDir;

*/
    //make the cutter image black area transparent
	$outputFileName = TMPGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "convert -transparent black $inputFileDir $outputFileDir";
    RecordCommand("XCUTTER TRANSPARENT $command");
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $cutterFileDir = $outputFileDir;


    //if animated do things painfully
    if (IsAnimatedGIF($imageFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($imageFileDir);
        $i = 0;
        foreach ($imageList as $imageFileDir)
        {

	        $outputFileName = TMPGIF();
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
	        $outputFilePath = "$CONVERT_PATH$outputFileName";

            $command = "composite -geometry +0+0 $cutterFileDir $imageFileDir $outputFileDir";
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            $AnimateString .= "$outputFileDir ";
	        //RecordCommand("XPIMP OVERLAY  $command $effect");
        }

        //now re-animate the morphed images
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay 25  $AnimateString -loop 0 $outputFileDir";
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    }
    else
    {

    //overlay the target image with the transparent cutter
	$outputFileName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";

    $command = "composite -geometry +0+0 $cutterFileDir $imageFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    }

RecordCommand("$command $cutter");
RecordCommand("FINAL $outputFilePath $cutter");
RecordAndComplete("CUTTER",$outputFilePath,FALSE);

?>
