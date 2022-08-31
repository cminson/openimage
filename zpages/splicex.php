<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_SPLICE;

$Setting = $_POST['SETTING'];
$Direction = $_POST['DIRECTION'];
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

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
            $outputFileName = ConvertImage($imageFileDir);
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
		$outputFileName = ConvertImage($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
	}


RecordCommand("$outputFilePath");

RecordAndComplete("SPLICE",$outputFilePath,FALSE);



function ConvertImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $Setting, $Direction;

//
// currently unsupported compositions in IM
// linear_burn, color_burn, pegtop_light, linear_dodge, vivid_light


$v = $Setting * 6;
$command = "../zshells/stutter.sh -s $v -d $Direction";
$targetName = NewName($inputFileDir);

$outputFileDir = "$CONVERT_DIR$targetName";
$command = "$command $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("SPLICE $command");
return $targetName;
}
?>
