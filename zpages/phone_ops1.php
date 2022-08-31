<?php
print "<table class=\"phoneops\" cellpadding=\"9\" cellspacing=\"9\">\n";
print "<tr>\n";
DisplayPhoneOpCategory($X_FORMAT,3);
DisplayPhoneOp("$X_GIF","convertx.php","GIF");
DisplayPhoneOp("$X_JPG","convertx.php","GIF");
print "</tr><tr>\n";
DisplayPhoneOp("$X_PNG","convertx.php","PNG");
DisplayPhoneOp("$X_BMP","convertx.php","BMP");
/*
print "</tr><tr>\n";
DisplayPhoneOp("$X_ICO","convertx.php","ICO");
DisplayPhoneOp("","convertx.php","");
*/
print "</tr>\n";

print "<tr><td colspan=\"3\"> <hr> </td></tr>\n";

print "<tr>\n";
DisplayPhoneOpCategory($X_ENHANCE,4);
DisplayPhoneOp("$X_INSTANTFIX", "simplex.php","NORMALIZE");
DisplayPhoneOp("$X_HDR", "simplex.php","HDR");
print "</tr><tr>\n";
DisplayPhoneOp("$X_SMOOTH","simplex.php","SMOOTH");
DisplayPhoneOp("$X_ENRICH","simplex.php","ENRICH");
print "</tr><tr>\n";
DisplayPhoneOp("$X_BLUR","simplex.php","BLUR");
DisplayPhoneOp("$X_TEXTURE","texture.php","");
/*
print "</tr><tr>\n";
DisplayPhoneOp("$X_OUTLINE","outlinex.php","X");
DisplayPhoneOp("","convertx.php","");
*/
print "</tr>\n";

print "<tr><td colspan=\"3\"> <hr> </td></tr>\n";

print "<tr>\n";
DisplayPhoneOpCategory($X_ADJUST,3);
DisplayPhoneOp("$X_MONOCHROME", "simplex.php","GRAYSCALE");
DisplayPhoneOp("$X_NEGATE","simplex.php","NEGATE");
print "</tr><tr>\n";
DisplayPhoneOp("$X_TINT","tint.php","");
DisplayPhoneOp("$X_BEAUTY","simplex.php","BEAUTY");
print "</tr><tr>\n";
DisplayPhoneOp("$X_ACCENT","accent.php","");
DisplayPhoneOp("$X_REDUCECOLOR","reducecolor.php","");
print "</tr>\n";

print "<tr>\n";
print "<tr><td colspan=\"3\"> <hr> </td></tr>\n";
print "</tr>\n";

print "<tr>\n";
DisplayPhoneOpCategory($X_CONTRAST,1);
DisplayPhoneOp("$X_HIGHER", "simplex.php","CONTRASTUP");
DisplayPhoneOp("$X_LOWER", "simplex.php","CONTRASTDOWN");
print "</tr>\n";

print "<tr><td colspan=\"3\"> <hr> </td></tr>\n";

print "<tr>\n";
DisplayPhoneOpCategory($X_BRIGHTNESS,1);
DisplayPhoneOp("$X_HIGHER", "simplex.php","BRIGHTUP");
DisplayPhoneOp("$X_LOWER", "simplex.php","BRIGHTDOWN");
print "</tr>\n";

print "<tr><td colspan=\"3\"> <hr> </td></tr>\n";

print "<tr>\n";
DisplayPhoneOpCategory($X_SHARPNESS,1);
DisplayPhoneOp("$X_HIGHER", "simplex.php","SHARPUP");
DisplayPhoneOp("$X_LOWER", "simplex.php","SHARPDOWN");
print "</tr>\n";

print "<tr><td colspan=\"3\"> <hr> </td></tr>\n";

print "<tr>\n";
DisplayPhoneOpCategory($X_SATURATION,1);
DisplayPhoneOp("$X_HIGHER","simplex.php","SATURATEUP");
DisplayPhoneOp("$X_LOWER", "simplex.php","SATURATEDOWN");
print "</tr>\n";

print "<tr><td colspan=\"3\"> <hr> </td></tr>\n";

print "<tr>\n";
DisplayPhoneOpCategory($X_HUE,1);
DisplayPhoneOp("$X_HIGHER", "simplex.php","HUEUP");
DisplayPhoneOp("$X_LOWER", "simplex.php","HUEDOWN");
print "</tr>\n";



print "</tr></table>\n";

?>
