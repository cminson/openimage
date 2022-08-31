<?php
include '../zcommon/common.inc';
include '../zcommon/pig.inc';


declare (ticks=5)
{
if (CompleteWithNoAction()) return;


$X_GROOVYBORDERIMAGE = "Groovy";
$LastOperation = $X_GROOVYBORDERIMAGE;

$UploadSuccess = FALSE;
$DEFAULT="x11-heart.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/groovyborders/";
$PATTERN_DIR = "$BASE_DIR/wimages/examples/groovy/";
$EX_PATH = "$BASE_PATH/wimages/examples/groovyborders/";
$CUTTER_DIR = "$BASE_DIR/wimages/cutters/";
$PATTERN_DIR = "$BASE_DIR/wimages/groovy/";


$rand = MakeRandom();

$t = $_POST['SETTING'];
if (isset($t) == FALSE)
	$t = "x13-heart.jpg";
//$t = "x20-amoeba.jpg";
list($setting,$cutter) = explode('-',$t);
RecordCommand(" $setting $cutter ");
$cutter = str_ireplace(".jpg", "", $cutter);
$cutter = "$cutter$GIFSUFFIX";
$setting = "$setting$GIFSUFFIX";
RecordCommand(" $setting $cutter ");

/*
$cutter = "heart.gif";
$cutter = "star.gif";
$cutter = "starburst1.gif";
$cutter = "ellipse.gif";
$cutter = "diamond.gif";
$cutter = "amoeba.gif";
*/


$Animate = $_POST['ANIMATE'];
$Tile = $_POST['TILE'];



$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);
RecordCommand(" size $inputFile_width $inputFile_height $size");

if ($Animate == 'on')
{
	if (($inputFile_width > 300) || ($inputFile_height > 300))
	{
	$inputFileDir = ResizeImage($inputFileDir,300,300,FALSE);
	RecordCommand(" init resized ");
	}
}
$inputFileName = basename($inputFileDir);


$patternFileDir = "$PATTERN_DIR$setting";
RecordCommand(" patternFileDir $patternFileDir");
//    chmod($patternFileDir,0777);
GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);


$tile_width =  ($inputFile_width > 400) ? 100 : $inputFile_width / 5;
$tile_height =  ($inputFile_height > 400) ? 100 : $inputFile_height / 5;
RecordCommand(" PATTERN 1 $patternFileDir $inputFile_width $inputFile_height");
if ($Tile == 'on')
	$patternFileDir = ResizeImage($patternFileDir,$tile_width,$tile_height,FALSE);
else
	$patternFileDir = ResizeImage($patternFileDir,$inputFile_width,$inputFile_height,TRUE);

RecordCommand(" PATTERN 2 $patternFileDir");
GetImageAttributes($patternFileDir,$tile_width,$tile_height,$size);

RecordCommand(": $inputFileDir");
if (IsAnimatedGIF($inputFileDir) == TRUE)  // inputfile is an animation  
{       
	$inputAnimList = GetAnimatedImages($inputFileDir);
	RecordCommand(": ANIMATED INPUT");

	$i = 0;
	$tmpList = GetAnimatedImages($patternFileDir);
	if ($Animate == 'on')
		$patternAnimList = $tmpList;
	else
		$patternAnimList[] = $tmpList[0];
	
	// cut the input image into right shape, and overaly each frame into 
	// the background pattern image
	foreach ($inputAnimList as $inputFileDir)
	{
		$patternFileDir = GeneratePattern($patternAnimList[$i],$setting,$cutter);
		$outputFileDir = GenerateCutOut($inputFileDir,$patternFileDir,$cutter);
		$AnimateString .= "$outputFileDir ";
		$i++;
		if ($i >= count($patternAnimList))
		$i = 0;
	}

	//now re-animate the morphed images
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25 %FILES -loop 0 $outputFileDir";
	$command = str_replace("%FILES", $AnimateString, $command);
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
}
else //input image is NOT animated
{
	RecordCommand(": NOT ANIMATED INPUT $patternFileDir");

	if ($Animate == 'on')
	{
	$patternAnimList = GetAnimatedImages($patternFileDir);
	foreach ($patternAnimList as $patternFileDir)
	{
		$patternFileDir = GeneratePattern($patternFileDir,$setting,$cutter);
		$outputFileDir = GenerateCutOut($inputFileDir,$patternFileDir,$cutter);

		$AnimateString .= "$outputFileDir ";
		RecordCommand(" $command");
	}

	//now re-animate the composited images
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  %FILES -loop 0 $outputFileDir";
	$command = str_replace("%FILES", $AnimateString, $command);
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	}
	else
	{
		$patternFileDir = GeneratePattern($patternFileDir,$setting,$cutter);
		$outputFileDir = GenerateCutOut($inputFileDir,$patternFileDir,$cutter);
		$targetName = baseName($outputFileDir);
		$outputFileDir = "$CONVERT_DIR$targetName";
		$outputFilePath = "$CONVERT_PATH$targetName";
	}
}


