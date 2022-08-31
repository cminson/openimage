<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_JIGSAWIMAGE;

$WORK_DIR = "$BASE_DIR/wimages/jigsaws/";
$DEFAULT="jigsaw1-A.png";


$jigName = $_POST['SETTING'];
$jigName = str_replace("-A","",$jigName);
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

// get height and width, resize if necessary
GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
$real_width = $real_height = 300;
$inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);  
$inputFileName = basename($inputFileDir);

$AnimateString = "";
if (IsAnimatedGIF($inputFileDir) == TRUE)
{
    $imageList = GetAnimatedImages($inputFileDir);
    foreach ($imageList as $image)
    {
        $targetName = MakeJigSaw($image,$real_width,$real_height,$jigName,TRUE);
        $AnimateString .= "$CONVERT_DIR$targetName";
        $AnimateString .= " ";
    }
    $targetName = StripSuffix($inputFileName);
    $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";
    $outputFilePath = "$CONVERT_PATH$targetName$GIFSUFFIX";
    $command = "convert -dispose previous -delay 20  %FILES -loop 0 $outputFileDir";
    $command = str_replace("%FILES", $AnimateString, $command);
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XJIGSAW ANIM $command");
    RecordCommand("XJIGSAW FINAL $outputFilePath ANIM");
}
else
{
/*
    $interval = $real_width / 5;
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir -bordercolor LimeGreen -border $interval $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XJIG border $command");
    $real_height += $interval;
    $real_width += $interval;
    $inputFileDir = $outputFileDir;
    */


    $targetName = MakeJigSaw($inputFileDir,$real_width,$real_height,$jigName,FALSE);
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    RecordCommand("XJIGSAW FINAL $outputFilePath");
}
RecordAndComplete("JIGSAW",$outputFilePath,FALSE);


function MakeJigSaw($inputFileDir,$real_width,$real_height,$jigName,$temp)
{
global $WORK_DIR;
global $CONVERT_DIR;
global $GIFSUFFIX;
global $PNGSUFFIX;

    $inputFileName = basename($inputFileDir);

    $arg = $real_width.'x'.$real_height;
    $real_width = $real_height = $real_width + 60;
    $arg1 = $real_width.'x'.$real_height;

    $jigFileDir = "$WORK_DIR$jigName";
    $targetName = TMPName($jigFileDir);
    $jigOutputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -resize $arg\! $jigFileDir $jigOutputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XJIG $command");

    $targetName = TMPPNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir -crop $arg+0+0\! -background none -flatten +repage \( $jigOutputFileDir +matte \) -compose CopyOpacity -composite -rotate -20 -gravity center -crop $arg1+0+0 +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XJIG $command");

    $inputFileDir = $outputFileDir;
    $targetName = TMPPNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir \( +clone -channel A -separate +channel -negate -background black -virtual-pixel background -blur 0x2 -shade 120x21.78 -contrast-stretch 0% +sigmoidal-contrast 7x50%  -fill grey50 -colorize 10% +clone +swap -compose overlay -composite \) -compose In -composite $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XJIG $command ");

    $inputFileDir = $outputFileDir;
    $targetName = TMPPNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir \( +clone -fill DarkSlateGrey -colorize 100% -repage +0+1 \) \( +clone -repage +1+2 \)  \( +clone -repage +1+3 \) \( +clone -repage +2+4 \)  \( +clone -repage +2+5 \) -background none -compose DstOver -flatten $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XJIG $command $ConvertResultCode");

    $inputFileDir = $outputFileDir;
    if ($temp == TRUE)
        $targetName = TMPGIF();
	 else
        $targetName = NewNameGIF($inputFileDir);
    $targetName = "$targetName";
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert $inputFileDir \( +clone   -background Black -shadow 50x3+4+4 \) -background none -compose DstOver -flatten $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XJIG $command $ConvertResultCode");
    return $targetName;
}
?>
