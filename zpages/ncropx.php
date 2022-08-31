<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_CROPPED;
$Title = $X_CROPIMAGE;

$clientX1 = $_POST['X1'];
$clientY1 = $_POST['Y1'];
$clientX2 = $_POST['X2'];
$clientY2 = $_POST['Y2'];


if (($clientX1 == 0) && ($clientX2 == 0) && ($clientY1 == 0) && ($clientY2 == 0))
{
    RecordCommand("No Selection Seen");
    ReportError("No target area selected for crop. Click the 'Activate Image Selection' link below");
}
    $ArgEllipse = $_POST['ELLIPSE'];

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	RecordCommand("$clientX1 $clientY1 $clientX2 $clientY2 $real_width $real_height");
	$display_height = $HEIGHT_IMAGE;
	$display_width = (int)(($display_height/$real_height)*$real_width);

	if ($clientX1 < 0) $clientX1 = 0;
	if ($clientY1 < 0) $clientY1 = 0;
	if ($clientX2 < 0) $clientX2 = 0;
	if ($clientY2 < 0) $clientY2 = 0;

	if (($clientX1 == 0) && ($clientY1 == 0)
		&& ($clientX2 == 0) && ($clientY2 == 0))
	{
		$ErrorCode = 1;
	}
	else if (($clientX2 == 0) || ($clientY2 == 0))
	{
		$ErrorCode = 2;
	}
	else if ($clientX2 < $clientX1) 
	{
		$ErrorCode = 3;
	}
	else if ($clientY2 < $clientY1) 
	{
		$ErrorCode = 4;
	}

	if ($ErrorCode != 0)
	{
		$x = intval($display_width / 10);
		$y = intval($display_height / 10);
		$clientX1 = $x;
		$clientY1 = $y;
		$clientX2 = $display_width - $x;
		$clientY2 = $display_height - $y;
	}


	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";


	$clientX1 = (int)(($real_width/$display_width) * $clientX1);
	$clientY1 = (int)(($real_height/$display_height) * $clientY1);
	$clientX2 = (int)(($real_width/$display_width) * $clientX2);
	$clientY2 = (int)(($real_height/$display_height) * $clientY2);

	$new_real_width = $real_width - $clientX1 - ($real_width - $clientX2);
	$new_real_height = $real_height - $clientY1 - ($real_height - $clientY2);

	$command = "convert -background white -crop $new_real_width"."x".$new_real_height."+$clientX1+$clientY1 +repage $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");

	$EX_DIR = "$BASE_DIR/wimages/examples/cutters/bigcircle1trans.gif";
	$cutterFileDir = $EX_DIR;
    if ($ArgEllipse == 'on')
	{
		RecordCommand("ELLIPSE");
		$inputFileDir = $outputFileDir;
        GetImageAttributes($inputFileDir, $real_width, $real_height, $size);

		RecordCommand("RESIZE $cutterFileDir");
		$cutterFileDir = ResizeImage($cutterFileDir,$real_width,$real_height,TRUE);

/*
		$targetName = TMPGIF();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$command = "convert -transparent black -fuzz 50% $cutterFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("TRANSPARENT $command");
		$cutterFileDir = $outputFileDir;
*/

		if (IsAnimatedGIF($inputFileDir) == TRUE)
		{
			$imageList = GetAnimatedImages($inputFileDir);
			$AnimateString = "";
			foreach ($imageList as $imageFileDir)
			{
				$targetName = TMPGIF();
				$outputFileDir = "$CONVERT_DIR$targetName";
				$command = "composite -geometry +0+0 $cutterFileDir $imageFileDir $outputFileDir";
				$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
				RecordCommand("$command");

				$inputFileDir = $outputFileDir;
				$targetName = TMPGIF();
				$outputFileDir = "$CONVERT_DIR$targetName";
				$command = "convert -transparent white -fuzz 10% $inputFileDir $outputFileDir";
				$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
				RecordCommand("$command");
				$AnimateString .= "$outputFileDir ";
			}

			$targetName = TMPGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$outputFilePath = "$CONVERT_PATH$targetName";
			$command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("$command");
		}	//end if isAnimatedGIF
		else 
		{
			$targetName = TMPGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$outputFilePath = "$CONVERT_PATH$targetName";
			$command = "composite -geometry +0+0 $cutterFileDir $inputFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("$command");

			$inputFileDir = $outputFileDir;
			$targetName = TMPGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$outputFilePath = "$CONVERT_PATH$targetName";
			$command = "convert -transparent white -fuzz 10% $inputFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("$command");
		}

	}

RecordCommand("FINAL $outputFilePath");
RecordAndComplete("CROP",$outputFilePath,FALSE);
?>
