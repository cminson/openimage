<?php
include '../zcommon/common.inc';
include '../zcommon/pig.inc';


declare (ticks=5)
{
if (CompleteWithNoAction()) return;


$LastOperation = $X_DEFORM;

$DEFAULT="FLAG1.gif";
$EX_DIR = "$BASE_DIR/wimages/examples/anfun/";
$EX_PATH = "$BASE_PATH/wimages/examples/anfun/";


	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".gif","",$Setting);
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileDir = ConvertToJPG($inputFileDir);
    $inputFileName = basename($inputFileDir);

	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";

/*
	switch ($Setting)
	{
	case "EXPLODE1":
	case "EXPLODE2":
	case "EXPLODE3":
	case "IMPLODE1":
	case "IMPLODE2":
	case "IMPLODE3":
	case "LOOPER1":
	case "LOOPER2":
		$bigImage = TRUE;
		$w = $h = 900;
		break;
	default:
		$bigImage = FALSE;
		$w = $h = 600;
	}
	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 60000)
	{
		if (($real_width > $w) || ($real_height > $h))
		{
			$inputFileDir = ResizeImage($inputFileDir,$w,$w,FALSE);
			RecordCommand("DEFORM: pre resizing image");
		}
	}
*/


RecordCommand("DEFORM $Setting");
//$Setting = 'MELT1';
switch ($Setting)
{
case 'FLAG1':
	$command = "../zshells/anflag.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand($command);
	break;
case 'FLAG2':
	$command = "../zshells/anflag2.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand($command);
	break;
case 'FLAG3':
	$command = "../zshells/anflag3.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand($command);
	break;
case 'EXPLODE1':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 12; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode -.1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'EXPLODE2':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 7; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode -.1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'EXPLODE3':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 2; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode -.1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'IMPLODE1':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 12; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode .1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'IMPLODE2':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 7; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode .1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'IMPLODE3':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 2; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode .1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'FLEX1':
    $command = "../zshells/anflex.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'FLEX2':
    $command = "../zshells/anflex2.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'FLEX3':
    $command = "../zshells/anflex3.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'FLEX4':
    $command = "../zshells/anflex4.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'MIXER1':
    $command = "../zshells/anmixer.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'MIXER2':
    $command = "../zshells/anmixer2.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'MIXER3':
    $command = "../zshells/anmixer3.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'ROTATE':
	GetImageAttributes($inputFileDir,$width,$height,$size);
	$inputFileDir = ResizeImage($inputFileDir, $width, $width, true);
    $command = "../zshells/anrotate.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'WHIRLPOOL':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 15; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -swirl 15 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
/*
    $command = "./anwhirlpool.sh $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
*/
	break;
case 'MELT1':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 15; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
		$command = "./melt.sh $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'LOOPER1':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 2; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode -.1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";

	$Anim = array();
	$inputFileDir = $anim;
	for ($i = 0; $i < 3; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode .1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";

	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
case 'LOOPER2':
	$Anim = array();
	$Anim[] = $inputFileDir;
	$AnimList ="";
	for ($i = 0; $i < 7; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode -.1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";

	$Anim = array();
	$inputFileDir = $anim;
	for ($i = 0; $i < 7; $i++)
	{
		$targetName = NewNameJPG();
		$outputFileDir = "$CONVERT_DIR$targetName";
	    $command = "convert -implode .1 $inputFileDir $outputFileDir";
		$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
		RecordCommand(" $command");
		$Anim[] = $outputFileDir;
		$inputFileDir = $outputFileDir;
	}
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";
	$Anim = array_reverse($Anim);
	foreach ($Anim as $anim)
		$AnimList .= "$anim ";

	$targetName = NewNameGIF();
	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "convert -dispose previous -delay 25  $AnimList -loop 0 $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand(" $command");
	break;
}


RecordCommand("$command");
$outputFilePath = CheckFileSize($outputFileDir);

RecordCommand("FINAL $outputFilePath");
RecordAndComplete("DEFORM",$outputFilePath,FALSE);
}

?>
