<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;



$LastOperation=$X_MONTAGE;
$MAX_MONTAGE = 16;
$UploadSuccess = TRUE;
$ErrorCode = 0;

$ImageSize = '170x170';
$ArgDimensions = $_POST['SIZE'];
$ArgEffects = $_POST['EFFECTS'];
$ArgBackgroundColor = $_POST['COLOR'];
$ArgSeparation = $_POST['SEPARATION'];
$ArgAnimate = $_POST['ANIMATE'];

if (isset($ArgEffects) == FALSE)
{
	$ArgEffects = 'POLAROID';
}
if (isset($ArgDimensions) == FALSE)
	$ArgDimensions = '2x2';
if (isset($ArgBackgroundColor) == FALSE)
	$ArgBackgroundColor = '#FF9900';
if (isset($ArgSeparation) == FALSE)
	$ArgSeparation = '+5+5';

RecordCommand("Effects: $ArgEffects Color: $ArgBackgroundColor ArgSeparation: $ArgSeparation ArgAnimate: $ArgAnimate InputFile: $inputFileDir ");
$UploadSuccess = FALSE;

$tmpName = array();
$sourceName = array();
$sourceFilePath = array();
$targetName = array();

$FileArray = array();

$UploadSuccess = TRUE;

for ($i = 1; $i <= $MAX_MONTAGE; $i++)
{
    $file = $_POST["FRAMEPATH$i"];
    RecordCommand("RAW: $i FILE = $file");
    if (strlen($file) <= 1)
        continue;
    if (stristr($file,"ezimbanoop") != FALSE)
        continue;
    $file = GetWorkPath($file);
    $file = "$BASE_DIR/".$file;
    RecordCommand("$i FILE = $file");
	chmod($outputFileDir,0777);
    $FileArray[] = $file;
}


if ($UploadSuccess == TRUE)
{
        if (count($FileArray) == 0)
            $FileArray[] = $baseFileDir;

        //expand out any uploaded animations as individual frames
        $i = 0;
        $tmp = array();
        foreach ($FileArray as $file)
        {
            $i++;
            $inputFileDir = $file;
            if (IsAnimatedGIF($file) == TRUE)
            {
                $imageList = GetAnimatedImages($file);
                $tmp = array_merge($tmp, $imageList);
                RecordCommand("Expand animation: $file");
            }
            else
            {
                $tmp[] = $file;
            }
        }
        //FileArray now holds the expanded list of animation pieces 
        $FileArray = $tmp;

        //
        //if using an effect that requires polaroid backdrops,
        //create these images
        //
        if (($ArgEffects == 'POLAROID') || ($ArgEffects == 'HEAP'))
        {
            $FileArray = $tmp;
            $tmp = array();
            foreach ($FileArray as $file)
            {
                $inputFileDir = $file;
                $targetName = TMPPNG();
                $outputFileDir = "$CONVERT_DIR$targetName";
                $command = "convert -resize $ImageSize $inputFileDir +polaroid $outputFileDir";
                $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
                chmod($outputFileDir,0777);
                RecordCommand("POLAROID $targetName");
                $tmp[] = $outputFileDir;
            }
            $FileArray = $tmp;
        }


        //build up vars for use by HEAP effect
        list($row,$col) = explode('x',$ArgDimensions);
        list($x_c,$y_c) = explode('x',$ArgDimensions);
        list($x_i,$y_i) = explode('x',$ImageSize);
        $x_canvas = $x_c * $x_i * .70;
        $y_canvas = $y_c * $y_i * .70;
        $CanvasSize = $x_canvas."x".$y_canvas;

        $Rot = array();
        $Geo = array();
        $tmp = array();

        //pad the file list if we are less than the size of the montage
        //also use this loop to fill out the Geo and Rot arrays (used
        //at the moment only by the HEAP effect)
        $j = 0;
        $count = $row * $col;
        if ($ArgEffects == 'HEAP')
            $count = count($FileArray);
        for ($i = 0; $i < $count; $i++)
        {
            $rot = rand(0,360);
            $x = rand(-($x_i/2),$x_canvas-$x_i);
            $y = rand(-($y_i/2),$y_canvas-$y_i);
            if ($x >= 0) $x = "+$x";
            if ($y >= 0) $y = "+$y";
            $Geo[] = "$x$y";
            $Rot[] = $rot;

            $file = $FileArray[$j];
            $tmp[] = $file;
            $j++;
            if ($j >= count($FileArray))
                $j = 0;
            RecordCommand("XMONT: $i $file");
        }
        $FileArray = $tmp;


        RecordCommand(" ANIMATE FLAG: $ArgAnimate");
        if ($ArgAnimate == 'on')
        {
            $AnimList = "";
            for ($i = 0; $i < $count; $i++)
            {
            shuffle($FileArray);

            $FileList = "";
            foreach ($FileArray as $file)
                $FileList .= "$file ";

            if ($ArgEffects == 'HEAP')
                $outputFileDir = MakeHeap($inputFileDir);
            else
                $outputFileDir = MakeTile($inputFileDir);
            RecordCommand("MON OUT $outputFileDir");
            $targetName = basename($outputFileDir);
            $outputFileDir = "$CONVERT_DIR$targetName";
            $AnimList .= "$outputFileDir ";

            }
            $targetName = NewNameGIF();
            $outputFileDir = "$CONVERT_DIR$targetName";
            $outputFilePath = "$CONVERT_PATH$targetName";
            $command = "convert -dispose previous -delay 50  $AnimList -loop 0 $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            RecordCommand(" FINAL $outputFilePath");
        }
        else //no animate
        {
            //build up the string list
            $FileList = "";
            foreach ($FileArray as $file)
                $FileList .= "$file ";

            if ($ArgEffects == 'HEAP')
                $outputFileDir = MakeHeap($inputFileDir);
            else
                $outputFileDir = MakeTile($inputFileDir);
            RecordCommand("MON OUT $outputFileDir");
            $targetName = basename($outputFileDir);
            $outputFilePath = "$CONVERT_PATH$targetName";
            RecordCommand(" FINAL $outputFilePath");
        }

RecordAndComplete("MONTAGE",$outputFilePath,FALSE);
}


