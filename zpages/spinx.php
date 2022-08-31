<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation=$X_SPIN;

    $rand = MakeRandom();

    $ArgTime = $_POST['TIME'];
    $ArgOrient = $_POST['ORIENT'];

    $FileList = "";
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$inputFileDir = ConvertToJPG($inputFileDir);
    $inputFileName = basename($inputFileDir);

    $targetName = TMPJPG();
    $outputFileDir = "$CONVERT_DIR$targetName";

    $command = "convert -resize 200x200 -virtual-pixel background -distort arc 361  +repage $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XDVD $command");
    $inputFileDir = $outputFileDir;
    $FileList .= $outputFileDir;
    $FileList .= " ";

    if ($ArgOrient == "BROKEN")
    {
        for ($i = 1; $i <= 3; $i++)
        {
	    $outputFileName = TPMJPG();
	    $outputFileDir = "$CONVERT_DIR$outputFileName";
        $rot = $i * 90;
        $command = "convert -rotate $rot";
	    $command = "$command $inputFileDir $outputFileDir";
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    //RecordCommand("XDVD $command");
        $FileList .= $outputFileDir;
        $FileList .= " ";
        }
        for ($i = 1; $i <= 3; $i++)
        {
	    $outputFileName = TMPJPG();
	    $outputFileDir = "$CONVERT_DIR$outputFileName";
        $rot = 360 - ($i * 90);
        $command = "convert -rotate $rot";
	    $command = "$command $inputFileDir $outputFileDir";
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    //RecordCommand("XDVD $command");
        $FileList .= $outputFileDir;
        $FileList .= " ";
        }
    }
    else
    {
        for ($i = 1; $i <= 3; $i++)
        {
	    $outputFileName = TMPJPG();
	    $outputFileDir = "$CONVERT_DIR$outputFileName";
        if ($ArgOrient == 'FORWARD')
        {
            $rot = $i * 90;
        }
        else
        {
            $rot = 360 - ($i * 90);
        }
        $command = "convert -rotate $rot";
	    $command = "$command $inputFileDir $outputFileDir";
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    //RecordCommand("XDVD $command");
        $FileList .= $outputFileDir;
        $FileList .= " ";
        }
    }

	$outputFileName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "convert -dispose previous -delay $ArgTime  %FILES -loop 0 $outputFileDir";
    $command = str_replace("%FILES", $FileList, $command);
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	//RecordCommand("XDVD FINAL $command");
	RecordCommand("XDVD FINAL $outputFilePath");

	RecordAndComplete("SPIN",$outputFilePath,FALSE);
?>
