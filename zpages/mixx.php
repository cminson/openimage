<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$Title = $LastOperation = $X_WORLDMIX;

$TEXTURE_DIR = "$BASE_DIR/wimages/textures/";
$DEFAULT="MIXMAG1.jpg";
$effectFileDir = "$BASE_DIR/wimages/blends/MIXMAG1.jpg";

	//get the command parameters
	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    $isAnimated = FALSE;
	RecordCommand("$inputFileDir");
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
    }


	if ($isAnimated == TRUE)
    {
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
            $outputFileName = MixImage($imageFileDir);
            $outputFileDir = "$CONVERT_DIR$outputFileName";
			RecordCommand("ANIM $outputFileDir");
            $AnimateString .= "$outputFileDir ";
        }

        // rebuild animation
        $outputFileName = NewNameGIF($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    }
	else
	{
        $outputFileName = MixImage($inputFileDir);
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
		RecordCommand("$outputFileDir $outputFilePath");
	}

$outputFilePath = CheckFileSize($outputFileDir);
RecordCommand("FINAL $outputFilePath");
RecordAndComplete("MIX",$outputFilePath,FALSE);


function MixImage($inputFileDir)
{
global $CONVERT_DIR, $CONVERT_PATH, $GIFSUFFIX, $Setting;
global $TEXTURE_DIR;
global $BASE_DIR, $BASE_PATH;

RecordCommand("MIX SETTING=$Setting");

$outputFileName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";

//$Setting = 'CARDS1';
switch ($Setting)
{
case 'CARDS1':
$effectFileDir = "$BASE_DIR/wimages/blends/cards1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/cards1rect.gif";

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 170x250! -rotate 52 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +160+50 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

break;

case 'JUSTIN30':
$effectFileDir = "$BASE_DIR/wimages/blends/justin1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/justin1rect.jpg";

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 195x215! -rotate -10 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +120+90 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;

$inputFileDir = $outputFileDir;
$targetName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
break;
case 'DOLLAR100':
$effectFileDir = "$BASE_DIR/wimages/blends/dollar100.gif";
$backFileDir = "$BASE_DIR/wimages/blends/dollar100rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -fill '#2a2d32' -tint 50% -resize 258x296! $inputFileDir $outputFileDir";
$command = "convert -type Grayscale -resize 258x296! -paint 1 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +216+16  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
break;
case 'TOPGUN':
$effectFileDir = "$BASE_DIR/wimages/blends/fp.gif";
$backFileDir = "$BASE_DIR/wimages/blends/fprect.jpg";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -border 10x10 -bordercolor white -resize 206x229! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +421+80 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
break;
case 'SUNSET3':
$effectFileDir = "$BASE_DIR/wimages/blends/mixsunet3.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 432x120! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
//$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -watermark 30% -gravity North  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

break;
case 'GOD2':
$effectFileDir = "$BASE_DIR/wimages/blends/space3.jpg";
GetImageAttributes($effectFileDir,$width,$height,$size);
$inputFileDir =  ResizeImage($inputFileDir,$width,$height,TRUE);
$inputFileName = basename($inputFileDir);

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -blend 60 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
break;

case 'GOD1':
$effectFileDir = "$BASE_DIR/wimages/blends/space2.jpg";
GetImageAttributes($effectFileDir,$width,$height,$size);
$inputFileDir =  ResizeImage($inputFileDir,$width,$height,TRUE);
$inputFileName = basename($inputFileDir);

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -blend 60 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
break;

case 'CHURCH1':
$effectFileDir = "$BASE_DIR/wimages/blends/church1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/church1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 140x450! $inputFileDir $outputFileDir";
$command = "convert -resize 140x450! -modulate 100,200 -sharpen 10x10 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "../zshells/stainedglass.sh -t 0 -s 3 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +166+158 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
break;
case 'SUNSET3':
$effectFileDir = "$BASE_DIR/wimages/blends/mixsunet3.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 432x120! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -watermark 30% -gravity North  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
	break;

case 'CHEER3':
$effectFileDir = "$BASE_DIR/wimages/blends/cheer3.gif";
$backFileDir = "$BASE_DIR/wimages/blends/cheer3rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 300x180! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +30+5 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;

case 'MIXMAG3':
$effectFileDir = "$BASE_DIR/wimages/blends/mixmag3.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixmag3rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 317x285! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +96+329 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;

case 'MIXPOSTER2':
$effectFileDir = "$BASE_DIR/wimages/blends/mixposter2.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixposter2rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 209x196! -paint 2 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +186+205 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX3 $command");
break;
case 'MIXLIBRARY1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixlibrary1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixlibrary1rect.gif";
$textureImageDir = "$BASE_DIR/wimages/textures/marble.gif";

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 340x415! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "composite -tile $textureImageDir -compose Hardlight $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX3 $command");
break;

break;
case 'MIXCAT2':
RecordCommand("MIXCAT");
$effectFileDir = "$BASE_DIR/wimages/blends/mixcat2.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixcat2rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 150x173! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "montage  -background transparent -tile 2x1 -geometry +1+0 $inputFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -rotate -33 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;


