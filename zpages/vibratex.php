<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;

$LastOperation = $X_VIBRATE;

    $rand = MakeRandom();

    //get the command parameters
    $ArgTime = $_POST['TIME'];
    $ArgOrient = $_POST['ORIENT'];
    $ArgAmplitude = $_POST['AMPLITUDE'];

	if (isset($ArgTime) == FALSE)
		$ArgTime = 50;
	if (isset($ArgOrient) == FALSE)
		$ArgOrient = 'X';
	if (isset($ArgAmplitude) == FALSE)
		$ArgAmplitude = 'L';

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileDir = ConvertToJPG($inputFileDir);
    $inputFileName = basename($inputFileDir);

    //get size of target image, resize if necessary
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 60000)
    {
        $real_width = $real_height = 300;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
    }


    if ($ArgOrient == 'Y')
    {
        if ($real_height < 40)
            $real_height = 40;
        switch ($ArgAmplitude)
        {
        case "L":
            $interval = $real_height / 40;
            break;
        case "M":
            $interval = $real_height / 20;
            break;
        case "H":
            $interval = $real_height / 10;
            break;
        };
    }
    else
    {
        if ($real_width < 40)
            $real_width = 40;
        switch ($ArgAmplitude)
        {
        case "L":
            $interval = $real_width / 40;
            break;
        case "M":
            $interval = $real_width / 20;
            break;
        case "H":
            $interval = $real_width / 10;
        };
    }

    // first, border the image
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir -bordercolor LimeGreen -border $interval $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XVIBRATE border $command");
    $inputFileDir = $outputFileDir;

    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -transparent LimeGreen $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XVIBRATE border $command");
    $inputFileDir = $outputFileDir;
    $FileList = $inputFileDir;
    $FileList .= " ";

    // roll the image 
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    //$command = "convert $inputFileDir -roll +%CLIENTX+%CLIENTY ";
    if ($ArgOrient == 'Y')
    {
        $command = "convert $inputFileDir -roll +0+$interval ";
    }
    else
    {
        $command = "convert $inputFileDir -roll +$interval+0 ";
    }
    $command = "$command $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XVIBRATE roll $command");

    // create the animation
    $FileList .= $outputFileDir;
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";        
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -dispose previous -delay %TIME  %FILES -loop 0 $outputFileDir";
    $command = str_replace("%TIME", $ArgTime, $command);
    $command = str_replace("%FILES", $FileList, $command);
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
	RecordCommand("FINAL $outputFilePath");
	RecordAndComplete("VIBRATE",$outputFilePath,FALSE);
?>
