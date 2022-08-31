<?php
include '../zcommon/common.inc';


// hidden frame image load area 
print "<iframe id=\"frameupload_target\" name=\"frameupload_target\" src=\"#\" style=\"width:0;height:0px;border:1px solid #fff; display: none\"></iframe>\n\n";

// hidden submit form
print "<form enctype=\"multipart/form-data\" id=\"FRAMELOADFORM\" action=\"./zpages/loadframex.php\" method=\"post\" target=\"frameupload_target\">\n";
print "<input type=hidden name=\"MAX_FILE_SIZE\" value=\"8000000\">\n";
//print "<input type=hidden name=\"ID\" value=\"$DeviceId\">\n";
//print "<input type=hidden name=\"PLATFORM\" value=\"$Platform\">\n";
print "<div style=\"height:0px;overflow:hidden\">\n";
print  "<input onchange=\"submitFrameFile();\" size=\"90\" maxLength=\"200\" type=\"FILE\" id=\"SUBMITFRAMEFILE\" name=\"FRAMEFILENAME[]\" multiple=\"multiple\">\n";
print "</div>\n";
print "</form>\n";

$current = $_POST['CURRENTFILE'];	//Reminder: need to set current now so post will work!

$Title = $X_MAKETRANSPARENT." - BATCHED -";
$LastOperation = $X_TRANSPARENCYADDED." BATCHED ";
$MAX_ANIM = 16;
DisplayLineSep0();
DisplayDivColorPicker();
DisplayLineSep0();
DisplayLineSep0();


DisplayMainPageReturn();
DisplayTitle($Title);
DisplayLineSep0();
DisplayFormStart();
DisplayColorPicker($X_PICKCOLOR,'PICKCOLOR','COLOR1','');
DisplaySep1();
DisplayFuzzPicker($X_FUZZ,'FUZZ',1);
DisplayLineSep1();
DisplayTransTable($current,$MAX_ANIM);
DisplayConvertButton();
DisplayLineSep1();
DisplayHiddenField('CLIENTX');
DisplayHiddenField('CLIENTY');
if (IsHandHeld() == FALSE)
{
print $TRANS_HELP_NEW;
}
DisplayFormEnd();


function DisplayTransTable($current)
{
global $BASE_DIR;
global $BASE_PATH;

    print  "<center>\n";
    print  "<input type=\"hidden\" name=\"CURRENTFILE\" value=\"$current\">\n";
    print  "<input type=\"hidden\" name=\"ANIMATION\" value=\"TRUE\">\n";

    $inputFileDir = "$BASE_DIR$current";
    if (strlen($current) < 8)
        {
            $image = "$BASE_PATH/wimages/tools/ezimbanoop.png";
        }
        else
        {
            $image = "$BASE_PATH$current";
        }
    $imageList[] = $image;
    RecordCommand("Setting Current Image = $image");

    $maxImages = count($imageList);
    $imageCount = 0;
    $c = 1;
    RecordCommand("$inputFileDir $imageList[0] $maxImages");
    print  "<table class=\"selections\" cellspacing=5 cellpadding=5>\n";
    for ($row = 0; $row < 7; $row++)
    {

        print  "<tr>\n";
        for ($col = 0; $col < 8; $col++)
        {
            print  "<td style=\"align: center\">\n";
            print "<center>\n";
            print "<span style=\"font-size: 10\"> $c </span>\n";
            DisplaySep1();
            print "<img onclick=\"deleteFrameImage($c)\" width=\"8\" src=\"../wimages/tools/delicon1.png\">\n";
            print "<br>\n";
            if ($imageCount < $maxImages)
            {
                $image = $imageList[$imageCount];
                RecordCommand("Setting Image $c  = $image");
                //print  "<img style=\"border:1px solid black\" src=\"$image\" width=\"50\"  id=\"FRAME$c\" alt=\"\">\n";
                print  "<img onclick=\"chooseFrameFile($c)\" style=\"border:1px solid black\" src=\"$image\" width=\"50\"  id=\"FRAME$c\" alt=\"\">\n";
                print  "<input type=\"hidden\" name=\"FRAMEPATH$c\" id=\"FRAMEPATH$c\" value=\"$image\">\n";
                $imageCount++;
            }
            else
            {
                $image = "$BASE_PATH/wimages/tools/ezimbanoop.png";
                print  "<img onclick=\"chooseFrameFile($c)\" style=\"border:1px solid black\" src=\"$image\" width=\"50\"  id=\"FRAME$c\" alt=\"\">\n";
                print  "<input type=\"hidden\" name=\"FRAMEPATH$c\" id=\"FRAMEPATH$c\" value=\"$image\">\n";
            }
            print "<br>\n";
            print "<img onclick=\"chooseFrameFile($c)\" border=\"0\" src=\"$BASE_PATH/wimages/icons/LoadIconSmall-Globe.png\" width=\"20\" alt=\"\">\n";
            print "</center>\n";
            print "</td>\n";
            $c++;
        }
        print  "</tr>\n";
    }
    print "</table>\n";
}


?>
