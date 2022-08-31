<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_MOTIVATIONALPOSTER;
$Title = $X_MOTIVATIONALPOSTER;

	//get the command parameters
	$orient = $_POST['ORIENTATION'];
	$border = $_POST['BORDER'];


	$titleColor = $_POST['TITLECOLOR'];
	$textColor = $_POST['TEXTCOLOR'];
	$backgroundColor = $_POST['BACKGROUNDCOLOR'];
    $font = $_POST['SETTING'];
    $font = str_replace(".png", "", $font);

	$title = $_POST['TITLE'];
	$text1 = $_POST['TEXT1'];
	$text2 = $_POST['TEXT2'];

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	//$inputFileDir = ConvertToJPG($inputFileDir);
    $inputFileName = basename($inputFileDir);


    $titleColor = str_replace("#", "", $titleColor);
    $textColor = str_replace("#", "", $textColor);
    $backgroundColor = str_replace("#", "", $backgroundColor);

	switch ($border)
	{
	case 'DOUBLE':
		$poster = ($orient == 'landscape') ? 'motlanddouble.jpg' : 'motportdouble.jpg';
		break;
	case 'FANCY':
		$poster = ($orient == 'landscape') ? 'motlandfancy.jpg' : 'motportfancy.jpg';
		break;
	case 'SINGLE':
		$poster = ($orient == 'landscape') ? 'motlandsingle.jpg' : 'motportsingle.jpg';
		break;
	default:
        $poster = ($orient == 'landscape') ? 'motlandsingle.jpg' : 'motportsingle.jpg';
        break;
	}
	$posterDir = "$BASE_DIR/wimages/posters/$poster";

	// set the poster colors
	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "convert -fuzz 10% -fill \"$backgroundColor\" -opaque \"#000000\" $posterDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XPOSTER $command");
	$posterDir = $outputFileDir;

    $hash = "";
    if (ctype_xdigit($titleColor) == TRUE)
        $hash = "#";

	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "convert -fuzz 16% -fill \"$hash$titleColor\" -opaque \"#ffffff\" $posterDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XPOSTER $command");
	$posterDir = $outputFileDir;


	switch ($orient)
	{
	case  'landscape':
		$w = 600;
		$h = 400;
		$geo = "+75+50";
		break;
	case  'portrait':
		$w = 490;
		$h = 540;
		$geo = "+55+55";
		break;
	default:
		$w = 490;
		$h = 540;
		$geo = "+55+55";
		break;
	}

	$inputFileDir = ResizeImage($inputFileDir,$w,$h,TRUE);
    $inputFileName = basename($inputFileDir);

	$isAnimated = FALSE;
	if (IsAnimatedGIF($inputFileDir) == TRUE)    
	{
		$imageList = GetAnimatedImages($inputFileDir);
		$isAnimated = TRUE;
	}

	//$font = "Lucida";
	$font = "$FONT_DIR$font";

	if ($isAnimated == TRUE)
	{
		$AnimateString = "";
		foreach ($imageList as $imageFileDir)
		{
			$outputFileName = CreatePosterImage($geo, $posterDir,
				$title,$text1,$text2,$font,
				$backgroundColor, $titleColor, $textColor,
				$pointSizeTitle,$pointSizeText,
				$imageFileDir);
			$outputFileDir = "$CONVERT_DIR$outputFileName";
            $AnimateString .= "$outputFileDir ";
		}

		// rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

	}
	else
	{
		$outputFileName = CreatePosterImage($geo, $posterDir,
			$title,$text1,$text2,$font,
			$backgroundColor, $titleColor, $textColor,
			$pointSizeTitle,$pointSizeText,
			$inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
	}

    //resize of result is too humongous
    $inputFileDir = $outputFileDir;
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 900000)
	{
		if (($real_width > 400) || ($real_height > 400))
		{
        $inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
        $targetName = basename($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        RecordCommand("XPOSTER FINAL RESIZE $outputFilePath");
		}
	}
	RecordCommand("XPOSTER FINAL $outputFilePath");

	RecordAndComplete("POSTER",$outputFilePath,FALSE);


function CreatePosterImage($geo, $posterDir,
	$title,$text1,$text2, $font,
	$backgroundColor, $titleColor, $textColor,
	$pointSizeTitle,$pointSizeText,
	$inputFileDir)
{
	global $CONVERT_DIR;

	$inputFileName = basename($inputFileDir);

	$outputFileName = TMPName($inputFileName);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "composite -geometry $geo $inputFileDir $posterDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XPOSTER $command");

    $hasht = "";
    if (ctype_xdigit($titleColor) == TRUE)
        $hasht = "#";
    $hashb = "";
    if (ctype_xdigit($backgroundColor) == TRUE)
        $hashb = "#";
    $hashtx = "";
    if (ctype_xdigit($textColor) == TRUE)
        $hashtx = "#";

	$len = strlen($title);
	$pointSizeTitle = ($len < 10) ? 90 : 60;
	if ($len  > 0)
	{
	$inputFileDir = $outputFileDir;
    $outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "montage -background \"$hashb$backgroundColor\" -fill \"$hasht$titleColor\" -geometry +0+0 -font $font -pointsize $pointSizeTitle -label \"$title\" $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XPOSTER $command");
	}

	$len1 = strlen($text1);
	$len2 = strlen($text2);
	$len = ($len1 > $len2) ? $len1 : $len2;
	$pointSizeText = ($len < 20) ? 50 : 30;
	
	$len = strlen($text1);
	if ($len > 0)
	{
	$inputFileDir = $outputFileDir;
    $outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "montage -background \"$hashb$backgroundColor\" -fill \"$hashtx$textColor\" -geometry +0+0 -font $font -pointsize $pointSizeText -label \"$text1\" $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XPOSTER $command");
	}

	$len = strlen($text2);
	if ($len > 0)
	{
	$inputFileDir = $outputFileDir;
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "montage -background \"$hashb$backgroundColor\" -fill \"$hashtx$textColor\" -geometry +0+0 -font $font -pointsize $pointSizeText -label \"$text2\" $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XPOSTER $command");
	}
	return $outputFileName;
}

?>
