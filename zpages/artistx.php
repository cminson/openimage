<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_ARTGALLERY;
$DEFAULT="IMPRESSIONIST.jpg";

	$Setting = $_POST['SETTING'];
    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$targetName = NewName($inputFileDir);

//$Setting = 'PHOTOREALIST';
switch ($Setting)
{
case INFRARED1:
	$command = 'convert -solarize 20%';
	break;
case INFRARED2:
	$command = 'convert -solarize 40%';
	break;
case 'HELL1':
	$command = "../zshells/mytri.sh -l red -m black -h white";
	break;
case 'HELL2':
	$command = "../zshells/mytri.sh -l orange -m black -h white";
	break;
case 'PHOTOREALIST':
    $command = "../zshells/mkhdr.sh 23";
    $lastOperation = "Photo Realist";
    break;
case 'LATERNA':
	$command = "../zshells/mytri.sh -l red -m green -h blue";
	$lastOperation = "Laterna";
	break;
case 'ETCHEDGOLD':
	$command = "convert -shade 60x21.78 -normalize -raise 3x3 -tint 100 -fill gold";
	$lastOperation = "Etched Gold";
	break;
case 'ICE':
	$command = "convert -swirl -50  -shade 80x21.78 -normalize -raise 3x3 -tint 80 -fill blue";
	break;
case 'HELL1':
	$command = "../zshells/mytri.sh -l red -m black -h white";
	break;
case 'HELL2':
	$command = "../zshells/mytri.sh -l orange -m black -h white";
	break;
case 'PHOTOREALIST':
    $command = "../zshells/mkhdr.sh 23";
    $lastOperation = "Photo Realist";
    break;
case 'LATERNA':
	$command = "../zshells/mytri.sh -l red -m green -h blue";
	$lastOperation = "Laterna";
	break;
case 'ETCHEDGOLD':
	$command = "convert -shade 60x21.78 -normalize -raise 3x3 -tint 100 -fill gold";
	$lastOperation = "Etched Gold";
	break;
case 'ICE':
	$command = "convert -swirl -50  -shade 80x21.78 -normalize -raise 3x3 -tint 80 -fill blue";
	$lastOperation = "Pop Ice";
	break;
case 'REMBRANDT':
	$command = "convert -black-threshold 50%";
	$lastOperation = "Rembrandt";
	break;
case 'ANSELADAMS':
	$command = "convert -colors 8 -emboss 1 -type Grayscale";
	$command = "convert -sharpen 0.0x1.0 -emboss 1 -type Grayscale";
	$lastOperation = "Ansel Adams";
	break;
case 'FOSSIL':
	$command = "convert -blur 0x1  -shade 90x31.78 -normalize -raise 5x5";
	$lastOperation = "Fossil";
	break;
case 'ESCHER':
	$command = "../zshells/recursion.sh -d 9 -a 45 -r 7 -z 0.80 -i 5 ";
	$lastOperation = "Escher";
	break;
case 'GHOSTS':
	//SWIRL=60&WAVE=1x5&SOLAR=20%&CYCLE=80&SPECIAL=-negate
	$command = "convert -negate -cycle 80 -swirl 60 -solarize 20 -wave 1x5";
	$lastOperation = "Ghosts";
	break;
case 'OILPAINT1':
	$command = "convert -modulate 100,130 -paint 2 ";
	$lastOperation = "Oil Painting (1)";
	break;
case 'OILPAINT2':
	$command = "convert -modulate 100,140 -paint 4 ";
	$lastOperation = "Oil Painting (2)";
	break;
case 'JUTE':
	$command = "convert -emboss 5 -sharpen 3.5x2.0 -tint 30 -fill green -normalize";
	$lastOperation = "Jute";
	break;
case 'METAL':
	$command = "convert -blur 0x1  -shade 120x21.78 -normalize -raise 5x5 -sepia-tone 65% -emboss 3 -modulate 110 -sharpen 0.0x1.0";
	$lastOperation = "Liquid Metal";
	break;
case 'IMPRESSIONIST':
    $command = "convert -modulate 100,160 -emboss 2 -spread 2 -swirl 40";
	$lastOperation = "French Impressionist";
	break;
case 'ABSTRACT':
	$command = "convert -paint 10";
	$lastOperation = "Abstract";
	break;
case 'CHARCOALLIGHT':
    $command = "convert -charcoal 1";
	$lastOperation = "Light Charcoal";
	break;
case 'CHARCOAL':
    $command = "convert -charcoal 2";
	$lastOperation = "Medium Charcoal";
	break;
case 'CHARCOALDARK':
    $command = "convert -charcoal 5";
	$lastOperation = "Dark Charcoal";
	break;
case 'PASTEL':
	$command = "convert -colors 8 -paint 2";
	$lastOperation = "Pastel";
	break;
case 'PENCILDARK':
    $command = "convert -edge 1";
	$lastOperation = "Dark Pencil";
	break;
case 'PENCILLIGHT':
    $command = "convert -edge 3";
	$lastOperation = "Light Pencil";
	break;
case 'WATERCOLOR':
	$command = "convert -modulate 100,200 -sharpen 10x10 -median 5 -paint 2";
	$lastOperation = "Watercolor";
	break;
case 'DADA':
    $command = "convert -modulate 110,130 -equalize -emboss 3 -paint 5 -swirl 80";
	$lastOperation = "Dada";
	break;
case 'WOODBLOCK':
    $command = "convert -emboss 3  -colors 8 -tint 20 -fill brown";
	$lastOperation = "Japanese Wood Block";
	break;
case 'BLACKWHITESTUDY':
    $command = 'convert -type Grayscale -black-threshold 50% -white-threshold 50%';
	$lastOperation = "Black White Study";
	break;
default:
    $command = 'convert -type Grayscale -black-threshold 50% -white-threshold 50%';
	$lastOperation = "Black White Study";
	RecordCommand("ERROR SETTING=$Setting");
	break;
case 'GLOW':
	$command = "../zshells/glow.sh -a 1.7 -s 36";
	$lastOperation = "Glowing";
	break;
case 'GRITTY':
	$command = "../zshells/gritty.sh -b 1.5 -c -5 -d 2 -s 175";
	$lastOperation = "Gritty";
	break;
}


	$outputFileDir = "$CONVERT_DIR$targetName";
	$outputFilePath = "$CONVERT_PATH$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("ARTIST $command");

	$inputFileDir = $outputFileDir;
	GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
	if ($size > 500000)
	{
		if (($real_width > 400) || ($real_height > 400))
		{
		$inputFileDir = ResizeImage($inputFileDir,400,400,FALSE);
		$targetName = basename($inputFileDir);
		$outputFilePath = "$CONVERT_PATH$targetName";
		}
	}

	RecordCommand("FINAL $outputFilePath");

	$LastOperation = "$LastOperation: $lastOperation";
    RecordAndComplete("ARTIST",$outputFilePath,FALSE);
?>
