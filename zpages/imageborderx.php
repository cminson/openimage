<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;



$LastOperation = $X_BLENDFRAME;
$UploadSuccess = FALSE;
$CUTTER_DIR = "$BASE_DIR/wimages/cutters/";


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);

$setting = $_POST['SETTING'];
if (isset($setting) == FALSE)
	$setting = "ellipse.jpg";
$setting = StripSuffix($setting);

$patternFileDir = GetWorkDir($_POST['FRAMEPATH1']);
RecordCommand("InputFileDir: $inputFileDir  Pattern : $patternFileDir");

chmod($patternFileDir,0777);
GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);
$isAnimatedPattern = FALSE;
if (IsAnimatedGIF($patternFileDir) == TRUE) 
{
        $isAnimatedPattern = TRUE;

	    RecordCommand("PATTERN ANIMATED");
        //must resize target file to avoid too large files
        if ($size > 120000)
        {
            $inputFile_width = $inputFile_height = 350;
            $inputFileDir = ResizeImage($inputFileDir, $inputFile_width,$inputFile_height, FALSE);
			GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);
            $inputFileName = basename($inputFileDir);
	        //RecordCommand("XIMAGEBORDER RESIZE TARPOST $pattern");
        }
}

$tile_width =  ($inputFile_width > 400) ? 100 : $inputFile_width / 5;
$tile_height =  ($inputFile_height > 400) ? 100 : $inputFile_height / 5;
$patternFileDir = ResizeImage($patternFileDir,$tile_width,$tile_height,FALSE);
GetImageAttributes($patternFileDir,$tile_width,$tile_height,$size);

RecordCommand("$inputFileDir");
if (IsAnimatedGIF($inputFileDir) == TRUE)  // inputfile is an animation  
{       
        $inputAnimList = GetAnimatedImages($inputFileDir);
		RecordCommand("ANIMATED INPUT");

		$i = 0;
        if ($isAnimatedPattern == FALSE) // pattern is not an animation
		{
            $patternFileDir = GeneratePattern($patternFileDir,$setting);
			RecordCommand("XIMAGEBORDER: Pattern = $patternFileDir");

			// the background pattern image
			foreach ($inputAnimList as $inputFileDir)
			{
				$outputFileDir = GenerateCutOut($inputFileDir,$patternFileDir,$setting);
				$AnimateString .= "$outputFileDir ";
			}
		}
		else	// pattern is an animation (along with the input file!)
		{
			$patternAnimList = GetAnimatedImages($patternFileDir);
	
			// cut the input image into right shape, and overaly each frame into 
			// the background pattern image
			foreach ($inputAnimList as $inputFileDir)
			{
				$patternFileDir = GeneratePattern($patternAnimList[$i],$setting);
				$outputFileDir = GenerateCutOut($inputFileDir,$patternFileDir,$setting);
				$AnimateString .= "$outputFileDir ";
				$i++;
				if ($i >= count($patternAnimList))
				$i = 0;
			}
    }

    //now re-animate the morphed images
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -dispose previous -delay 25 %FILES -loop 0 $outputFileDir";
    $command = str_replace("%FILES", $AnimateString, $command);
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XIMAGEBORDER $command");
}
else //input image is NOT animated
{
		RecordCommand("XIMAGEBORDER: NOT ANIMATED INPUT");

        if ($isAnimatedPattern == FALSE) // pattern is not an animation
        {
			RecordCommand("XIMAGEBORDER SIMPLE");
            //a simmple non-animated pattern
            $patternFileDir = GeneratePattern($patternFileDir,$setting);
			$inputFileDir = GenerateCutOut($inputFileDir,$patternFileDir,$setting);
			$outputFileName = baseName($inputFileDir);
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
	        $outputFilePath = "$CONVERT_PATH$outputFileName";
        }
        else  //patttern is an animation
        {
	        RecordCommand("XIMAGEBORDER PATTERN ANIM");
			$patternAnimList = GetAnimatedImages($patternFileDir);
            foreach ($patternAnimList as $patternFileDir)
            {
				$patternFileDir = GeneratePattern($patternFileDir,$setting);
				$outputFileDir = GenerateCutOut($inputFileDir,$patternFileDir,$setting);

                $AnimateString .= "$outputFileDir ";
	            RecordCommand("XIMAGEBORDER $command");
            }

            //now re-animate the composited images
            $targetName = NewNameGIF();
            $outputFileDir = "$CONVERT_DIR$targetName";
            $outputFilePath = "$CONVERT_PATH$targetName";
            $command = "convert -dispose previous -delay 25  %FILES -loop 0 $outputFileDir";
            $command = str_replace("%FILES", $AnimateString, $command);
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        RecordCommand("XIMAGEBORDER $command");
        }
}

