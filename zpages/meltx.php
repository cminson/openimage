<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_MELTED;

	$Setting = $_POST['SETTING'];
    if (isset($Setting) == FALSE)
        $Setting = 5;

	$inputFileDir = $_POST['CURRENTFILE'];
	$inputFileDir = "$BASE_DIR$inputFileDir";
	$inputFileName = basename($inputFileDir);
	RecordCommand("begin -> $inputFileDir $inputFileName");

    $isAnimated = FALSE;
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
    }

	GetImageAttributes($inputFileDir, $real_width, $real_height, $size);
	if ($size > 50000)
	{
    $real_width = $real_height = 300;
    $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
	}


   if ($isAnimated == TRUE)
    {
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
            $outputFileName = MeltImage($imageFileDir);
            $outputFileDir = "$CONVERT_DIR$outputFileName";
            $AnimateString .= "$outputFileDir ";
        }

        // rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
		RecordCommand("ANIM $outputFileName");
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

    }
    else
    {
        $outputFileName = MeltImage($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
		RecordCommand("NONANIM $outputFileName");
    }

	$outputFilePath = "$CONVERT_PATH$outputFileName";
	RecordCommand("FINAL $outputFilePath");
RecordAndComplete("MELT",$outputFilePath,FALSE);


function MeltImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $Setting, $real_width, $real_height ;

	$inputFileName = basename($inputFileDir);
    RecordCommand("MeltImage $inputFileDir $Setting $real_width $real_height");

	$height = 0;
	$incy = $real_height / 7;
	$incx = $real_width / 7;
	$inc = ($incx > $incy) ? $incx : $incy;
	if ($inc <= 10) $inc = 10;

	if ($inc > 60) $inc = 60;
	$inc /= $Setting;
	RecordCommand("FACTOR: $inc");
	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";

	for ($i=0; $i < $inc; $i++)
	{
    $width = rand(70, 250);
    $height = rand(70, 250);
    $swirl = rand(80,150);
	$off = 0 - ($swirl / 2);
    $x = rand($off,$real_width);
    $y = rand($off,$real_height);
    $command = "convert -region $width"."x"."$height+$x+$y -swirl $swirl $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $tmp = $outputFileDir;
    $outputFileDir = $inputFileDir;
    $inputFileDir = $tmp;
    RecordCommand("$command");
	}
    RecordCommand("RETURN $outputFileName");

	return $outputFileName;
}
?>
