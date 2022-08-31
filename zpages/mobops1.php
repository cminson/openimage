<?php
print "<div class=\"opslist1\" id=\"opslist1\">\n";
print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_FORMAT</caption>";
print "<tr>\n";
DisplayMobOp("$X_GIF","","&rarr; GIF","convertx.php","GIF");
DisplayMobOp("$X_JPG","","&rarr; JPG","convertx.php","JPG");
DisplayMobOp("$X_PNG","","&rarr; PNG","convertx.php","PNG");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_BMP","","&rarr; BMP","convertx.php","BMP");
DisplayMobOp("$X_ICO","","&rarr; ICO","convertx.php","ICO");
print "</tr>\n";
print "</table>\n";
print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_ENHANCE</caption>";
print "<tr>\n";
DisplayMobOp("$X_INSTANTFIX","", "$X_INSTANTFIX", "simplex.php","NORMALIZE");
DisplayMobOp("$X_HDR","", "$X_HDR", "simplex.php","HDR");
DisplayMobOp("$X_SMOOTH","","$X_SMOOTH","simplex.php","SMOOTH");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_BLUR","","$X_BLUR","simplex.php","BLUR");
DisplayMobOp("$X_TEXTURE","","$X_TEXTURE","texture.php","");
DisplayMobOp("$X_OUTLINE","","$X_OUTLINE","outlinex.php","X");
print "</tr>\n";
print "</table>\n";
print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_COLOR</caption>";
print "<tr>\n";
DisplayMobOp("$X_MONOCHROME","", "$X_MONOCHROME", "simplex.php","GRAYSCALE");
DisplayMobOp("$X_NEGATE","","$X_NEGATE","simplex.php","NEGATE");
DisplayMobOp("$X_SEPIA","","$X_SEPIA","sepia.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_TINT","","$X_TINT","tint.php","");
DisplayMobOp("$X_TINT","","HSL","hsl.php","");
DisplayMobOp("$X_ACCENT","","$X_ACCENT","accent.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_REDUCECOLOR","","$X_REDUCECOLOR","reducecolor.php","");
print "</tr>\n";
print "</table>\n";
print "<p><br>\n";


print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_ADJUST</caption>";
print "<tr>\n";
DisplayMobOp("$X_CONTRAST","", "+ $X_CONTRAST ", "simplex.php","CONTRASTUP");
DisplayMobOp("$X_CONTRAST","", "", "simplex.php","");
DisplayMobOp("$X_CONTRAST","", "- $X_CONTRAST", "simplex.php","CONTRASTDOWN");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_BRIGHTNESS","","+ $X_BRIGHTNESS", "simplex.php","BRIGHTUP");
DisplayMobOp("$X_CONTRAST","", "", "simplex.php","");
DisplayMobOp("$X_BRIGHTNESS","","- $X_BRIGHTNESS", "simplex.php","BRIGHTDOWN");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_SHARPNESS", "","+ $X_SHARPNESS", "simplex.php","SHARPUP"); 
DisplayMobOp("$X_CONTRAST","", "", "simplex.php","");
DisplayMobOp("$X_SHARPNESS", "","- $X_SHARPNESS", "simplex.php","SHARPDOWN");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_SATURATION","","+ $X_SATURATION","simplex.php","SATURATEUP");
DisplayMobOp("$X_CONTRAST","", "", "simplex.php","");
DisplayMobOp("$X_SATURATION", "","- $X_SATURATION", "simplex.php","SATURATEDOWN");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_HUE", "","+ $X_HUE", "simplex.php","HUEUP");
DisplayMobOp("$X_CONTRAST","", "", "simplex.php","");
DisplayMobOp("$X_HUE", "","- $X_HUE", "simplex.php","HUEDOWN");
print "</tr>\n";
print "</table>\n";
print "</div>\n";
?>


