<?php
include '../zcommon/common.inc';

if (CompleteWithNoAction()) return;

$LastOperation = $X_3DSHAPE;
$COLORS = array(
        "red","green","cyan","orange","purple","yellow",
        "pink", "olive","blue","yellow","silver","lime","magenta",
        "teal","orange","navy","red", "gold","aqua",
        "orange","yellow","red","lime");
$COLOR_COUNT = count($COLORS) - 1;

$DEFAULT="FLATBOX.gif";



	$setting = $_POST['SETTING'];
   if (strlen($setting) < 2)
        $setting = $DEFAULT;
    $setting = str_replace(".gif","",$setting);

	//$setting = 'CYLV';

    $inputFileDir = $_POST['CURRENTFILE'];
    $inputFileDir = "$BASE_DIR$inputFileDir";
    $inputFileName = basename($inputFileDir);

    $isAnimated = FALSE;
    if (IsAnimatedGIF($inputFileDir) == TRUE)
    {
        $imageList = GetAnimatedImages($inputFileDir);
        $isAnimated = TRUE;
    }


    // resize if too big
    GetImageAttributes($inputFileDir,$real_width,$real_height,$size);
    if ($size > 90000)
    {
        $real_width = $real_height = 300;
        $inputFileDir = ResizeImage($inputFileDir,$real_width,$real_height,FALSE);
        $inputFileName = basename($inputFileDir);
    }


	if ($isAnimated == TRUE)
    {
		RecordCommand("THREED ANIM");
        $AnimateString = "";
        foreach ($imageList as $imageFileDir)
        {
            $outputFileName = Image3D($imageFileDir);
            $outputFileDir = "$CONVERT_DIR$outputFileName";
            $AnimateString .= "$outputFileDir ";
        }

        // rebuild animation
        $outputFileName = NewNameGIF();
        $outputFileDir = "$CONVERT_DIR$outputFileName";
        $outputFilePath = "$CONVERT_PATH$outputFileName";
        $command = "convert -dispose previous -delay 25 $AnimateString -loop 0 $outputFileDir";
        $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    }
	else
	{
		RecordCommand("SINGLE");
		$outputFileName = Image3D($inputFileDir);
		$outputFileDir = "$CONVERT_DIR$outputFileName";
		$outputFilePath = "$CONVERT_PATH$outputFileName";
	}

RecordCommand("FINAL $outputFilePath");
RecordAndComplete("3D",$outputFilePath,FALSE);


