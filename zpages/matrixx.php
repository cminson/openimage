<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation=$X_MATRIX;


	//get the command parameters
	$ArgSize = $_POST['SIZE'];
	$ArgTime = $_POST['TIME'];
	$ArgHoles = $_POST['HOLES'];
	$ArgBase = $_POST['BASE'];


    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    if (IsAnimatedGIF($inputFileDir) == TRUE)
		$inputFileDir = ConvertToJPG($inputFileDir);

    // resize if too big
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 10000)
    {
        $real_width = $real_height = 250;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
        $inputFileName = basename($inputFileDir);
    }

    //have to do again to take care of aspect ratio changes!!
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);

    //generate the reality hole
    $targetName = StripSuffix($inputFileName);
    $targetName = TMPName($targetName);
    $outputFileName = "$targetName$GIFSUFFIX";
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "convert $inputFileDir -background transparent -compose Dst -flatten $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);

    $q = array();

    switch ($ArgSize)
    {
    case '2x2':
        $xDiv = $real_width / 2;
        $yDiv = $real_height / 2;
        $count = 4;
        $q[] = CropRect($inputFileName,0,0,$xDiv,$yDiv);
        $q[] = CropRect($inputFileName,$xDiv,0,$xDiv*2,$yDiv);

        $q[] = CropRect($inputFileName,0,$yDiv,$xDiv,$yDiv*2);
        $q[] = CropRect($inputFileName,$xDiv,$yDiv,$xDiv*2,$yDiv*2);
        break;
    case '3x3':
        $xDiv = $real_width / 3;
        $yDiv = $real_height / 3;
        $count = 9;
        $q[] = CropRect($inputFileName,0,0,$xDiv,$yDiv);
        $q[] = CropRect($inputFileName,$xDiv,0,$xDiv*2,$yDiv);
        $q[] = CropRect($inputFileName,$xDiv*2,0,$xDiv*3,$yDiv);

        $q[] = CropRect($inputFileName,0,$yDiv,$xDiv,$yDiv*2);
        $q[] = CropRect($inputFileName,$xDiv,$yDiv,$xDiv*2,$yDiv*2);
        $q[] = CropRect($inputFileName,$xDiv*2,$yDiv,$xDiv*3,$yDiv*2);

        $q[] = CropRect($inputFileName,0,$yDiv*2,$xDiv,$yDiv*3);
        $q[] = CropRect($inputFileName,$xDiv,$yDiv*2,$xDiv*2,$yDiv*3);
        $q[] = CropRect($inputFileName,$xDiv*2,$yDiv*2,$xDiv*3,$yDiv*3);
        break;
    case '4x4':
        $xDiv = $real_width / 4;
        $yDiv = $real_height / 4;
        $count = 16;
        $q[] = CropRect($inputFileName,0,0,$xDiv,$yDiv);
        $q[] = CropRect($inputFileName,$xDiv,0,$xDiv*2,$yDiv);
        $q[] = CropRect($inputFileName,$xDiv*2,0,$xDiv*3,$yDiv);
        $q[] = CropRect($inputFileName,$xDiv*3,0,$xDiv*4,$yDiv);

        $q[] = CropRect($inputFileName,0,$yDiv,$xDiv,$yDiv*2);
        $q[] = CropRect($inputFileName,$xDiv,$yDiv,$xDiv*2,$yDiv*2);
        $q[] = CropRect($inputFileName,$xDiv*2,$yDiv,$xDiv*3,$yDiv*2);
        $q[] = CropRect($inputFileName,$xDiv*3,$yDiv,$xDiv*4,$yDiv*2);

        $q[] = CropRect($inputFileName,0,$yDiv*2,$xDiv,$yDiv*3);
        $q[] = CropRect($inputFileName,$xDiv,$yDiv*2,$xDiv*2,$yDiv*3);
        $q[] = CropRect($inputFileName,$xDiv*2,$yDiv*2,$xDiv*3,$yDiv*3);
        $q[] = CropRect($inputFileName,$xDiv*3,$yDiv*2,$xDiv*4,$yDiv*3);

        $q[] = CropRect($inputFileName,0,$yDiv*3,$xDiv,$yDiv*4);
        $q[] = CropRect($inputFileName,$xDiv,$yDiv*3,$xDiv*2,$yDiv*4);
        $q[] = CropRect($inputFileName,$xDiv*2,$yDiv*3,$xDiv*3,$yDiv*4);
        $q[] = CropRect($inputFileName,$xDiv*3,$yDiv*3,$xDiv*4,$yDiv*4);
        break;
    }

    // lastly, make the reality hole the right size!
    $RealityHole  = CropRect($outputFileName,0,0,$xDiv,$yDiv);

    // creat list of montages with the above images
    if ($ArgBase == TRUE)
        $FileList = "$inputFileDir ";
    for ($i =0; $i < $count; $i++)
    {
        $anim  = MakeImage($inputFileName,$q,$ArgSize,$ArgHoles, $RealityHole);
        $FileList .= "$anim ";
    }

    $outputFileName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$outputFileName";
	$outputFilePath = "$CONVERT_PATH$outputFileName";
    $command = "convert -dispose previous -delay %TIME  %FILES -loop 0 $outputFileDir";
    $command = str_replace("%FILES", $FileList, $command);
    $command = str_replace("%TIME", $ArgTime, $command);
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("$command");

    $outputFilePath = ReduceSize($outputFileName);
	RecordCommand("FINAL $outputFilePath");
	RecordAndComplete("MATRIX",$outputFilePath,FALSE);


