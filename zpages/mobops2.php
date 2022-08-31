<?php
print "<div class=\"opslist2\" id=\"opslist2\">\n";
print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_ORIENT </caption>";
print "<tr>\n";
DisplayMobOp("$X_ROTATE","","$X_ROTATE","rotate.php","");
DisplayMobOp("$X_FLIPVERTICALLY","","Flip Vertical","simplex.php","FLIP");
DisplayMobOp("$X_FLIPHORIZONTALLY","","Flip Horizontal","simplex.php","FLOP");
print "</tr>\n";
print "</table>\n";

print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_CUT </caption>";
print "<tr>\n";
DisplayMobOp("$X_VIGNETTE","","$X_VIGNETTE","simplex.php","VIGNETTE");
DisplayMobOp("$X_SHAVE","","$X_SHAVE","shave.php","");
DisplayMobOp("$X_TRIM","","$X_TRIM","trim.php","");
print "</tr>\n";
print "</table>\n";

print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_BORDER </caption>";
print "<tr>\n";
DisplayMobOp("$X_BORDER","$X_BORDER","$X_COLOR","border.php","");
DisplayMobOp("$X_PEARL","","$X_PEARL","pearlborder.php","");
DisplayMobOp("$X_GLITTER","","$X_GLITTER","glitterborder.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_ARTFRAME ","","$X_ARTFRAME","artframe.php","");
DisplayMobOp("$X_BLENDFRAME ","","$X_BLENDFRAME","imageborder.php","");
DisplayMobOp("$X_GEOMETRIC ","","$X_GEOMETRIC","geoborder.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_EDGE","","$X_EDGE","edge.php","");
DisplayMobOp("$X_BUTTON","","$X_BUTTON","button.php","");
print "</tr>\n";
print "</table>\n";

print "<p><br>\n";


print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_LABEL </caption>";
print "<tr>\n";
DisplayMobOp("$X_LABEL","","$X_BORDERTEXT","label.php","");
DisplayMobOp("$X_STAMPTEXT","","$X_STAMPTEXT","stamp.php","");
DisplayMobOp("$X_GLITTERTEXT","","$X_GLITTERTEXT","glittertext.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_STENCIL","","$X_STENCIL","stencil.php","");
DisplayMobOp("3D","","3D","3DLabel.php","");
DisplayMobOp("$X_WATERMARK","","$X_WATERMARK","water.php","");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_POSTER","","$X_POSTER","poster.php","");
print "</tr>\n";
print "</table>\n";

print "<p><br>\n";

print "<table class=\"ops\" border=\"1\" cellpadding=\"13\" cellspacing=\"13\">\n";
print "<caption class=\"ops\" align=top>$X_RESIZE </caption>";
print "<tr>\n";
DisplayMobOp("$X_RESIZE","","Percent","sizepercent.php","");
DisplayMobOp("$X_RESIZEPIXELS","","Pixels","sizepixels.php","");
DisplayMobOp("$X_THUMBNAIL","","$X_THUMBNAIL","simplex.php","THUMBNAIL");
print "</tr>\n";
print "<tr>\n";
DisplayMobOp("$X_REDUCFILESIZE","$X_FILEKBSIZE","$X_REDUCE","reduce.php","");
DisplayMobOp("$X_INSTANTREDUCE","","Instant","instantreducex.php","NA");
print "</tr>\n";
print "</table>\n";
print "</div>\n";
?>
