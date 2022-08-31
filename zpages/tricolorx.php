<?php
include '../zcommon/common.inc';
if (CompleteWithNoAction()) return;


$LastOperation =  $X_BLACKLIGHT;
    
	$inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    $isAnimated = FALSE;
	RecordCommand("TRICOLOR $inputFileDir");
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
    }

	$targetName = StripSuffix($inputFileName);
	$c1 = $_POST['C1'];
	$c2 = $_POST['C2'];
	$c3 = $_POST['C3'];
	$c1 = str_replace("#", "", $c1);
	$c2 = str_replace("#", "", $c2);
	$c3 = str_replace("#", "", $c3);

	$hash1 = $hash2 = $hash3 = "";
	if (ctype_xdigit($c1) == TRUE)
		$hash1 = "#";
	if (ctype_xdigit($c2) == TRUE)
		$hash2 = "#";
	if (ctype_xdigit($c3) == TRUE)
		$hash3 = "#";

	if ($isAnimated == TRUE)
    {
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
			$outputFileName = TMPName($targetName);
			$outputFileName = "$outputFileName$GIFSUFFIX";
			$outputFileDir = "$CONVERT_DIR$outputFileName";
			$command = "../zshells/mytri.sh -l '$hash1$c1' -m '$hash2$c2' -h '$hash3$c3' $imageFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            $AnimateString .= "$outputFileDir ";
			RecordCommand("TRICOLOR $command");
        }

        // rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("TRICOLOR $command");
    }
	else
	{
		$outputFileName = NewNameGIF();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
		$command = "../zshells/mytri.sh -l '$hash1$c1' -m '$hash2$c2' -h '$hash3$c3' $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("$command");
	}

	$inputFileDir = $outputFileDir;
	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 250000)
	{
    if (($real_width > 600) || ($real_height > 600))
    {
    $inputFileDir = ResizeImage($inputFileDir,600,600,FALSE);
    $targetName = basename($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    }
	}

	$lastOperation = "Black Light Poster: $c1 $c2 $c3";
    RecordCommand("XFINAL $outputFilePath");
	RecordAndComplete("TRICOLOR",$outputFilePath,FALSE);
?>
