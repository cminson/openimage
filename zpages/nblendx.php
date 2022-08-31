<?php
include '../zcommon/common.inc';



if (CompleteWithNoAction()) return;


$LastOperation = $X_BLENDED;
$UploadSuccess = FALSE;


$effect = $_POST['EFFECT'];
if (isset($effect) == FALSE)
	$effect = "OVERLAY";
$dissolve = $_POST['DISSOLVE'];
if (isset($dissolve) == FALSE)
	$dissolve = "30";
$BlendImage = $_POST['FRAMEPATH1'];
$patternFileDir = GetWorkDir($BlendImage);
RecordCommand("DEV Pattern 2: $patternFileDir");
$UploadSuccess = TRUE;


//everything worked.  do the command
if ($UploadSuccess == TRUE)
{
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

	//$patternFileDir = $outputFileDir;
    chmod($patternFileDir,0777);
	//RecordCommand(" pattern: $patternFileDir");
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

	RecordCommand(": Effect = $effect");
	$options = "";
	switch ($effect)
	{
	case 'MANUAL':
		$clientX1 = $_POST['X1'];
		$clientY1 = $_POST['Y1'];
		$clientX2 = $_POST['X2'];
		$clientY2 = $_POST['Y2'];
		$display_height = $HEIGHT_IMAGE;
		$display_width = (int)(($display_height/$inputFile_height)*$inputFile_width);
		RecordCommand(" $display_height $display_width $clientX1 $clientX2 $clientY1 $clientY2");
		if (($clientX1 == 0) && ($clientX2 == 0) && ($clientY1 == 0) && ($clientY2 == 0))
		{
			$clientY2 = $display_height;
			$clientX2 = $display_width;
		}

		$clientX1 = (int)(($inputFile_width/$display_width) * $clientX1);
		$clientY1 = (int)(($inputFile_height/$display_height) * $clientY1);
		$clientX2 = (int)(($inputFile_width/$display_width) * $clientX2);
		$clientY2 = (int)(($inputFile_height/$display_height) * $clientY2);

		$w = $clientX2 - $clientX1;
		$h = $clientY2 - $clientY1;

		$patternFileDir = ResizeImage($patternFileDir,$w,$h,TRUE);
        $patternAnimList = GetAnimatedImages($patternFileDir);
		GetImageAttributes($patternFileDir,$w,$h,$size);

		$x = $clientX1;
		$y = $clientY1;
		$options = "-geometry +$x+$y";

		$patternAnimList = GetAnimatedImages($patternFileDir);
		$count = count($patternAnimList);
		break;
	case 'OVERLAY':	// pattern is same size as target
		if ($isAnimatedPattern == TRUE) 
		{
			$patternFileDir = ResizeImage($patternFileDir,$w,$h,TRUE);
			$patternAnimList = GetAnimatedImages($patternFileDir);
		}
		else
		{
			$patternFileDir = ResizeImage($patternFileDir,$inputFile_width,$inputFile_height,TRUE);
		}

		$patternAnimList = GetAnimatedImages($patternFileDir);
		$count = count($patternAnimList);
		break;
	case 'TILED':	// tiled pattern, small tile size
		$patternAnimList = GetAnimatedImages($patternFileDir);
		if ($isAnimatedPattern == FALSE) 
			$patternFileDir = GenerateTiledPattern($patternFileDir);
		else
		{
			foreach ($patternAnimList as $patternFileDir)
			{
				//RecordCommand(" $patternFileDir");
				$temp[] = GenerateTiledPattern($patternFileDir);
			}
			$patternAnimList = $temp;
			$count = count($patternAnimList);
		}
		break;
    case 'BORDER':
		RecordCommand("BORDER 1");
		$patternAnimList = GetAnimatedImages($patternFileDir);
		RecordCommand("BORDER 2 $isAnimatedPattern");
        if ($isAnimatedPattern == FALSE)
		{
			RecordCommand("temp NonAnim GenerateBorder");
            $patternFileDir = GenerateBorder($patternFileDir);
		}
        else
        {
			RecordCommand("BORDER 3");
            foreach ($patternAnimList as $patternFileDir)
            {
                $temp[] = GenerateBorder($patternFileDir);
				RecordCommand("temp Anim GenerateBorder");
            }
            $patternAnimList = $temp;
			$count = count($patternAnimList);
        }
	}  // end switch 


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
            $command = "composite $options -blend $dissolve $patternFileDir $imageFileDir $outputFileDir";
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            $AnimateString .= "$outputFileDir ";
			}
		}
		else  //pattern is ALSO animated 
		{
			foreach ($inputAnimList as $imageFileDir)
			{
            $patternFileDir = $patternAnimList[$i];
	        $outputFileName = NewName($inputFileDir);
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
	        $outputFilePath = "$CONVERT_PATH$outputFileName";
            $command = "composite $options -blend $dissolve $patternFileDir $imageFileDir $outputFileDir";
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
	    RecordCommand(" FINAL $outputFilePath $command");
    }
    else //input image is NOT animated
    {
		RecordCommand("NOT Anim");
		if ($isAnimatedPattern == FALSE) // pattern is not an animation
        {
	        RecordCommand(" PATTERN SIMPLE");

            //simplest case, non-animated pattern and non-animated input
	        $outputFileName = NewName($inputFileDir);
	        $outputFileDir = "$CONVERT_DIR$outputFileName";
	        $outputFilePath = "$CONVERT_PATH$outputFileName";
            $command = "composite $options -blend $dissolve $patternFileDir $inputFileDir $outputFileDir";
            //$command = "composite $options  $patternFileDir $inputFileDir $outputFileDir";
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        RecordCommand(" $command");
	        RecordCommand(" FINAL $outputFilePath $command");
        }
        else  //the pattern is an animation
        {
	        RecordCommand(" PATTERN ANIMATE");
            foreach ($patternAnimList as $patternFileDir)
            {
	            $outputFileName = NewName($inputFileDir);
	            $outputFileDir = "$CONVERT_DIR$outputFileName";
                $command = "composite $options -blend $dissolve $patternFileDir $inputFileDir $outputFileDir";
	            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
                $AnimateString .= "$outputFileDir ";
	            //RecordCommand(" $command");
            }

            //now re-animate the morphed images
            $targetName = NewNameGIF();
            $outputFileDir = "$CONVERT_DIR$targetName";
            $outputFilePath = "$CONVERT_PATH$targetName";
            $command = "convert -dispose previous -delay 25  %FILES -loop 0 $outputFileDir";
            $command = str_replace("%FILES", $AnimateString, $command);
	        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        RecordCommand(" FINAL $outputFilePath");
        }
    }

$outputFilePath = CheckFileSize($outputFileDir);
RecordAndComplete("BLEND",$outputFilePath,FALSE);
}	//DEV END UPLOAD SUCCESS


