<?php
print "<table class=\"ops\" cellpadding=\"0\" cellspacing=\"0\">\n";
print "<caption class=\"ops\" align=top>$X_CONVERT</caption>";
DisplayOp("$X_GIF","$X_FORMAT","$X_GIF","convertx.php","GIF");
DisplayOp("$X_JPG","","$X_JPG","convertx.php","JPG");
DisplayOp("$X_PNG","","$X_PNG","convertx.php","PNG");
DisplayOp("$X_BMP","","$X_BMP","convertx.php","BMP");
DisplayOp("$X_ICO","","$X_ICO","convertx.php","ICO");
DisplayOp("$X_INSTANTFIX","$X_ENHANCE", "$X_INSTANTFIX", "simplex.php","NORMALIZE");
DisplayOp("$X_HDR","", "$X_HDR", "simplex.php","HDR");
DisplayOp("$X_SMOOTH","","$X_SMOOTH","simplex.php","SMOOTH");
DisplayOp("$X_ENRICH","","$X_ENRICH","simplex.php","ENRICH");
DisplayOp("$X_BLUR","","$X_BLUR","simplex.php","BLUR");
DisplayOp("$X_TEXTURE","","$X_TEXTURE","texture.php","");
DisplayOp("$X_OUTLINE","","$X_OUTLINE","outlinex.php","X");
DisplayOp("$X_MONOCHROME","$X_ADJUST", "$X_MONOCHROME", "simplex.php","GRAYSCALE");
DisplayOp("$X_NEGATE","","$X_NEGATE","simplex.php","NEGATE");
//DisplayOp("$X_SEPIA","","$X_SEPIA","sepia.php","");
DisplayOp("$X_TINT","","$X_TINT","tint.php","");
//DisplayOp("$X_TINT","","HSL","hsl.php","");
DisplayOp("$X_BEAUTY","","$X_BEAUTY","simplex.php","BEAUTY");
DisplayOp("$X_ACCENT","","$X_ACCENT","accent.php","");
DisplayOp("$X_REDUCECOLOR","","$X_REDUCECOLOR","reducecolor.php","");
DisplayOp("$X_CONTRAST","$X_CONTRAST", "$X_HIGHER", "simplex.php","CONTRASTUP");
DisplayOp("$X_CONTRAST","", "$X_LOWER", "simplex.php","CONTRASTDOWN");
DisplayOp("$X_BRIGHTNESS","$X_BRIGHTNESS","$X_HIGHER", "simplex.php","BRIGHTUP");
DisplayOp("$X_BRIGHTNESS","","$X_LOWER", "simplex.php","BRIGHTDOWN");
DisplayOp("$X_SHARPNESS", "$X_SHARPNESS","$X_HIGHER", "simplex.php","SHARPUP"); 
DisplayOp("$X_SHARPNESS", "","$X_LOWER", "simplex.php","SHARPDOWN");
DisplayOp("$X_SATURATION","$X_SATURATION","$X_HIGHER","simplex.php","SATURATEUP");
DisplayOp("$X_SATURATION", "","$X_LOWER", "simplex.php","SATURATEDOWN");
DisplayOp("$X_HUE", "$X_HUE","$X_HIGHER", "simplex.php","HUEUP");
DisplayOp("$X_HUE", "","$X_LOWER", "simplex.php","HUEDOWN");
//DisplayOp(".","",".","nbatchtrans.php","");
print "<tr><td></td></tr>\n";
print "</table>\n";
?>