$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +47+23 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX3 $command");
break;

case 'MIXPUZZLE1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixpuzzle1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixpuzzle1rect.gif";

$startFileDir = $inputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 559x373! $startFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir1 = $outputFileDir;

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 221x83! $startFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir2 = $outputFileDir;

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +60+23 $inputFileDir1 $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");
$backFileDir = $outputFileDir;

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +251+355 $inputFileDir2 $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");
$inputFileDir = $outputFileDir;

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX3 $command");

break;
case 'MIXMAG1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixmag1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixmag1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 372x459! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +60+241 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX3 $command");
break;

case 'MIXMATCH1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixmatch1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixmatch1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 260x190! -rotate -11  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +205+190 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX2 $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX3 $command");
break;


case 'BUS':
$effectFileDir = "$BASE_DIR/wimages/blends/pbus.jpg";
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 162x225!  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +20+26  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$effectFileDir = $outputFileDir;

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 158x225! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +210+26 $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'BUSP':

if ($len > 8)
{
    $prevFileDir = $previousImages[0];
    $prevFileDir = "$BASE_DIR$prevFileDir";
}
else
{
    $prevFileDir = $inputFileDir;
}

$effectFileDir = "$BASE_DIR/wimages/blends/pbus.jpg";
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 162x225!  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +20+26  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$effectFileDir = $outputFileDir;

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 158x225! $prevFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFiledir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +210+26 $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'MODEL':
$effectFileDir = "$BASE_DIR/wimages/blends/model.jpg";
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
//$command = "convert -resize 120x147! -background white -rotate 33.5 -transparent white  $inputFileDir $outputFileDir";
$command = "convert -resize 120x147! -background transparent -rotate 33.5 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry -27+183  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$effectFileDir = $outputFileDir;
break;
case 'STREET1':
$effectFileDir = "$BASE_DIR/wimages/blends/street1.jpg";
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 240x237! -paint 1 -emboss 1 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +109+32  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$effectFileDir = $outputFileDir;
break;
case 'TV1':
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$effectFileDir = "$BASE_DIR/wimages/blends/tv.jpg";
$command = "convert -sharpen 0.0x1.0 -resize 368x230!  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +35+25  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'TV2':
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$effectFileDir = "$BASE_DIR/wimages/blends/tv2.jpg";
$command = "convert -sharpen 0.0x1.0 -resize 142x83!  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +74+24  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'TOILET1':
	$TOILET_DIR = "$BASE_DIR/wimages/cutters/toilet.gif";
    $CUTTER_DIR = "$BASE_DIR/wimages/cutters/circle.gif";
    $inputFileDir = ResizeImage($inputFileDir,240,240,TRUE);

    $targetName= StripSuffix($inputFileDir);
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
    $command = "convert -transparent black -resize 240x240\! $CUTTER_DIR $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $cutterFileDir = $outputFileDir;

    $targetName = StripSuffix($inputFileDir);
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
    $outputFilePath = "$CONVERT_PATH$targetName$GIFSUFFIX";
    $command = "composite -geometry +0+0 $cutterFileDir $inputFileDir $outputFileDir"
