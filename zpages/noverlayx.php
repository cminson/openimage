<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_OVERLAY;

$Post = TRUE;
$clientX1 = $_POST['X1'];
$clientY1 = $_POST['Y1'];
$clientX2 = $_POST['X2'];
$clientY2 = $_POST['Y2'];
$rand = MakeRandom();

if (($clientX1 == 0) && ($clientX2 == 0) && ($clientY1 == 0) && ($clientY2 == 0))
{
	RecordCommand("No Selection Seen");
	ReportError("No target area selected for overlay. Click the 'Activate Image Selection' link below");
}


$patternFileDir = GetWorkDir($_POST['FRAMEPATH1']);
RecordCommand("DEV Pattern 2: $patternFileDir");
$UploadSuccess = TRUE;

//everything worked.  do the command
if ($UploadSuccess == TRUE)
{
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    chmod($patternFileDir,0777);
	RecordCommand("pattern: $patternFileDir");
    GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);
    $isAnimatedPattern = FALSE;
    if (IsAnimatedGIF($patternFileDir) == TRUE)
    {
        $isAnimatedPattern = TRUE;

        //must resize target file to avoid too large files
        if ($size > 300000)
        {
            $inputFile_width = $inputFile_height = 350;
            $inputFileDir = ResizeImage($inputFileDir, $inputFile_width,$inputFile_height, FALSE);
            $inputFileName = basename($inputFileDir);
			GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);
        }
    }

	$display_height = $HEIGHT_IMAGE;
	$display_width = (int)(($display_height/$inputFile_height)*$inputFile_width);
	RecordCommand(" $display_height $display_width $clientX1 $clientX2 $clientY1 $clientY2");
	if (($clientX1 == 0) && ($clientX2 == 0) && ($clientY1 == 0) && ($clientY2 == 0))
	{
		$clientY2 = $display_height; $clientX2 = $display_width;
	}

	$clientX1 = (int)(($inputFile_width/$display_width) * $clientX1);
	$clientY1 = (int)(($inputFile_height/$display_height) * $clientY1);
	$clientX2 = (int)(($inputFile_width/$display_width) * $clientX2);
	$clientY2 = (int)(($inputFile_height/$display_height) * $clientY2);

	$w = $clientX2 - $clientX1;
	$h = $clientY2 - $clientY1;

	$patternFileDir = ResizeImage($patternFileDir,$w,$h,TRUE);
	GetImageAttributes($patternFileDir,$w,$h,$size);

	$x = $clientX1;
	$y = $clientY1;
	$options = "-geometry +$x+$y";

    if (IsAnimatedGIF($inputFileDir) == TRUE)    
    {       
        $inputAnimList = GetAnimatedImages($inputFileDir);
		if ($isAnimatedPattern == FALSE) // pattern is not an animation
		{
			foreach ($inputAnimList as $imageFileDir)
			{
	        $outputFileName = NewName($inputFileDir);
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
	        $outputFilePath = "$CONVERT_PATH$outputFileName";
            $command = "composite $options $patternFileDir $imageFileDir $outputFileDir";
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            $AnimateString .= "$outputFileDir ";
			}
		}
		else  //pattern is ALSO animated 
		{
			$patternAnimList = GetAnimatedImages($patternFileDir);
			foreach ($inputAnimList as $imageFileDir)
			{
            $patternFileDir = $patternAnimList[$i];
	        $outputFileName = NewName($inputFileDir);
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
	        $outputFilePath = "$CONVERT_PATH$outputFileName";
            $command = "composite $options $patternFileDir $imageFileDir $outputFileDir";
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            $AnimateString .= "$outputFileDir ";
            $i++;
            if ($i >= count($patternAnimList))
               $i = 0;
			}
		} // end else

        //now re-animate the morphed images
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay 25  %FILES -loop 0 $outputFileDir";
        $command = str_replace("%FILES", $AnimateString, $command);
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    RecordCommand(" FINAL $outputFilePath");
    }
    else //input image is NOT animated
    {
		if ($isAnimatedPattern == FALSE) // pattern is not an animation
        {
	        RecordCommand(" PATTERN SIMPLE");

            //simplest case, non-animated pattern and non-animated input
	        $outputFileName = NewName($inputFileDir);
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
	        $outputFilePath = "$CONVERT_PATH$outputFileName";
            $command = "composite $options $patternFileDir $inputFileDir $outputFileDir";
            //$command = "composite $options  $patternFileDir $inputFileDir $outputFileDir";
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        RecordCommand(" $command");
	        RecordCommand(" FINAL $outputFilePath");
        }
        else  //the pattern is an animation
        {
	        RecordCommand(" PATTERN ANIMATE");
			$patternAnimList = GetAnimatedImages($patternFileDir);
            foreach ($patternAnimList as $patternFileDir)
            {
	            $outputFileName = NewName($inputFileDir);
	            $outputFileDir = "$CONVERT_DIR$outputFileName";
                $command = "composite $options $patternFileDir $inputFileDir $outputFileDir";
	            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
                $AnimateString .= "$outputFileDir ";
	            RecordCommand(" $command");
            }

            //now re-animate the morphed images
            $targetName = NewNameGIF();
            $outputFileDir = "$CONVERT_DIR$targetName";
            $outputFilePath = "$CONVERT_PATH$targetName";
            $command = "convert -dispose previous -delay 25  %FILES -loop 0 $outputFileDir";
            $command = str_replace("%FILES", $AnimateString, $command);
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        RecordCommand("FINAL $outputFilePath");
        }
    }

$outputFilePath = CheckFileSize($outputFileDir);
RecordAndComplete("OVERLAY",$outputFilePath,FALSE);
}
?>
