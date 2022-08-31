<?php
include '../zcommon/common.inc';
include '../zcommon/pig.inc';


declare (ticks=5)
{
if (CompleteWithNoAction()) return;


$LastOperation = $X_POPART;

$VALID_COLORS = array(
        "red","green","cyan","orange","purple","yellow",
        "pink", "olive","blue","yellow","silver","lime","magenta",
        "teal","orange","navy","red", "gold","aqua",
        "orange","yellow","red","lime");

	$ArgSize = $_POST['SIZE'];
	$ArgTime = $_POST['TIME'];
	$ArgType = $_POST['TYPE'];
	$ArgSeparation = $_POST['SEPARATION'];
	$ArgBackgroundColor = $_POST['COLOR'];
	$ArgAbstract = $_POST['ABSTRACT'];


    if (isset($ArgBackgroundColor) == FALSE)
        $ArgBackgroundColor = '#FF9900';



    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);
	$inputFileDir = ConvertToJPG($inputFileDir);
	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);

	RecordCommand("XPOPART ARGUMENTS $inputFileDir $ArgType $ArgSize $ArgTime $ArgSeparation $ArgBackgroundCoor");

    // resize source image if too big
    GetImageAttributes($inputFileDir,$width,$height,$size);
    if ($size > 10000)
    {
		if (($width > 250) || (height > 250))
		{
			$width = $height = 250;
			$inputFileDir = ResizeImage($inputFileDir,$width,$height,FALSE);
			$inputFileName = basename($inputFileDir);
		}
    }

	if ($ArgSize == '2x1') $Count = 2;
	if ($ArgSize == '1x2') $Count = 2;
	if ($ArgSize == '2x2') $Count = 4;
	if ($ArgSize == '3x3') $Count = 9;
	if ($ArgSize == '4x4') $Count = 16;
	if ($ArgSize == '5x5') $Count = 25;
	if ($ArgSize == '6x6') $Count = 36;
	if ($ArgSize == '7x7') $Count = 49;
	if ($ArgSize == '8x8') $Count = 64;

	if ($ArgType == 'STATIC')
	{
		$imageList = GenerateImageList($Count, $inputFileDir);
		$outputFileName = NewName($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";    
		$outputFilePath = "$CONVERT_PATH$outputFileName";
		$command = "montage -background '$ArgBackgroundColor' -tile $ArgSize -geometry $ArgSeparation $imageList $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("POPARTX $command");
		RecordCommand("FINAL $outputFilePath");

		$outputFilePath = CheckFileSize($outputFileDir);
		$LastOperation .= ": Static Warhol";
		RecordAndComplete("POPART",$outputFilePath,FALSE);
	}
	else if ($ArgType == 'ANIM1')
	{
		$FileList = "";
		if (($real_width > 200) || ($real_height > 200))
		{
			$inputFileDir = ResizeImage($inputFileDir,200,200,FALSE);
			RecordCommand("XPOPART RESIZED $inputFileDir");
		}
		for ($i=0; $i<7; $i++)
		{
			$imageList = GenerateImageList($Count, $inputFileDir);
			$outputFileName = TMPJPG();
			$outputFileDir = "$CONVERT_DIR$outputFileName";    
			$command = "montage -background '$ArgBackgroundColor' -tile $ArgSize -geometry $ArgSeparation $imageList $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

			GetImageAttributes($outputFileDir,$width,$height,$size);
			if ($size > 90000)
			{
				$width = $height = 400;
				$outputFileDir = ResizeImage($outputFileDir,$width,$height,FALSE);
			}

			$FileList .= $outputFileDir;
			$FileList .= " ";

		}

		$outputFileName = NewNameGIF();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
		$command = "convert -dispose previous -delay $ArgTime $FileList -loop 0 $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("FINAL $outputFilePath");

		$outputFilePath = ReduceSize($outputFileName);

		$LastOperation .= ": Animated Warhol";
		RecordAndComplete("POPART",$outputFilePath,FALSE);

	}
	else if ($ArgType == 'ANIM2')
	{
		$FileList = "";
		for ($i = 0; $i < $Count; $i++)
		{
			$imageArray[] = TriColorImage($inputFileDir);
		}
		shuffle($imageArray);
		foreach ($imageArray as $image)
			$FileList .="$image ";

		$outputFileName = NewNameGIF();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay $ArgTime $FileList -loop 0 $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XPOPART FINAL $outputFilePath");

		//$outputFilePath = ReduceSize($outputFileName);

		$LastOperation .= ": Animated Single";
		RecordAndComplete("POPART",$outputFilePath,FALSE);
	}
	else if ($ArgType == 'ANIM3')
	{
		$FileList = "";
		for ($i = 0; $i < $Count; $i++)
			$imageArray[] = TriColorImage($inputFileDir);

		$imageCount = count($imageArray);
		for ($i=0; $i<7; $i++)
		{
			$imageList = "";

			for ($j=0; $j<1; $j++)
			{
				$src = rand(0,$imageCount-1);
				$dst = rand(0,$imageCount-1);
				$t = $imageArray[$dst];
				$imageArray[$dst] = $imageArray[$src];
				$imageArray[$src] = $t;
			}
			//shuffle($imageArray);
			foreach ($imageArray as $image)
				$imageList .="$image ";

			$outputFileName = TMPJPG();
			$outputFileDir = "$CONVERT_DIR$outputFileName";    
			$command = "montage -background '$ArgBackgroundColor' -tile $ArgSize -geometry $ArgSeparation $imageList $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

			GetImageAttributes($outputFileDir,$width,$height,$size);
			if ($size > 90000)
			{
				$width = $height = 400;
				$outputFileDir = ResizeImage($outputFileDir,$width,$height,FALSE);
			}

			$FileList .= $outputFileDir;
			$FileList .= " ";
		}

		$outputFileName = NewNameGIF();
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
		$command = "convert -dispose previous -delay $ArgTime $FileList -loop 0 $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XPOPART FINAL $outputFilePath");

		$outputFilePath = ReduceSize($outputFileName);

		$LastOperation .= ": Spotted Warhol";
		RecordAndComplete("POPART",$outputFilePath,FALSE);
	}
}