function CropRect($inputFileName, $x1,$y1,$x2,$y2)
{
global $CONVERT_DIR;
global $GIFSUFFIX;

    $width = $x2 - $x1;
    $height = $y2 - $y1;
    $inputFileDir = "$CONVERT_DIR$inputFileName";
    $targetName = StripSuffix($inputFileName);
    $outputFileName = TMPName($targetName);

    $outputFileDir = "$CONVERT_DIR$outputFileName$GIFSUFFIX";
    $command = "convert -crop $width"."x"."$height+$x1+$y1 $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	//RecordCommand(" CROP $command");
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
	//RecordCommand(" REDUCE $command");

    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 800000)
	{
        $inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
		$outputFileName = basename($inputFileDir);
		$outputFilePath = "$CONVERT_PATH$outputFileName";
	}
    return $outputFilePath;
}

function MakeImage($inputFileName, $qs,$size,$percenthole,$ahole)
{
global $CONVERT_DIR;
global $GIFSUFFIX;

    $inputFileDir = "$CONVERT_DIR$inputFileName";

    //randomize the quads
    shuffle($qs);

    // distribute the holes
    $count = count($qs);
    switch ($percenthole)
    {
    case 25:
        for ($i = 0; $i < $count; $i++)
        {
            if (($i % 4) == 0)
            {
                $qs[$i] = $ahole;
            }
        }
        break;
    case 33:
        for ($i = 0; $i < $count; $i++)
        {
            if (($i % 3) == 0)
            {
                $qs[$i] = $ahole;
            }
        }
        break;
    case 50:
        for ($i = 0; $i < $count; $i++)
        {
            if (($i % 2) == 0)
            {
                $qs[$i] = $ahole;
            }
        }
        break;
    case 75:
        for ($i = 0; $i < $count; $i++)
        {
            $q = $qs[$i];
            if (($i % 4) == 0)
            {
                $qs[$i] = $q;
            }
            else
            {
                $qs[$i] = $ahole;
            }
        }
        break;
    }

    // yes, do this TWICE so holes will be random too!
    shuffle($qs);
    foreach ($qs as $q)
        $qlist .="$q ";

    //and build a random image that is a montage of them
    $outputFileName = TMPGIF();

    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "montage -background transparent -tile $size -geometry +0+0 $qlist $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" IMAGE $command");
    return $outputFileDir;
}
?>