function MakeHeap($inputFileDir)
{
global $JPGSUFFIX;
global $PNGSUFFIX;
global $CONVERT_DIR;
global $ArgDimensions;
global $ArgSeparation;
global $ArgBackgroundColor;
global $FileArray;
global $Geo;
global $Rot;
global $CanvasSize;

    //make the background canvas 
    $targetName = TMPGIF();
    $canvasFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -size $CanvasSize xc:'$ArgBackgroundColor' $canvasFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" HEAP1 $command");

    $i = 0;
    foreach ($FileArray as $file)
    {
        $geo = $Geo[$i];
        $rot = $Rot[$i];
        $i++;

        $targetName = TMPGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -background transparent -rotate $rot $file $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        //RecordCommand(" FILE ROTATE $command");
        $inputFileDir = $outputFileDir;

        $command = "composite -geometry $geo $inputFileDir $canvasFileDir $canvasFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand(" FILE COMP $command");
    }

    $outputFileDir = $canvasFileDir;
    GetImageAttributes($outputFileDir,$real_width,$real_height,$size);
    if ($size > 30000)
        $quality = 50;
    else
        $quality = 100;

    $targetName = NewNameJPG();
    $inputFileDir = $outputFileDir;
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -quality $quality $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" XREDUCE HEAP $command");

    return $outputFileDir;
}




function MakeTile($inputFileDir)
{
global $JPGSUFFIX;
global $PNGSUFFIX;
global $CONVERT_DIR;
global $ImageSize;
global $ArgDimensions;
global $ArgSeparation;
global $ArgBackgroundColor;
global $FileList;

    //do the conversion
    $targetName = NewNamePNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "montage -tile $ArgDimensions -geometry $ImageSize$ArgSeparation -background '$ArgBackgroundColor' $FileList $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand(" PRELIM $command");

    //decrease image size
    GetImageAttributes($outputFileDir,$real_width,$real_height,$size);
    if ($size > 30000)
        $quality = 50;
    else
        $quality = 100;

    $inputFileDir = $outputFileDir;
    $targetName = NewNameJPG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -quality $quality $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("REDUCE TILE $command");

    return $outputFileDir;
}
?>
