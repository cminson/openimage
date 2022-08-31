<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation=$X_HEATMAP;

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
		$command = 'convert -solarize 20%';
    break;
	case '30':
		$command = 'convert -solarize 30%';
    break;
	case '40':
		$command = 'convert -solarize 40%';
    break;
	case '50':
		$command = 'convert -solarize 50%';
    break;
	case '60':
		$command = 'convert -solarize 60%';
    break;
	case '70':
		$command = 'convert -solarize 70%';
    break;
	}

	$targetName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");


    RecordCommand("FINAL $outputFilePath");

	RecordAndComplete("HEATMAP",$outputFilePath,FALSE);
?>
