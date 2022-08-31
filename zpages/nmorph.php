<?php
include '../zcommon/common.inc';

$DEFAULT="radius2.gif";
$EX_DIR = "$BASE_DIR/wimages/examples/morphs/";
$EX_PATH = "$BASE_PATH/wimages/examples/morphs/";

$Title = $X_POWERMORPH;


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

$imagePath = "$BASE_PATH/wimages/tools/bel13.jpg";
$image = "$BASE_DIR/wimages/tools/bel13.jpg";
print  "<img onclick=\"chooseFrameFile(1)\" style=\"border:1px solid black\" src=\"$imagePath\" width=\"120\"  id=\"FRAME1\" alt=\"\">\n";
print  "<input type=\"hidden\" name=\"FRAMEPATH1\" id=\"FRAMEPATH1\" value=\"$image\">\n";
print "<br>\n";
print "<img onclick=\"chooseFrameFile(1)\" border=\"0\" src=\"$BASE_PATH/wimages/icons/LoadIconSmall-Globe.png\" width=\"20\" alt=\"\">\n";

DisplayLineSep0();
DisplaySelectionTable('EFFECT',$EX_DIR,$EX_PATH,8,90,90,FALSE,$DEFAULT);
DisplayLineSep0();
$v= array('400','200','100','50','10');
$s=array('1','2','3','4','5');
DisplayGenStringPicker($X_SPEED,'SPEED',$v,$s,2);
DisplaySep0();
DisplayCheckBox($X_REVERSE,'REVERSE',true);
DisplaySep1();
DisplaySlowNote();
DisplayConvertButton();
DisplayFormEnd();


?>
