<?php
include '../zcommon/common.inc';


$LastOperation = $X_POWERMORPH;
$MAX_MORPH = 50000;
$ArgEffect = $_POST['EFFECT'];
$ArgFrames = 10;
$ArgSpeed = $_POST['SPEED'];
$ArgReverse = $_POST['REVERSE'];

if (isset($ArgEffect) == FALSE)
	$ArgEffect = "radius2.jpg";
if (isset($ArgSpeed) == FALSE)
	$ArgSpeed = 50;

$ArgEffect = str_replace("gif","jpg",$ArgEffect);
$ArgMode = ($ArgEffect == "fade.jpg") ? "dissolve" : "wipe";
$ArgReverse = ($ArgReverse =="on") ? "-r" : " ";
$ArgGradual = ($ArgMode == "dissolve") ? "-e" : " ";

$maskFileDir = "$BASE_DIR/wimages/morphs/$ArgEffect";
RecordCommand("mask = $maskFileDir");


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);
$targetFile = $_POST['FRAMEPATH1'];
$targetFileDir = GetWorkDir($targetFile);
$inputFileDir = ConvertToJPG($inputFileDir);
chmod($targetFileDir,0777);
RecordCommand("$inputFileDir $targetFileDir");

//resize the target and mask to be same size as input
GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
$targetFileDir = ResizeImage($targetFileDir,$real_width,$real_height,TRUE);
RecordCommand("RESIZED TARGET $targetFileDir");
$maskFileDir = ResizeImage($maskFileDir,$real_width,$real_height,TRUE);
RecordCommand("RESIZED MASK $maskFileDir");

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
    {
        $FileList .= "$file ";
    }
    $FileArray = array_reverse($FileArray);
    foreach ($FileArray as $file)
    {
        $FileList .= "$file ";
    }
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -dispose previous -delay $ArgSpeed  $FileList -loop 0 $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XMORPH $command");
}
else
{
	$outputFileName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "../zshells/transitions.sh -d $ArgSpeed -m $ArgMode -f $ArgFrames $ArgReverse $ArgGradual $inputFileDir $targetFileDir $maskFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
}

RecordCommand("FINAL $outputFilePath");
RecordAndComplete("MORPH",$outputFilePath,FALSE);
?>
