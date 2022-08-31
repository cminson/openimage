<?php
include '../zcommon/common.inc';
include '../zcommon/pig.inc';

RecordCommand("ENTER");

declare (ticks=5)
{
if (CompleteWithNoAction()) return;


$LastOperation = $X_POWERMORPH;
$MAX_MORPH = 50000;

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

$UploadSuccess = FALSE;
$rand = MakeRandom();

$tmpName = $_FILES['FILENAME']['tmp_name']; 
$sourceFilePath = $_FILES['FILENAME']['name']; 
$sourceName = basename($sourceFilePath); 
$targetName = $sourceName;



$ArgEffect = $_POST['EFFECT'];
$ArgFrames = 10;
$ArgSpeed = $_POST['SPEED'];

if (isset($ArgEffect) == FALSE)
	$ArgEffect = "radius2.jpg";
if (isset($ArgSpeed) == FALSE)
	$ArgSpeed = 50;


$ArgEffect = str_replace("gif","jpg",$ArgEffect);
$ArgReverse = 'on';
$ArgMode = ($ArgEffect == "fade.jpg") ? "dissolve" : "wipe";
$ArgReverse = ($ArgReverse =="on") ? "-r" : " ";
$ArgGradual = ($ArgMode == "dissolve") ? "-e" : " ";

$maskFileDir = "$BASE_DIR/wimages/morphs/$ArgEffect";
RecordCommand("mask = $maskFileDir");



//
// if nothing loaded, see if we have a previous file
// we were working on.  if this file exists, use it.
// if it doesnt, then report an error.
//
if (empty($sourceName))
{
	//upload failed because no data entered
	$ErrorCode = 1;
}
else if (!IsValidImageFormat($sourceName))
{
	//upload failed due to this is not an image type we deal with
	$ErrorCode = 5;
}
else if (!is_uploaded_file($tmpName))
{
	//upload failed due to size constraints or non-existence
	$ErrorCode = 2;
}
else if (($size = filesize($tmpName)) != 0)
{
	//means upload from a remote file system succeeded.  
	//move it to our upload directory
	//and then put a copy into the convert directory.

	//means upload from a remote file system succeeded.  
	//move the tmp file into our convert directory.
	//this is our starting point for all future conversions
	$outputFileDir = MakeRandomTextName($CONVERT_DIR, $targetName, $rand);
	$outputFilePath = MakeRandomTextName($CONVERT_PATH, $targetName, $rand);
	move_uploaded_file($tmpName, $outputFileDir);

	$outputFileDir = ConvertToJPG($outputFileDir);
/*
    if (IsValidTIF($outputFileDir))
    {
        $outputFileDir = ConvertTIF($outputFileDir);
        $targetName = basename($outputFileDir);
        $outputFilePath = "$CONVERT_PATH$targetName";
        RecordCommand("TIFF Convert $outputFileDir $targetName");
    }


    //knock any big images down to size
    if ($size > $MAX_MORPH)
    {
        $outputFileDir = ResizeImage($outputFileDir,300,300,FALSE);
    }
*/
	$UploadSuccess = TRUE;
}
else
{
	//otherwise, nothing worked.  This is is an error.
	$ErrorCode = 3;
}
if ($UploadSuccess == FALSE)
{
	$outputFileDir = "$BASE_DIR/wimages/tools/standard_defaultimage.jpg";
}

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);

	$current = $_POST['CURRENTFILE'];
	$inputFileName = basename($current);
	$sourceSuffix = GetSuffix($inputFileName);
    $inputFileDir = "$BASE_DIR$current";
    $inputFileDir = ConvertToJPG($inputFileDir);

	$targetFileDir = $outputFileDir;
    chmod($targetFileDir,0777);
	RecordCommand("$inputFileDir $targetFileDir");

    //get size of target image, resize if necessary
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 30000)
    {
        $real_width = $real_height = 800;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
		GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	}

    //resize the target to be same size as the 1st image
    $targetFileDir = ResizeImage($targetFileDir,$real_width,$real_height,TRUE);
	RecordCommand(": RESIZED TARPOST $targetFileDir");

    //resize the mask to be same size as the 1st image
	RecordCommand("$maskFileDir");
    $maskFileDir = ResizeImage($maskFileDir,$real_width,$real_height,TRUE);
	RecordCommand("$maskFileDir");

    //
    // do it baby
	//
	if ($ArgEffect == "peel.jpg")
	{
		$FileArray = array();
		$FileArray[] = $inputFileDir;
		$FileList = "";
		for ($i = 0; $i < 10; $i++)
		{
			$a = ($i + 1) * 10;
			$targetName = TMPName($inputFileName);
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "../zshells/pagepeel.sh -a $a -p white $inputFileDir $targetFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("XMORPH $command");
			$FileArray[] = $outputFileDir;
		}
        foreach ($FileArray as $file)
			$FileList .= "$file ";
		$FileArray = array_reverse($FileArray);
        foreach ($FileArray as $file)
			$FileList .= "$file ";
		$targetName = NewNameGIF();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay $ArgSpeed  $FileList -loop 0 $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XMORPH $command");
	}
	else
	{
    $targetName = StripSuffix($inputFileName);
	$outputFileName = NewNameGIF($inputFileDir);
	RecordCommand("NAME: $outputFileName");
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "../zshells/transitions.sh -p $ArgSpeed -m $ArgMode -f $ArgFrames $ArgReverse $ArgGradual $inputFileDir $targetFileDir $maskFileDir $outputFileDir";
    $command = str_replace("%MORPHCOUNT", $ArgMorphCount, $command);

	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
	}

	RecordCommand("FINAL $outputFilePath");
	RecordAndComplete("MORPH",$outputFilePath,FALSE);
}
?>
