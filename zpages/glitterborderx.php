<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$eastOperation = "$X_GLITTER";

$EX_DIR = "$BASE_DIR/wimages/glitters/";
$EX_PATH = "$BASE_PATH/wimages/glitters/";
$DEFAULT = "sil04.gif";


	$Setting = $_POST['SETTING'];
	$Width = $_POST['WIDTH'];
	$Animate = $_POST['ANIMATE'];
	$Inset = $_POST['INSET'];
	
    $Setting = StripSuffix($Setting);
    $patternFileDir = "$EX_DIR$Setting$GIFSUFFIX";

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
	GetImageAttributes($inputFileDir,$inputFile_width,$inputFile_height,$size);

	$w = $inputFile_width + ($Width * 2);
	$h = $inputFile_height + ($Width * 2);
	$dims = $w."x".$h;
	//RecordCommand(" $inputFile_width $inputFile_height $Width $w $h");


	//animated PNGS have a problem so we just convert all PNG to JPG
	if (IsValidPNG($inputFileDir) == TRUE)
	{
		$inputFileDir = ConvertToJPG($inputFileDir);
	}


	$patternFileDir = "$EX_DIR$Setting$GIFSUFFIX";
	if ($Animate == 'on')
	{
        $imageList = GetAnimatedImages($patternFileDir);

		foreach ($imageList as $imageFileDir)
		{
			$targetName = TMPGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "convert -size $dims tile:$imageFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand(" $command");
			$imageFileDir = ResizeImage($outputFileDir, $w, $h, TRUE);
			RecordCommand(" resize $w $h");

			$targetName = TMPJPG();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "convert $inputFileDir -matte -mattecolor '#CCC6' -frame $border \( $imageFileDir \) -compose DstOver -composite $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand(" $command");
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
		$targetName = TMPGIF();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$command = "convert -size $dims tile:$patternFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$patternFileDir = ResizeImage($outputFileDir, $w, $h, TRUE);
		RecordCommand(" resize $w $h");

		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$outputFilePath = "$CONVERT_PATH$targetName";

		$command = "convert $inputFileDir -matte -mattecolor '#CCC6' -frame $border \( $patternFileDir -normalize \) -compose DstOver -composite $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	}
	$outputFilePath = CheckFileSize($outputFileDir);

	RecordCommand(" $command");
	RecordCommand("FINAL $outputFilePath");

	RecordAndComplete("BORDER,",$outputFilePath,FALSE);
?>
