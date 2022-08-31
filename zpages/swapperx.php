<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$IMAGEOFFSET_X = 2;
$IMAGEOFFSET_Y = 2;

$OldColor = $_POST['PICKCOLOR'];
$NewColor = $_POST['NEWCOLOR'];    
$NewColor = str_replace("#", "", $NewColor);
$OldColor = str_replace("#", "", $OldColor);
$ArgFuzz = $_POST['FUZZ'];
$clientX = $_POST['CLIENTX'];
$clientY = $_POST['CLIENTY'];

RecordCommand("$NewColor $OldColor $ArgFuzz $clientX $clientY");
$clientX = $clientX - $IMAGEOFFSET_X;
$clientY = $clientY - $IMAGEOFFSET_Y;
if ($clientX < 0) $clientX = 0;
if ($clientY < 0) $clientY = 0;


$ColorChosen = FALSE;
if (strlen($NewColor) < 1)
{
	$NewColor = "ffffff";
}
if (strlen($OldColor) > 2)
{
	$ColorChosen = TRUE;
}

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

$isAnimated = IsAnimatedGIF($inputFileDir);

//
// if color not manually picked, get it from the pick poin
// get the true X and Y pick point
// (use a png file for this, cuz colorpick only takes pngs)
//
if ($ColorChosen != TRUE)
{
	RecordCommand(" Pick Option Taken");

	// must resize really small images so pick will work properly
	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if (($real_width < 30) || ($real_height < 30))
	{
		$inputFileDir = ResizeImage($inputFileDir,30,30,FALSE);
	}


	// pick requires a PNG format
	$targetName = NewNamePNG();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$command = "convert +matte $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
	if ($isAnimated == TRUE)
	{
		// hack: if animated the conversion will have given us the -0 suffix
        $outputFileDir = StripSuffix($outputFileDir);
        $outputFileDir = $outputFileDir."-0".$PNGSUFFIX;
	}
	$PNGFileDir = $outputFileDir;

	$display_height = $HEIGHT_IMAGE;
	$display_width = (int)(($display_height/$real_height)*$real_width);
	$clientX = (int)(($real_width/$display_width) * $clientX);
	$clientY = (int)(($real_height/$display_height) * $clientY);

	if ($clientX >= $real_width)
	{
		RecordCommand(" Caught X $clientX $real_width");
		$clientX = $real_width-1;
	}
	if ($clientY >= $real_height)
	{
		RecordCommand("Caught Y $clientY $real_height");
		$clientY = $real_height-1;
	}

	//now get the color hit code for clientX and clientY
	$command = "$BASE_DIR/tools/colorpick/colorpick $PNGFileDir $clientX $clientY";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("COLORPICK $command $lines[0]");
	$OldColor = $execResult;
}

//do the conversion
if ($isAnimated == TRUE)
	$targetName = NewNameGIF();
else
	$targetName = NewNamePNG();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
	
$oldHash = $newHash = "";
if (ctype_xdigit($OldColor) == TRUE)
	$oldHash = "#";
if (ctype_xdigit($NewColor) == TRUE)
	$newHash = "#";
if ($ArgFuzz > 0)
{
	$command = "convert -fuzz $ArgFuzz -fill \"$newHash$NewColor\" -opaque \"$oldHash$OldColor\"";
}
else
{
	$command = "convert -fill \"$NewColor\" -opaque \"$OldColor\"";
}

$command = "$command $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

RecordCommand("$command");
$LastOperation .= " $X_COLOR $OldColor -> $NewColor   $ArgFuzz $X_FUZZ";
RecordCommand("FINAL $outputFilePath");
RecordAndComplete("SWAPPER",$outputFilePath,FALSE);

?>
