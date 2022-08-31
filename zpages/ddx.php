<?php
include '../zcommon/common.inc';

$GREENPIXEL = "/home/httpd/vhosts/ezimba/httpdocs/wimages/tools/green-pixel.jpg";

if (CompleteWithNoAction()) return;
RecordCommand("DEEPDREAM START");

$LastOperation=$X_DEEPDREAM;
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

$inputFileDir = ConvertToDNNImage($inputFileDir);
$targetName = NewNameJPG();
$outputFileDir = "$CONVERT_DIR$targetName";

// tell dnn to do the conversion
$command = "/home/httpd/vhosts/ezimba/httpdocs/zdnn/xdd.py $inputFileDir $outputFileDir";
RecordCommand("DeepDream $command");
exec($command);
RecordCommand("DeepDream1 COMPLETE");

$inputFileDir = $outputFileDir;
$targetName = NewNameJPG();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "/home/httpd/vhosts/ezimba/httpdocs/zdnn/xdd.py $inputFileDir $outputFileDir";
exec($command);
RecordCommand("DeepDream2 COMPLETE");


//$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand("FINAL $outputFilePath");

$LastOperation = "Deep Dream";

RecordAndComplete("DEEPDREAM",$outputFilePath,FALSE);


//
// uber hackish function to 1) make current file JPG 2) make sure small enough
// for the dnn 3) make sure it's not monochrome.  Conditions necessary to ensure
// dnn works on it.
// 
function ConvertToDNNImage($inputFileDir)
{
global $CONVERT_DIR;
global $JPGSUFFIX;
global $GREENPIXEL;

    $targetName = TMPJPG();

    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if (($real_width > 1000) || ($real_height > 1000))
    {
        $outputFileDir = "$CONVERT_DIR$targetName$JPGSUFFIX";
        $width = $height = 1000;
        $command = "convert -resize $width"."x"."$height $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand("Deep Dream ConvertToJPG Resized $command");
        $inputFileDir = $outputFileDir;
    }

    $outputFileDir = "$CONVERT_DIR$targetName$JPGSUFFIX";
    //$command = "convert $inputFileDir $outputFileDir";
    $command = "composite -geometry +1+1 $GREENPIXEL $inputFileDir $outputFileDir";
    RecordCommand("ConvertToJPG $command");
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
