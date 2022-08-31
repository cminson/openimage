<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$Title = $X_MAKETRANSPARENT." - BATCHED -";
$LastOperation = $X_TRANSPARENCYADDED." BATCHED ";
$IMAGEOFFSET_X = 2;
$IMAGEOFFSET_Y = 2;

$MAX_ANIM = 16;

$current = $_POST['CURRENTFILE'];
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);


	$clientX = $_POST['CLIENTX'];
	$clientY = $_POST['CLIENTY'];
    $NewColor = $_POST['PICKCOLOR'];

	RecordCommand("NewColor = $NewColor $clientX $clientY");

	// if the user manually entered a color, use it
	$ColorChosen = FALSE;
	if (strlen($NewColor) >= 3)
	{
		$ColorChosen = TRUE;
		$Color = str_replace("#", "", $NewColor);
		$clientX = $clientY = 0;
		RecordCommand(" Chosen Color = $Color");
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
			$ErrorCode = 1;
		}
	}

	//build up the input and output paths
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$saveinputFileDir = $inputFileDir;
	RecordCommand(" $inputFileDir");

    //get image dimensions, filter out images that aren't cooperating
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
	RecordCommand(" $command");

    //
    // if it's an animation we'll get multiple png files,
    // otherwise a single file
    //
    if ((file_exists($outputFileDir)) == FALSE)
    {
        $outputFileDir = StripSuffix($outputFileDir);
        $outputFileDir = $outputFileDir."-0".$PNGSUFFIX;
        if ((file_exists($outputFileDir)) == FALSE)
        {
            $ErrorCode = 10;
        }
    }
	$inputFileDir = $outputFileDir;

    //
	// if color not manually picked, get it from the pick poin
    // get the true X and Y pick point
    // (use the png file for this, cuz colorpick only takes pngs)
    //
	RecordCommand(" ColorChosen=$ColorChosen");
    if ($ColorChosen != TRUE)
	{
        RecordCommand(": Pick");

		$display_height = $HEIGHT_IMAGE;
		$display_width = (int)(($display_height/$real_height)*$real_width);
		$clientX = (int)(($real_width/$display_width) * $clientX);
		$clientY = (int)(($real_height/$display_height) * $clientY);

		if ($clientX >= $real_width)
		{
			RecordCommand(": Caught X $clientX $real_width");
			$clientX = $real_width-1;
		}
		if ($clientY >= $real_height)
		{
        RecordCommand(": Caught Y $clientY $real_height");
        $clientY = $real_height-1;
		}

		//now get the color hit code for clientX and clientY
		$command = "$BASE_DIR/tools/colorpick/colorpick $inputFileDir $clientX $clientY";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" COLORPICK $command $lines[0]");
		$Color = $execResult;
	}

	// get all the batch images
    $tmpName = array();
    $sourceName = array();
    $sourceFilePath = array();
    $targetName = array();
    $FileArray = array();

        $FileArray[] = $saveinputFileDir;
		RecordCommand(" NO ANIM $saveinputFileDir");
        for ($i = 2; $i <= $MAX_ANIM; $i++)
        {
            $rand = MakeRandom();
            $tmpName[$i] = $_FILES["FILENAME$i"]['tmp_name']; 
            $sourceFilePath[$i] = $_FILES["FILENAME$i"]['name']; 
            $sourceName[$i] = basename($sourceFilePath[$i]); 
            $targetName[$i] = $sourceName[$i];

            if (filesize($tmpName[$i]) != 0)
            {
                $outputFileDir = MakeTMPRandomTextName($CONVERT_DIR, $targetName[$i], $rand);  
                $outputFilePath = MakeTMPRandomTextName($CONVERT_PATH, $targetName[$i], $rand);
                move_uploaded_file($tmpName[$i], $outputFileDir);    

				if (IsValidTIF($outputFileDir))
				{
					$outputFileDir = ConvertTIF($outputFileDir);
					$targetName = basename($outputFileDir);
					$outputFilePath = "$CONVERT_PATH$targetName";
					RecordCommand("TIFF Convert $outputFileDir $targetName");
				}

                $FileArray[] = $outputFileDir;
				RecordCommand(" Loading $i $outputFileDir");
            }
        } //end for

		$rand = MakeRandom();
		$j = 0;
        $FileList="";
		foreach ($FileArray as $file)
        {
			RecordCommand("FILELIST $file");
			if (strlen($file) > 3)
			{
				$outputFileDir = ConvertTrans($file,$rand+$j);
				if ($j == 0)
				{
					$targetName = baseName($outputFileDir);
					$outputFilePath = "$CONVERT_PATH$targetName";
				}
				$j++;
			}
        }
        RecordCommand("FINAL $outputFilePath");
		RecordAndComplete("TRANS",$outputFilePath,FALSE);


function NewIncGIF($rand)
{
global $SESSION;
global $PREFIXCODE;
global $GIFSUFFIX;

    $SESSION =  ((isset($_COOKIE["SESSION"])) == FALSE)
        ? "9000" : $_COOKIE["SESSION"];
    $PREFIXCODE =  ((isset($_COOKIE["PREFIXCODE"])) == FALSE)
        ? "1" : $_COOKIE["PREFIXCODE"];
    $newName = "$PREFIXCODE$SESSION$rand$GIFSUFFIX";
    return $newName;
}



function ConvertTrans($inputFileDir,$rand)
{
global $ArgFuzz;
global $Color;
global $CONVERT_DIR;

    RecordCommand("XBATCHTRANS $inputFileDir");

    //make sure it's a gif (just go ahead and do the convert)
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XBATCHTRANS $command");
    $inputFileDir = $outputFileDir;

    //do the conversion
    $targetName = NewIncGIF($rand);
    $outputFileDir = "$CONVERT_DIR$targetName";

    $Color = str_replace("#", "", $Color);
    if (ctype_xdigit($Color) == TRUE)
    {
        if ($ArgFuzz > 0)
            $command = "convert -fuzz $ArgFuzz -transparent \"#$Color\"";
        else
            $command = "convert -transparent \"#$Color\"";
    }
    else
    {
        if ($ArgFuzz > 0)
            $command = "convert -fuzz $ArgFuzz -transparent $Color";
        else
            $command = "convert -transparent $Color";
    }

    $command = "$command $inputFileDir  $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XBATCHTRANS $command");
    return $outputFileDir;
}




?>

