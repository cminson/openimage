<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation=$X_STAINEDGLASS;

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileDir = ConvertToJPG($inputFileDir);
	GetImageAttributes($inputFileDir,$w,$h,$size);
	if ($size > 150000)
	{
		if (($w > 600) || ($h > 600))
		{
			$inputFileDir = ResizeImage($inputFileDir,600,600,FALSE);
		}
	}

	$kind = $_POST['KIND'];
	$size = $_POST['SIZE'];
	$brightness = $_POST['BRIGHTNESS'];
	$brightness = 150;
    $color = "black";
    $thick = 0;
	RecordCommand("$kind $size $brightness $color $thick");

	$targetName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
	$command = "../zshells/stainedglass.sh -k $kind -s $size -b $brightness -e '$color' -t $thick $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");

    $outputFilePath = CheckFileSize($outputFileDir);

    RecordCommand("FINAL $outputFilePath");

	RecordAndComplete("GLASS",$outputFilePath,FALSE);
?>
