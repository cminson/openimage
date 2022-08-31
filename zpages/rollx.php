<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation = $X_ROLLIMAGE;
$Direction = $_POST['DIRECTION'];
$Amount = $_POST['AMOUNT'];
$Wrap = $_POST['WRAP'];
//$Wrap = 'on';

//build up the input and output paths
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);

GetImageAttributes($inputFileDir, $w, $h, $size);

switch ($Direction)
{
case 'XLEFT':
	$amt = intval($w * $Amount);
	$targetName = NewName($inputFileName);
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -roll -$amt+0 $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$nh = $h;
	$nw = $amt;
	$x = $w - $amt;
	$y = 0;
	RecordCommand(" $x $y");
	break;
case 'XRIGHT':
	$amt = intval($w * $Amount);
	$targetName = NewName($inputFileName);
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -roll +$amt+0 $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$nh = $h;
	$nw = $amt;
	$x = 0;
	$y = 0;
	break;
case 'YUP':
	$amt = intval($h * $Amount);
	$targetName = NewName($inputFileName);
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -roll +0-$amt $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$nh = $amt;
	$nw = $w;
	$x = 0;
	$y = $h - $amt;
	break;
case 'YDOWN':
	$amt = intval($h * $Amount);
	$targetName = NewName($inputFileName);
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -roll +0+$amt $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$nh = $amt;
	$nw = $w;
	$x = 0;
	$y = 0;
	break;
}

if ($Wrap != 'on')
{
	$inputFileDir = $outputFileDir;

    $rectImage = "$BASE_DIR/wimages/tools/rect-white.gif";
	$rectImage = ResizeImage($rectImage,$nw,$nh,TRUE);

	$targetName = NewName($inputFileName);
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	//$command = "convert -background white -crop $nw"."x".$nh."+$x+$y +repage $inputFileDir $outputFileDir";
	$command = "composite -geometry +$x+$y $rectImage $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
}


RecordCommand("$command");
RecordCommand("FINAL $outputFilePath $effect $setting");
RecordAndComplete("ROLL",$outputFilePath,FALSE);
?>
