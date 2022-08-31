<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_BUTTON;

$EX_DIR = "$BASE_DIR/wimages/examples/buttons/";
$EX_PATH = "$BASE_PATH/wimages/examples/buttons/";
$TARPOST_DIR = "$BASE_DIR/wimages/buttons/";

$DEFAULT = 'square-A.png';


$button = $_POST['SETTING'];
$pbutton = str_replace("-A","",$button);
$pbutton = str_replace(".png","",$pbutton);

$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);


switch ($button)
{
case 'circle-A.png':
case 'diamond-A.png':
case 'triangle-left-A.png':
case 'triangle-right-A.png':
case 'triangle-up-A.png':
case 'triangle-down-A.png':
    $pbutton = str_replace("-A","",$button);
    $real_width = $real_height = 150;
    $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,TRUE);  
    $inputFileName = basename($inputFileDir);
    $AnimateString = "";

    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        foreach ($imageList as $image)
        {
            $outputFilePath = MakeShape($image,$real_width,$real_height,$pbutton);
            $targetName = basename($outputFilePath);
            $AnimateString .= "$CONVERT_DIR$targetName";
            $AnimateString .= " ";
        }

        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay 20  %FILES -loop 0 $outputFileDir";
        $command = str_replace("%FILES", $AnimateString, $command);
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        //RecordCommand("BUTTON ANIMATE $command $button");
        //RecordCommand("BUTTON ANIM $outputFilePath $button");
    } 
    else
    {
        $outputFilePath = MakeShape($inputFileDir,$real_width,$real_height,$pbutton);
    }     
    break;
case 'square-big-A.png':
    $real_width = $real_height = 150;
    $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,TRUE);  
    $inputFileName = basename($inputFileDir);
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -raise 6x6 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'rectangle1-A.png':
    $real_width = 100;
    $real_height = 50;
    $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,TRUE);  
    $inputFileName = basename($inputFileDir);
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -raise 5x5 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'rectangle2-A.png':
    $real_width = 50;
    $real_height = 100;
    $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,TRUE);  
    $inputFileName = basename($inputFileDir);
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -raise 5x5 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'square-small-A.png':
    $real_width = $real_height = 60;
    $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,TRUE);  
    $inputFileName = basename($inputFileDir);
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -raise 3x3 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'square-very-small-A.png':
    $real_width = $real_height = 30;
    $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,TRUE);  
    $inputFileName = basename($inputFileDir);
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -raise 2x2 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'square-A.png':
    $real_width = $real_height = 100;
    $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,TRUE);  
    $inputFileName = basename($inputFileDir);
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -raise 5x5 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
}

RecordCommand("FINAL $outputFilePath");
RecordAndComplete("BUTTON",$outputFilePath,FALSE);


function MakeShape($inputFileDir,$real_width,$real_height,$shapeName)
{
global $TARPOST_DIR;
global $CONVERT_DIR;
global $CONVERT_PATH;

    $inputFileName = basename($inputFileDir);

    $arg = $real_width.'x'.$real_height;
    $shapeFileDir = "$TARPOST_DIR$shapeName";
    $targetName = TMPGIF();
    $shapeOutputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -resize $arg\!";
    $command = "$command $shapeFileDir $shapeOutputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("SHAPE1 $command");


    $targetName = TMPPNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir -crop $arg+0+0\! -background none -flatten +repage \( $shapeOutputFileDir +matte \) -compose CopyOpacity -composite -gravity center -crop $arg+0+0 +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("SHAPE2 $command $inputFileName $ConvertResultCode");

    $inputFileDir = $outputFileDir;
    $targetName = TMPPNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir \( +clone -channel A -separate +channel -negate -background black -virtual-pixel background -blur 0x2 -shade 120x21.78 -contrast-stretch 0% +sigmoidal-contrast 7x50%  -fill grey50 -colorize 10% +clone +swap -compose overlay -composite \) -compose In -composite $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("SHAPE3 $command $ConvertResultCode");

    $inputFileDir = $outputFileDir;
    $targetName = TMPPNG();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir \( +clone -fill DarkSlateGrey -colorize 100% -repage +0+1 \) \( +clone -repage +1+2 \)  \( +clone -repage +1+3 \) \( +clone -repage +2+4 \)  \( +clone -repage +2+5 \) -background none -compose DstOver -flatten $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("SHAPE4 $command ");

    $inputFileDir = $outputFileDir;
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert $inputFileDir \( +clone   -background Black -shadow 50x3+4+4 \) -background none -compose DstOver -flatten $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("SHAPE5 $command ");
    return $outputFilePath;
}
?>
