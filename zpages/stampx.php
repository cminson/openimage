<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_STAMPTEXT;



	$labelColor = $_POST['LABELCOLOR'];
	$gravity = $_POST['POSITION'];
	$pointSize = $_POST['FONTSIZE'];
	$font = $_POST['SETTING'];
    $font = str_replace(".png", "", $font);
	$label1 = $_POST['LABEL1'];
	$label2 = $_POST['LABEL2'];
	$label3 = $_POST['LABEL3'];
	$label4 = $_POST['LABEL4'];
	$label5 = $_POST['LABEL5'];
	$label6 = $_POST['LABEL6'];
	$tile = $_POST['TILE'];
	$form = $_POST['FORM'];


    if (isset($labelColor) == FALSE)
        $labelColor = "#ff0000";
    if (isset($font) == FALSE)
        $font = "arialuni";
    if (isset($pointSize) == FALSE)
        $pointSize = 10;
    if (isset($gravity) == FALSE)
        $gravity = 'South';

    $labelColor = str_replace("#", "", $labelColor);

	if (NonAsciiLanguage() == TRUE)
	{
        $font = "arialuni";
	}


	$font = "$FONT_DIR$font";
    //$labelColor = "white";

	$label = "$label1";
	if (strlen($label2) > 0)
        $label .= "\n$label2";
	if (strlen($label3) > 0)
        $label .= "\n$label3";
	if (strlen($label4) > 0)
        $label .= "\n$label4";
	if (strlen($label5) > 0)
        $label .= "\n$label5";
	if (strlen($label6) > 0)
        $label .= "\n$label6";

    $label = EscapeSpecialChars($label);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);

    if (IsAnimatedGIF($inputFileDir) == TRUE)
	{
        $imageList = GetAnimatedImages($inputFileDir);
        $i = 0;
        $AnimateString = "";

        // label each image in animation
        foreach ($imageList as $imageFileDir)
        {
			$outputFileDir = StampText($imageFileDir);
            $AnimateString .= "$outputFileDir ";
        }

        // rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        //RecordCommand("XSTAMP FINAL $command");
	}
	else
	{
		$outputFileDir = StampText($inputFileDir);
	}

    $inputFileDir = $outputFileDir;
	RecordCommand("XSTAMP $outputFileDir");
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 600000)
	{
		if (($real_width > 400) || ($real_height > 400))
		{
		$inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
		$targetName = basename($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$targetName";
		$outputFilePath = "$CONVERT_PATH$targetName";
		RecordCommand("XSTAMP FINAL RESIZE $outputFilePath");
		}
	}
	RecordCommand("XSTAMP FINAL $outputFilePath");

	RecordAndComplete("STAMP",$outputFilePath,FALSE);

function StampText($inputFileDir)
{
global $gravity, $real_width, $real_height, $pointDSize, $labelClor, $label,$labelColor,$font,$pointSize;
global $CONVERT_DIR, $CONVERT_PATH;
global $outputFilePath;

    $inputFileName = basename($inputFileDir);

    $hash = "";
    if (ctype_xdigit($labelColor) == TRUE)
        $hash = "#";

    //make the transparency
	$outputFileName = NewNamePNG();
	$outputFileName = "CJM-IAZA$outputFileName";
	$outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "convert -gravity %GRAVITY -size %WIDTHx%HEIGHT xc:transparent -font %FONT -pointsize %POINTSIZE  -fill \"%LABELCOLOR\" -annotate +0+0 \"%LABEL\"";
	$command = str_replace("%GRAVITY", "$gravity", $command);
	$command = str_replace("%WIDTH", "$real_width", $command);
	$command = str_replace("%HEIGHT", "$real_height", $command);
	$command = str_replace("%POINTSIZE", "\"$pointSize\"", $command);
	$command = str_replace("%FONT", "\"$font\"", $command);
	$command = str_replace("%LABELCOLOR", "$hash$labelColor", $command);
	$command = str_replace("%LABEL", "$label", $command);

	$command = "$command $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $trans = $outputFileDir;
	RecordCommand("XSTAMP TRANS $command");

    //make the mask
	$outputFileName = NewNamePNG();
	$outputFileName = "CJM-IAZA$outputFileName";
	$outputFileDir = "$CONVERT_DIR$outputFileName";

    $command = "convert -gravity %GRAVITY -size %WIDTHx%HEIGHT xc:black -font %FONT -pointsize %POINTSIZE  -fill white -annotate +0+0 '%LABEL'";
	$command = str_replace("%GRAVITY", "$gravity", $command);
	$command = str_replace("%WIDTH", "$real_width", $command);
	$command = str_replace("%HEIGHT", "$real_height", $command);
	$command = str_replace("%POINTSIZE", "\"$pointSize\"", $command);
	$command = str_replace("%FONT", "\"$font\"", $command);
	$command = str_replace("%LABEL", "$label", $command);
	$command = "$command $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $mask = $outputFileDir;
	RecordCommand("XSTAMP MASK $command");

    //now stamp the text
	//$outputFileName = NewNamePNG();
	$outputFileName = NewNameJPG(); // CJM DEV
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "composite $trans $inputFileDir $mask $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XSTAMP $command");

    //resize of result is too humongous
    $inputFileDir = $outputFileDir;
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 100000)
    {
	    $targetName = NewNameJPG();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -quality 50 $inputFileDir $outputFileDir";
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    RecordCommand("XSTAMP RESIZE $command");
    }

	return $outputFileDir;
}

?>
