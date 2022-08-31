<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_FRACTAL;

$ArgSpread = $_POST['SPREAD'];
$ArgDensity = $_POST['DENSITY'];
$ArgCurve = $_POST['CURVE'];

    if (isset($ArgSpread) == FALSE)
        $ArgSpread = 5;
    if (isset($ArgDensity) == FALSE)
        $ArgDensity = '5';
    if (isset($ArgCurve) == FALSE)
        $ArgCurve = '5';


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
            $outputFileName = FractalImage($imageFileDir);
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
$outputFileName = FractalImage($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";
}


RecordCommand("PREV $outputFilePath");
$outputFilePath = CheckFileSize($outputFileDir);


RecordCommand("FINAL $outputFilePath");
RecordAndComplete("FRACTAL",$outputFilePath,FALSE);


function FractalImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $ArgSpread, $ArgDensity, $ArgCurve;

$outputFileName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$outputFileName";
$command = "../zshells/disperse.sh -s $ArgSpread -d $ArgDensity -c $ArgCurve $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("FRACTAL $command");
return $outputFileName;

}
?>
