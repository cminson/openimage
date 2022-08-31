<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$LastOperation = $X_BENT;

$DEFAULT="01.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/bend/";
$EX_PATH = "$BASE_PATH/wimages/examples/bend/";

	$Setting = $_POST['SETTING'];
	$AreaSelect = $_POST['AREASELECT'];

    if (strlen($Setting) < 2)
        $Setting = $DEFAULT;
    $Setting = str_replace(".jpg","",$Setting);
	$Orient = $_POST['ORIENT'];

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
	$targetName = NewName($inputFileDir);

    $outputFileName = BendImage($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$outputFileName";
    $outputFilePath = "$CONVERT_PATH$outputFileName";

	$outputFilePath = CheckFileSize($outputFileDir);


	RecordCommand("FINAL $outputFilePath");
	RecordAndComplete("BEND",$outputFilePath,FALSE);



function BendImage($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $Setting,$Orient;
global $HEIGHT_IMAGE, $AreaSelect;

	$region = "";
    if ($AreaSelect == 'on')
    {
        $clientX1 = $_POST['X1'];
        $clientX2 = $_POST['X2'];
        $clientY1 = $_POST['Y1'];
        $clientY2 = $_POST['Y2'];
        GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
        $display_height = $HEIGHT_IMAGE;
        $display_width = (int)(($display_height/$real_height)*$real_width);

        $clientX1 = (int)(($real_width/$display_width) * $clientX1);
        $clientY1 = (int)(($real_height/$display_height) * $clientY1);
        $clientX2 = (int)(($real_width/$display_width) * $clientX2);
        $clientY2 = (int)(($real_height/$display_height) * $clientY2);
        $w= $clientX2 - $clientX1;
        $h= $clientY2 - $clientY1;
        $region = "-region ".$w."x".$h."+$clientX1+$clientY1";
	}



	if ($Orient == 'DOWN')
		$command = "convert $region -virtual-pixel background -distort arc $Setting  -background white +repage";
	else
		$command = "convert $region -virtual-pixel background -background white -rotate 180   -distort arc '$Setting 180' +repage";

	$targetName = NewName($inputFileDir);

	$outputFileDir = "$CONVERT_DIR$targetName";
	$command = "$command $inputFileDir $outputFileDir";
	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	RecordCommand("BEND $command");
	return $targetName;
}
?>
