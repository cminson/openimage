<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation =$X_FOSSILIZE;
$DEFAULT="01.jpg";
	//get the command parameters
	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$targetName = NewName($inputFileDir);

//$Setting = '01';
switch ($Setting)
{
case '01':
    $command = "convert -blur 0x1  -shade 120x21.78 -normalize -raise 5x5";
    break;
case '02':
    $command = "convert -blur 4x1  -shade 20x21.78 -normalize -raise 5x5";
    break;
case '03':
    $command = "convert -blur 0x1  -shade 60x51 -normalize -raise 5x5";
    break;
case '04':
    $command = "convert  -shade 160x51 -normalize -raise 5x5";
    break;
case '05':
    $command = "convert  -shade 60x11 -normalize -raise 5x5";
    break;
case '06':
    $command = "convert -blur 1x1  -shade 20x141.78 -normalize -raise 5x5";
    break;
case '07':
    $command = "convert -shade 120x21.78 -raise 5x5";
	break;
case '08':
    $command = "convert -sharpen 0.0x1.0 -shade 40x70 -normalize -raise 5x5";
	break;

}


$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "$command $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");


$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand("FOSSIL FINAL $outputFilePath");
RecordAndComplete("FOSSIL",$outputFilePath,FALSE);


?>
