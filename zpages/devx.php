<?php
include '../zcommon/common.inc';


$MAXANIMFILESIZE = 5000000;	//5M max upload size for all files
$MAXANIMFILESIZE = 10000000;	//10M max upload size for all files


$Title = $X_FRAMEANIMATION;
$LastOperation = $X_FRAMEANIMATION;

$MAX_ANIM = 60;

$ArgLoop = $_POST['LOOP'];
$ArgTime = $_POST['TIME'];
$ArgResize = $_POST['RESIZE'];

//RecordCommand("$ArgLoop $ArgTime $ArgResize");
if (isset($ArgLoop) == FALSE)
		$ArgLoop = 0;
if (isset($ArgTime) == FALSE)
		$ArgTime = 50;

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
	RecordCommand("$i FILE = $file");
	$FileArray[] = $file;
}

// make sure max size is not exceeded
$size = 0;
foreach ($FileArray as $file)
{
	$size += filesize($file);
	//RecordCommand("FILESIZEDEV CHECK $size $file");
	if ($size > $MAXANIMFILESIZE)
	{
		ReportError("Max total file size of 10M exceeded. Try with fewer or smaller files");
	}
}

//automatically resize all images, if this option selection
if ($ArgResize == 'on')
{
	GetImageAttributes($FileArray[0],$real_width,$real_height,$size);
	$count = count($FileArray);
	//RecordCommand("GETTING SIZE $count $FileArray[0] $real_width $real_height");
	for ($i = 1; $i < $count; $i++)
	{
		$file = $FileArray[$i];
		RecordCommand("RESIZED: $i $file");
		if (strlen($file) > 3)
		{
			$resizeFile = ResizeTMPImage($file,$real_width,$real_height,TRUE);
			$FileArray[$i] = $resizeFile;
			//RecordCommand("RESIZED: $i $file $resizeFile $real_width $real_height");
		}
	}
}

$FileList="";
foreach ($FileArray as $file)
{
	if (strlen($file) > 3)
	{
		$FileList .= "$file ";
		RecordCommand("FILELIST $file");
	}
}

//do the conversion
$targetName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";

$command = "convert -dispose previous -delay $ArgTime $FileList -loop $ArgLoop $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("NANIM $command");

GetImageAttributes($outputFileDir,$real_width,$real_height,$size);
if ($size > 2800000)
{
	if (($real_width > 800) || ($real_height > 800))
	{
		RecordCommand("RESIZING $size $outputFileDir");
		$outputFileDir = ResizeImage($outputFileDir,800,800,FALSE);
		RecordCommand("RESIZED $outputFileDir");
		$targetName = basename($outputFileDir);
		$outputFilePath = "$CONVERT_PATH$targetName";
	}
}

RecordCommand("FINAL $outputFilePath");
RecordAndComplete("NANIM",$outputFilePath,FALSE);

?>
