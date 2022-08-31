<?php
include '../zcommon/common.inc';
include '../zcommon/pig.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_KALEIDOSCOPE;
$DEFAULT="01.jpg";

$Setting = $_POST['SETTING'];
if (strlen($Setting) < 2)
    $Setting = $DEFAULT;
$Setting = str_replace(".jpg","",$Setting);

$Animate = $_POST['ANIMATE'];

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);

GetImageAttributes($inputFileDir,$width,$height,$size);
if ($size > 30000)
{
    if (($width > 450) || ($height > 450))
    {
        $width = $height = 450;
	    $inputFileDir = ResizeImage($inputFileDir, $width, $height, false);
	    $inputFileName = basename($inputFileDir);
	}
}

if ($Animate == 'on')
{
    $outputFileName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$outputFileName";

    $inputFileDir = ResizeImage($inputFileDir, $width, $width, true);
    RecordCommand("KAL ANIMATE $inputFileDir");
    $command = "../zshells/anrotate.sh $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $inputFileDir = $outputFileDir;
    $inputFileName = basename($inputFileDir);
}



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
			$outputFileName = KalImage($imageFileDir);
            $outputFileDir = "$CONVERT_DIR$outputFileName";
            $AnimateString .= "$outputFileDir ";
        }

        // rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 10 $AnimateString -loop 0 $outputFileDir";
		//RecordCommand("KAL REBUILD ANIMATE $command");
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
}
else
{
		//RecordCommand("KAL $inputFileDir");
		$outputFileName = KalImage($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
}

$inputFileDir = $outputFileDir;
GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
if ($size > 1500000)
{
    if (($real_width > 450) || ($real_height > 450))
    {
		$inputFileDir = ResizeImage($inputFileDir,450,450,FALSE);
		$targetName = basename($inputFileDir);
		$outputFilePath = "$CONVERT_PATH$targetName";
    }
}

RecordCommand("FINAL $outputFilePath");
RecordAndComplete("KAL",$outputFilePath,FALSE);



function KalImage($inputFileDir)
{
global $CONVERT_DIR, $Setting;

	$inputFileDir = ConvertToJPG($inputFileDir); //MUST do this - kal.sh only takes JPGs
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";

	switch ($Setting)
	{
	case '01':
		$command = "../zshells/kal.sh -m image -o 0  $inputFileDir $outputFileDir";
		break;
	case '02':
		$command = "../zshells/kal.sh -m image -o 90  $inputFileDir $outputFileDir";
		break;
	case '03':
		$command = "../zshells/kal.sh -m image -o 180  $inputFileDir $outputFileDir";
		break;
	case '04':
		$command = "../zshells/kal.sh -m image -o 270  $inputFileDir $outputFileDir";
		break;
	case '05':
		$command = "../zshells/kal.sh -m image -o 0  -i $inputFileDir $outputFileDir";
		break;
	case '06':
		$command = "../zshells/kal.sh -m image -o 90  -i $inputFileDir $outputFileDir";
		break;
	case '07':
		$command = "../zshells/kal.sh -m image -o 180  -i $inputFileDir $outputFileDir";
		break;
	case '08':
		$command = "../zshells/kal.sh -m image -o 270  -i $inputFileDir $outputFileDir";
		break;
	case '09':
		$command = "../zshells/kal.sh -m disperse -o 0 -s 5 -d 5 -c 10 -n 1 $inputFileDir $outputFileDir";
		break;
	case '10':
		$command = "../zshells/kal.sh -m disperse -o 90 -s 5 -d 5 -c 10 -n 1 $inputFileDir $outputFileDir";
		break;
	case '11':
		$command = "../zshells/kal.sh -m disperse -o 180 -s 5 -d 5 -c 10 -n 1 $inputFileDir $outputFileDir";
		break;
	case '12':
		$command = "../zshells/kal.sh -m disperse -o 270 -s 5 -d 5 -c 10 -n 1  $inputFileDir $outputFileDir";
		break;
	}


	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);$inputFileDir = $outputFileDir;
	GetImageAttributes($inputFileDir, $width, $height, $size);
	RecordCommand("KAL : $command $lines[0]");

	return $targetName;
}

?>
