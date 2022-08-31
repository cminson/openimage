<?php
include '../zcommon/common.inc';


//CJM

$Title = $X_DEEPDREAM;
$X_EDGE="Edge";
$DEFAULT="01.jpg";
$LastOperation = $X_DEEPDREAM;

$current = $_POST['CURRENTFILE'];
RecordCommand("DEV DISPLAY FORMSTART 1 CURRENT=$current");
$inputFileDir = "$BASE_DIR$current";


DisplayMainPageReturn();
DisplayTitle($Title);
/*
if (IsAnimatedGIF($inputFileDir) == TRUE)
	DisplayStatusArea("Animations Are Not Supported - Convert to a JPG First");
else
	DisplayStatusArea("");
*/
DisplayStatusArea("");

DisplayFormStart();

print "<center>";
print "<div style=\"width: 500px; background-color: white\">";
if (IsEnglish() == TRUE)
{
print "<strong>\n";
print "What would your image look like in a <a target=_blank href=../zs/dd.html>dream?</a>";
//DisplayLineSep1();
//print "<img style=\"height: auto; max-width:100%; max-height:100%\" onclick=\"xxx()\" id=\"dreamframe\"  width=150 src=\"http://www.ezimba.com/wimages/dreams/thescream.jpg\" border=\"1\" alt=\"\">\n";
DisplayLineSep1();
print "Your image will be interpreted by a deep neural network (a form of artifical intelligence).";
DisplayLineSep1();
print "Dreaming usually takes a minute to run. So click the button and await your dream - or nightmare.\n";
DisplayLineSep1();
print "</strong>\n";
}
else
{
DisplayLineSep1();
print "<img style=\"height: auto; max-width:100%; max-height:100%\" onclick=\"xxx()\" id=\"dreamframe\"  width=150 src=\"http://www.ezimba.com/wimages/dreams/thescream.jpg\" border=\"1\" alt=\"\">\n";
}
print "</div>";
print "</center>";
$v= array('1','2');
$s = array('GoogleNet','Hybrid CNN');
//DisplayGenStringPicker($X_SETTING,'SETTING',$v,$s,0);

//DisplayConvert2Button($current,$jpgcurrent);
DisplayConvertButton($current);
DisplayFormEnd();

// just like reg funtion, but calls timer on convert click
function DisplayConvert2Button($current,$jpgcurrent)
{
global $X_CONVERT;
global $BASE_PATH;


    $op = $_SERVER['PHP_SELF'];
    $op = basename($op);
    $op = str_replace(".php","x.php",$op);
    $op = "./zpages/$op";
    RecordCommand($op);

    print "<P><BR>";
    print "<center>";
    print "<a href=\"#top\">";
    print "<img height=\"20px\" border=\"0\" src=\"$BASE_PATH/wimages/arrows/bracket-up.jpg\">";

    print "</a>\n";
    DisplaySep1();
    print "<img onclick=\"homeImage()\" id=\"homeimage2\" height=\"20px\" src=\"$BASE_PATH/wimages/arro
ws/bracket-up.jpg\">\n";
    print "</div>\n";
    print "<BR>";
    print "<div id=\"convertdiv\">\n";
    //print  "<input id=\"convert1\" class=\"convert1\" onclick=\"executeOpTimer('$jpgcurrent')\" type=submit value=\"$X_CONVERT\">\n";
    print  "<input id=\"convert1\" class=\"convert1\" onclick=\"executeOpTimer()\" type=button value=\"$X_CONVERT\">\n";
    print "</div>\n";
    print "<div id=\"busydivsmall\">\n";
    print "<img border=\"0\" src=\"$BASE_PATH/wimages/tools/smallbusyicon.gif\">";
    print "</div>\n";
    print "</center>";
}

?>
