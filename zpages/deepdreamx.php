<?php
include '../zcommon/common.inc';

$GREENPIXEL = "/home/httpd/vhosts/ezimba/httpdocs/wimages/tools/green-pixel.jpg";

if (CompleteWithNoAction()) return;
RecordCommand("DEEPDREAM START");
$Action = "google.py";
/*
$Setting = $_POST['SETTING'];

switch ($Setting)
{
case '1':
	$Action = "google.py";
	break;
case '2';
	$Action = "ddn-cnn.py";
	break;
default:
	$Action = "google.py";
}
*/

$LastOperation=$X_DEEPDREAM;
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";


// CJM - resizing breaks tracking. DEV FIX
/*
GetImageAttributes($inputFileDir,$pwidth,$pheight,$size);
if ($pheight > $pwidth)
{
	if ($pheight > 900)
	{
		$inputFileDir = ResizeImage($inputFileDir,900,900,FALSE);
		RecordCommand("DEV $inputFileDir Resized $pwidth $pheight");
	}

}
else
{

	if ($pwidth > 900)
	{
		$inputFileDir = ResizeImage($inputFileDir,900,900,FALSE);
		RecordCommand("DEV $inputFileDir Resized $pwidth $pheight");
	}
}
*/

RecordCommand("DEV $inputFileDir");
$inputFileDir = ConvertToJPGInPlace($inputFileDir);
RecordCommand("DEV $inputFileDir");


// tell dnn to do the conversion
$path =  getenv("PYTHONPATH");
$command = "PYTHONPATH=$path /home/httpd/vhosts/ezimba/httpdocs/zdnn/xcaffe.py $inputFileDir";
RecordCommand("DeepDream DEV $command");
exec($command);
RecordCommand("DeepDream DEV $lines[0] COMPLETE");

//
// do this to defeat caching
//
$outputFileName = NewNameJPG();
$outputFileDir = "$CONVERT_DIR$outputFileName";
$command = "cp $inputFileDir $outputFileDir";
exec($command);
RecordCommand("DeepDream DEV $command");

$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand("FINAL $outputFilePath");

$LastOperation = "Deep Dream";

RecordAndComplete("DEEPDREAM",$outputFilePath,FALSE);


//
// uber hackish function to 1) make current file JPG 2) make sure small enough
// for the dnn 3) make sure it's not monochrome.  Conditions necessary to ensure
// dnn works on it.
// 
function ConvertToJPGInPlace($inputFileDir)
{
global $CONVERT_DIR;
global $JPGSUFFIX;
global $GREENPIXEL;

    $targetName = baseName($inputFileDir);
    $targetName = StripSuffix($targetName);

    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if (($real_width > 800) || ($real_height > 800))
    {
        $outputFileDir = "$CONVERT_DIR$targetName$JPGSUFFIX";
        $width = $height = 800;
        $command = "convert -resize $width"."x"."$height $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand("DEV ConvertToJPG Resized $command");
        $inputFileDir = $outputFileDir;
    }

    $outputFileDir = "$CONVERT_DIR$targetName$JPGSUFFIX";
    //$command = "convert $inputFileDir $outputFileDir";
    $command = "composite -geometry +1+1 $GREENPIXEL $inputFileDir $outputFileDir";
    RecordCommand("DEV ConvertToJPG $command");
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

    if (file_exists($outputFileDir) == FALSE)
    {
        $targetName = StripSuffix($targetName);
        $inputFileDir = "$CONVERT_DIR$targetName-0$JPGSUFFIX";
        $command = "cp $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand("ConvertToJPG ANIM SEEN $outputFileDir");
    }
    return $outputFileDir;
}



?>
