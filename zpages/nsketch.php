<?php
include '../zcommon/common.inc';
$Title=$X_SKETCH;

$DEFAULT="03.jpg";
$EX_DIR = "$BASE_DIR/wimages/examples/sketch/";
$EX_PATH = "$BASE_PATH/wimages/examples/sketch/";

function DisplayImageSelectionTable($id,$dir,$path,$maxcols,$width,$default)
{
global $X_SELECTED;


    $handle = opendir($dir);
    $files = array();
    while ($file = readdir($handle))
    {
        if (($file == '.') || ($file == '..'))
            continue;
     $files[] = $file;
    }
    closedir($handle);
    sort($files);

	$original = "$path"."00.jpg";

    print  "<center>\n";

    print  "<table class=\"selections\" cellspacing=5 cellpadding=5>";
    $i = $j = 0;
    $num = 1;
    $pdefault = 1;
    foreach ($files as $file)
    {
		if (strstr($file,"00") != FALSE)
			continue;
        $j++;
        $psel = $j;

        if ($default == $file)
            $pdefault = $j;

        if ($i == 0)
            print  "<tr>\n";
        //print  "<td class=selection onclick=\"alert('$file'+'$psel')\">\n";
        print  "<td class=selection onclick=\"selectTableItem('$id','$path','$file','$psel')\">\n";
        print  "<img border=0 align=center src=$path$file alt=$file width=\"$width\">\n";
        print  "<br><center>$psel</center>\n";
        print  "</td>\n";
        $i++;
        if ($i == $maxcols)
        {
            print  "</tr>\n";
            $i = 0;
        }
        $num++;
    }
    print  "</table>\n";
    print  "<p><br>\n";

    print  "<input type=\"hidden\" name=\"$id\" id=\"$id\" value=\"$default\">\n";
    print  "<table class=\"selections\" cellspacing=5 cellpadding=5>";
	print "<tr>\n";
	print "<td><img border=0 src=$original width=$width></td>\n";
	print "<td><img border=0 src=\"../wimages/arrows/rightarrow1.gif\" width=50></td>\n";
    print  "<td><img id=\"IMAGE\" border=\"0\" align=\"center\" src=\"$path$default\" alt=\"$file\" width=\"$width\"></td>\n";
	print "</tr>\n";
    print  "</table>\n";
    print  "<div id=\"STATUS\" >\n$pdefault</div>\n";
	print "<br>\n";
    print  "</center>\n";

}



DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayImageSelectionTable('SETTING',$EX_DIR,$EX_PATH,6,120,$DEFAULT);
DisplayConvertButton();
DisplayFormEnd();
?>
