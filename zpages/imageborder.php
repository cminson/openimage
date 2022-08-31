<?php
include '../zcommon/common.inc';

$Title = $X_BLENDFRAME;

$current = $_POST['CURRENTFILE'];

$DEFAULT="ellipse.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/imageborder/";
$EX_PATH = "$BASE_PATH/wimages/examples/imageborder/";

// hidden frame image load area
print "<iframe id=\"frameupload_target\" name=\"frameupload_target\" src=\"#\" style=\"width:0;height:0px;border:1px solid #fff; display: none\"></iframe>\n\n";


// hidden submit form
print "<form enctype=\"multipart/form-data\" id=\"FRAMELOADFORM\" action=\"./zpages/loadsingleframex.php\" method=\"post\" target=\"frameupload_target\">\n";
print "<input type=hidden name=\"MAX_FILE_SIZE\" value=\"8000000\">\n";
print "<input type=hidden name=\"ID\" value=\"$DeviceId\">\n";
print "<input type=hidden name=\"PLATFORM\" value=\"$Platform\">\n";
print "<div style=\"height:0px;overflow:hidden\">\n";
print  "<input onchange=\"submitFrameFile();\" size=\"90\" maxLength=\"200\" type=\"FILE\" id=\"SUBMITFRAMEFILE\" name=\"FRAMEFILENAME\">\n";
print "</div>\n";
print "</form>\n";

DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();

$imagePath = "$BASE_PATH/wimages/tools/tab.jpg";
$image = "$BASE_DIR/wimages/tools/tab.jpg";
print  "<img onclick=\"chooseFrameFile(1)\" style=\"border:1px solid black\" src=\"$imagePath\" width=\"80\"  id=\"FRAME1\" alt=\"\">\n";
print  "<input type=\"hidden\" name=\"FRAMEPATH1\" id=\"FRAMEPATH1\" value=\"$image\">\n";
print "<br>\n";
print "<img onclick=\"chooseFrameFile(1)\" border=\"0\" src=\"$BASE_PATH/wimages/icons/LoadIconSmall-Globe.png\" width=\"20\" alt=\"\">\n";

//DisplayFilePicker($X_BORDERIMAGE,"FILENAME");
DisplayLineSep0();
DisplaySelectionTable('SETTING',$EX_DIR,$EX_PATH,7,70,70,FALSE,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
DisplaySlowNote();
?>
