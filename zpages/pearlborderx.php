<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = "$X_PEARL";
$DEFAULT="black-black.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/pearl/";
$EX_PATH = "$BASE_PATH/wimages/examples/pearl/";

$ErrorCode = 0;
$ConvertResultCode = -1;


	$Setting = $_POST['SETTING'];
	$Width = $_POST['WIDTH'];
	$Animate = $_POST['ANIMATE'];
    $Inset = $_POST['INSET'];

	
	$Setting = str_replace(".jpg","",$Setting);
	list ($color1,$color2) = explode('-',$Setting);
/*
	$color1 = "lightpink1";
	$color2 = "lavender";
*/

	$inset1 = (intval($Width / 2)) - 2;
	$inset2 = (intval($width / 2)) - 1;
    if ($Inset == 'on')
        $border = "$Width"."x"."$Width"."+"."$inset1"."+"."$inset2";
    else
        $border = "$Width"."x"."$Width";




	//build up the input and output paths
    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	if (IsAnimatedGIF($inputFileDir) == TRUE)
	{
		$inputFileDir = ConvertToJPG($inputFileDir);
	}
	GetImageAttributes($inputFileDir,$w,$h,$size);

	$w = $w + ($Width * 2);
	$h = $h + ($Width * 2);
	$dims = $w."x".$h;

	//animated PNGS have a problem so we just convert all PNG to JPG
	if (IsValidPNG($inputFileDir) == TRUE)
	{
		$inputFileDir = ConvertToJPG($inputFileDir);
	}

	$targetName = NewNameJPG();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";

	if ($Animate == 'on')
	{
		for ($i=0; $i < 8; $i++)
		{
			$targetName = TMPJPG();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "convert $inputFileDir -matte -mattecolor '#CCC6' -frame $border \( -size $dims plasma:$color1-$color2 -normalize -blur 0x1 \) -compose DstOver -composite $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("XPEARLBORDER $command");
			$FileList .= $outputFileDir;
			$FileList .= " ";
		}
		$targetName = NewNameGIF();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$outputFilePath = "$CONVERT_PATH$targetName";    
		$command = "convert -dispose previous -delay 20 $FileList -loop 0 $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	}
	else
	{
		$command = "convert $inputFileDir -matte -mattecolor '#CCC6' -frame $border \( -size $dims plasma:$color1-$color2 -normalize -blur 0x1 \) -compose DstOver -composite $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	}
	$outputFilePath = CheckFileSize($outputFileDir);

	RecordCommand("XPEARLBORDER $command");
	RecordCommand("XPEARLBORDER FINAL $outputFilePath");

	RecordAndComplete("BORDER",$outputFilePath,FALSE);
?>
