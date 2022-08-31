<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation = "Color Fill";
$IMAGEOFFSET_X = 2;
$IMAGEOFFSET_Y = 2;


	//build up the input and output paths
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	RecordCommand("$inputFileDir");

	$clientX = $_POST['CLIENTX'];
	$clientY = $_POST['CLIENTY'];
    $NewColor = $_POST['NEWCOLOR'];
    $Color = $_POST['PICKCOLOR'];

	RecordCommand("fill NewColor = $NewColor $clientX $clientY");

	if (strlen($NewColor) < 6)
	{
		$NewColor = "FF0000";
	}

	
	$ArgFuzz = $_POST['FUZZ'];
	//$ArgFuzz= "20%";

	$clientX = $clientX - $IMAGEOFFSET_X;
	$clientY = $clientY - $IMAGEOFFSET_Y;

	if ($clientX < 0) $clientX = 0;
	if ($clientY < 0) $clientY = 0;
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	$display_height = $HEIGHT_IMAGE;
	$display_width = (int)(($display_height/$real_height)*$real_width);
	$clientX = (int)(($real_width/$display_width) * $clientX);
	$clientY = (int)(($real_height/$display_height) * $clientY);


	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	
    $Color = str_replace("#", "", $Color);
	if ($ArgFuzz > 0)
		$command = "convert -fuzz $ArgFuzz -fill \"$NewColor\" -floodfill +$clientX+$clientY \"#$Color\"";
	else
		$command = "convert -fill \"$NewColor\" -floodfill +$clientX+$clientY \"#$Color\"";

	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");

	if ($ImageResized == TRUE)
	{
		$outputFileDir = ResizeImage($outputFileDir,$w,$h,FALSE);
		$targetName = basename($outputFileDir);
		$outputFilePath = "$CONVERT_PATH$targetName";
		RecordCommand("Resized back to original size");
	}

	$LastOperation = "Fill Color: $Color Fuzz: $ArgFuzz";
	RecordCommand("FINAL $outputFilePath");
	RecordAndComplete("FILL",$outputFilePath,FALSE);

?>
