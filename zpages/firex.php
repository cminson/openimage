<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation = $X_FIREANDRAIN;


   $VALID_COLORS = array(
           "red","green","cyan","orange","purple","yellow",
                   "pink", "olive","blue","silver","lime","maroon",
                           "teal","orange","navy","gold","aqua",
                                   "orange","yellow","lime");




$DEFAULT="fire.gif";
$FIRERAIN_DIR = "$BASE_DIR/wimages/firerain/";

$ErrorCode = 0;

    $rand = MakeRandom();

    //get the command parameters
    $effect = $_POST['SETTING'];
    if (strlen($effect) < 2)
        $effect = $DEFAULT;

    $peffect = StripSuffix($effect);
    //$LastOperation = "$LastOperation: $peffect";

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$inputFileDir = ConvertToJPG($inputFileDir);
    $inputFileName = basename($inputFileDir);

    //get size of target image, resize if necessary
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 35000)
    {
        $real_width = $real_height = 300;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
		$inputFileName = basename($inputFileDir);
	}
    $imageFileDir = $inputFileDir;


//$peffect = "quake3";
RecordCommand("XFIRERAIN Effect: $peffect");

switch ($peffect)
{
case 'breeze':
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
    $command = "../zshells/anflag.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'multiverse':
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_width,TRUE);
		$inputFileName = basename($inputFileDir);
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


    $FileList .= " ";
	for ($j =0; $j < 7; $j++)
	{
		$colorCount = count($VALID_COLORS)-1;
		$qlist = "";
		$qarray = array();
		$r = rand(0,$colorCount);
		
		foreach ($q as $iq)
		{        
			$color = $VALID_COLORS[$r];
			$outputFileName = TMPName($inputFileName);
			$outputFileDir = "$CONVERT_DIR$outputFileName";
			$command = "convert -tint 90 -fill $color $iq $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			//RecordCommand("XTMP $command");
			$qarray[] = $outputFileDir;
			$r += 1;
			if ($r >= $colorCount)
				$r = 0;
		}

		//shuffle($qarray);
		foreach ($qarray as $x)
		{
			$qlist .= $x;
			$qlist .= " ";
		}

		//and build a compsite image that is a montage of them
		$outputFileName = NewName($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";

		$command = "montage -background transparent -tile 4x4 -geometry +0+0 $qlist $outputFileDir";
        RecordCommand("XFIRERAIN $command");
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList .= $outputFileDir;
        $FileList .= " ";
	}

	// now animate the result
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	//$command = "convert -dispose previous -delay 10 %FILES -loop 0 $outputFileDir";
	$command = "convert -dispose previous -delay 10 %FILES -loop 0 $outputFileDir";
	$command = str_replace("%FILES", $FileList, $command);
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;

case 'singularity':
        $real_width = 200;
        $real_height = 200;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
        $inputFileName = basename($inputFileDir);
        $height = 0;
        $inc = $real_height / 9;
        for ($i=0; $i < 9; $i++)
        {
			$targetName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$targetName";
            $command = "convert -roll +0+$height";
            $command = "$command $inputFileDir $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
            $FileList .= $outputFileDir;
            $FileList .= " ";
            $height += $inc;
        }
        $specialArg = '-virtual-pixel background -background transparent -distort arc 120  +repage';
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert $specialArg -dispose previous -delay 20 $FileList -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'quake1':
case 'quake2':
    $interval = 50;

    // first, border the image
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir -bordercolor LimeGreen -border $interval $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XFIRERAIN-EARTHQUAKE border $command");
    $inputFileDir = $outputFileDir;

    $targetName = NewNameGIF(); 
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -transparent LimeGreen $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XFIRERAIN-EARTHQUAKE border $command");
    $inputFileDir = $outputFileDir;
    $FileList = $inputFileDir;
    $FileList .= " ";

    // jump the image 
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";

	if ($peffect == 'quake1')
		$command = "convert $inputFileDir -roll +0+$interval ";
	else
		$command = "convert $inputFileDir -roll +$interval+0";
    $command = "$command $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XFIRERAIN-EARTHQUAKE roll $command");
    $FileList .= $outputFileDir;
    $FileList .= " ";

    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
	if ($peffect == 'quake1')
		$command = "convert $inputFileDir -roll +0-$interval ";
	else
		$command = "convert $inputFileDir -roll -$interval+0";
    $command = "$command $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XFIRERAIN-EARTHQUAKE roll $command");
    $FileList .= $outputFileDir;
    $FileList .= " ";

    // create the animation
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";        
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -dispose previous -delay 10 %FILES -loop 0 $outputFileDir";
    $command = str_replace("%FILES", $FileList, $command);
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'quake3':
    $interval = 50;

    // first, border the image
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert $inputFileDir -bordercolor LimeGreen -border $interval $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    RecordCommand("XFIRERAIN-EARTHQUAKE border $command");
    $inputFileDir = $outputFileDir;

    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -transparent LimeGreen $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XFIRERAIN-EARTHQUAKE border $command");
    $inputFileDir = $outputFileDir;
    $FileList = $inputFileDir;
    $FileList .= " ";

    // jump the image 
	for ($i = 0; $i < 8; $i++)
	{
		$targetName = NewNameGIF();
		$outputFileDir = "$CONVERT_DIR$targetName";

		$x = rand(-$interval,$interval);
		$y = rand(-$interval,$interval);
		if ($x >= 0) $x = "+$x";
		if ($y >= 0) $y = "+$y";
		$command = "convert $inputFileDir -roll $x$y $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand("XFIRERAIN-EARTHQUAKE $command");
		$FileList .= $outputFileDir;
		$FileList .= " ";
	}

    // create the animation
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";        
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -dispose previous -delay 10 %FILES -loop 0 $outputFileDir";
    $command = str_replace("%FILES", $FileList, $command);
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
break;
case 'tornado':
        $targetName = "cjm-ezimba";
        if (($real_width > 200) || ($real_height > 200))
        {
        $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";        
        $command="convert -resize 200x200 -swirl 5 -virtual-pixel background -distort arc 361  +repage $inputFileDir $outputFileDir";
        $command="convert -resize 200x200 - -virtual-pixel background -distort arc 361  +repage $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $inputFileDir = $outputFileDir;
        }

        $FileList = "";
        for ($i=0; $i<8; $i++)
        {
            $targetName = "cjm-ezimba$i";
            $outputFileDir = "$CONVERT_DIR$targetName$GIFSUFFIX";        
            $swirl = 20 + ($i * 40);
            $rot = 90 + ($i * 90);
            $command = "convert -rotate $rot  -swirl $swirl $inputFileDir $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        //RecordCommand("XFIRERAIN $command");
            $FileList .= $outputFileDir;
            $FileList .= " ";

        }

        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";        
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay 10 %FILES -loop 0 $outputFileDir";
        $command = str_replace("%FILES", $FileList, $command);
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	    //RecordCommand("XFIRERAIN $command");
break;
case 'reflection':
        GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
        if (($real_width > 300) || ($real_height > 300))
        {
            $real_width = $real_height = 300;
            $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
            $inputFileName = baseName($inputFileDir);
            GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
        }

        $distortFileDir = "$FIRERAIN_DIR"."reflection-0.gif";
        $distortFileDir = ResizeImage($distortFileDir,$real_width,$real_height,TRUE);

        $targetName = TMPGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $command = "convert -spread 8 $distortFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $distortFileDir = $outputFileDir;

        $Distortions = array();
        for ($i = 1; $i <= 10; $i++)
        {
            $targetName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$targetName";
            $roll = $i * 10;
            $command = "convert -roll +$roll+$roll $distortFileDir $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        //RecordCommand("XFIRERAIN A $command");
            $Distortions[] = $outputFileDir;
        }
        $FileList = "";
        foreach ($Distortions as $distortion)
        {
            $targetName = TMPGIF();
            $outputFileDir = "$CONVERT_DIR$targetName";
            $command = "composite $distortion $inputFileDir -displace 6x6 $outputFileDir";
            $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	        //RecordCommand("XFIRERAIN B $command");
            $FileList .= "$outputFileDir ";
        }

        $targetName = TMPGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay 40 $FileList -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $inputFileDir = $outputFileDir;

        //now post-process the image to get rid of artifacts
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -gaussian 8 $inputFileDir $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		break;
case 'mosquito1':
case 'mosquito2':
case 'mosquito3':
		$delay = 5;
		if ($peffect == "mosquito1") $delay = 5;
		if ($peffect == "mosquito2") $delay = 20;
		if ($peffect == "mosquito3") $delay = 80;

        GetImageAttributes($inputFileDir,$w,$h,$size);
        if (($w > 300) || ($h > 300))
        {
            $w = $h = 300;
            $inputFileDir = ResizeImage($inputFileDir,$w,$h,FALSE);
            $inputFileName = baseName($inputFileDir);
            GetImageAttributes($inputFileDir,$w,$h,$size);
        }
		$incx = intval($w / 15);
		$incy = intval($h / 15);
        $FileList = "";
		$files = array();

        $FileList .= "$inputFileDir ";
		$files[] = $inputFileDir;
		for ($i = 0; $i < 10; $i++)
		{
			$x1 = $incx;
			$y1 = $incy;
			$x2 = $w - $incx;
			$y2 = $h - $incy;

			$wn = $x2 - $x1;
			$hn = $y2 - $y1;
			$targetName = TMPGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "convert -background white -crop $wn"."x"."$hn+$x1+$y1 +repage $inputFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("XFR CROP $command");
			$inputFileDir = ResizeImage($outputFileDir,$w,$h,TRUE);
            $FileList .= "$inputFileDir ";
			$files[] = $inputFileDir;
		}
		$files = array_reverse($files);
		foreach ($files as $file)
			$FileList .= "$file ";
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";
        $command = "convert -dispose previous -delay $delay $FileList -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		break;
