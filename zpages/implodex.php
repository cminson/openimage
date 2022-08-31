<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_IMPLODED;

//get the command parameters
$Setting = $_POST['SETTING'];
$AreaSelect = $_POST['AREASELECT'];


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$targetName = NewName($inputFileDir);

$outputFileName = ImplodeImage($inputFileDir);
$outputFileDir = "$CONVERT_DIR$outputFileName";
$outputFilePath = "$CONVERT_PATH$outputFileName";

$inputFileDir = $outputFileDir;
GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
if ($size > 500000)
{
if (($real_width > 400) || ($real_height > 400))
{
$inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
$targetName = basename($inputFileDir);
$outputFilePath = "$CONVERT_PATH$targetName";
}
}

RecordCommand("FINAL $outputFilePath");

RecordAndComplete("IMPLODE",$outputFilePath,FALSE);



function ImplodeImage($inputFileDir)
{
global $CONVERT_DIR, $Setting;
global $HEIGHT_IMAGE, $AreaSelect;

$region = "";
if ($AreaSelect == 'on')
{
        $clientX1 = $_POST['X1'];
        $clientX2 = $_POST['X2'];
        $clientY1 = $_POST['Y1'];
        $clientY2 = $_POST['Y2'];
        GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
        $display_height = $HEIGHT_IMAGE;
        $display_width = (int)(($display_height/$real_height)*$real_width);

        $clientX1 = (int)(($real_width/$display_width) * $clientX1);
        $clientY1 = (int)(($real_height/$display_height) * $clientY1);
        $clientX2 = (int)(($real_width/$display_width) * $clientX2);
        $clientY2 = (int)(($real_height/$display_height) * $clientY2);
        $w= $clientX2 - $clientX1;
        $h= $clientY2 - $clientY1;
        $region = "-region ".$w."x".$h."+$clientX1+$clientY1";
}


$v = $Setting / 10;
$command = "convert $region -implode $v";

$targetName = NewName($inputFileDir);

$outputFileDir = "$CONVERT_DIR$targetName";
$command = "$command $inputFileDir $outputFileDir";
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
RecordCommand("IMPLODE $v $command");
return $targetName;
}
?>
