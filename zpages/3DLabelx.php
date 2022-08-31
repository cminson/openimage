<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$FONT_DIR = "$BASE_DIR/wimages/fonts/";
$LastOperation = "3D $X_LABEL";
$Title = "3D $X_LABEL";

	$pointSize = $_POST['FONTSIZE'];
	$gravity = $_POST['GRAVITY'];
	$font = $_POST['SETTING'];
    	$font = str_replace(".png", "", $font);
	$intensity = $_POST['INTENSITY'];
	$label1 = $_POST['LABEL1'];
	$label2 = $_POST['LABEL2'];


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

	$font = "$FONT_DIR$font";

    if (strlen($label2) > 0)
        $label = "$label1\n$label2";
    else
        $label = "$label1";

	if (strlen($label) <= 0)
		$label = " ";

	$label = "\"$label\"";

	//build up the input and output paths
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	GetImageAttributes($inputFileDir,$w,$h,$size);

	// build up command string
	//if animated images, do things differently than if non-animated...
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $i = 0;
		$AnimateString = "";

		// label each image in animation
        foreach ($imageList as $imageFileDir)
        {
			$outputFileName = TMPJPG();
			$outputFileDir = "$CONVERT_DIR$outputFileName";
			$command = "../zshells/bumptext.sh -i $intensity -g $gravity -t $label -f $font -s $w"."x"."$pointSize $imageFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("XLABEL ANIM $command");
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
		$command = "../zshells/bumptext.sh -i $intensity -g $gravity -t $label -f $font -s $w"."x"."$pointSize $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XLABEL $command");
		RecordCommand("XLABEL FINAL $outputFilePath");
	}


	$outputFilePath = CheckFileSize($outputFileDir);

	RecordAndComplete("LABEL",$outputFilePath,FALSE);


?>
