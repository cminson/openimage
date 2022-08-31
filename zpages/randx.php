<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;


$Commands = array(
" -virtual-pixel background -distort arc 120  +repage",
" -emboss 1",
" +raise 20x20",
" -rotate -90",
" +raise 20x20",
" -wave 5x50",
" -virtual-pixel background -distort arc 240  +repage",
" -paint 2",
" -swirl 40",
" -emboss 1",
" -swirl 20",
" -raise 10x10",
" -virtual-pixel background -distort arc 361  +repage",
" -radial-blur  3",
" -wave 5x50",
" -wave 10x40",
" -sepia-tone  95%",
" -vignette 10x20 ",
" -raise 30x30",
" +raise 30x30",
" -implode 1",
" +raise 20x20",
" -wave 9x70",
" -implode -1",
" -gaussian  1",
" -swirl 50",
" -swirl 80",
);


$LastOperation = $X_RANDOMYOUFELTUCKY;
$TARPOSTTYPE = ($_POST['TGT']);


$inputFileDir = $_POST['CURRENTFILE'];
$inputFileDir = "$BASE_DIR$inputFileDir";
$inputFileDir = ConvertToJPG($inputFileDir);
$inputFileName = basename($inputFileDir);


$count = count($Commands);

$targetName = NewNameJPG();
$outputFileDir = "$CONVERT_DIR$targetName";
$outputFilePath = "$CONVERT_PATH$targetName";
$command = "convert ";
for ($i =0; $i<5; $i++)
{
    $r = rand(0,$count);
    $command .= $Commands[$r];
    $command .= " ";
}
$command = "$command $inputFileDir $outputFileDir";
RecordCommand(" $command");
RecordCommand(" FINAL $outputFilePath");
$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
$outputFilePath = CheckFileSize($outputFileDir);

RecordAndComplete("RANDOM",$outputFilePath,TRUE);

?>
