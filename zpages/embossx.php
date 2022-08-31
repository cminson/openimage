<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_EMBOSSED;
$DEFAULT="16.jpg";

	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);
	RecordCommand("$Setting=$Setting");

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);


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
            $outputFileName = EmbossImage($imageFileDir);
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
		$outputFileName = EmbossImage($inputFileDir);
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

	RecordCommand("EMBOSS FINAL $outputFilePath");

RecordAndComplete("EMBOSS",$outputFilePath,FALSE);



function EmbossImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $Setting;

//
// currently unsupported compositions in IM
// linear_burn, color_burn, pegtop_light, linear_dodge, vivid_light

	//$Setting = '24';
	switch ($Setting)
	{
	case '01':
		$command = "../zshells/emboss.sh -m 1 -d 4 -c hard_light";
		break;
	case '02':
		$command = "../zshells/emboss.sh -m 1 -d 10 -c hard_light";
		break;
	case '03':
		$command = "../zshells/emboss.sh -m 1 -d 25 -c hard_light";
		break;

	case '04':
		$command = "../zshells/emboss.sh -m 1 -d 4 -c multiply";
		break;
	case '05':
		$command = "../zshells/emboss.sh -m 1 -d 10 -c multiply";
		break;
	case '06':
		$command = "../zshells/emboss.sh -m 1 -d 25 -c multiply";
		break;

	case '07':
		$command = "../zshells/emboss.sh -m 1 -d 4 -c color_burn";
		break;
	case '08':
		$command = "../zshells/emboss.sh -m 1 -d 10 -c color_burn";
		break;
	case '09':
		$command = "../zshells/emboss.sh -m 1 -d 25 -c color_burn";
		break;


	case '10':
		$command = "../zshells/emboss.sh -m 1 -d 4 -c linear_light";
		break;
	case '11':
		$command = "../zshells/emboss.sh -m 1 -d 10 -c linear_light";
		break;
	case '12':
		$command = "../zshells/emboss.sh -m 1 -d 25 -c linear_light";
		break;


	case '13':
		$command = "../zshells/emboss.sh -m 1 -d 4 -c soft_light";
		break;
	case '14':
		$command = "../zshells/emboss.sh -m 1 -d 10 -c soft_light";
		break;
	case '15':
		$command = "../zshells/emboss.sh -m 1 -d 25 -c soft_light";
		break;

	case '16':
		$command = "../zshells/emboss.sh -m 1 -d 4 -c overlay";
		break;
	case '17':
		$command = "../zshells/emboss.sh -m 1 -d 10 -c overlay";
		break;
	case '18':
		$command = "../zshells/emboss.sh -m 1 -d 25 -c overlay";
		break;

	case '19':
		$command = "../zshells/emboss.sh -m 1 -d 4 -c color_dodge";
		break;
	case '20':
		$command = "../zshells/emboss.sh -m 1 -d 10 -c color_dodge";
		break;
	case '21':
		$command = "../zshells/emboss.sh -m 1 -d 25 -c color_dodge";
		break;

	case '22':
		$command = "../zshells/emboss.sh -m 1 -d 4";
		break;
	case '23':
		$command = "../zshells/emboss.sh -m 1 -d 10";
		break;
	case '24':
		$command = "../zshells/emboss.sh -m 1 -d 25";
		break;
	}

	$targetName = NewName($inputFileDir);

	$outputFileDir = "$CONVERT_DIR$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("EMBOSS $command");
	return $targetName;
}

?>
