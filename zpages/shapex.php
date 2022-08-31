<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = "$X_SHAPE";

$EX_DIR = "$BASE_DIR/wimages/examples/shapes/";
$WORK_DIR = "$BASE_DIR/wimages/examples/shapes/";
$EX_PATH = "$BASE_PATH/wimages/examples/shapes/";
$DEFAULT="square.jpg";


$Setting = $_POST['SETTING'];
$Combine = $_POST['COMBINE'];
$Vignette = $_POST['VIGNETTE'];
$Color = $_POST['COLOR'];
$Blend = $_POST['BLEND'];


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$shapeDir = "$WORK_DIR$Setting";

GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);
GetImageAttributes($shapeDir,$shape_width,$shape_height,$size);

//set the new color (if not black)
if ($Color != "#000000")
{
    $targetName = NewNamePNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
	$command = "convert -fuzz 40% -fill \"$Color\" -opaque \"black\" $shapeDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$shapeDir = $outputFileDir;
    RecordCommand("XSHAPE COLOR $Color $command");
}

//combine the input file, if desired
if ($Combine == 'none')
{
	$targetName = NewName($Setting);
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "cp $shapeDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
}
else
{
	if ($Vignette == 'on')
	{
		$targetName = TMPGIF();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$command = "convert -background \"$Color\" -vignette 10x20 +repage $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		$inputFileDir = $outputFileDir;

	}

	$max_width = $shape_width * $Combine;
	$max_height = $shape_height * $Combine;
	$inputFileDir = ResizeImage($inputFileDir,$max_width,$max_height,FALSE);

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
			$targetName = TMPGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "composite -gravity center -blend $Blend $imageFileDir $shapeDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("XSHAPE ANIM COMBINE $command");
            $AnimateString .= "$outputFileDir ";
        }

        // rebuild animation
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XSHAPE REANIM $command");
    }
	else
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$command = "composite -gravity center -blend $Blend $inputFileDir $shapeDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("COMBINE $command");
	}
	$outputFilePath = "$CONVERT_PATH$targetName";
}

RecordCommand("FINAL $outputFilePath");


RecordAndComplete("SHAPE",$outputFilePath,FALSE);

?>
