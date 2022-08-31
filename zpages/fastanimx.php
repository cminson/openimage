<?php
include '../zcommon/common.inc';
include '../zcommon/pig.inc';

$MAXANIMFILESIZE = 10000000;	//10M max upload size for all files
$MAXANIMFILESIZE = 5000000;	//5M max upload size for all files

declare (ticks=5)
{
if (CompleteWithNoAction()) return;



$Title = $X_FRAMEANIMATION;
$LastOperation = $X_FRAMEANIMATION;

$MAX_ANIM = 52;

$current = $_POST['CURRENTFILE'];
$inputFileDir = $current;
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileName = basename($inputFileDir);

$ArgLoop = $_POST['LOOP'];
$ArgTime = $_POST['TIME'];
$ArgResize = $_POST['RESIZE'];
$ArgAnimation = $_POST['ANIMATION'];

//RecordCommand("$inputFileDir $ArgLoop $ArgTime $ArgResize $ArgAnimation");
if (isset($ArgLoop) == FALSE)
		$ArgLoop = 0;
if (isset($ArgTime) == FALSE)
		$ArgTime = 50;

$tmpName = array();
$sourceName = array();
$sourceFilePath = array();
$targetName = array();
$outputFileDir = "$BASE_DIR$current";
$FileArray = array();

    if ($ArgAnimation == 'TRUE')
    {
		// first the original files (posted, some of them possibly delete)
        for ($i = 1; $i <= $MAX_ANIM; $i++)
        {
            $file = $_POST["FILENAME$i"];
			$seq = $_POST["SEQ$i"];
            if (strlen($file) <= 1)
                continue;
            //save off the first image, in case ALL images get deleted
            //(in which case we stick this first image back in so that
            //something will display at least)
            if ($i == 1)
                $baseFileDir = "$CONVERT_DIR$file";
            $delete = $_POST["DELETE$i"];
            if ($delete != "on")
            {
                //$FileArray[$seq] = "$CONVERT_DIR$file";
                $FileArray[] = "$CONVERT_DIR$file";
				//RecordCommand("FILEARRAY SET $seq: $file");
				//RecordCommand("1: $FileArray[1] 2: $FileArray[2] 3: $FileArray[3]");
				
            }
			else
			{
				RecordCommand(": DELETE $i");
			}
        }
		// FileArray now holds all the old files
		// now get any new files and put them into FileArray as well
        for ($j = 0; $j <= $MAX_ANIM; $j++)
        {
            $rand = MakeRandom();
            $tmpName[$j] = $_FILES["FILENAME$j"]['tmp_name']; 
            $sourceFilePath[$j] = $_FILES["FILENAME$j"]['name']; 
            $sourceName[$j] = basename($sourceFilePath[$j]); 
            $targetName[$j] = $sourceName[$j];

            if (filesize($tmpName[$j]) != 0)
            {
                $outputFileDir = MakeTMPRandomTextName($CONVERT_DIR, $targetName[$j], $rand);  
                $outputFilePath = MakeTMPRandomTextName($CONVERT_PATH, $targetName[$j], $rand);
                move_uploaded_file($tmpName[$j], $outputFileDir);    
                $FileArray[] = $outputFileDir;
				//RecordCommand("Loading $j $outputFileDir");
            }

        }
    }
    else
    {
        $FileArray[] = $outputFileDir;
        for ($i = 2; $i <= $MAX_ANIM; $i++)
        {
            $rand = MakeRandom();
            $tmpName[$i] = $_FILES["FILENAME$i"]['tmp_name']; 
            $sourceFilePath[$i] = $_FILES["FILENAME$i"]['name']; 
            $sourceName[$i] = basename($sourceFilePath[$i]); 
            $targetName[$i] = $sourceName[$i];

            if (filesize($tmpName[$i]) != 0)
            {
                $outputFileDir = MakeTMPRandomTextName($CONVERT_DIR, $targetName[$i], $rand);  
                $outputFilePath = MakeTMPRandomTextName($CONVERT_PATH, $targetName[$i], $rand);
                move_uploaded_file($tmpName[$i], $outputFileDir);    

				//RecordCommand("UPLOAD $outputFileDir");
				if (IsValidTIF($outputFileDir))
				{
					$outputFileDir = ConvertTIF($outputFileDir);
					$outputFileName = basename($outputFileDir);
					$outputFilePath = "$CONVERT_PATH$outputFileName";
					//RecordCommand("TIFF Convert $outputFileDir");
				}

                $FileArray[] = $outputFileDir;
				//RecordCommand(" Loading $j $outputFileDir");
            }
        } //end for
    }

	// make sure max size is not exceeded
	$size = 0;
	foreach ($FileArray as $file)
	{
		$size += filesize($file);
		//RecordCommand("FILESIZEDEV CHECK $size $file");
		if ($size > $MAXANIMFILESIZE)
		{
			ReportError("Max file size of 10M exceeded. Try with fewer or smaller files");

		}
	}

        //automatically resize all images, if this option selection
        if ($ArgResize == 'on')
        {
            GetImageAttributes($FileArray[0],$real_width,$real_height,$size);
			//RecordCommand("GETTING SIZE $FileArray[0] $real_width $real_height");
            $count = count($FileArray);
            for ($i = 1; $i < 100; $i++)
            {
                $file = $FileArray[$i];
				if (strlen($file) > 3)
				{
					$resizeFile = ResizeTMPImage($file,$real_width,$real_height,TRUE);
					$FileArray[$i] = $resizeFile;
					RecordCommand("RESIZEDZ $i $file $resizeFile $real_width $real_height");
				}
            }
        }

        $FileList="";
		$count = count($FileArray);
        if ($count == 0)
            $FileArray[0] = $baseFileDir;
		for ($i = 0; $i < 100; $i++)
        {
			$file = $FileArray[$i];
			if (strlen($file) > 3)
			{
				$FileList .= "$file ";
				RecordCommand("FILELIST $file");
			}
        }
            

        //do the conversion
        $targetName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$targetName";
        $outputFilePath = "$CONVERT_PATH$targetName";

        $command = "convert -dispose previous -delay %TIME  %FILES -loop %LOOP $outputFileDir";
        $command = str_replace("%TIME", $ArgTime, $command);
        $command = str_replace("%FILES", $FileList, $command);
        $command = str_replace("%LOOP", $ArgLoop, $command);
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
        RecordCommand("$command");

        GetImageAttributes($outputFileDir,$real_width,$real_height,$size);
        if ($size > 1800000)
        {
			if (($real_width > 800) || ($real_height > 800))
			{
            RecordCommand("RESIZING $size $outputFileDir");
            $outputFileDir = ResizeImage($outputFileDir,800,800,FALSE);
            RecordCommand("RESIZED $outputFileDir");
            $targetName = basename($outputFileDir);
            $outputFilePath = "$CONVERT_PATH$targetName";
			}
        }


        if ($ArgLoop == 0)
            $loops = "Infinite";
        else
            $loops = $ArgLoop;
        switch ($ArgTime)
        {
        case 300:
            $time = "3 seconds";
            break;
        case 200:
            $time = "2 seconds";
            break;
        case 100:
            $time = "1 seconds";
            break;
        case 50:
            $time = "1/2 second";
            break;
        case 25:
            $time = "1/4 second";
            break;
        case 20:
            $time = "1/5 second";
            break;
        case 10:
            $time = "1/10 second";
            break;
        case 1:
            $time = "1/100 second";
            break;
        }

        RecordCommand("FINAL $outputFilePath");

		RecordAndComplete("ANIM",$outputFilePath,FALSE);
}
?>
