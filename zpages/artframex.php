<?php
include '../zcommon/common.inc';


if (CompleteWithNoAction()) return;


$Title = $X_ARTISTICFRAME;
$LastOperation =$X_ARTISTICFRAME;

$current = $_POST['CURRENTFILE'];


$Setting = $_POST['SETTING'];
RecordCommand("ENTER $Setting");

$Setting = strtolower($Setting);
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

if (stristr($Setting,"tran") != FALSE)
{
		$inputFileDir = ConvertToJPG($inputFileDir);
        RecordCommand(" JPG CONVERT $outputFileDir");
}

    $inputFileName = basename($inputFileDir);

    $isAnimated = FALSE;
	if (IsAnimatedGIF($inputFileDir) == TRUE)
	{
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
	}

    GetImageAttributes($inputFileDir,$width,$height,$size);
    if ($isAnimated == TRUE)
    {
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
            $outputFileName = FrameImage($imageFileDir,$width,$height);
            $outputFileDir = "$CONVERT_DIR$outputFileName";
            RecordCommand(" ANIM $outputFileDir");
            $AnimateString .= "$outputFileDir ";
        }

        // rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
		$command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" REBUILD ANIM $command");
    }
    else
    {
        $outputFileName = FrameImage($inputFileDir, $width, $height);
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
    }

    GetImageAttributes($outputFileDir,$width,$height,$size);
    if ($size > 800000)
    {
        if (($width > 300) || (height > 300))
        {
            $width = $height = 300;
            RecordCommand(" WILL RESIZE  $outputFileDir");
            $outputFileDir = ResizeImage($outputFileDir,$width,$height,FALSE);
            $outputFileName = basename($outputFileDir);
            $outputFileDir = "$CONVERT_DIR$outputFileName";
            $outputFilePath = "$CONVERT_PATH$outputFileName";
            RecordCommand(" RESIZED $outputFileDir");
        }
    }

	RecordCommand(" FINAL $outputFilePath");


    RecordAndComplete("ARTFRAME",$outputFilePath,FALSE);


function FrameImage($inputFileDir, $width, $height)
{
global $CONVERT_DIR, $GIFSUFFIX, $Setting;
global $BASE_DIR;

	$TARPOST_DIR = "$BASE_DIR/wimages/jigsaws/";

	$setting = $Setting;
	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";


	//$setting="one-gold3";

	//RecordCommand(" FRAMEIMAGE SETTING = $setting");
	if (stristr($setting,"simple") != FALSE)
	{
		$file = str_replace("simple-","",$setting);
		$file = str_replace(".jpg","",$file);
		$width += 28;
		$tile = "$TARPOST_DIR$file$GIFSUFFIX";
		$command = "convert $inputFileDir \( -size $height"."x14  -tile-offset +100+0 tile:$tile -transpose \) \( -size $height"."x14  -tile-offset +0+0  tile:$tile -transpose \) -swap 0,1 +append \( -size $width"."x14 -tile-offset +140+0 tile:$tile \) \( -size $width"."x14 -tile-offset +50+0 tile:$tile \) -swap 0,1 -append $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	}
	else if (stristr($setting,"art") != FALSE)
	{
		$file = str_replace("art-","",$setting);
		$file = str_replace(".jpg","",$file);
		$command = "/var/www/christopherminson/httpdocs/openimage/zshells/picframe.sh -f $file -m 20 -b 1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	}
	else if (stristr($setting,"newpicframe") != FALSE)
	{
		$file = str_replace("newpicframe-","",$setting);
		$command = "../zshells/newpicframe.sh -f $file -m 20 -b 1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	}
	else if (stristr($setting,"tran") != FALSE)
	{
		$setting = str_replace("tran-","",$setting);
		$setting = str_replace(".jpg","",$setting);
	    $command = "../zshells/imageborder.sh -b 3 -m $setting -p 30 -r white -t 1 -e edge $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	}
	else if (stristr($setting,"color") != FALSE)
	{
		$setting = str_replace("color-","",$setting);
	    $command = "convert -mattecolor $setting -frame 13x13+5+5 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	}
	else if (stristr($setting,"one-gold1") != FALSE)
	{
		$frameDir = "$BASE_DIR/wimages/frames/one-gold1.jpg";
	    $command = "convert -resize 307x398! $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" command=$command");
		$inputFileDir = $outputFileDir;
		$outputFileName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
	    $command = "composite -geometry +125+123 $inputFileDir $frameDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" command=$command");
	}
	else if (stristr($setting,"one-gold2") != FALSE)
	{
		$frameDir = "$BASE_DIR/wimages/frames/one-gold2.jpg";
	    $command = "convert -resize 490x578! $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" command=$command");
		$inputFileDir = $outputFileDir;
		$outputFileName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
	    $command = "composite -geometry +32+32 $inputFileDir $frameDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" command=$command");
	}
	else if (stristr($setting,"one-gold3") != FALSE)
	{
		$frameDir = "$BASE_DIR/wimages/frames/one-gold3.jpg";
	    $command = "convert -resize 337x438! $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("command=$command");
		$inputFileDir = $outputFileDir;
		$outputFileName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
	    $command = "composite -geometry +110+103 $inputFileDir $frameDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("command=$command");
	}
	else if (stristr($setting,"one-silver1") != FALSE)
	{
		$frameDir = "$BASE_DIR/wimages/frames/one-silver1.jpg";
	    $command = "convert -resize 385x418! $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("command=$command");
		$inputFileDir = $outputFileDir;
		$outputFileName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
	    $command = "composite -geometry +74+100 $inputFileDir $frameDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("command=$command");
	}
	else if (stristr($setting,"one-silver2") != FALSE)
	{
		$frameDir = "$BASE_DIR/wimages/frames/one-silver2.jpg";
	    $command = "convert -resize 307x398! $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("command=$command");
		$inputFileDir = $outputFileDir;
		$outputFileName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
	    $command = "composite -geometry +125+123 $inputFileDir $frameDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("command=$command");
	}
	RecordCommand("$command");

	$outputFileName = basename($outputFileDir);
	RecordCommand("RETURN $outputFileName");
	return $outputFileName;
}
