<?php
include "../zcommon/common.inc";


$LastOperation = $X_FILELOADED;

$UploadSuccess = TRUE;
$ErrorCode = 0;

$xq = 0;


$remote = FALSE;
$UploadSuccess = FALSE;
$rand = MakeRandom();

$tmpName = $_FILES['FILENAME']['tmp_name']; 

$sourceFilePath = $_FILES['FILENAME']['name']; 
$sourceName = basename($sourceFilePath); 
$targetName = $sourceName;
RecordCommand("LOAD: tmp=$tmpName source=$sourceFilePath");

if (array_key_exists('URL',$_POST) == TRUE)
{
    $urlPath= $_POST['URL'];
    if ((strlen($urlPath) > 10) && (stristr($urlPath,".") != FALSE))
    {
        $remote = TRUE;
        $sourceFilePath = $urlPath;
    }
}


if ($remote == TRUE)    //loading from the web
{
	$sourceFilePath = trim($sourceFilePath);
	RecordCommand("XLOAD remote: sourceFilePath = $sourceFilePath");
    $targetName = basename($sourceFilePath);
    $suffix = GetSuffix($targetName);
    if (((stristr($suffix,"jpeg")) != FALSE) || (strlen($suffix) < 1))
    {
        $targetName = StripSuffix($targetName);
        $targetName .= "$JPGSUFFIX";
    }

	$targetName = TMPName($targetName);
    $inputFileDir = "$CONVERT_DIR$targetName";

    if (!IsValidImageFormat($inputFileDir))
    {
        //upload failed cuz bad format
        $ErrorCode = 5;
		$Error = "Bad File Format";
        //RecordCommand("XLOAD $inputFileDir Error=$ErrorCode");
    }
    else if (strncasecmp($sourceFilePath, "http",4) != 0)
    {
		//upload failed cuz the url is bad
	    $ErrorCode = 102;
		$Error = "Bad URL";
	    //RecordCommand("XLOAD Error=$ErrorCode");
    }
    else 
    {
		$s = htmlentities($sourceFilePath);
		$s = str_replace(" ","%20",$s);
        $fh_remote = @fopen($s, 'r');
        $fh_local = fopen($inputFileDir, 'w');
        if (($fh_remote == 0 ) || ($fh_local == 0))
		{
            $ErrorCode = 101;
			$Error = "This File Does Not Exist Or Is Invalid";
            //RecordCommand("XLOAD Error=$ErrorCode");
		}
		else
		{
			while (!feof($fh_remote))
			{
				$contents = fread($fh_remote, 4096);
				fwrite($fh_local,$contents);
			}
		}
		if ($fh_remote != 0)
			fclose($fh_remote);
		if ($fh_local != 0)
			fclose($fh_local);

		if ($ErrorCode == 0)
		{
		$size = filesize($inputFileDir);
		if ((file_exists($inputFileDir)) == FALSE)
		{
			$ErrorCode = 103;
			$Error = "File Does Not Exist";
			RecordCommand("XLOAD NO FILE EXISTS $inputFileDir");
		}
		else if (($size > 8000000) || ($size < 2))
		{
			$ErrorCode = 103;
			$Error = "File Is Too Large";
			RecordCommand("XLOAD Error=$ErrorCode");
		}
		else if (IsPSDImage($inputFileDir))
		{
			$ErrorCode = 9;
			$Error = "File Bad Format";
			RecordCommand("XLOAD PSD SEEN Error=$ErrorCode");
		}
		else if (!IsGoodImage($inputFileDir))
		{
			//upload failed cuz bad something
			$ErrorCode = 15;
			$Error = "Corrupt Image or  Bad Format";
			RecordCommand("XLOAD BAD IMAGE SEEN $inputFileDir Error=$ErrorCode");
		}
		else if (IsValidTIF($inputFileDir))
		{
			$inputFileDir = ConvertTIF($inputFileDir);
			$LastOperation .= "- Converted to JPG";
		}
		else if (IsAnimatedNonGIF($inputFileDir))
		{
			$inputFileDir = ConvertToGIF($inputFileDir);
			RecordCommand("XLOAD GIF CONVERT $inputFileDir");
			$LastOperation .= "- Automatically Converted to GIF";
		}
		}

		if ($ErrorCode == 0)
		{
			//copy the uploaded image so as to give it a perm name
			$targetName = NewName($inputFileDir);
			$outputFileDir = "$CONVERT_DIR$targetName";
			$outputFilePath = "$CONVERT_PATH$targetName";
			copy($inputFileDir, $outputFileDir);
			RecordCommand("XLOAD COPY $inputFileDir $outputFileDir");
			$UploadSuccess = TRUE;
		}
	}
}
else    //load from local disk
{
	//
	// special case hack: 
	// check for jpeg suffix. if exists, convert it to jpg
	// (for simplicity, we assume all file endings are 3 chars)
	//
	$suffix = GetSuffix($sourceName);
    RecordCommand("SUFFIX = $suffix");
	if (((stristr($suffix,"jpeg")) != FALSE) || (strlen($suffix) < 1))
	{
		$sourceName = StripSuffix($sourceName);
		$sourceName .= "$JPGSUFFIX";
		$targetName = $sourceName;
	}

	//
	// if nothing loaded, see if we have a previous file
	// we were working on.  if this file exists, use it.
	// if it doesnt, then report an error.
	//
	if (empty($sourceName))
	{
		//upload failed because no data entered
		$ErrorCode = 1;
		$Error = "No File Specified";
		//RecordCommand("XLOAD Error=$ErrorCode");
	}
	else if (!IsValidImageFormat($sourceName))
	{
		//upload failed due to this is not an image type we deal with
		$ErrorCode = 5;
		$Error = "File Bad Format";
		//RecordCommand("XLOAD Error=$ErrorCode");
	}
	else if (!is_uploaded_file($tmpName))
	{
		//upload failed due to size constraints or non-existence
		$Error = "File Too Large Or Doe Not Exist";
		$ErrorCode = 2;
		$t = ini_get('upload_max_filesize');
		//RecordCommand("XLOAD $errmsg $t Error=$ErrorCode");
	}
	else if (filesize($tmpName) != 0)
	{
		//means upload from a remote file system succeeded.  
		//move the tmp file into our convert directory.
		//this is our starting point for all future conversions
		//$targetName = preg_replace('/[^a-zA-Z0-9\s]/', '', $targetName);
		$targetName = NewName($targetName);
		$outputFileDir = "$CONVERT_DIR$targetName";
		$outputFilePath = "$CONVERT_PATH$targetName";
		RecordCommand("XLOAD: MOVE $tmpName $outputFileDir");
		move_uploaded_file($tmpName, $outputFileDir);

		// if a tiff, just convert to a jpg here give tifs 
		// cause us grief downstream
		if (IsTIFFImage($outputFileDir))
		{
			$outputFileDir = ConvertToJPG($outputFileDir);
			$targetName = basename($outputFileDir);
			$outputFilePath = "$CONVERT_PATH$targetName";
			RecordCommand("TIFF Convert $outputFileDir $targetName");
			$LastOperation .= "- TIFF Automatically Converted to JPG";
		}


		if (IsAnimatedNonGIF($outputFileDir))
		{
			$outputFileDir = ConvertToGIF($outputFileDir);
			$targetName = basename($outputFileDir);
			$outputFilePath = "$CONVERT_PATH$targetName";
			//RecordCommand("GIF Convert $outputFileDir $targetName");
			$LastOperation .= "- Automatically Converted to GIF";
		}
		else if (!IsGoodImage($outputFileDir))
		{
			//upload failed cuz bad something
			$ErrorCode = 15;
			$Error = "Corrupt Or Unsupported File";
			//RecordCommand("XLOAD BAD IMAGE SEEN $inputFileDir Error=$ErrorCode");
		}
		else
		{
			chmod($outputFileDir,0777);
			$UploadSuccess = TRUE;
		}
	}
	else
	{
		//otherwise, nothing worked.  This is is an error.
		$ErrorCode = 3;
		$Error = "File Was Too Large or Bad Format";
		//RecordCommand("XLOAD Error=$ErrorCode");
	}
}


