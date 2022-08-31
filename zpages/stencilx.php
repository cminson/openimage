<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_STENCIL;


	$color = $_POST['COLOR'];
	$font = $_POST['FONT'];
	$font = "arialbd";
	$setting = $_POST['SETTING'];
	$label1 = $_POST['LABEL1'];
	$label2 = $_POST['LABEL2'];

    if (isset($color) == FALSE)
        $color = "#ffffff";
    if (isset($setting) == FALSE)
        $setting = "01";
	$setting = StripSuffix($setting);

    if (isset($color) == FALSE)
        $color = "#ffffff";
    if (isset($font) == FALSE)
        $font = "arialuni";

    $color = str_replace("#", "", $color);
    $hash = "";
    if (ctype_xdigit($color) == TRUE)
        $hash = "#";

	if (NonAsciiLanguage() == TRUE)
	{
        $font = "arialuni";
	}


	$font = "$FONT_DIR$font".".ttf";

	$label = "$label1";
	if (strlen($label2) > 0)
        $label .= "\n$label2";



    $label = EscapeSpecialChars($label);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);

    $isAnimated = IsAnimatedGIF($inputFileDir);
	if ($setting != "01")
	{ 
		if ($isAnimated == TRUE)
			$inputFileDir = ConvertToJPG($inputFileDir);
		$inputFileDir = AnimateImage($inputFileDir,$setting);
	}

	$trancolor = ($color == "000000") ? "#ffffff" : "#000000";
	$outputFileName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "convert -background \"$hash$color\" -fill \"$trancolor\" -font $font -pointsize 500 label:\"$label\" $outputFileDir";
	$command = "convert -background \"$hash$color\" -fill \"$trancolor\" -font $font -size 900x label:\"$label\" $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("XSTENCIL $command");
	$stencilFileDir = $outputFileDir;


	$outputFileName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$command = "convert -transparent \"$trancolor\" $stencilFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$stencilFileDir = $outputFileDir;
	RecordCommand("XSTENCIL $command");

	$stencilFileDir = ResizeImage($stencilFileDir,$real_width,$real_height,FALSE);
    GetImageAttributes($stencilFileDir,$stencil_width,$stencil_height,$size);

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
        RecordCommand("$command");
	}
	else
	{
		$outputFileDir = StampText($inputFileDir);
	}

    $inputFileDir = $outputFileDir;
	RecordCommand("$outputFileDir");
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 600000)
	{
		if (($real_width > 400) || ($real_height > 400))
		{
		$inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
		$targetName = basename($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$targetName";
		$outputFilePath = "$CONVERT_PATH$targetName";
		RecordCommand("RESIZE $outputFilePath");
		}
	}
	RecordCommand("FINAL $outputFilePath");

	RecordAndComplete("STENCIL",$outputFilePath,FALSE);

function StampText($inputFileDir)
{
global $gravity, $real_width, $real_height,$stencil_width,$stencil_height;
global $CONVERT_DIR, $CONVERT_PATH;
global $stencilFileDir;
global $outputFilePath;

	$inputFileDir = ResizeImage($inputFileDir,$stencil_width,$stencil_height,TRUE);
	$outputFileName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";


    $command = "composite $stencilFileDir $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");
	return $outputFileDir;
}


function AnimateImage($inputFileDir,$setting)
{
global $CONVERT_DIR, $real_height, $real_width;

if ($setting == "02")
{
    $height = 0;
    $inc = $real_height / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPJPG();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -roll +0+$height";
        $command = "$command $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
        $height += $inc;
    }
}
else if ($setting == "03")
{
    $height = 0;
    $inc = $real_height / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPJPG();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -roll +0-$height";
        $command = "$command $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
        $height += $inc;
    }
}
else if ($setting == "04")
{
    $width = 0;
    $inc = $real_width / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPJPG();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -roll -$width+0";
        $command = "$command $inputFileDir $outputFileDir";
		RecordCommand("SCROLL $command");
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
        $width += $inc;
    }
}
else if ($setting == "05")
{
    $width = 0;
    $inc = $real_width / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPJPG();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -roll +$width+0";
        $command = "$command $inputFileDir $outputFileDir";
		RecordCommand("SCROLL $command");
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
        $width += $inc;
    }
}
else if ($setting == "06")
{
    $width = 0;
    $height = 0;
    $incx = $real_width / 9;
    $incy = $real_height / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPJPG();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -roll -$width-$height";
        $command = "$command $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
        $width += $incx;
        $height += $incy;
    }
}
else if ($setting == "07")
{
    $width = 0;
    $height = 0;
    $incx = $real_width / 9;
    $incy = $real_height / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPJPG();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -roll +$width+$height";
        $command = "$command $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
        $width += $incx;
        $height += $incy;
    }
}


$targetName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert $specialArg -dispose previous -delay 25 $FileList -loop 0 $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");
return $outputFileDir;

}

?>
