<?php
print "<div class=\"opslist4\" id=\"opslist4\">\n";
print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_LAYER</caption>";
print "<tr>\n";
DisplayMobOp("$X_TRANSPARENT","",$X_TRANSPARENT,"trans.php","");
DisplayMobOp("","", "", "simplex.php","");
DisplayMobOp("$X_TRANSLUCENT","",$X_TRANSLUCENT,"opacity.php","");
print "</tr>\n";
print "</table>\n";
print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_WARP</caption>";
print "<tr>\n";
DisplayMobOp("$X_BEND","", "$X_BEND", "bend.php","");
DisplayMobOp("$X_SWIRL","", "$X_SWIRL", "swirl.php","");
DisplayMobOp("$X_EXPLODE","", "$X_EXPLODE", "explode.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_WAVE","","$X_BLUR","wave.php","");
DisplayMobOp("$X_FRACTAL","","$X_TEXTURE","fractal.php","");
DisplayMobOp("$X_SPLICE","","$X_OUTLINE","splice.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_MELT","","$X_MELT","melt.php","");
DisplayMobOp("$X_PIXELATE","","$X_PIXELATE","pixel.php","");
DisplayMobOp("$X_ROLLIMAGE","","$X_ROLLIMAGE","roll.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_KALEIDOSCOPE","","$X_KALEIDOSCOPE","k.php","");
DisplayMobOp("$X_MUTATE","","$X_MUTATE","mutate.php","");
DisplayMobOp("$X_STRETCH","","$X_STRETCH","stretch.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_TIMETUNNEL","","$X_TIMETUNNEL","timetunnel.php","");
DisplayMobOp("$X_FOSSILIZE","","$X_FOSSILIZE","fossil.php","");
DisplayMobOp("$X_3DSHAPE","","$X_3DSHAPE","3D.php","");
print "</tr>\n";
print "</table>\n";
print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_ARTISTIC</caption>";
print "<tr>\n";
DisplayMobOp("$X_SKETCH","", "$X_SKETCH", "sketch.php","");
DisplayMobOp("$X_PENCIL","","$X_PENCIL","pencil.php","");
DisplayMobOp("$X_OILPAINT","","$X_OILPAINT","paint.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_TINT","","$X_TINT","tint.php","");
DisplayMobOp("$X_TINT","","HSL","hsl.php","");
DisplayMobOp("$X_ACCENT","","$X_ACCENT","accent.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("David Hill","","David Hill","davidhill.php","");
DisplayMobOp("$X_BLACKLIGHT","","$X_BLACKLIGHT","tricolor.php","");
DisplayMobOp("$X_STAINEDGLASS","","$X_STAINEDGLASS","glass.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_PSYCHEDELIC","","$X_PSYCHEDELIC","lsd.php","");
DisplayMobOp("Inscribe","","Inscribe","inscribe.php","");
DisplayMobOp("$X_EMBOSS","","$X_EMBOSS","emboss.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_ARTGALLERY","","$X_ARTGALLERY","artist.php","");
DisplayMobOp("","","","simplex.php","");
DisplayMobOp("$X_FEELLUCKY","","$X_FEELLUCKY","randx.php","");
print "</tr>\n";
print "</table>\n";

print "</div>\n";
?>


