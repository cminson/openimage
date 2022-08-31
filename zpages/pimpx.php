<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation = $X_PIMPED;


$EX_DIR = "$BASE_DIR/wimages/glitters/";
$EX_PATH = "$BASE_PATH/wimages/glitters/";
$DEFAULT = "sil04.gif";



    $rand = MakeRandom();

    //get the command parameters
    $effect = $_POST['SETTING'];
    $dissolve = $_POST['DISSOLVE'];
    $peffect = StripSuffix($effect);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

	RecordCommand("XPIMP $effect $dissolve");

    //get size of target image, resize if necessary
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 35000)
    {
        $real_width = $real_height = 350;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
        $inputFileName = basename($inputFileDir);
	}
    $imageFileDir = $inputFileDir;

    //extract the glitter animation sequence
    $effect = StripSuffix($effect);
    $outputFileName = TMPPNG();
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $effectsFileDir = "$EX_DIR$effect$GIFSUFFIX";
    $command = "convert $effectsFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XPIMP $command");
    $effectsList = array();
    $effectFileName = StripSuffix($outputFileName);
    for ($i =0;; $i++)
    {
        $effectsFileDir = "$CONVERT_DIR$effectFileName-$i$PNGSUFFIX";
		RecordCommand("XPIMP $effectsFileDir");
        if (file_exists($effectsFileDir) == FALSE)
            break;
        GetImageAttributes($effectsFileDir,$pat_width,$pat_height,$size);
        if (($pat_width > $real_width) || ($pat_height > $real_height))
        {
            $pat_width = $real_width;
            $pat_height = $real_height;
            $effectsFileDir = ResizeImage($effectsFileDir,$pat_width,$pat_height,FALSE);
            //RecordCommand("XPIMP Resized effect $effectsFileDir");
        }
        $effectsList[] = $effectsFileDir;
    }

    //if animated images, do things differently than if non-animated...
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $i = 0;
        foreach ($imageList as $imageFileDir)
        {
            $effectsFileDir = $effectsList[$i];
            //RecordCommand("XPIMP Animation Loop $imageFileDir");
            $i++;
            if ($i >= count($effectsList))
            {
                $i = 0;
            }

            //overlay the target image with the transparent glitter effect
	        $outputFileName = NewNameGIF();
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
            $command = "composite -dissolve %DISSOLVE -tile $effectsFileDir $imageFileDir $outputFileDir";
            $command = str_replace("%DISSOLVE", $dissolve, $command);
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            $AnimateString .= "$outputFileDir ";
	        //RecordCommand("XPIMP OVERLAY  $command $effect");
        }

        //now re-animate the morphed images
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay 25  %FILES -loop 0 $outputFileDir";
        $command = str_replace("%FILES", $AnimateString, $command);
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    RecordCommand("XPIMP FINAL $outputFilePath $effect");
	    //RecordCommand("XPIMP FINAL 1 $outputFileDir $effect");

    }
    else  //non-animated case
    {
        $AnimateString = "";
        foreach ($effectsList as $effectsFileDir)
        {
            //overlay the target image with the transparent glitters
	        $outputFileName = TMPGIF();
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
            $command = "composite -dissolve %DISSOLVE -tile $effectsFileDir $imageFileDir $outputFileDir";
            $command = str_replace("%DISSOLVE", $dissolve, $command);
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        RecordCommand("XPIMP $command $effect");
            $AnimateString .= "$outputFileDir ";
        }

        //now animate the resulting files
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay 25  %FILES -loop 0 $outputFileDir";
        $command = str_replace("%FILES", $AnimateString, $command);
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    RecordCommand("$command");
	    RecordCommand("FINAL $outputFilePath $effect");
    }

	$outputFilePath = CheckFileSize($outputFileDir);
	RecordAndComplete("PIMP",$outputFilePath,FALSE);

function ReduceSize($inputFileName)
{
global $CONVERT_DIR;
global $CONVERT_PATH;

    $inputFileDir = "$CONVERT_DIR$inputFileName";
    $outputFileName = NewName($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "convert -layers Optimize $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    return $outputFilePath;
}

?>
