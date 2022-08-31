<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_GEOMETRIC;

$current = $_POST['CURRENTFILE'];
	$ArgSpread = $_POST['SPREAD'];
	$ArgDensity = $_POST['DENSITY'];
	$ArgCurve = $_POST['CURVE'];
	$ArgPixel = $_POST['PIXEL'];
	$ArgColor = $_POST['NEWCOLOR'];

    if (isset($ArgSpread) == FALSE)
        $ArgSpread = 5;
    if (isset($ArgDensity) == FALSE)
        $ArgDensity = '5';
    if (isset($ArgCurve) == FALSE)
        $ArgCurve = '5';
    if (isset($ArgPixel) == FALSE)
        $ArgPixel = '5';
    if (isset($ArgColor) == FALSE)
        $ArgColor = white;


    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    $isAnimated = FALSE;
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
    }

    // resize if too big
/*
    GetImageAttributes($inputFileDir,$width,$height,$size);
    if ($size > 500000)
    {
        $width = $height = 350;
        $inputFileDir = ResizeImage($inputFileDir,$width,$height,FALSE);
        $inputFileName = basename($inputFileDir);
    }
*/

   if ($isAnimated == TRUE)
    {
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
            $outputFileName = GeoImage($imageFileDir);
            $outputFileDir = "$CONVERT_DIR$outputFileName";
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
		$outputFileName = GeoImage($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
	}


	RecordCommand("PREV $outputFilePath");
    $outputFilePath = CheckFileSize($outputFileDir);


	RecordCommand("FINAL $outputFilePath");
	RecordAndComplete("BORDER",$outputFilePath,FALSE);


function GeoImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $ArgSpread, $ArgDensity, $ArgCurve, $ArgPixel, $ArgColor;

$outputFileName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$outputFileName";
$command = "../zshells/bordereffects.sh -s $ArgSpread -d $ArgDensity -c $ArgCurve -g $ArgPixel -b '$ArgColor' -p 2 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("GEOBORDER $command");
return $outputFileName;

}


?>
