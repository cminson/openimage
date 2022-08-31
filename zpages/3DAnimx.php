<?php
include '../zcommon/common.inc';
include '../zcommon/pig.inc';

RecordCommand("ENTER");

declare (ticks=5)
{
if (CompleteWithNoAction()) return;

$Title = $X_3DSHAPEANIMATIONS;
$LastOperation = $X_3DANIMATED;


$DEFAULT="HBOXPLAIN.gif";

RecordCommand("3DANIM START");

$MAX_ANIM = 6;

$current = $_POST['CURRENTFILE'];
$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);



    $ArgLoop =  0;
    $ArgSetting = StripSuffix($_POST['SETTING']);

	if (isset($ArgSetting) == FALSE)
		$ArgSetting = "HBOXPLAIN";

	RecordCommand("3DANIM ArgSetting = $ArgSetting");

    $lastOperation = "3D Animation: ";

/*
	$command="which convert";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("DEV $lines[0] $lines[1] $command");
	$command="convert -version";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("DEV $lines[0] $lines[1] $command");
*/

    $UploadSuccess = TRUE;

    $tmpName = array();
    $sourceName = array();
    $sourceFilePath = array();
    $targetName = array();
    $FileArray = array();

	RecordCommand("curr=$current");
    $inputFileDir = "$BASE_DIR$current";
    $current = $inputFileDir = ConvertToJPG($inputFileDir);
	$baseCurrent = basename($current);

    if (empty($baseCurrent))
    {
        $ErrorCode = 1;
        RecordDebug("XLOAD Error=$ErrorCode");
        $ErrorFile = $baseCurrent;
    }

	RecordCommand("base=$baseCurrent curr=$current");
    $FileArray[] = "$CONVERT_DIR$baseCurrent";
	RecordCommand("first file=$FileArray[0]");

    for ($i = 2; $i <= $MAX_ANIM; $i++)
    {
		$rand = MakeRandom();
		$tmpName[$i] = $_FILES["FILENAME$i"]['tmp_name']; 
		$sourceFilePath[$i] = $_FILES["FILENAME$i"]['name']; 
		$sourceName[$i] = basename($sourceFilePath[$i]); 
		$targetName[$i] = $sourceName[$i];

        if (filesize($tmpName[$i]) != 0)
        {
			$outputFileDir = MakeRandomTextName($CONVERT_DIR, $targetName[$i], $rand);  
			$outputFilePath = MakeRandomTextName($CONVERT_PATH, $targetName[$i], $rand);
			move_uploaded_file($tmpName[$i], $outputFileDir);    

			if (IsValidTIF($outputFileDir))
			{
				$outputFileDir = ConvertTIF($outputFileDir);
				$outputFileName = basename($outputFileDir);
				$outputFilePath = "$CONVERT_PATH$outputFileName";
				RecordCommand("TIFF Convert $outputFileDir");
			}

			$FileArray[] = $outputFileDir;
        }
	} //end for

    if ($UploadSuccess == TRUE)
    {

        $AnimFileArray = array();
        $baseImage = $FileArray[0];
		$fcount = count($FileArray);

		//
		// now do the conversions
		//
		if (stristr($ArgSetting,'BOX') != FALSE)
		{
            GetImageAttributes($baseImage,$width,$height,$size);
			$dim = ($width < $height) ? $width : $height;

			if ((stristr($ArgSetting,'JEWEL') != FALSE) && ($dim > 100))
				$dim = 100;
			else if ($dim > 130) $dim = 130;
/*
			if ($dim > 130) $dim = 130;
*/

			for ($i = 0; $i<6; $i++)
			{
				if ($i < $fcount)
					$targetFileDir = $FileArray[$i];
				else
					$targetFileDir = $FileArray[0];
				RecordCommand("JPG $i $targetFileDir");
				$targetFileDir = ConvertToJPG($targetFileDir);
				RecordCommand("RESIZING $i $targetFileDir");
				$targetFileDir = ResizeImage($targetFileDir,$dim,$dim,TRUE);

				$FileArray[$i] = $targetFileDir;
				//RecordCommand("RESIZED $i $targetFileDir");
			}

			$inputFileName = basename($FileArray[0]);

			$vertRotate = TRUE;
			switch ($ArgSetting)
			{
			case 'HBOXWATERCOLOR':
				$vertRotate = FALSE;
			case 'VBOXWATERCOLOR':
				for ($i = 0; $i< 6; $i++)
				{
				$inputFileDir = $FileArray[$i];
				$targetName = TMPName($inputFileName);
				$outputFileDir = "$CONVERT_DIR$targetName";
				$outputFilePath = "$CONVERT_PATH$targetName";
				$command = "convert -modulate 100,200 -sharpen 10x10 -median 5 -paint 2 $inputFileDir $outputFileDir";
				$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
				$AnimFileArray[] = $outputFileDir;
				}
				$lastOperation .= " Rotating Watercolored Box";
				break;
			case 'HBOXFOSSIL':
				$vertRotate = FALSE;
			case 'VBOXFOSSIL':
				for ($i = 0; $i< 6; $i++)
				{
				$inputFileDir = $FileArray[$i];
				$targetName = TMPName($inputFileName);
				$outputFileDir = "$CONVERT_DIR$targetName";
				$outputFilePath = "$CONVERT_PATH$targetName";
				$command = "convert -blur 0x1  -shade 120x21.78 -normalize -raise 5x5 $inputFileDir $outputFileDir";
				$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
				RecordCommand("$command");
				$AnimFileArray[] = $outputFileDir;
				}
				$lastOperation .= "Rotating Fossilized Box";
				break;
			case 'HBOXJEWEL':
				$vertRotate = FALSE;
			case 'VBOXJEWEL':
				for ($i = 0; $i< 6; $i++)
				{
				$inputFileDir = $FileArray[$i];
				$targetName = TMPName($inputFileName);
				$outputFileDir = "$CONVERT_DIR$targetName";
				$outputFilePath = "$CONVERT_PATH$targetName";
				$command = "convert $inputFileDir \
				\( -clone 0 -resize 130% -fill white -colorize 40% \) \
				\( -clone 0 -bordercolor black -border 1x1 \) \
				-delete 0 -gravity center -composite  $outputFileDir";
				RecordCommand("BOXJEWEL ENTER $command");
				$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
				RecordCommand("BOXJEWEL ENTERING $command");
				$AnimFileArray[] = $outputFileDir;
				RecordCommand("BOXJEWEL LEFT $command");
				}
				$lastOperation .= "Rotating Mirror Box";
				break;
			case 'HBOXHDR':
				$vertRotate = FALSE;
			case 'VBOXHDR':
				for ($i = 0; $i< 6; $i++)
				{
				$inputFileDir = $FileArray[$i];
				$targetName = TMPName($inputFileName);
				$outputFileDir = "$CONVERT_DIR$targetName";
				$outputFilePath = "$CONVERT_PATH$targetName";
				$command = "../zshells/mkhdr.sh 13 $inputFileDir $outputFileDir";
				RecordCommand("$command");
				$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
				$AnimFileArray[] = $outputFileDir;
				}
				$lastOperation .= "Rotating HDR Energized Box";
				break;
			case 'HBOXGOLD':
				$vertRotate = FALSE;
			case 'VBOXGOLD':
				for ($i = 0; $i< 6; $i++)
				{
				$inputFileDir = $FileArray[$i];
				$targetName = TMPName($inputFileName);
				$outputFileDir = "$CONVERT_DIR$targetName";
				$outputFilePath = "$CONVERT_PATH$targetName";
				$command = "convert -shade 60x21.78 -normalize -raise 9x9 -tint 100 -fill gold $inputFileDir $outputFileDir";
				$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
				$AnimFileArray[] = $outputFileDir;
				}
				$lastOperation .= "Rotating Gold Box";
				break;
			case 'HBOXBW':
				$vertRotate = FALSE;
			case 'VBOXBW':
				for ($i = 0; $i< 6; $i++)
				{
				$inputFileDir = $FileArray[$i];
				$targetName = TMPName($inputFileName);
				$outputFileDir = "$CONVERT_DIR$targetName";
				$outputFilePath = "$CONVERT_PATH$targetName";
				$command = "convert -type Grayscale -black-threshold 50% -white-threshold 50% $inputFileDir $outputFileDir";
				$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
				RecordCommand("PRE $command");
				$AnimFileArray[] = $outputFileDir;
				}
				$lastOperation .= "Rotating Black White Box";
				break;
			case 'HBOXPLAIN':
				$vertRotate = FALSE;
			case 'VBOXPLAIN':
				for ($i = 0; $i< 6; $i++)
					$AnimFileArray[] = $FileArray[$i];
				$lastOperation .= "Rotating Box";
				break;
			}
			$FileList = "";
			foreach ($AnimFileArray as $file)
				$FileList .= "$file ";

			$targetName = basename($current);
			$targetName = NewNameGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$outputFilePath = "$CONVERT_PATH$targetName";
			if ($vertRotate == TRUE)
				$command = "../zshells/3DAnimBoxVert.sh $FileList $outputFileDir";
			else
				$command = "../zshells/3DAnimBoxHori.sh $FileList $outputFileDir";

			RecordCommand("$command");
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			$l = "";
			foreach ($lines as $line)
				RecordCommand("DEV $command $line");
		}	//end box conversions
		else if (stristr($ArgSetting,'ZROT') != FALSE)  // a z rotate conversion
		{
            GetImageAttributes($inputFileDir,$width,$height,$size);
			$dim = ($width < $height) ? $width : $height;
			if ($dim > 400) $dim = 400;
				$inputFileDir = ResizeImage($inputFileDir,$dim,$dim,FALSE);


			$targetName = basename($current);
			$targetName = NewNameGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$outputFilePath = "$CONVERT_PATH$targetName";

			RecordCommand("ArgSetting = $ArgSetting");
			
			switch ($ArgSetting)
			{
			case 'ZROT1':
				$command = "../zshells/rota1.sh $inputFileDir $outputFileDir";
				break;
			case 'ZROT2':
				$command = "../zshells/rota2.sh $inputFileDir $outputFileDir";
				break;
			case 'ZROT3':
				$command = "../zshells/rota3.sh $inputFileDir $outputFileDir";
				break;
			case 'ZROT4':
				$command = "../zshells/rota4.sh $inputFileDir $outputFileDir";
				break;
			case 'ZROT5':
				$command = "../zshells/rota5.sh $inputFileDir $outputFileDir";
				break;
			case 'ZROT6':
				$command = "../zshells/rota6.sh $inputFileDir $outputFileDir";
				break;
			}

			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("DEV $lines[0] $lines[1] $command");
			RecordCommand("ROTA $command");
		}
		else // we're a misc 3D conversion
		{
			RecordCommand("NONBOX $command");
			if ($fcount < 2)
			{
			$targetName = basename($current);
			$targetName = TMPName($targetName);
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "convert -rotate 90 $inputFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			$FileArray[] = $outputFileDir;

			$targetName = basename($current);
			$targetName = TMPName($targetName);
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "convert -rotate 180 $inputFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			$FileArray[] = $outputFileDir;

			$targetName = basename($current);
			$targetName = TMPName($targetName);
			$outputFileDir = "$CONVERT_DIR$targetName";
			$command = "convert -rotate 270 $inputFileDir $outputFileDir";
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			$FileArray[] = $outputFileDir;
			$fcount = 4;
			}

            GetImageAttributes(baseImage,$width,$height,$size);
            $count = 5;
            for ($i = 0; $i < $count; $i++)
			{
				$r = rand(0, $fcount -1);
                $file1 = $FileArray[$r];
				if ($r++ >= $fcount-1) $r = 0;
                $file2 = $FileArray[$r];
				if ($r++ >= $fcount-1) $r = 0;
                $file3 = $FileArray[$r];
				if ($r++ >= $fcount-1) $r = 0;
                $file4 = $FileArray[$r];
               $AnimFileArray[$i] = Image3D($file1,$file2,$file3,$file3);
			}


			if (count($AnimFileArray) == 0)
				$AnimFileArray[] = $baseFileDir;
			foreach ($AnimFileArray as $file)
			{
            $FileList .= "$file ";
            //RecordCommand("XFILELIST $file");
			}
            
			//do the conversion
			$targetName = NewNameGIF();
			$outputFileDir = "$CONVERT_DIR$targetName";
			$outputFilePath = "$CONVERT_PATH$targetName";

			$command = "convert -dispose previous -delay 50 %FILES -loop $ArgLoop $outputFileDir";
			$command = str_replace("%FILES", $FileList, $command);
			$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
			RecordCommand("$command");

			GetImageAttributes($outputFileDir,$width,$height,$size);
			if ($size > 600000)
			{
            RecordCommand("XRESIZING $size $outputFileDir");
            $outputFileDir = ResizeImage($outputFileDir,300,300,FALSE);
            RecordCommand("RESIZED $outputFileDir");
            $targetName = basename($outputFileDir);
            $outputFilePath = "$CONVERT_PATH$targetName";
			}
			if ($ArgLoop == 0)
				$loops = "Infinite";
			else
				$loops = $ArgLoop;
		} //end nonbox conversions

        //$lastOperation = "$lastOperation   (Loops: $loops Interval: $time)";
        RecordCommand("FINAL $outputFilePath");

		RecordAndComplete("3DANIM",$outputFilePath,FALSE);

    }
}



