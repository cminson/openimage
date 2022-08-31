<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_TRANSLUCENTADDED;

$IMAGEOFFSET_X = 2;
$IMAGEOFFSET_Y = 2;

	$clientX = $_POST['CLIENTX'];
	$clientY = $_POST['CLIENTY'];
    $Color = $_POST['PICKCOLOR'];
    $Level = $_POST['LEVEL'];

	// if the user manually entered a color, use it
	$ColorChosen = FALSE;
	if (strlen($Color) >= 6)
	{
		$ColorChosen = TRUE;
		$Color = str_replace("#", "", $Color);
		$clientX = $clientY = 0;
		RecordCommand("XOPACITY Chosen Color = $Color");
	}

	
	$ArgFuzz = $_POST['FUZZ'];

	if ($ColorChosen == FALSE)
	{
		$clientX = $clientX - $IMAGEOFFSET_X;
		$clientY = $clientY - $IMAGEOFFSET_Y;

		if ($clientX < 0) $clientX = 0;
		if ($clientY < 0) $clientY = 0;

		if (($clientX == 0) && ($clientY == 0))
		{
			$Color = "000000";
		}
	}


    //build up the input and output paths
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$inputFileDir = ConvertToJPG($inputFileDir);
    $inputFileName = basename($inputFileDir);


	// if color not manually chosen then get from pick 
    if ($ColorChosen == FALSE)
	{
		GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
		if ($real_height <= 0)
		{
        $ErrorCode = 10;
        $real_height = 300;
		}
		if ($real_width <= 0)
		{
        $ErrorCode = 10;
        $real_width = 300;
		}

		//convert to png (if not png already)
		//gotta use matte option, so to disregard any transparent layer
		$targetName = NewNamePNG();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$command = "convert +matte $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XOPACITY $command");
		$inputFileDir = $outputFileDir;

		//
		//get the true X and Y pick point
		//(use the png file for this, cuz colorpick only takes pngs)
		//
		$display_width = $HEIGHT_IMAGE;
		$display_width = (int)(($display_height/$real_height)*$real_width);
        $clientX = (int)(($real_width/$display_width) * $clientX);
        $clientY = (int)(($real_height/$display_height) * $clientY);

		if ($clientX >= $real_width)
		{
        RecordCommand("XOPACITY: Caught X $clientX $real_width");
        $clientX = $real_width-1;
		}
		if ($clientY >= $real_height)
		{
        RecordCommand("XOPACITY: Caught Y $clientY $real_height");
        $clientY = $real_height-1;
		}

		//now get the color hit code for clientX and clientY
		$command = "$BASE_DIR/tools/colorpick/colorpick $inputFileDir $clientX $clientY";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XOPACITY $command");
		$Color = $execResult;
	}

    //do the conversion (using either the manual color or the picked color)
    $targetName = NewNamePNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";

    $R = substr($Color,0,2);
    $R = hexdec($R);
    $G = substr($Color,2,2);
    $G = hexdec($G);
    $B = substr($Color,4,2);
    $B = hexdec($B);

	if ($ArgFuzz > 0)
		$command = "convert -fuzz $ArgFuzz% -channel rgba -matte -fill \"rgba(%R,%G,%B,%LEVEL)\" -opaque \"rgb(%R,%G,%B)\"";
	else
		$command = "convert -channel rgba -matte -fill \"rgba(%R,%G,%B,%LEVEL)\" -opaque \"rgb(%R,%G,%B)\"";

    $command = str_replace("%R", $R, $command);
    $command = str_replace("%G", $G, $command);
    $command = str_replace("%B", $B, $command);
    $command = str_replace("%LEVEL", $Level, $command);

	$command = "$command $inputFileDir  $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
	RecordCommand("FINAL $outputFilePath");

	$outputFilePath = CheckFileSize($outputFileDir);
	$LastOperation .= " $X_COLOR $Color: $Level $X_LEVEL $ArgFuzz $X_FUZZ";
    RecordAndComplete("OPACITY",$outputFilePath,FALSE);
?>