;
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

    $inputFileDir = $outputFileDir;    
	$targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";    $command = "convert -transparent white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

    $inputFileDir = $outputFileDir;
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
    $command = "convert -swirl 190 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

	$inputFileDir = $outputFileDir;
    $targetName = StripSuffix($inputFileDir);
    $targetName = NewName($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$targetName$PNGSUFFIX";
    $outputFilePath = "$CONVERT_PATH$targetName$PNGSUFFIX";
    $command = "composite -geometry +80+95 -dissolve 80% $inputFileDir $TOILET_DIR $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'TOILET2':
	$TOILET_DIR = "$BASE_DIR/wimages/cutters/toilet.gif";
    $CUTTER_DIR = "$BASE_DIR/wimages/cutters/circle.gif";
    $inputFileDir = ResizeImage($inputFileDir,240,240,TRUE);

    $targetName= StripSuffix($inputFileDir);
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
    $command = "convert -transparent black -resize 240x240\! $CUTTER_DIR $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $cutterFileDir = $outputFileDir;

    $targetName = StripSuffix($inputFileDir);
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
    $outputFilePath = "$CONVERT_PATH$targetName$GIFSUFFIX";
    $command = "composite -geometry +0+0 $cutterFileDir $inputFileDir $outputFileDir"
;
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

    $inputFileDir = $outputFileDir;    
	$targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";    $command = "convert -transparent white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

	$inputFileDir = $outputFileDir;
    $targetName = StripSuffix($inputFileDir);
    $targetName = NewName($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$targetName$PNGSUFFIX";
    $outputFilePath = "$CONVERT_PATH$targetName$PNGSUFFIX";
    $command = "composite -geometry +80+95 -dissolve 80% $inputFileDir $TOILET_DIR $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'NAPKIN':
$effectFileDir = "$BASE_DIR/wimages/blends/napkin4.jpg";
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -charcoal 3 -resize 460x420! $inputFileDir $outputFileDir";
$command = "convert -charcoal 3 -resize 390x250! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("NAPKIN $command");

$inputFileDir = $outputFileDir;
$targetName = StripSuffix($inputFileDir);
$targetName = NewName($targetName);
$outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
$command = "convert -white-threshold 50% $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("NAPKIN $command");

$inputFileDir = $outputFileDir;
$targetName = StripSuffix($inputFileDir);
$targetName = NewName($targetName);
$outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
$command = "convert -transparent white $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("NAPKIN $command");

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "/usr/bin/composite -geometry +83+78  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("NAPKIN $command");
break;
case 'MISSING':
$effectFileDir = "$BASE_DIR/wimages/blends/milkcartoon.gif";
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -paint 2 -resize 165x128!  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +50+210  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'MIXLOVE1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixlove1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixlove1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 350x350!  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +80+90  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'MIXDEATH1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixdeath1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixdeath1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 220x310! -sepia-tone 75%  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +340+20  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'MIXARTIST1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixartist1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixartist1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 305x250! -paint 2  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +30+25  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'MIXPORTRAIT1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixportrait1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixportrait1.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 140x180! -modulate 100,200 -sharpen 10x10 -median 1 -paint 1 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +330+210  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;


case 'CARDORCHID':
$effectFileDir = "$BASE_DIR/wimages/blends/mixcardorchid.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixcardorchidrect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 260x380! -rotate -15  $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +90+50  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;

case 'CITYINFO':
$effectFileDir = "$BASE_DIR/wimages/blends/mixcityinfo.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixcityinforect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -fill red -tint 50% -resize 160x240! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +105+120  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'AWARD1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixaward1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixaward1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 167x270! -paint 1 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +202+47  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;

case 'BOOK1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixbook1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixbook1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 305x255! -paint 1 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +340+172  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;


// now texture this file
$textureImageDir = "$TEXTURE_DIR"."oldpaper1.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert  -resize 700x552! $textureImageDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$textureImageDir = $outputFileDir;

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "/usr/bin/composite -dissolve 50% $inputFileDir $textureImageDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "/usr/bin/composite  $inputFileDir $textureImageDir -compose bumpmap -gravity center $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
$inputFileDir = $outputFileDir;

// combine textured file with foreground
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
break;
case 'ROMANCE1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixromance1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixromance1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 235x228! -rotate -27 -paint 1 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +30-39  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'SHOP1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixshop1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixshop1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 235x395! -rotate -20 -paint 1 -swirl 30 $inputFileDir $outputFileDir";
$command = "convert -resize 235x395! -rotate -20 $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +30+129  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'HCARD1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixhcard1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixhcard1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 320x430! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +290+42  $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'MIXCAT1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixcat1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixcat1rect.gif";
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 360x380! $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +150+350 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'SCHOOL1':
$effectFileDir = "$BASE_DIR/wimages/blends/mixschool1.gif";
$backFileDir = "$BASE_DIR/wimages/blends/mixschool1rect.gif";

$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert -resize 220x165! -sepia-tone 85% $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = TMPGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +105+07 $inputFileDir $backFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +0+0 $effectFileDir $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'CHARCOAL':
    $command = "convert -charcoal 1";
    break;
case 'VANGOGH':
    $command = "convert -modulate 100,130 -edge 3 -paint 1 -swirl 39";
    break;
case 'DADA':
    $command = "convert -modulate 110,130 -equalize -emboss 3 -paint 5";
    break;
case 'HEAVYMETAL':
    $command = "convert -blur 0x1  -shade 120x21.78 -normalize -raise 2x2 -sepia-tone 65% -emboss 3 -modulate 110 -sharpen 0.0x1.0";
    break;
case 'IMPRESSION':
    $command = "convert -colorize 210 -edge 11 -swirl 10";
    $command = "convert -modulate 100,160 -emboss 2 -spread 2 -swirl 40";
    break;
case 'WATERCOLOR':
    $command = "convert -modulate 100,200 -sharpen 10x10 -median 5 -paint 2";
    break;
case 'PASTEL':
    $command = "convert -colors 8 -paint 2";
    break;
}

RecordCommand("MIX $command $Setting");

switch ($Setting)
{
case 'CHARCOAL':
case 'VANGOGH':
case 'DADA':
case 'HEAVYMETAL':
case 'IMPRESSION':
case 'WATERCOLOR':
case 'PASTEL':
$effectFileDir = "$BASE_DIR/wimages/blends/painting3.jpg";
$w = 281;
$h = 233;
$inputFileDir = ResizeImage($inputFileDir,$w,$h,TRUE);
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "$command $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");


$inputFileDir = $outputFileDir;
$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "composite -geometry +32+65  $inputFileDir $effectFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("MIX $command");
break;
} //end switch

$outputFileName = basename($outputFileDir);
RecordCommand("MIX RETURN $outputFileName");
return $outputFileName;

}

?>
