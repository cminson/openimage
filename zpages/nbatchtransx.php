<?php
include '../zcommon/common.inc';
RecordCommand("DEV HERE");

if (CompleteWithNoAction()) return;
RecordCommand("DEV HERE");


$Title = $X_MAKETRANSPARENT." - BATCHED -";
$LastOperation = $X_TRANSPARENCYADDED." BATCHED ";
$IMAGEOFFSET_X = 2;
$IMAGEOFFSET_Y = 2;

$MAX_ANIM = 16;

$current = $_POST['CURRENTFILE'];
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);


$clientX = $_POST['CLIENTX'];
$clientY = $_POST['CLIENTY'];
$NewColor = $_POST['PICKCOLOR'];

RecordCommand("NewColor = $NewColor $clientX $clientY");

// if the user manually entered a color, use it
if (strlen($NewColor) >= 3)
{
		$Color = str_replace("#", "", $NewColor);
		$clientX = $clientY = 0;
		RecordCommand(" Chosen Color = $Color");
}
else
{
    $Color = 'white';
}

$ArgFuzz = $_POST['FUZZ'];


//build up the input and output paths
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$saveinputFileDir = $inputFileDir;
RecordCommand(" $inputFileDir");

//get image dimensions, filter out images that aren't cooperating
GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
if ($real_height <= 0)
{
    $ErrorCode = 10;
    $real_height = 300;
}
if ($real_width <= 0)
{
    $ErrorCode = 10;
    $real_width = 300;
}

//convert to png (if not png already)
//gotta use matte option, so to disregard any transparent layer
$targetName = NewNamePNG();
$outputFileDir = "$CONVERT_DIR$targetName";
$command = "convert +matte $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand(" $command");

//
// if it's an animation we'll get multiple png files,
// otherwise a single file
//
if ((file_exists($outputFileDir)) == FALSE)
{
        $outputFileDir = StripSuffix($outputFileDir);
        $outputFileDir = $outputFileDir."-0".$PNGSUFFIX;
        if ((file_exists($outputFileDir)) == FALSE)
        {
            $ErrorCode = 10;
        }
}
$inputFileDir = $outputFileDir;

// get all the batch images
$tmpName = array();
$sourceName = array();
$sourceFilePath = array();
$targetName = array();
$FileArray = array();
            
for ($i = 1; $i <= $MAX_ANIM; $i++)
{           
    $file = $_POST["FRAMEPATH$i"];
    //RecordCommand("RAW: $i FILE = $file");
    if (strlen($file) <= 1) 
        continue;
    if (stristr($file,"ezimbanoop") != FALSE)
        continue;
    $file = GetWorkPath($file);
    $file = "$BASE_DIR/".$file;
    //RecordCommand("$i FILE = $file");
    $FileArray[] = $file;
}               
            
if (count($FileArray) < 1)
{           
    RecordCommand("Exiting: Less than 1 file input");
    echo '<html><head><title>-</title></head><body>';
    echo '<script language="JavaScript" type="text/javascript">'."\n";
    echo "parent.completeWithNoAction();";
    echo "\n".'</script></body></html>';
    return; 
}           

$rand = MakeRandom();
$j = 0;
$FileList="";
foreach ($FileArray as $file)
{
    RecordCommand("FILELIST $file");
    if (strlen($file) > 3)
    {
        $outputFileDir = ConvertTrans($file,$rand+$j);
        if ($j == 0)
        {
            $targetName = baseName($outputFileDir);
            $outputFilePath = "$CONVERT_PATH$targetName";
        }
        $j++;
    }
}
RecordCommand("FINAL $outputFilePath");
RecordAndComplete("TRANS",$outputFilePath,FALSE);


function NewIncGIF($rand)
{
global $SESSION;
global $PREFIXCODE;
global $GIFSUFFIX;

    $SESSION =  ((isset($_COOKIE["SESSION"])) == FALSE)
        ? "9000" : $_COOKIE["SESSION"];
    $PREFIXCODE =  ((isset($_COOKIE["PREFIXCODE"])) == FALSE)
        ? "1" : $_COOKIE["PREFIXCODE"];
    $newName = "$PREFIXCODE$SESSION$rand$GIFSUFFIX";
    return $newName;
}



function ConvertTrans($inputFileDir,$rand)
{
global $ArgFuzz;
global $Color;
global $CONVERT_DIR;

    RecordCommand("XBATCHTRANS $inputFileDir");

    //make sure it's a gif (just go ahead and do the convert)
    $targetName = TMPGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XBATCHTRANS $command");
    $inputFileDir = $outputFileDir;

    //do the conversion
    $targetName = NewIncGIF($rand);
    $outputFileDir = "$CONVERT_DIR$targetName";

    $Color = str_replace("#", "", $Color);
    if (ctype_xdigit($Color) == TRUE)
    {
        if ($ArgFuzz > 0)
            $command = "convert -fuzz $ArgFuzz -transparent \"#$Color\"";
        else
            $command = "convert -transparent \"#$Color\"";
    }
    else
    {
        if ($ArgFuzz > 0)
            $command = "convert -fuzz $ArgFuzz -transparent $Color";
        else
            $command = "convert -transparent $Color";
    }

    $command = "$command $inputFileDir  $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XBATCHTRANS $command");
    return $outputFileDir;
}




?>