function Image3D($f1,$f2,$f3,$f4)
{
global $CONVERT_DIR;
global $ArgSetting; 
global $lastOperation; 

    $targetName = basename($baseImage);
    $targetName = TMPName($targetName);
    $outputFileDir = "$CONVERT_DIR$targetName";

	$t = array();
	$t[] = $f1;
	$t[] = $f2;
	$t[] = $f3;
	$t[] = $f4;
	shuffle($t);
	$baseImage = $t[0];
	$animImage = $t[1];
	$animImage2 = $t[2];
	$animImage3 = $t[3];


    switch ($ArgSetting)
    {
    case 'MFLAT':  //flat
        $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $animImage -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $animImage3 -matte +distort Affine '511,0 0,0 0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $animImage2 -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir";
		$lastOperation = "3D Animation: Flat Box";
        break;
    case 'MGIFT': //gift
        $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 1024x1024! $animImage -matte +distort Affine '0,1024 0,0   0,0 -174,-100  1024,1024 174,-100' \) \( -resize 1024x1024! $animImage2 -matte +distort Affine '1024,0 0,0   0,0 -174,-100  1024,1024 0,200' \) \( -resize 638x638! $animImage3 -matte +distort Affine '  0,0 0,0   0,638 0,200    638,0 174,-100' \) -background white -layers merge +repage $outputFileDir";
		$lastOperation = "3D Animation: Gift Box";
        break;
    case 'MBOOK': //book
       $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 400x80! -rotate 90 $baseImage -matte  +distort Perspective '0,0 -60,40   0,398 -60,358   78,398 0,398   78,0 0,0' \) \( -resize 300x400! $animImage -matte  +distort Perspective '0,0 0,0   0,398 0,398   298,398 198,310   298,0 198,60' \) -background white -layers merge  +repage $outputFileDir";
		$lastOperation = "3D Animation: Book Box";
        break;
    case 'MQUAD': //quad
        $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 1022x1022! $baseImage -matte +distort Affine '  0,0 -248,-200   0,1022 -248,-0    1022,1022 0,-200' \) \( -resize 1022x1022! $animImage -matte +distort Affine '0,0 0,-400   0,1022 0,-200  1022,0 248,-200' \) \( -resize 1022x1022! $animImage2 -matte +distort Affine '1022,0 0,0   0,0 -248,-200  1022,1022 0,200' \) \( -resize 1022x1022! $animImage3 -matte +distort Affine '  0,0 0,0   0,1022 0,200    1022,0 248,-200' \) -background white -layers merge +repage $outputFileDir";
		$lastOperation = "3D Animation: Quad";
        break;
	default:
		RecordCommand("ARGSET ERROR $ArgSetting");
        $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 1022x1022! $baseImage -matte +distort Affine '  0,0 -248,-200   0,1022 -248,-0    1022,1022 0,-200' \) \( -resize 1022x1022! $animImage -matte +distort Affine '0,0 0,-400   0,1022 0,-200  1022,0 248,-200' \) \( -resize 1022x1022! $animImage2 -matte +distort Affine '1022,0 0,0   0,0 -248,-200  1022,1022 0,200' \) \( -resize 1022x1022! $animImage3 -matte +distort Affine '  0,0 0,0   0,1022 0,200    1022,0 248,-200' \) -background white -layers merge +repage $outputFileDir";
		$lastOperation = "3D Animation: Quad";

    }
    RecordCommand("$ArgSetting $command");
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    return $outputFileDir;
}

?>
