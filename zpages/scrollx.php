<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_SCROLLED;
$setting = $_POST['ORIENT'];
$effect = $_POST['EFFECT'];
$time = $_POST['TIME'];


RecordCommand("Setting = $setting");

//build up the input and output paths
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";


$inputFileDir = ConvertToJPG($inputFileDir);
$inputFileName = basename($inputFileDir);


$size = filesize($inputFileDir);
if ($size > 20000)
{
    $inputFileDir = ResizeImage($inputFileDir,200,200,FALSE);
    $inputFileName = basename($inputFileDir);
}


$targetName = StripSuffix($inputFileName);
$targetName .= $GIFSUFFIX;

GetImageAttributes($inputFileDir, $real_width, $real_height, $size);

if ($setting == "DVERTICAL")
{
    $height = 0;
    $inc = $real_height / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPName($targetName);
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -roll +0+$height";
        $command = "$command $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
        $height += $inc;
    }
}
else if ($setting == "UVERTICAL")
{
    $height = 0;
    $inc = $real_height / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPName($targetName);
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -roll +0-$height";
        $command = "$command $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
        $height += $inc;
    }
}
else if ($setting == "RHORIZONTAL")
{
    $width = 0;
    $inc = $real_width / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPName($targetName);
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
else if ($setting == "LHORIZONTAL")
{
    $width = 0;
    $inc = $real_width / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPName($targetName);
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
else if ($setting == "LDIAG")
{
    $width = 0;
    $height = 0;
    $incx = $real_width / 9;
    $incy = $real_height / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPName($targetName);
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
else if ($setting == "RDIAG")
{
    $width = 0;
    $height = 0;
    $incx = $real_width / 9;
    $incy = $real_height / 9;
    for ($i=0; $i < 9; $i++)
    {
        $targetName = TMPName($targetName);
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

switch ($effect)
{
case 'NONE':
    $specialArg = '';
    break;
case 'WAVE':
    $specialArg = '-matte -background none -wave 5x50';
    break;
case 'SWIRL':
    $specialArg = '-swirl 50';
    break;
case 'BEND':
    $specialArg = '-virtual-pixel background -distort arc 120  +repage';
    $specialArg = '-virtual-pixel background -background white -distort arc 120  +repage';
    break;
case 'SWIRLWAVE':
    $specialArg = '-matte -background none -wave 5x50 -swirl 50';
    break;
}


$targetName = NewNameGIF();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert $specialArg -dispose previous -delay $time %FILES -loop 0 $outputFileDir";
$command = str_replace("%FILES", $FileList, $command);
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("$command");
RecordCommand("FINAL $outputFilePath $effect $setting");
RecordAndComplete("SCROLL",$outputFilePath,FALSE);
?>