function Image3D($inputFileDir)
{
global $CONVERT_DIR, $GIFSUFFIX, $setting,$real_width, $real_height;
global $COLOR_COUNT, $COLORS;


	$outputFileName = NewName($inputFileDir);
	$outputFileDir = "$CONVERT_DIR$outputFileName";

	RecordCommand("XTHREED $outputFileDir");

	$r = rand(0,$COLOR_COUNT);
	$c1 = $COLORS[$r];
	$r = ($r + 1) % $COLOR_COUNT;
	$c2 = $COLORS[$r];
	$r = ($r + 1) % $COLOR_COUNT;
	$c3 = $COLORS[$r];

	switch ($setting)
	{
case 'SPHERETILE':	//Sphere
	//$inputFileDir = ConvertToJPG($inputFileDir);
	if (($size > 80000) && ($real_width >  300))
	{
		$inputFileDir = ResizeImage($inputFileDir, 300, 300, FALSE);
	}
	$command = "../zshells/bubblewarp.sh \"1,1,.2,0\" -t arcsin -m polar -v tile -b white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'FLATBOX': //Box
	$f = ResizeImage($inputFileDir,200,200,TRUE);
	$command = "../zshells/New3DBox.sh pan=45 tilt=45 pef=0.5 filter=point $f $f $f $f $f $f $outputFileDir";
	$command = "../zshells/n3Dbox.sh pan=45 tilt=45 pef=0.5 filter=point $f $f $f $f $f $f $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$command = "cp -f $outputFileDir $BASE_DIR/tmp";
    	$execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'GIFTBOX': //Gift 174
	$f = ResizeImage($inputFileDir,200,200,TRUE);
	$command = "../zshells/3Dbox.sh pan=45 tilt=-45 pef=0 filter=point bgcolor=white $f $f $f $f $f $f $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'BOOKBOX': //Book
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 400x80! -rotate 90 $inputFileDir -matte  +distort Perspective '0,0 -60,40   0,398 -60,358   78,398 0,398   78,0 0,0' \) \( -resize 300x400! $inputFileDir -matte  +distort Perspective '0,0 0,0   0,398 0,398   298,398 198,310   298,0 198,60' \) -background white -layers merge  +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 3: // Box 3 Images
    $imageDir[0] = $inputFileDir;
    $imageDir[1] = $inputFileDir;
    $imageDir[2] = $inputFileDir;
    $previousImages = explode('XX',$PreviousFiles);
    $count = count($previousImages);
    if ($count > 2)
    {
        $imageDir[1] = "$BASE_DIR$previousImages[0]";
        $imageDir[2] = "$BASE_DIR$previousImages[1]";
    }
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $imageDir[0] -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $imageDir[1] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 4: // Gift Box 3 Images
    $imageDir[0] = $inputFileDir;
    $imageDir[1] = $inputFileDir;
    $imageDir[2] = $inputFileDir;
    $previousImages = explode('XX',$PreviousFiles);
    $count = count($previousImages);
    if ($count > 2)
    {
        $imageDir[1] = "$BASE_DIR$previousImages[0]";
        $imageDir[2] = "$BASE_DIR$previousImages[1]";
    }
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 1024x1024! $imageDir[0] -matte +distort Affine '0,1024 0,0   0,0 -174,-100  1024,1024 174,-100' \) \( -resize 1024x1024! $imageDir[1] -matte +distort Affine '1024,0 0,0   0,0 -174,-100  1024,1024 0,200' \) \( -resize 638x638! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,638 0,200    638,0 174,-100' \) -background white -layers merge +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 5: // Book 2 Images
    $imageDir[0] = $inputFileDir;
    $imageDir[1] = $inputFileDir;
    $previousImages = explode('XX',$PreviousFiles);
    $count = count($previousImages);
    if ($count > 1)
    {
        $imageDir[1] = "$BASE_DIR$previousImages[0]";
    }
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 400x80! -rotate 90 $imageDir[0] -matte  +distort Perspective '0,0 -60,40   0,398 -60,358   78,398 0,398   78,0 0,0' \) \( -resize 300x400! $imageDir[1] -matte  +distort Perspective '0,0 0,0   0,398 0,398   298,398 198,310   298,0 198,60' \) -background white -layers merge  +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 6: // UFO Invasion
    $command = "convert -vignette 10x20 $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    $inputFileDir = $outputFileDir;

    $targetName = NewName($inputFileDir);
    $outputFileDir = "$CONVERT_DIR$targetName";
    $outputFilePath = "$CONVERT_PATH$targetName";

    $imageDir[0] = ColorImage($inputFileDir,$c1);
    $imageDir[1] = ColorImage($inputFileDir,$c2);
    $imageDir[2] = ColorImage($inputFileDir,$c3);
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 1024x1024! $imageDir[0] -matte +distort Affine '0,1024 0,0   0,0 -174,-100  1024,1024 174,-100' \) \( -resize 1024x1024! $imageDir[1] -matte +distort Affine '1024,0 0,0   0,0 -174,-100  1024,1024 0,200' \) \( -resize 638x638! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,638 0,200    638,0 174,-100' \) -background white -layers merge +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'FOLDEDMIRROR': // Mirror Box
    $command = "/usr/bin/convert -virtual-pixel transparent \( -frame 13x13+5+5 -resize 511x511! $inputFileDir -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,200' \) \( -frame 13x13+5+5 -resize 511x511! $inputFileDir -matte +distort Affine '  0,0 0,0   0,511 0,200    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir";
    $command = "/usr/bin/convert -virtual-pixel transparent \( -frame 13x13+5+5 -resize 511x511! $inputFileDir -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,200' \) \( -frame 13x13+5+5 -resize 511x511! $inputFileDir -matte +distort Affine '  0,0 0,0   0,511 0,200    511,0 174,-100' \) -background white -layers merge $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'FLATBOXART': // Box Pop Art
    $imageDir[0] = ColorImage($inputFileDir,$c1);
    $imageDir[1] = ColorImage($inputFileDir,$c2);
    $imageDir[2] = ColorImage($inputFileDir,$c3);
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $imageDir[0] -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $imageDir[1] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'GIFTBOXART': // Gift Box Pop Art
    $f1 = $imageDir[0] = ColorImage($inputFileDir,$c1);
    $f2 = $imageDir[1] = ColorImage($inputFileDir,$c2);
    $f3 = $imageDir[2] = ColorImage($inputFileDir,$c3);
	$f1 = ResizeImage($f1,200,200,TRUE);
	$f2 = ResizeImage($f2,200,200,TRUE);
	$f3 = ResizeImage($f3,200,200,TRUE);
	$command = "../zshells/3Dbox.sh pan=45 tilt=-45 pef=0 filter=point bgcolor=white $f1 $f2 $f3  $outputFileDir";
    #$command = "/usr/bin/convert -virtual-pixel transparent \( -resize 1024x1024! $imageDir[0] -matte +distort Affine '0,1024 0,0   0,0 -174,-100  1024,1024 174,-100' \) \( -resize 1024x1024! $imageDir[1] -matte +distort Affine '1024,0 0,0   0,0 -174,-100  1024,1024 0,200' \) \( -resize 638x638! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,638 0,200    638,0 174,-100' \) -background white -layers merge +repage $outputFileDir";

    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'BOOKBOXART': // Book Box Pop Art
    $imageDir[0] = ColorImage($inputFileDir,$c1);
    $imageDir[1] = ColorImage($inputFileDir,$c2);
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 400x80! -rotate 90 $imageDir[0] -matte  +distort Perspective '0,0 -60,40   0,398 -60,358   78,398 0,398   78,0 0,0' \) \( -resize 300x400! $imageDir[1] -matte  +distort Perspective '0,0 0,0   0,398 0,398   298,398 198,310   298,0 198,60' \) -background white -layers merge  +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'FOLDEDMIRRORART': // Folded Mirror Pop Art
    $imageDir[0] = ColorImage($inputFileDir,"yellow");
    $imageDir[1] = ColorImage($inputFileDir,"green");
    $command = "/usr/bin/convert -virtual-pixel transparent \( -frame 13x13+5+5 -resize 511x511! $imageDir[0] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,200' \) \( -frame 13x13+5+5 -resize 511x511! $imageDir[1] -matte +distort Affine '  0,0 0,0   0,511 0,200    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 14: // Folded Mirror last 2 Images
    $imageDir[0] = $inputFileDir;
    $imageDir[1] = $inputFileDir;
    $previousImages = explode('XX',$PreviousFiles);
    $count = count($previousImages);
    if ($count > 1)
    {
        $imageDir[1] = "$BASE_DIR$previousImages[0]";
    }
    $command = "/usr/bin/convert -virtual-pixel transparent \( -frame 13x13+5+5 -resize 511x511! $imageDir[0] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,200' \) \( -frame 13x13+5+5 -resize 511x511! $imageDir[1] -matte +distort Affine '  0,0 0,0   0,511 0,200    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    break;
