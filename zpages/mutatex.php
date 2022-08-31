<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_MUTATED;
$Setting = $_POST['SETTING'];
switch ($Setting)
{
case 1:
    $Setting = 'East';
    break;
case 2:
    $Setting = 'West';
    break;
case 3:
    $Setting = 'North';
    break;
case 4:
    $Setting = 'South';
    break;
case 5:
    $Setting = 'NorthWest';
    break;
case 6:
    $Setting = 'NorthEast';
    break;
case 7:
    $Setting = 'SouthWest';
    break;
case 8:
    $Setting = 'SouthEast';
    break;
}


    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

   // $isAnimated = FALSE;
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
    }

	if ($isAnimated == TRUE)
    {
        $AnimateString = "";
		RecordCommand("ANIM TRUE");
        foreach ($imageList as $imageFileDir)
        {
			$outputFileName = MutateImage($imageFileDir);
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
		RecordCommand("ANIM NOT TRUE");
		$outputFileName = MutateImage($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
	}


	$inputFileDir = $outputFileDir;
	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 500000)
	{
		if (($real_width > 400) || ($real_height > 400))
		{
		$inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
		$targetName = basename($inputFileDir);
		$outputFilePath = "$CONVERT_PATH$targetName";
		}
	}

	RecordCommand("FINAL $outputFilePath");

RecordAndComplete("MUTATE",$outputFilePath,FALSE);



function MutateImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $Setting;

	RecordCommand("MutateImage: $inputFileDir");
	$outputFileName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";

	$command = "../zshells/mirrorize.sh -r $Setting $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);$inputFileDir = $outputFileDir;
	GetImageAttributes($inputFileDir, $width, $height, $size);
	RecordCommand("MUTATE : $command $width $height");

	//$targetName = basename($outputFileDir);

	$targetName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$targetName";

	switch ($Setting)
	{
	case "East":
        //$x = $width;
        $x = 0;
        $y = 0;
        break;
	case "West":
        $x = 0;
        $y = 0;
        break;
	case "North":
        $x = 0;
        $y = 0;
        break;
    default:
        $x = 0;
        $y = 0;
        break;
	}

	$command = "convert -crop $width"."x"."$height+$x+$y! $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
	return $targetName;

}

?>
