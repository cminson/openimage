<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


//RecordCommand("CONVX ENTER");
$TARGETTYPE = strtolower($_POST['TGT']);
$TARGETCAPTYPE = strtoupper($TARGETTYPE);
$TARGETSUFFIX = ".$TARGETTYPE";

$ConvertSuccess = FALSE;
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);
//RecordCommand("CONVX ENTER $inputFileDir");


switch ($TARGETTYPE)
{
case "bmp":
	$LastOperation = $X_BMP;
	break;
case "gif":
	$LastOperation = $X_GIF;
	break;
case "jpg":
	$LastOperation =  $X_JPG;
	break;
case "png":
	$LastOperation = $X_PNG;
	break;
case "ico":
	$LastOperation = $X_ICO;
    GetImageAttributes($inputFileDir,$w,$h,$size);
	if (($w > 200) ||  ($h > 200))
	{
		$inputFileDir = ResizeImage($inputFileDir,200,200, FALSE);
	}

	break;
};

$LastOperation = $TARGETCAPTYPE;

$targetName = StripSuffix($inputFileName);
$targetName = NewName($targetName);
//RecordCommand("Name: $targetName");

$outputFileDir = "$CONVERT_DIR$targetName$TARGETSUFFIX";
$outputFilePath = "$CONVERT_PATH$targetName$TARGETSUFFIX";
$command = "convert $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");
//RecordCommand("$outputFilePath ");
if ($ConvertResultCode == 0)
	$ConvertSuccess = TRUE;


// 
// check to make sure the output file really exists ..
//
// if it doesn't, we assume it's because the source file
// was an animated image.  
//
if ((file_exists($outputFileDir)) == FALSE)
{
	RecordCommand("$outputFileDir File Not Found");
    $imageDir = "$CONVERT_DIR$targetName";
    $imageDir .= "-0";
    $imageDir .= "$TARGETSUFFIX";
	$outputFileDir = RenameImage($imageDir);

	$targetName = baseName($outputFileDir);
    $outputFilePath = "$CONVERT_PATH$targetName";

    $LastOperation .= "   (Note: Animation Removed In Conversion)";
}
else
{
    $inputFileDir = $outputFileDir;
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 1600000)
    {
        if (($real_width > 500) || ($real_height > 500))
        {
            RecordCommand("XCF RESIZED $size $outputFileDir");
            $inputFileDir = ResizeImage($inputFileDir,500,500,FALSE);
            $targetName = basename($inputFileDir);
            $outputFilePath = "$CONVERT_PATH$targetName";
        }
    }
}

RecordAndComplete($LastOperation,$outputFilePath,TRUE);


function RenameImage($imageDir)
{
global $CONVERT_DIR;

    $targetName = NewName($imageDir);
    $outputFileDir = "$CONVERT_DIR$targetName";
	$command = "cp $imageDir $outputFileDir";
    RecordCommand("RENAME $command");
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    return $outputFileDir;
}
?>