if ($UploadSuccess == TRUE)
{

// CJM DEV XXX - Handle the fact BMPs aren't working
// properly in this IM version
if (IsValidBMP($outputFileDir))
{
RecordCommand("BMP HACK $outputFileDir");
$outputFileDir = ConvertToPNG($outputFileDir);
RecordCommand("BMP HACK CONVERTED -> $outputFileDir");
}

	GetImageAttributes($outputFileDir,$width,$height,$size);
	RecordCommand("LOADX $outputFileDir");
	//if ($size > 400000)
	if ($size > 900000)
	{
		if (($width > 1000) || ($height > 1000))
		{
		$outputFileDir = ResizeImage($outputFileDir, 1000, 1000, FALSE);
		$targetName = basename($outputFileDir);
		$outputFilePath = "$CONVERT_PATH$targetName";
		RecordCommand("LOADX RESIZE $size $width $height");
		}
	}

	$stats = GetStatString($outputFileDir);

	RecordCommand("LOADX SUCCESS $outputFilePath");
	$outputFilePath="$BASE_PATH$outputFilePath";
	echo '<html><head><title>-</title></head><body>';
	echo '<script language="JavaScript" type="text/javascript">'."\n";
	echo "parent.completeImageLoad(\"$outputFilePath\",\"$stats\");";
	echo "\n".'</script></body></html>';
}
else
{
	//RecordCommand("WEBLOAD ERROR $outputFilePath");
	$ErrorReport = "Error: $Error";
	echo '<html><head><title>-</title></head><body>';
	echo '<script language="JavaScript" type="text/javascript">'."\n";
	echo "parent.reportLoadError(\"$ErrorReport\");";
	echo "\n".'</script></body></html>';

}

function IsTIFFImage($targetFileDir)
{
global $CONVERT_DIR;

    $icommand = "identify $targetFileDir";
    $execResult = exec("$icommand 2>&1", $lines, $ConvertResultCode);
    RecordCommand("DEV $execResult $lines[0]");
    if ((stristr($execResult, "TIF") != FALSE))
        return TRUE;
        return FALSE;
}




?>