case 'CYLH': //cylinder h
    $command = "../zshells/cylinderize.sh -m horizontal -p 45 -p 45  -l 500 -v white -b white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'CYLV': //cylinder v
    $command = "../zshells/cylinderize.sh -m vertical -p 45 -p 45  -l 500 -v white -b white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'BOXCYLV':
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $inputFileDir -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $inputFileDir -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $inputFileDir -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background transparent -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$inputFileDir = $outputFileDir;
    $command = "../zshells/cylinderize.sh -m vertical -p 45 -p 45  -l 500 -v white -b white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'BOXCYLH':
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $inputFileDir -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $inputFileDir -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $inputFileDir -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background transparent -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$inputFileDir = $outputFileDir;
    $command = "../zshells/cylinderize.sh -m horizontal -p 45 -p 45  -l 500 -v white -b white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'ARTBOXCYLV':
    $imageDir[0] = ColorImage($inputFileDir,$c1);
    $imageDir[1] = ColorImage($inputFileDir,$c2);
    $imageDir[2] = ColorImage($inputFileDir,$c3);
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $imageDir[0] -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $imageDir[1] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$inputFileDir = $outputFileDir;
    $command = "../zshells/cylinderize.sh -m vertical -p 45 -p 45  -l 500 -v white -b white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'ARTBOXCYLH':
    $imageDir[0] = ColorImage($inputFileDir,$c1);
    $imageDir[1] = ColorImage($inputFileDir,$c2);
    $imageDir[2] = ColorImage($inputFileDir,$c3);
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $imageDir[0] -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $imageDir[1] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$inputFileDir = $outputFileDir;
    $command = "../zshells/cylinderize.sh -m horizontal -p 45 -p 45  -l 500 -v white -b white $inputFileDir $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'ARTFLATBOOK';
    $imageDir[0] = ColorImage($inputFileDir,$c1);
    $imageDir[1] = ColorImage($inputFileDir,$c2);
    $imageDir[2] = ColorImage($inputFileDir,$c3);
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $imageDir[0] -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $imageDir[1] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$inputFileDir = $outputFileDir;
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 400x80! -rotate 90 $inputFileDir -matte  +distort Perspective '0,0 -60,40   0,398 -60,358   78,398 0,398   78,0 0,0' \) \( -resize 300x400! $inputFileDir -matte  +distort Perspective '0,0 0,0   0,398 0,398   298,398 198,310   298,0 198,60' \) -background white -layers merge  +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'ARTFLATBOX';
    $imageDir[0] = ColorImage($inputFileDir,$c1);
    $imageDir[1] = ColorImage($inputFileDir,$c2);
    $imageDir[2] = ColorImage($inputFileDir,$c3);
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $imageDir[0] -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $imageDir[1] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$inputFileDir = $outputFileDir;
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 1024x1024! $inputFileDir -matte +distort Affine '0,1024 0,0   0,0 -174,-100  1024,1024 174,-100' \) \( -resize 1024x1024! $inputFileDir -matte +distort Affine '1024,0 0,0   0,0 -174,-100  1024,1024 0,200' \) \( -resize 638x638! $inputFileDir -matte +distort Affine '  0,0 0,0   0,638 0,200    638,0 174,-100' \) -background white -layers merge +repage $outputFileDir";
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
case 'ARTFLATFLAT';
    $imageDir[0] = ColorImage($inputFileDir,$c1);
    $imageDir[1] = ColorImage($inputFileDir,$c2);
    $imageDir[2] = ColorImage($inputFileDir,$c3);
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $imageDir[0] -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $imageDir[1] -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $imageDir[2] -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background white -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	$inputFileDir = $outputFileDir;
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $inputFileDir -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $inputFileDir -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $inputFileDir -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background transparent -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
default:
	RecordCommand("THREED ERROR in Setting $setting ");
    $command = "/usr/bin/convert -virtual-pixel transparent \( -resize 511x511! $inputFileDir -matte +distort Affine '0,511 0,0   0,0 -174,-100  511,511 174,-100' \) \( -resize 511x511! $inputFileDir -matte +distort Affine '511,0 0,0   0,0 -174,-100  511,511 0,100' \) \( -resize 511x511! $inputFileDir -matte +distort Affine '  0,0 0,0   0,511 0,100    511,0 174,-100' \) -background transparent -layers merge +repage $outputFileDir"; 
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
	break;
	}
	RecordCommand("THREED $setting $command");
	return $outputFileName;
}


function ColorImage($imageDir, $color)
{
global $CONVERT_DIR;

    $targetName = basename($imageDir);
    $targetName = TMPName($targetName);
    $outputFileDir = "$CONVERT_DIR$targetName";
    $command = "convert -tint 80% -fill $color $imageDir $outputFileDir";
    //RecordCommand("ZZ ResizeColorImage: $color");
    $execResult = exec("$command 2>&1", $lines, $ConvertResultCode);
    return $outputFileDir;
}

?>
