<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation=$X_BLACKANDWHITE;

$DEFAULT="50.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/blackwhite/";
$EX_PATH = "$BASE_PATH/wimages/examples/blackwhite/";

	//get the command parameters
	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$targetName = NewName($inputFileDir);

	switch ($Setting)
	{
	case '20':
		$command = 'convert -type Grayscale -black-threshold 20% -white-threshold 80%';
    break;
	case '30':
		$command = 'convert -type Grayscale -black-threshold 30% -white-threshold 70%';
    break;
	case '40':
		$command = 'convert -type Grayscale -black-threshold 40% -white-threshold 60%';
    break;
	case '50':
		$command = 'convert -type Grayscale -black-threshold 50% -white-threshold 50%';
    break;
	case '60':
		$command = 'convert -type Grayscale -black-threshold 60% -white-threshold 40%';
    break;
	case '70':
		$command = 'convert -type Grayscale -black-threshold 70% -white-threshold 30%';
    break;
	}

	$targetName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");


    RecordCommand("FINAL $outputFilePath");

	RecordAndComplete("BLACKWHITE",$outputFilePath,FALSE);

?>
