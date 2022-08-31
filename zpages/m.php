<?php
include '../zcommon/common.inc';

$DEFAULT="radius2.gif";
$EX_DIR = "$BASE_DIR/wimages/examples/morphs/";
$EX_PATH = "$BASE_PATH/wimages/examples/morphs/";

$Title = $X_POWERMORPH;
DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayFileInput($X_TARPOSTIMAGE);
DisplayLineSep0();
DisplaySelectionTable('EFFECT',$EX_DIR,$EX_PATH,4,100,100,FALSE,$DEFAULT);
DisplayLineSep0();
$v= array('400','200','100','50','10');
$s=array('1','2','3','4','5');
DisplayGenStringPicker($X_SPEED,'SPEED',$v,$s,2);
DisplaySep1();
DisplaySlowNote();
DisplayConvertButton();
DisplayFormEnd();



function DisplayMorphPicker()
{
    global $BASE_PATH;

    $name = "EFFECT";
    print "<input type=\"radio\" name=\"$name\" value=\"radius2.jpg\" checked>\n";
    print "<img width=\"60\" src=\"$BASE_PATH/wimages/examples/morphs/circle.gif\">\n";
    DisplaySep1();
    print "<input type=\"radio\" name=\"$name\" value=\"plasma2.jpg\">\n";
    print "<img width=\"60\" src=\"$BASE_PATH/wimages/examples/morphs/ds1.gif\">\n";
    DisplaySep1();
    print "<input type=\"radio\" name=\"$name\" value=\"fade.jpg\">\n";
    print "<img width=\"60\" src=\"$BASE_PATH/wimages/examples/morphs/fading1.gif\">\n";
    DisplaySep1();
    print "<input type=\"radio\" name=\"$name\" value=\"peel.jpg\">\n";
    print "<img width=\"60\" src=\"$BASE_PATH/wimages/examples/morphs/peel.gif\">\n";
    DisplayLineSep1();
    print "<input type=\"radio\" name=\"$name\" value=\"random2.jpg\">\n";
    print "<img width=\"60\" src=\"$BASE_PATH/wimages/examples/morphs/rp1.gif\">\n";
    DisplaySep1();
    print "<input type=\"radio\" name=\"$name\" value=\"grad_alt3.jpg\">\n";
    print "<img width=\"60\" src=\"$BASE_PATH/wimages/examples/morphs/sb.gif\">\n";
    DisplaySep1();
    print "<input type=\"radio\" name=\"$name\" value=\"shutters2.jpg\">\n";
    print "<img width=\"60\" src=\"$BASE_PATH/wimages/examples/morphs/shutters.gif\">\n";
    DisplaySep1();
    print "<input type=\"radio\" name=\"$name\" value=\"grad2.jpg\">\n";
    print "<img width=\"60\" src=\"$BASE_PATH/wimages/examples/morphs/vb.gif\">\n";
}

?>