function GenerateImageList($count, $inputFileDir)
{
global $VALID_COLORS;

	$imageList = "";

	$colorCount = count($VALID_COLORS)-1;
	for ($i = 0; $i < $count; $i++)
	{
		$imageArray[] = TriColorImage($inputFileDir);
	}

	shuffle($imageArray);
	for ($i = 0; $i < $count; $i++)
		$imageList .="$imageArray[$i] ";
	return $imageList;

}

function ColorImage($imageDir, $color)
{
global $CONVERT_DIR;

    $targetName = basename($imageDir);
    $targetName = TMPJPG();
    $outputFileDir = "$CONVERT_DIR$targetName";

	$command = "convert -modulate 130,170 -tint 80% -fill $color $imageDir $outputFileDir";

    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    return $outputFileDir;
}

function TriColorImage($imageDir)
{
global $CONVERT_DIR;
global $VALID_COLORS;
global $ArgAbstract;

	$colorCount = count($VALID_COLORS);

	while (1)
	{
		$r = rand(0,$colorCount-1);
		$c1 = $VALID_COLORS[$r];

		$r = rand(0,$colorCount-1);
		$c2 = $VALID_COLORS[$r];

		$r = rand(0,$colorCount-1);
		$c3 = $VALID_COLORS[$r];
		if (($c3 != $c1) && ($c3 != $c2) && ($c2 != $c1)) break;
	}

	if ($ArgAbstract == 'on')
	{
		$targetName = TMPJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$command = "../zshells//mytri.sh -l $c1 -m $c2 -h $c3 $imageDir $outputFileDir";

		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("TRICOLORIMAGE $command");
	}
	else
	{
		$outputFileDir = ColorImage($imageDir,$c3);
	}
    return $outputFileDir;
}


function ReduceSize($inputFileName)
{
global $CONVERT_DIR;
global $CONVERT_PATH;

    $inputFileDir = "$CONVERT_DIR$inputFileName";
    $outputFileName = NewName($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "convert -layers Optimize $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    return $outputFilePath;
}

?>
