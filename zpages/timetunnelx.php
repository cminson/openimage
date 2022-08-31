<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation=$X_TIMETUNNEL;

	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";

	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 500000)
	{
		if (($real_width > 500) || ($real_height > 500))
		{
		$inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
		}
	}


	$targetName = NewName($inputFileDir);

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
            $outputFileName = TimeTunnelImage($imageFileDir);
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
        $outputFileName = TimeTunnelImage($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
	}


$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand("FINAL $outputFilePath");

RecordAndComplete("TUNNEL",$outputFilePath,FALSE);



function TimeTunnelImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $Setting;

	switch ($Setting)
	{
	case '01':
    $command = "../zshells/tunnelize.sh -m 1";
    break;
	case '02':
    $command = "../zshells/tunnelize.sh -m 2";
    break;
	case '03':
    $command = "../zshells/recursion.sh -d 20 -a 80 -r 10 -z 0.85 -i 9";
    break;
	case '04':
    $command = "../zshells/recursion.sh -d 10 -a 60 -r 5 -z 0.85 -i 15";
    break;
	case '05':
    $command = "../zshells/recursion.sh -d 30 -a 70 -r 2 -z 0.85 -i 10";
    break;
	case '06':
    $command = "../zshells/recursion.sh -d 20 -a 90 -r 10 -z 0.85 -i 5";
    break;
	case '07':
    $command = "../zshells/recursion.sh -d 20 -a 80 -r 10 -z 0.85 -i 9";
	break;
	}

	$targetName = NewName($inputFileDir);

	$outputFileDir = "$CONVERT_DIR$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("TIMETUNNEL $Setting");
	RecordCommand("TIMETUNNEL $command");
	return $targetName;
}
?>
