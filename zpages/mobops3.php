<?php
print "<div class=\"opslist3\" id=\"opslist3\">\n";
print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_ANIMATE</caption>\n";
print "<tr>\n";
DisplayMobOp("$X_ANIMATE","","By Frames","anim.php","");
DisplayMobOp("$X_POPART","","$X_POPART","popart.php","");
DisplayMobOp("Special Effects","","$X_VIBRATE","vibrate.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("Special Effects: Scroll","","$X_SCROLL","scroll.php","");
DisplayMobOp("Special Effects: DVD","","$X_SPIN","spin.php","");
DisplayMobOp("Create Image Reflection","","$X_REFLECT","reflect.php","");
print "<tr>\n";
print "</tr>\n";
DisplayMobOp("Pimp The Image","","$X_PIMP","pimp.php","");
DisplayMobOp("Special Effects: Deform","","$X_DEFORM","deform.php","");
DisplayMobOp("Special Effects: 3D Animation","","$X_3DANIMATION","3DAnim.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("Special Effects: Morph Two Images","","$X_POWERMORPH","m.php","");
DisplayMobOp("Special Effects: The Matrix","","$X_THEMATRIX","matrix.php","");
print "</tr>\n";
print "</table>\n";

print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<tr>\n";
print "<caption class=\"ops\" align=top>$X_COMBINE</caption>\n";
DisplayMobOp("Overlay Two Images","E","$X_OVERLAY","overlay.php","");
DisplayMobOp("Ghost Blend Two Images","","$X_BLEND","blend.php","");
DisplayMobOp("blank shape","","$X_SHAPE","shape.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("groovy","","$X_GROOVY","groovy.php","");
DisplayMobOp("cookie cutter","","$X_COOKIE","cutter.php","");
DisplayMobOp("Jigsaw An Image","","$X_JIGSAW","jig.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("Montage","","$X_MONTAGE","mont.php",""); 
print "</tr>\n";
print "</table>\n";

print "<p><br>\n";


print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_MIX</caption>\n";
print "<tr>\n";
DisplayMobOp("Image Mix","$X_MIX","$X_WORLDMIX","mix.php","");
DisplayMobOp("Magazine","","$X_MAGAZINE","mag.php","");
DisplayMobOp("Apply A Natural Effect","","$X_FIREANDRAIN","fire.php","");
print "</tr>\n";
print "</table>\n";

print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_COLOR</caption>\n";
print "<tr>\n";
DisplayMobOp("Swap Image Colors","","$X_COLORSWAP","swapper.php","");
DisplayMobOp("Wash Image Colors","","$X_COLORWASH","simplex.php","WASH"); 
DisplayMobOp("Bleach This Image","","$X_BLEACH","bleached.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("Heat Map Solarize Image","","$X_HEATMAP","heatmap.php","");
DisplayMobOp("Black & White","","$X_BLACKANDWHITE","blackwhite.php","");
DisplayMobOp("cartoon or avatar","","Cartoon","cartoon.php","");
print "</tr>\n";
print "</table>\n";
print "</div>\n";
?>
