<?php
include '../zcommon/common.inc';

$Title = $X_WATERMARK;

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
DisplayLineSep1();
DisplayLineSep1();

DisplayPercentPicker($X_BLEND,'DISSOLVE');
DisplayLineSep1();
DisplayPositionPicker($X_POSITION,'POSITION');
DisplayLineSep1();
DisplayTextInput($X_WIDTH,'WIDTH','100',5);
DisplaySep1();
DisplayTextInput($X_HEIGHT,'HEIGHT','100',5);
DisplaySep1();
DisplayConvertButton();
DisplayFormEnd();
?>
