<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_PIXELLATE;

$DEFAULT="01.jpg";
	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);


    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);
	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);

    $isAnimated = FALSE;
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
			$outputFileName = PixelImage($imageFileDir);
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
		RecordCommand("PIXEL $inputFileDir");
		$outputFileName = PixelImage($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
	}

	$inputFileDir = $outputFileDir;
	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 1200000)
	{
		if (($real_width > 800) || ($real_height > 800))
		{
		$inputFileDir = ResizeImage($inputFileDir,800,800,FALSE);
		$targetName = basename($inputFileDir);
		$outputFilePath = "$CONVERT_PATH$targetName";
		}
	}

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("PIXEL",$outputFilePath,FALSE);



function PixelImage($inputFileDir)
{
global $CONVERT_DIR, $Setting, $real_width, $real_height;


    switch ($Setting)
    {
    case '01':
        $ArgSize = "20%";
        $Pixel = TRUE;
        break;
    case '02':
        $ArgSize = "15%%";
        $Pixel = TRUE;
        break;
    case '03':
        $ArgSize = "10%";
        $Pixel = TRUE;
        break;
    case '04':
        $ArgSize = "7%";
        $Pixel = TRUE;
        break;
    case '05':
        $ArgSize = "4%";
        $Pixel = TRUE;
        break;
    case '06':
        $h = intval($real_height / 50);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "circle";
    case '07':
        $h = intval($real_height / 40);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "circle";
        break;
    case '08':
        $h = intval($real_height / 30);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "circle";
        break;
    case '09':
        $h = intval($real_height / 50);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "square";
    case '10':
        $h = intval($real_height / 40);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "square";
        break;
    case '11':
        $h = intval($real_height / 30);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "square";
        break;
    case '12':
        $h = intval($real_height / 50);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "diamond";
    case '13':
        $h = intval($real_height / 40);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "diamond";
        break;
    case '14':
        $h = intval($real_height / 30);
        $w = $h;
        $arg1 = $w."x".$h;
        $arg2 = "diamond";
        break;
    }
    $ArgSample = 1000;

    $targetName = NewNameJPG();

    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    if ($Pixel == TRUE)
    {
        $command = "convert -resize $ArgSize -sample $ArgSample $inputFileDir $outputFileDir";
    }
    else
    {
        $command = "../zshells/spots.sh -s $arg1 -t $arg2  $inputFileDir $outputFileDir";
    }
    RecordCommand("XPIXEL $command $SETTING");
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	return $targetName;
}

?>
