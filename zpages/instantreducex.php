<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";


$oldSize = filesize($inputFileDir);
$oldSize /= 1000;
$oldSize = round($oldSize,0);

if (IsAnimatedGIF($inputFileDir) == TRUE)
{
	$targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -layers Optimize $inputFileDir $outputFileDir";
    $lastOperation = "Animation";
}
else 
{
	$targetName = NewNameJPG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -quality 50 $inputFileDir $outputFileDir";
}

RecordCommand("$command");
RecordCommand("FINAL $outputFilePath");
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$newSize = filesize($outputFileDir);
$newSize /= 1000;
$newSize = round($newSize,0);

if ($ErrorCode == 3)
{
    $lastOperation = "NOT reduced - animated GIFS not supported by this operation";
    $outputFilePath = "$CONVERT_PATH$inputFileName";
}
else if ($newSize >= $oldSize)
{
    $lastOperation = " NOT reduced - file is already smallest at $oldSize"."kb";
    //$outputFilePath = "$CONVERT_PATH$inputFileName";
}
else
{
    $lastOperation = " reduced file size from $oldSize"."kb"." to $newSize"."kb";
}
$LastOperation = trim($lastOperation);
RecordAndComplete("REDUCE",$outputFilePath,TRUE);

?>