default:
    //resize effect images to target dimensions
    $FileList1 = array();
    for ($i = 0;; $i++)
    {
        $effectsFileDir = "$FIRERAIN_DIR$peffect-$i.gif";
        if (file_exists($effectsFileDir) == FALSE)
            break;

        $outputFileName = TMPGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $command = "convert -resize %ARG $effectsFileDir $outputFileDir";
        $command = str_replace("%ARG","$real_width"."x"."$real_height"."\!",$command);
	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $FileList1[] = $outputFileDir;
		RecordCommand("$command");
    }

    $AnimateString = "";
    foreach ($FileList1 as $file)
    {
        //overlay the target image with the transparent firerains
        $targetName = StripSuffix($inputFileName);
	    $outputFileName = TMPJPG();
	    $outputFileDir = "$CONVERT_DIR$outputFileName";
        if ($effect == 'love')
            $command = "composite -dissolve 50 $file $imageFileDir $outputFileDir";
        else
            $command = "composite -dissolve 50 $file $imageFileDir $outputFileDir";

	    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        $AnimateString .= "$outputFileDir ";
    }

    //now animate the resulting files
    $targetName = NewNameGIF();
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";
    $command = "convert -dispose previous -delay 25  %FILES -loop 0 $outputFileDir";
    $command = str_replace("%FILES", $AnimateString, $command);
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
}  
RecordCommand("FINAL $outputFilePath");
RecordAndComplete("FIRERAIN",$outputFilePath,FALSE);


function CropRect($inputFileName, $x1,$y1,$x2,$y2)
{
global $CONVERT_DIR;
global $GIFSUFFIX;

    $width = $x2 - $x1;
    $height = $y2 - $y1;
    $inputFileDir = "$CONVERT_DIR$inputFileName";
    $outputFileName = TMPName($inputFileName);
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $command = "convert -crop $width"."x"."$height+$x1+$y1 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    //RecordCommand("XPOPINT CROP $command");
    //RecordCommand("XPOPINT Cropping: $inputFileName $inputFileDir $outputFileDir");
    return $outputFileDir;
}
?>
