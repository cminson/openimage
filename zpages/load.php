<?php
include '../zcommon/common.inc';
//RecordCommand(" HERE");

$Title = $X_LOADIMAGE;
$LastOperation = $X_LOADIMAGE;


DisplayLoadReturn();
DisplayLoadTitle($Title);
print  "<div class=\"loadimage\" id=\"loadarea\">\n";
print  "<form enctype=\"multipart/form-data\" action=\"./zpages/loadx.php\" method=\"post\" target=\"upload_target\">\n";
print  "<center>\n";

print  "<br> <INPUT size=90 maxLength=200 type=TEXT name=\"URL\">\n";
print  "<p><br>\n";
print  "<input type=hidden name=\"MAX_FILE_SIZE\" value=\"5000000\">\n";

print  "<input type=hidden name=\"ID\" value=\"$DeviceID\">\n";
print  "<input type=hidden name=\"PLATFORM\" value=\"$Platform\">\n";
print  "<input size=\"40\" maxLength=\"100\" type=\"FILE\" name=\"FILENAME\">\n";
print  "<p><br>\n";
print  "<input onclick=\"executeLoad()\" type=\"submit\" value=\"Load Image\"> &nbsp; &nbsp;\n";
print  "</center>\n";
print  "</form>\n";

?>