$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand("FINAL $outputFilePath");
RecordAndComplete("GROOVY",$outputFilePath,FALSE);
}


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


function GeneratePattern($patternFileDir,$setting,$cutter)
{
global $CUTTER_DIR;
global $CONVERT_DIR;
global $GIFSUFFIX;
global $inputFile_width;
global $inputFile_height;

	// create the tiled pattern same size as input image
	$dimensions = "$inputFile_width"."x"."$inputFile_height";
	$targetName = TMPJPG();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$command = "convert -size $dimensions tile:$patternFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	$patternFileDir = ResizeImage($outputFileDir, $inputFile_width, $inputFile_height, TRUE);

	// get the cutter template
	$cutterFileDir = "$CUTTER_DIR$cutter";
	RecordCommand("  CUTTER $cutterFileDir");
	$cutterFileDir = ResizeImageNoDither($cutterFileDir,$inputFile_width,$inputFile_height,TRUE);
	RecordCommand(" RESIZED CUTTER  $cutterFileDir $inputFile_width $inputFile_height");

	$targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
	$command = "convert -negate -fuzz 10% -transparent black $cutterFileDir $outputFileDir";
    //$command = "convert -transparent white  $patternFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" $command");
	$cutterFileDir = $outputFileDir;

    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "composite -geometry +0+0 $cutterFileDir $patternFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" $command");
	$patternFileDir = $outputFileDir;

	$targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
	$command = "convert -fuzz 10% -transparent white $patternFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" $command");
	$patternFileDir = $outputFileDir;

    RecordCommand(" $patternFileDir");
	return $patternFileDir;
}


function GenerateCutOut($inputFileDir,$patternFileDir,$cutter)
{
global $CUTTER_DIR;
global $CONVERT_DIR;
global $GIFSUFFIX;
global $inputFile_width;
global $inputFile_height;
global $setting;

	// get the cutter template
	$cutterFileDir = "$CUTTER_DIR$cutter";
	RecordCommand("  $cutterFileDir");
	$cutterFileDir = ResizeImageNoDither($cutterFileDir,$inputFile_width,$inputFile_height,TRUE);
	RecordCommand(" RESIZED CUTTER  $cutterFileDir $inputFile_width $inputFile_height");

    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
	$command = "convert -fuzz 10% -transparent black  $cutterFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" $command");
	$cutterFileDir = $outputFileDir;

    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "composite -geometry +0+0 $cutterFileDir $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" $command");
	$inputFileDir = ConvertToJPG($outputFileDir);

	//overlay the input image with the cut pattern
    $targetName = NewNameGIF();
    //$targetName = NewNameJPG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "composite -geometry +0+0 $patternFileDir $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" GENERATECUTOUT $command");

    RecordCommand(" GENERATECUTOUT CUTOUT: $inputFileDir");
	return $outputFileDir;
}


?>
