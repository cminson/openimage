<?php
include "../zcommon/common.inc";


$LastOperation = $X_FILELOADED;

$ErrorCode = 0;
$UploadSuccess = FALSE;
$ImageList = array();

foreach ($_FILES['FRAMEFILENAME']['tmp_name'] as $i => $uploadedFile)
{
	$sourceName = $_FILES['FRAMEFILENAME']['name'][$i];
    RecordCommand("$i $sourceName $uploadedFile");
	if (filesize($uploadedFile) != 0)
	{
		$targetName = NewTMPName($sourceName);
		$outputFileDir = "$CONVERT_DIR$targetName";
		$outputFilePath = "$CONVERT_PATH$targetName";
		move_uploaded_file($uploadedFile, $outputFileDir);
		RecordCommand("MOVE: $i $uploadedFile $outputFileDir");
		chmod($outputFileDir,0777);
		RecordCommand("MOVE: $i $uploadedFile $outputFileDir");
		GetImageAttributes($outputFileDir,$width,$height,$size);

		if ($size > 400000)
		{
			if (($width > 900) || ($height > 900))
			{
				$outputFileDir = ResizeImage($outputFileDir, 900, 900, FALSE);
				$targetName = basename($outputFileDir);
				$outputFilePath = "$CONVERT_PATH$targetName";
				RecordCommand("RESIZE $size $width $height");
			}
		}

		if (IsAnimatedGIF($outputFileDir) == TRUE)
		{
			$animList = GetAnimatedImages($outputFileDir);
			$count = count($animList);
			RecordCommand("Animation Seen: $count $outputFileDir");
			for ($i = 0; $i < $count; $i++)
			{
				$image = $animList[$i];
				$image = GetWorkPath($image);
				$image = "$BASE_PATH/$image";
				$ImageList[] = $image;
				RecordCommand("Setting GIF Image = $image");
			}
		}
		else
		{
			$image = GetWorkPath($outputFileDir);
			$image = "$BASE_PATH/$image";
			$ImageList[] = $image;
		}
	}
} // end foreach



$FileList = "";
foreach ($ImageList as $image)
{
	$FileList .= $image.",";
	$UploadSuccess = TRUE;
}
$FileList = trim($FileList,",");


$stats = "";
if ($UploadSuccess == TRUE)
{
	RecordCommand("SUCCESS $FileList");
	$outputFilePath="$BASE_PATH$outputFilePath";
	echo '<html><head><title>-</title></head><body>';
	echo '<script language="JavaScript" type="text/javascript">'."\n";
	echo "parent.completeFrameLoad(\"$FileList\",\"$stats\");";
	echo "\n".'</script></body></html>';
}
else
{
	RecordCommand("ERROR $outputFilePath");
	//$ErrorReport = "Error: $Error";
	$ErrorReport = "Error";
	echo '<html><head><title>-</title></head><body>';
	echo '<script language="JavaScript" type="text/javascript">'."\n";
	echo "parent.reportFrameLoadError(\"$ErrorReport\");";
	echo "\n".'</script></body></html>';
}

?>