function GenerateBorder($patternFileDir)
{
global $BASE_DIR;
global $CONVERT_DIR;
global $inputFile_width;
global $inputFile_height;

		$w =  ($inputFile_width > 400) ? 100 : $inputFile_width / 5;
		$h =  ($inputFile_height > 400) ? 100 : $inputFile_height / 5;
		$patternFileDir = ResizeImage($patternFileDir,$w,$h,FALSE);
		GetImageAttributes($patternFileDir,$w,$h,$size);

		$hole_width = $inputFile_width - ($w * 2);
		$hole_height = $inputFile_height - ($h * 2);
		$rectImage = "$BASE_DIR/wimages/tools/rect-white.gif";
		$rectImage = ResizeImage($rectImage,$hole_width,$hole_height,TRUE);
        $dimensions = "$inputFile_width"."x"."$inputFile_height";
	    $outputFileName = TMPName("temp.gif");
	    $outputFileDir = "$CONVERT_DIR$outputFileName";
        $command = "convert -size $dimensions tile:$patternFileDir $outputFileDir";
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    RecordCommand(" $command");
        $patternFileDir = $outputFileDir;

	    $outputFileName = TMPName("temp.gif");
	    $outputFileDir = "$CONVERT_DIR$outputFileName";
        $command = "composite -geometry +$w+$h $rectImage $patternFileDir $outputFileDir";
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    RecordCommand(" $command");
        $patternFileDir = $outputFileDir;

	    $outputFileName = TMPName("temp.gif");
	    $outputFileDir = "$CONVERT_DIR$outputFileName";
        $command = "convert -transparent white $patternFileDir $outputFileDir";
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    RecordCommand(" $command");
        return $outputFileDir;
}


function GenerateTiledPattern($patternFileDir)
{
global $CONVERT_DIR;
global $inputFile_width;
global $inputFile_height;

	$w =  ($inputFile_width > 400) ? 100 : $inputFile_width / 5;
	$y =  ($inputFile_height > 400) ? 100 : $inputFile_height / 5;
	$patternFileDir = ResizeImage($patternFileDir,$w,$h,FALSE);
	$dimensions = "$inputFile_width"."x"."$inputFile_height";
	$outputFileName = TMPName("temp.gif");
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "convert -size $dimensions tile:$patternFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
	$patternFileDir = $outputFileDir;
	return $patternFileDir;
}

?>
