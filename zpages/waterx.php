<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;


$LastOperation = $X_WATERMARK;

$ArgGravity = $_POST['POSITION'];
$ArgDissolve = $_POST['DISSOLVE'];
$ArgWidth = $_POST['WIDTH'];
$ArgHeight = $_POST['HEIGHT'];
if (isset($ArgDissolve) == FALSE)
	$ArgDissolve = "30";
if (isset($ArgGravity) == FALSE)
	$ArgGravity = "North";



$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

$inputFileDir = ConvertToJPG($inputFileDir);
$inputFileName = basename($inputFileDir);
$watermarkImageDir = GetWorkDir($_POST['FRAMEPATH1']);



chmod($watermarkImageDir,0777);
RecordCommand("XWATERMARK RESIZE $watermarkImageDir");
$watermarkImageDir = ResizeImage($watermarkImageDir,$ArgWidth,$ArgHeight,TRUE);
RecordCommand("XWATERMARK RESIZE $watermarkImageDir");

GetImageAttributes($watermarkImageDir,$pwidth,$pheight,$size);
GetImageAttributes($inputFileDir,$real_width,$real_height,$size);

$w = intval($real_width / 2);
$h = intval($real_height / 2);
/*
	if (($pwidth > $w) || ($pheight > $h))
	{
		$watermarkImageDir = ResizeImage($watermarkImageDir,$w,$h,FALSE);
	}
*/

// CJM TIFS no work as patterns
if (IsValidTIF($watermarkImageDir))
    $watermarkImageDir = ConvertTIF($watermarkImageDir);

$isAnimated = FALSE;
if (IsAnimatedGIF($watermarkImageDir) == TRUE)
{
		RecordCommand("XWATERMARK $watermarkImageDir here");
        $imageList = GetAnimatedImages($watermarkImageDir);
        $isAnimated = TRUE;
}

if ($isAnimated == TRUE)
{
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
            $outputFileName = ConvertImage($imageFileDir,$inputFileDir);
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
        $outputFileName = ConvertImage($watermarkImageDir, $inputFileDir);
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
}
RecordCommand("XWATERMARK FINAL $outputFilePath ");

RecordAndComplete("WATERMARK",$outputFilePath,FALSE);

function ConvertImage($watermarkImageDir, $inputFileDir)
{
global $CONVERT_DIR, $ArgDissolve, $ArgGravity;

    $targetName = NewName($inputFileDir);

    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "composite -dissolve $ArgDissolve% -gravity $ArgGravity $watermarkImageDir $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XWATERMARK $command");
    return $targetName;
}
?>
