<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_STRETCHED;
$DEFAULT="01.jpg";


	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    $isAnimated = FALSE;
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
    }

	GetImageAttributes($inputFileDir,$width,$height,$size);


  if ($isAnimated == TRUE)
    {
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
			$outputFileName = StretchImage($inputFileDir);
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
		$outputFileName = StretchImage($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
	}

$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand("FINAL $outputFilePath");
RecordAndComplete("STRETCH",$outputFilePath,FALSE);



function StretchImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $Setting, $width, $height;



$targetName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$targetName";

switch ($Setting)
{
case '01': //horizontal pull
    $x1 = $width / 2;
    $y1 = $height / 2;
    $x2 = $width / 6;
    $y2 = $height / 3;

    $x3 = $width / 2;
    $y3 = ($height / 2) + ($height / 6);
    $x4 = ($width / 2) + ($width / 4);
    $y4 = $height;
    break;
case '02':
    $x1 = $width / 2;
    $y1 = $height / 2;
    $x2 = ($width / 2) + ($width / 4);
    $y2 = ($height / 3);

    $x3 = $width / 2;
    $y3 = ($height / 2) + ($height / 6);
    $x4 = ($width / 2) + 50;
    $y4 = $height;
    break;
case '03':
    $x1 = $width / 6;
    $y1 = $height / 2;
    $x2 = ($width / 6) + ($width / 2);
    $y2 = ($height / 3);

    $x3 = $width / 2;
    $y3 = ($height / 2) + ($height / 6);
    $x4 = ($width / 4);
    $y4 = $height;
	break;
case '04':
    $x1 = $width / 3;
    $y1 = $height / 5;
    $x2 = ($width / 6) + ($width / 2);
    $y2 = ($height / 3);

    $x3 = $width / 3;
    $y3 = ($height / 2) + ($height / 6);
    $x4 = ($width / 5);
    $y4 = $height;
	break;
case '05':
    $x1 = $width / 2;
    $y1 = $height / 2;
    $x2 = ($width / 7) + ($width / 2);
    $y2 = ($height / 3);

    $x3 = $width / 3;
    $y3 = ($height / 2) + ($height / 2);
    $x4 = ($width / 5);
    $y4 = $height/2;
	break;
case '06':
    $x1 = $width / 3;
    $y1 = $height / 5;
    $x2 = ($width / 7) + ($width / 2);
    $y2 = ($height / 3);

    $x3 = $width / 2;
    $y3 = ($height / 3) + ($height / 6);
    $x4 = ($width / 5);
    $y4 = $height;
	break;

	}

	$command = "/usr/bin/convert $inputFileDir -distort Shepards '$x1,$y1 $x2, $y2 $x3,$y3,$x4,$y4' $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("STRETCH $command");


	return $targetName;
}
?>
