<?php
include '../zcommon/common.inc';
$FLOODFILL_HELP = "Pick the spot on the image to Flood Fill (if nothing selected will start at center of the image)<p>The image will be colored from that point to the NewColor<p>Use the Fuzz option to broaden the range of filled colors";

$Title=$X_FLOODFILL;
DisplayMainPageReturn();
DisplayTitle($Title);
DisplayFormStart();
DisplayColorPicker($X_PICKCOLOR,'PICKCOLOR','COLOR1','');
DisplaySep1();
DisplayColorPicker($X_NEWCOLOR,'NEWCOLOR','COLOR2','#ff0000');
DisplaySep1();
DisplayFuzzPicker($X_FUZZ,'FUZZ',10);
DisplayHiddenField('CLIENTX');
DisplayHiddenField('CLIENTY');
DisplayConvertButton();
DisplayFormEnd();
DisplayLineSep0();
print $FLOODFILL_HELP;
?>
