<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_REFLECT;

$FIRERAIN_DIR = "$BASE_DIR/wimages/firerain/";


    $rand = MakeRandom();

    //get the command parameters
    $ArgTime = $_POST['TIME'];
    $ArgAmplitude = $_POST['AMPLITUDE'];
    $ArgEffects = $_POST['EFFECTS'];
    $ArgMirror = $_POST['MIRROR'];

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
		$inputFileDir = ConvertToJPG($inputFileDir);
    }

    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if (($real_width > 300) || ($real_height > 300))
    {
        $real_width = $real_height = 300;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
        $inputFileName = baseName($inputFileDir);
        GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    }

    if ($ArgMirror == 'on')
    {
        $targetName = NewName($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -flip $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $flippedFileDir = $outputFileDir;
        RecordCommand("XREFLECT $command");
    }


    if ($ArgEffects == 'MURKY')
    {
        $targetName = NewName($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "composite -size $real_width"."x"."$real_height -compose DstOver $inputFileDir gradient:transparent-black $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand("XREFLECT MURKY $command");
        $inputFileDir = $outputFileDir;
        $inputFileName = baseName($inputFileDir);
    }

    if ($ArgEffects == 'CLEAR')
    {
        $distortFileDir = "$FIRERAIN_DIR"."water-0.gif";
        $targetName = NewName($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "composite -dissolve 20 -tile $distortFileDir $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand("XREFLECT $command");
        $inputFileDir = $outputFileDir;
        $inputFileName = baseName($inputFileDir);
    }

    if ($ArgMirror == 'on')
    {
        $targetName = NewName($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand("XREFLECT FLIP $command");
        $reflectedFileDir = $outputFileDir;

        $targetName = NewName($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert $reflectedFileDir $flippedFileDir -append $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand("REFLECT APPEND $command");
        $inputFileDir = $outputFileDir;
    }


    //0 works
    $distortFileDir = "$FIRERAIN_DIR"."reflection-0.gif";
    $distortFileDir = ResizeImage($distortFileDir,$real_width,$real_height,TRUE);
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -spread 4 $distortFileDir $outputFileDir";
    $command = "convert -spread 8 $distortFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $distortFileDir = $outputFileDir;

    $Distortions = array();
    for ($i = 1; $i < 8; $i++)
    {
        $targetName = TMPGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $roll = $i * 10;
        $command = "convert -roll +$roll+$roll $distortFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $Distortions[] = $outputFileDir;
    }
    $FileList = "";
    foreach ($Distortions as $distortion)
    {
        $outputFileName = TMPGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $command = "composite $distortion $inputFileDir -displace $ArgAmplitude $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XREFLECT $command");
        $FileList .= "$outputFileDir ";
    }

    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";        
    $command = "convert -dispose previous -delay $ArgTime $FileList -loop 0 $outputFileDir";
    //$command = str_replace("%FILES", $FileList, $command);
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XREFLECT $command");
    $inputFileDir = $outputFileDir;

    //now post-process the image to get rid of artifacts
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";        
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -spread 1 -gaussian 8 $inputFileDir $outputFileDir";
    $command = "convert  -gaussian 8 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XREFLECT $command");
    
    if ($ArgMirror == 'on')
    {
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";        
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -flip $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("$command");
    }

    RecordCommand("FINAL $outputFilePath");

	RecordAndComplete("REFLECT",$outputFilePath,FALSE);
?>
