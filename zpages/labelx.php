<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_BORDERTEXT;
	$labelColor = $_POST['LABELCOLOR'];
	$backgroundColor = $_POST['BACKGROUNDCOLOR'];
	$labelColor = str_replace("#", "", $labelColor);
	$backgroundColor = str_replace("#", "", $backgroundColor);
	$pointSize = $_POST['FONTSIZE'];
	$font = $_POST['SETTING'];
	$font = str_replace(".png", "", $font);
	$label1 = $_POST['LABEL1'];
	$label2 = $_POST['LABEL2'];

	if (isset($labelColor) == FALSE)
		$labelColor = "#ff0000";
	if (isset($backgroundColor) == FALSE)
		$backgroundColor = "#ff0000";
	if (isset($font) == FALSE)
		$font = "arialuni";
	if (isset($pointSize) == FALSE)
		$pointSize = 10;


	if ($LN == 'vn')
        $font = "arialuni";
    if ($LN == 'hi')
        $font = "arialuni";
    if ($LN == 'ja')
        $font = "arialuni";
    if ($LN == 'ch')
        $font = "arialuni";

	//CJM
	//$font = "$FONT_DIR$font";
	$font = "$FONT_DIR$font";

    if (strlen($label2) > 0)
        $label = "$label1\n$label2";
    else
        $label = "$label1";

	//$label = preg_replace("/^[^a-z0-9]?(.*?)[^a-z0-9]?$/i", "$1", $label);
	//$label= preg_replace('/[^\w\d_ -]/si', '', $label);
    //$label = EscapeSpecialChars($label);
	$border = $_POST['BORDER'];

	//build up the input and output paths
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

	// build up command string
	$hashl = $hashb = "";
	if (ctype_xdigit($labelColor) == TRUE)
		$hashl = "#";
	if (ctype_xdigit($backgroundColor) == TRUE)
		$hashb = "#";

	// CJM
	//$font = "Times-BoldItalic";
	//$font = "Bookman-LightItalic";
	$command = "montage -background \"$hashb$backgroundColor\" -fill \"$hashl$labelColor\" -geometry +0+0 -font $font -pointsize $pointSize -label \"$label\"";
    RecordCommand($commmand);

	//if animated images, do things differently than if non-animated...
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $i = 0;
		$AnimateString = "";

		// label each image in animation
        foreach ($imageList as $imageFileDir)
        {
			$outputFileName = NewName($inputFileDir);
			$outputFileDir = "$CONVERT_DIR$outputFileName";
			$acommand = "$command $imageFileDir $outputFileDir";
			RecordCommand("XLABEL ANIM $acommand");
			$execResult = exec("$acommand 2>&1", $lines, $ConvertResultCode);
            $AnimateString .= "$outputFileDir ";
		}

		// rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XLABEL $command");
		RecordCommand("XLABEL FINAL $outputFilePath");
	}
	else	// non-animated
	{
		$outputFileName = NewName($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";

		$command = "$command $inputFileDir $outputFileDir";
		RecordCommand("XLABEL $command");
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XLABEL FINAL $outputFilePath");
	}



$outputFilePath = CheckFileSize($outputFileDir);
RecordAndComplete("LABEL",$outputFilePath,FALSE);

?>
