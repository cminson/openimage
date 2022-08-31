<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_GLITTERTEXT;
$DEFAULT="gold14.gif";

$TRANSCOLOR="#0400A0";
$TRANSCOLOR="#00FE01";

$EX_DIR = "$BASE_DIR/wimages/examples/glitters/";
$EX_PATH = "$BASE_PATH/wimages/examples/glitters/";

	$labelColor = $_POST['LABELCOLOR'];
	$gravity = $_POST['POSITION'];
	$pointSize = $_POST['FONTSIZE'];
	$label1 = $_POST['LABEL1'];
	$label2 = $_POST['LABEL2'];
	$glitter = $_POST['SETTING'];

	if (isset($labelColor) == FALSE)
		$labelColor = 'red';
	if (isset($position) == FALSE)
		$position = 'TOP';
	if (isset($pointSize) == FALSE)
		$pointSize = 30;
	if (isset($glitter) == FALSE)
		$glitter = 'gold14.gif';


	$font = "timesbd.ttf";
	$font = "$FONT_DIR$font";

    $glitter = StripSuffix($glitter);
    $glitterFileDir = "$EX_DIR$glitter$GIFSUFFIX";

	RecordCommand("XGLITTER: Chosen glitter = $glitter");
    if (strlen($label2) > 0)
        $label = "$label1\n$label2";
    else
        $label = "$label1";

	$label = str_replace("'","",$label);

    //get size of image
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);
    GetImageAttributes($inputFileDir,$width,$height,$size);

	// resize the glitter to image size
	$outputFileName = TMPGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
    RecordCommand("XGLITTERTEXT $outputFileDir ");
	$dimension = "$width"."x"."$height";
	$command = "convert $glitterFileDir -virtual-pixel tile -set option:distort:viewport $dimension -distort SRT 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XGLITTERTEXT $command ");
	$glitterFileDir = $outputFileDir;

	if (IsAnimatedGIF($inputFileDir) == TRUE)
	{
		$sourceImageList = GetAnimatedImages($inputFileDir);
		$glitterImageList = GetAnimatedImages($glitterFileDir);
		$glitterCount = count($glitterImageList);
        $i = 0;
        foreach ($sourceImageList as $sourceImage)
        {
			$glitterImage = $glitterImageList[$i];
			$i++;
			if ($i >= $glitterCount)
				$i = 0;

            $outputFileName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$outputFileName";

			$command = "convert $sourceImage -gravity $gravity -font '$font' -pointsize $pointSize -fill '$TRANSCOLOR' -stroke '$labelColor' -strokewidth 1 -annotate 0 '$label' $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            RecordCommand("XGLITTERTEXT $command ");
			$inputFileDir = $outputFileDir;

            $outputFileName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$outputFileName";
			$command = "convert -transparent '$TRANSCOLOR' $inputFileDir $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            RecordCommand("XGLITTERTEXT $command ");
			$inputFileDir = $outputFileDir;

			//now compose
            $outputFileName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$outputFileName";
			$command = "composite $inputFileDir $glitterImage $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

            $AnimateString .= "$outputFileDir ";
            RecordCommand("XGLITTERTEXT $command ");
		}
	}
	else	// source not animated
	{
		$glitterImageList = GetAnimatedImages($glitterFileDir);
        $i = 0;
		$count = count($glitterImageList);
		RecordCommand("XGLITTERTEXT Not Anim $count $glitterFileDir");
        foreach ($glitterImageList as $glitterImage)
        {

            $outputFileName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$outputFileName";

			$command = "convert $inputFileDir -gravity $gravity -font '$font' -pointsize $pointSize -fill '$TRANSCOLOR' -stroke '$labelColor' -strokewidth 1 -annotate 0 '$label' $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            RecordCommand("XGLITTERTEXT $command ");
			$inputFileDir = $outputFileDir;

            $outputFileName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$outputFileName";
			$command = "convert -transparent '$TRANSCOLOR' $inputFileDir $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            RecordCommand("XGLITTERTEXT $command ");
			$inputFileDir = $outputFileDir;

			//now compose
            $outputFileName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$outputFileName";
			$command = "composite $inputFileDir $glitterImage $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

            $AnimateString .= "$outputFileDir ";
            RecordCommand("XGLITTERTEXT $command ");
        }
	}

	// now animate the resulting image list
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimateString -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XGLITTERTEXT $command");
    RecordCommand("XGLITTERTEXT FINAL $outputFilePath");

    //finally, reduce size before redirecting
    $inputFileDir = $outputFileDir;
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 300000)
    {
        if (($real_width > 600) || ($real_height > 600))
        {
            RecordCommand("XPIMP RESIZED $size $outputFileDir");
            $inputFileDir = ResizeImage($inputFileDir,600,600,FALSE);
            $targetName = basename($inputFileDir);
            $outputFilePath = "$CONVERT_PATH$targetName";
        }
    }



RecordAndComplete("GLITTETEXT",$outputFilePath,FALSE);
?>
