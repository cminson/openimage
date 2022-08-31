<?php
include '../zcommon/common.inc';
include '../zcommon/pig.inc';


declare (ticks=5)
{
if (CompleteWithNoAction()) return;


$TARGETTYPE = ($_POST['TGT']);
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";

switch ($TARGETTYPE)
{
case 'HDR':
	$inputFileDir = ConvertToJPG($inputFileDir);
	break;
case 'TRIM3':
case 'TRIM10':
	GetImageAttributes($inputFileDir,$w,$h,$size);
	break;
}


//Local String Resources
RecordCommand("XSIMPLE Begin $TARGETTYPE");
switch ($TARGETTYPE)
{
case 'CARTOON':
	$targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "../zshells/emboss.sh -m 1 -d 1 -c overlay $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $inputFileDir = $outputFileDir;
    RecordCommand("CARTOON $command $outputFilePath");

	$command = "../zshells/cartoon2.sh -p 70 -n 6 -e 4";
	$LastOperation = $X_PHOTOTOCARTOON;
	break;
case 'NEWSPRINT':
	$targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "../zshells/emboss.sh -m 1 -d 1 -c overlay $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $inputFileDir = $outputFileDir;
    RecordCommand("$command $outputFilePath");

	$command = "../zshells/newsprint.sh";
	$command = "../zshells/newsprint.sh -s 4 -d h6x6a -S 300";
	$command = "../zshells/newsprint.sh -s 4 -S 200";
	$command = "../zshells/newsprint.sh -s 4 -e 3 -S 200";
	$LastOperation = $X_NEWSPRINT;
	break;
case 'BEAUTY':
	$command = "../zshells/lucasarteffect.sh";
    $LastOperation = $X_BEAUTY;
	break;
case 'ENRICH':
	$command = "../zshells/enrich.sh";
    $LastOperation = $X_ENRICH;
	break;
case 'SMOOTH':
	$command = "convert -gaussian 2";
    $LastOperation = $X_SMOOTH;
	break;
case 'BLUR':
	$command = "convert -spread 4";
    $LastOperation = $X_BLUR;
	break;
case 'BLACKANDWHITE';
    $command = 'convert -type Grayscale -black-threshold 50% -white-threshold 50%';
    $command = 'convert -type Grayscale -black-threshold 70% -white-threshold 30%';
    $LastOperation = $X_BLACKANDWHITE;
    break;
case 'HDR':
	$command = "../zshells/mkhdr.sh 13";
	$LastOperation = $X_HDRENERGIZED;
    break;
case "PIXEL":
	$command = "convert -resize 10% -sample 1000%";
	$LastOperation = $X_PIXELLATED;
    break;
case "FRACTAL":
    $command = "./disperse.sh -s 5 -d 5 -c 10";
	$LastOperation = $X_FRACTALIZED;
    break;
case "BLENDER":
	$command = "./recursion.sh -d 30 -a 90 -r 10 -z 0.85 -i 10";
	$LastOperation = $X_BLENDERIZED;
    break;
case "TWILIGHT":
    $command = "convert -black-threshold 50%";
	$LastOperation = $X_TWILIGHT;
    break;
case "CIRCLE":
	$command = "convert -virtual-pixel background -distort arc 361  -background white +repage";
	$LastOperation = $X_ENCIRCLED;
    break;
case "BEND":
	$command = "convert -virtual-pixel background -distort arc 120  -background white +repage";
	$LastOperation = $X_BENT;
    break;
case "PENCIL":
	$command = "convert -edge 3";
	$LastOperation = $X_PENCILLED;
    break;
case "ROTATEUP":
	$command = "convert -rotate 90";
	$LastOperation = $X_ROTATED;
	break;
case "FLIP":
	$LastOperation = $X_FLIPPEDVERTICALLY;
	$command = "convert -flip";
	break;
case "FLOP":
	$LastOperation = $X_FLIPPEDHORIZONTALLY;
	$command = "convert -flop";
	break;
case "TRIM3":
	$LastOperation = "$X_TRIMMED 3%";
	$x = intval($w / 33);
	$y = intval($h / 33);
	$command = "convert -shave $x"."x".$y;
	break;
case "TRIM10":
	$LastOperation = "$X_TRIMMED 10%";
	$x = intval($w / 10);
	$y = intval($h / 10);
	$command = "convert -shave $x"."x".$y;
	break;
case "SHAVE":
	$LastOperation = $X_SHAVED;
	$command = "convert -shave 15x15";
	break;
case "WASH":
	$LastOperation = $X_COLORWASHED;
	$command = "convert -colors 32 -level 15%";
	break;
case "FOSSIL":
	$LastOperation = $X_FOSSILIZED;
    $command = "convert -blur 0x1  -shade 120x21.78 -normalize -raise 5x5";
	break;
case "SKETCH":
	$LastOperation = $X_BLEACHED;
	$command = "convert -white-threshold 30000";
	$command = "convert -white-threshold 8000";
	break;
case "THUMBNAIL":
	$LastOperation = $X_THUMBNAILED;
	$command = "convert -resize 120x120";
	break;
case "GRAYSCALE":
	$LastOperation = $X_MONOCHROMED;
	$command = "convert -type Grayscale";
	break;
case "NORMALIZE":
	$LastOperation = $X_INSTANTFIXED;
	$command = "convert -normalize";
    break;
case "NEGATE":
	$LastOperation = $X_NEGATED;
	$command = "convert -negate";
	break;
case "CONTRASTUP":
	$LastOperation = $X_CONTRASTINCREASED;
	$command = "convert -contrast";
	break;
case "CONTRASTDOWN":
	$LastOperation = $X_CONTRASTDECREASED;
	$command = "convert +contrast";
	break;
case "BRIGHTUP":
	$LastOperation = $X_BRIGHTNESSINCREASED;
	$command = "convert -modulate 110";
	break;
case "BRIGHTDOWN":
	$LastOperation = $X_BRIGHTNESSDECREASED;
	$command = "convert -modulate 90";
	break;
case "SATURATEUP":
	$LastOperation = $X_SATURATIONINCREASED;
	$command = "convert -modulate 100,130";
	break;
case "SATURATEDOWN":
	$LastOperation = $X_SATURATIONDECREASED;
	$command = "convert -modulate 100,70";
	break;
case "HUEUP":
	$LastOperation = $X_HUEINCREASED;
	$command = "convert -modulate 100,100,110";
	break;
case "HUEDOWN":
	$LastOperation = $X_HUEDECREASED;
	$command = "convert -modulate 100,100,90";
	break;
case "SHARPUP":
	$LastOperation = $X_SHARPNESSINCREASED;
	$command = "convert -sharpen 0.0x1.0";
	break;
case "SHARPDOWN":
	$LastOperation = $X_SHARPNESSDECREASED;
	$command = "convert -unsharp 0.0x1.0";
	break;
case "ABSTRACT":
	$LastOperation = $X_ABSTRACTED;
	$command = "convert -modulate 100,130 -paint 9";
    break;
case "PAINT":
	$LastOperation = $X_PAINTED;
	$command = "convert -paint 2";
	break;
case "COLORIZE":
	$LastOperation = $X_COLORIZED;
// 150 - 270
	$command =  'convert -colorize 270';
	break;
case "SOLARIZE":
	$LastOperation = $X_HEATMAPPED;
	$command = 'convert -solarize 70%';
    break;
case "CHARCOAL":
	$LastOperation = $X_CHARCOALED;
	$command = 'convert -charcoal 5';
	break;
case "SEPIA":
    $LastOperation = $X_SEPIATONED;
    $command = "convert -sepia-tone  50%";
    break;
case "VIGNETTE":
    $LastOperation = $X_VIGNETTED;
    $command = "convert -background white -vignette 10x20 +repage";
    break;
case "HARDLIGHT":
    $LastOperation = "Hard Light Applied";
    $command = "convert  \( granite: -blur 0x.5 -normalize -fill gray50 -colorize 70% \) -compose hardlight -composite";
    break;
case "SOFTLIGHT":
    $LastOperation = "Soft Light Applied";
    $command = "convert \( granite: -blur 0x.5 -normalize \) -compose softlight -composite ";
    break;
};

$targetName = NewName($inputFileDir);
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "$command $inputFileDir $outputFileDir";
RecordCommand("$command $TARGETTYPE $LastOperation");
RecordCommand("FINAL $outputFilePath");
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

$inputFileDir = $outputFileDir;
GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
if ($size > 800000)
{
    if (($real_width > 800) || ($real_height > 800))
    {
    $inputFileDir = ResizeImage($inputFileDir,800,800,FALSE);
    $targetName = basename($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    }
}

RecordAndComplete($TARGETTYPE,$outputFilePath,TRUE);
}


?>