//resize if too large
$inputFileDir = $outputFileDir;
GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);
RecordCommand("XIMAGEBORDER size $inputFile_width $inputFile_height $size");
if ($size > 800000)
{
		if (($inputFile_width > 500) || ($inputFile_height > 500))
		{
        $inputFile_width = $inputFile_height = 500;
        $outputFileDir = ResizeImage($inputFileDir,$inputFile_width,$inputFile_height,FALSE);
        $outputFileName = basename($outputFileDir);
        $outputFilePath = "$CONVERT_PATH$outputFileName";
		RecordCommand("XIMAGEBORDER resize $inputFile_width $inputFile_height ");
		}
}

RecordCommand("XIMAGEBORDER FINAL $outputFilePath");
RecordAndComplete("BORDER",$outputFilePath,FALSE);


function ResizeImageNoDither($imageDir, $width, $height, $exactFit)
{
global $CONVERT_DIR;

    $targetName = TMPJPG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    if ($exactFit == TRUE)
        $command = "convert -resize $width"."x"."$height"."\!"." -threshold 50% $imageDir $outputFileDir";
    else
        $command = "convert -resize $width"."x"."$height -threshold 50% $imageDir $outputFileDir";

    RecordCommand("XRESIZE: $command");
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    return $outputFileDir;
}


function GeneratePattern($patternFileDir,$setting)
{
global $CUTTER_DIR;
global $CONVERT_DIR;
global $GIFSUFFIX;
global $inputFile_width;
global $inputFile_height;

	// create the tiled pattern same size as input image
	$dimensions = "$inputFile_width"."x"."$inputFile_height";
	$outputFileName = TMPJPG();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "convert -size $dimensions tile:$patternFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XIMAGEBORDER $command");
	$patternFileDir = ResizeImage($outputFileDir, $inputFile_width, $inputFile_height, TRUE);

	// get the cutter template
	$cutterFileDir = "$CUTTER_DIR$setting$GIFSUFFIX";
	RecordCommand("XIMAGEBORDER  $cutterFileDir");
	$cutterFileDir = ResizeImageNoDither($cutterFileDir,$inputFile_width,$inputFile_height,TRUE);
	RecordCommand("XIMAGEBORDER RESIZED CUTTER  $cutterFileDir $inputFile_width $inputFile_height");

	$outputFileName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "convert -negate -fuzz 10% -transparent black $cutterFileDir $outputFileDir";
    //$command = "convert -transparent white  $patternFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XIMAGEBORDER $command");
	$cutterFileDir = $outputFileDir;

    $outputFileName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "composite -geometry +0+0 $cutterFileDir $patternFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XIMAGEBORDER $command");
	$patternFileDir = $outputFileDir;

	$outputFileName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "convert -fuzz 10% -transparent white $patternFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XIMAGEBORDER $command");
	$patternFileDir = $outputFileDir;

    RecordCommand("XIMAGEBORDER $patternFileDir");
	return $patternFileDir;
}


function GenerateCutOut($inputFileDir,$patternFileDir)
{
global $CUTTER_DIR;
global $CONVERT_DIR;
global $GIFSUFFIX;
global $inputFile_width;
global $inputFile_height;
global $setting;

	// get the cutter template
	$cutterFileDir = "$CUTTER_DIR$setting$GIFSUFFIX";
	RecordCommand("XIMAGEBORDER  $cutterFileDir");
	$cutterFileDir = ResizeImageNoDither($cutterFileDir,$inputFile_width,$inputFile_height,TRUE);
	RecordCommand("XIMAGEBORDER RESIZED CUTTER  $cutterFileDir $inputFile_width $inputFile_height");

    $outputFileName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "convert -fuzz 10% -transparent black  $cutterFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XIMAGEBORDER $command");
	$cutterFileDir = $outputFileDir;

    $outputFileName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "composite -geometry +0+0 $cutterFileDir $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XIMAGEBORDER $command");
	$inputFileDir = ConvertToJPG($outputFileDir);

	//overlay the input image with the cut pattern
    $outputFileName = NewNameGIF();
    //$outputFileName = NewNameJPG();
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "composite -geometry +0+0 $patternFileDir $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XIMAGEBORDER GENERATECUTOUT $command");

    RecordCommand("XIMAGEBORDER GENERATECUTOUT CUTOUT: $inputFileDir");
	return $outputFileDir;
}

?>
