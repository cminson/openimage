<?php
include '../zcommon/common.inc';

$Title = $X_MONTAGE;
$current = $_POST['CURRENTFILE'];


// hidden frame image load area
print "<iframe id=\"frameupload_target\" name=\"frameupload_target\" src=\"#\" style=\"width:0;height:0px;border:1px solid #fff; display: none\"></iframe>\n\n";


// hidden submit form
print "<form enctype=\"multipart/form-data\" id=\"FRAMELOADFORM\" action=\"./zpages/loadframex.php\" method=\"post\" target=\"frameupload_target\">\n";
print "<input type=hidden name=\"MAX_FILE_SIZE\" value=\"8000000\">\n";
print "<input type=hidden name=\"ID\" value=\"$DeviceId\">\n";
print "<input type=hidden name=\"PLATFORM\" value=\"$Platform\">\n";
print "<div style=\"height:0px;overflow:hidden\">\n";
print  "<input onchange=\"submitFrameFile();\" size=\"90\" maxLength=\"200\" type=\"FILE\" id=\"SUBMITFRAMEFILE\" name=\"FRAMEFILENAME[]\" multiple=\"multiple\">\n";
print "</div>\n";
print "</form>\n";

function DisplayAnimTable($current)
{
global $BASE_DIR;

    print  "<center>\n";
    print  "<input type=\"hidden\" name=\"CURRENTFILE\" value=\"$current\">\n";
    print  "<input type=\"hidden\" name=\"ANIMATION\" value=\"TRUE\">\n";

    $inputFileDir = "$BASE_DIR$current";
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        for ($i = 0; $i < count($imageList); $i++)
        {
            $image = $imageList[$i];
            $image = GetWorkPath($image);
            $image = "$BASE_PATH/$image";
            $imageList[$i] = $image;
            RecordCommand("Setting GIF Image = $image");
        }
    }
    else
    {
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
    }
    $maxImages = count($imageList);
    $imageCount = 0;
    $c = 1;
    RecordCommand("$inputFileDir $imageList[0] $maxImages");
    print  "<table class=\"selections\" cellspacing=10 cellpadding=10>\n";
    for ($row = 0; $row < 4; $row++)
    {

        print  "<tr>\n";
        for ($col = 0; $col < 4; $col++)
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
                print  "<img onclick=\"chooseFrameFile($c)\" style=\"border:1px solid black\" src=\"$image\" width=\"80\"  id=\"FRAME$c\" alt=\"\">\n";
                print  "<input type=\"hidden\" name=\"FRAMEPATH$c\" id=\"FRAMEPATH$c\" value=\"$image\">\n";
                $imageCount++;
            }
            else
            {
                $image = "$BASE_PATH/wimages/tools/ezimbanoop.png";
                print  "<img onclick=\"chooseFrameFile($c)\" style=\"border:1px solid black\" src=\"$image\" width=\"80\"  id=\"FRAME$c\" alt=\"\">\n";
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



DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayAnimTable($current);
DisplayLineSep0();

$v= array('1x2','2x1','2x2','3x3','4x4');
$s= array('1x2','2x1','2x2','3x3','4x4');
DisplayGenStringPicker($X_SIZE,'SIZE',$v,$s,4);

$v= array('+0+0','+1+1','+2+2','+3+3','+4+4','+5+5','+10+10','+15+15','+20+20','+25+25','+30+30');
DisplaySep1();
$s = array('None','1','2','3','4','5','10','15','20','25','30');
DisplayGenStringPicker($X_SEPARATION,'SEPARATION',$v,$s,0);

DisplaySep1();
DisplayColorPicker($X_COLOR,'COLOR','COLOR1','#ff0000');

$v= array('NONE','POLAROID','HEAP');
$s = array('NONE','Photo Spread','Random Photo Heap');
DisplaySep1();
DisplayGenStringPicker($X_EFFECT,'EFFECTS',$v,$s,0);

DisplaySep1();
DisplayCheckBox($X_RANDOMLYANIMATE,'ANIMATE',FALSE);
DisplaySlowNote();

DisplayConvertButton();
DisplayFormEnd();
?>
