#!/bin/bash
#
# Developed by Fred Weinhaus 3/11/2008 .......... revised 3/12/2008
#
# USAGE: picframe [-f frameid] [-m mattesize] [-c mattecolor] [-b bordersize] [-s shade] [-a adjust] infile outfile
# USAGE: picframe [-h or -help]
#
# OPTIONS:
#
# -f      frameid            id number of picture frame; Value are 
#                            between 1 and number of png files in 
#                            picframecorners directory; default=1;
#                            see below for id to name correspondences
# -m      mattesize          surrounding matte thickness in pixels; 
#                            default=0 or no matte
# -c      mattecolor         color to use for surrounding matte
#                            default=cornsilk
# -b      bordersize         thickness of black border between image and matte
#                            default=0 or no border
# -s      shade              percent shading to add to the border; 
#                            shade ranges from 1 to 100; integer; default=0
# -a      adjust             adjust the brightness, saturation and hue of 
#                            the frame. This is useful as shading reduces the 
#                            saturation. Values are expressed as three integers 
#                            percentage changes separated by commas. See -modulate.
#                            Default is 100,100,100 for no change.
#
###
#
# NAME: PICFRAME 
# 
# PURPOSE: To add a picture frame around and image.
# 
# DESCRIPTION: PICFRAME adds a picture frame around an image using pictures 
# of actual frames. There is an option to add a black border around the image 
# and/or a colored matte.
# 
# 
# OPTIONS: 
# 
# -f frameid ... FRAMEID is the id number for the picture frame. The following  
# id and frame types are allowd: 1) oak, 2) rustic mahogany, 3) light rosewood, 
# 4) light gold, 5) dark rosewood, 6) gold finish, 7) ornate rosewood, 
# 8) onate silver, 9) ornate walnut, 10) maple, 11) ornate gold and 12) mahogany.
# The default is 1) oak.
# 
# -m mattesize ... MATTESIZE is the thicknesses in pixels for the optional matte  
# around the image. The default is 0 or no matte.
# 
# -c mattecolor ... MATTECOLOR is the color of the optional matte surrounding the   
# image. Any IM color specification is valid. Be sure to enclose it in double quotes. 
# The default is cornsilk. For colornames see http://imagemagick.org/script/color.php
#
# -b bordersize ... BORDERSIZE is the thickness of the optional black border around 
# the image. The default is 0 or no black border.
#
# -s shade ... SHADE is the percentage shading from an upper left light source. Values 
# range from 0 to 100. Default is 0 or no shading.
# 
# -a adjust ... ADJUST affects the brightness, saturation and hue of the frame. It is 
# represented as three integer representing percentage changes separated by commas. 
# This is useful as shading decreases the saturation of the frame. Values greater/less 
# than 100 for the brightness and saturation will increase/decrease them. Values 
# greater/less than 100 for the hue will shift the color towards the green/red. A 
# value of 100,100,100 is the default and will make no change.
# 
# NOTE: Be sure to download the picframecorners folder and place it where you want it. 
# Then modify the framedir location in the defaults section just below to point 
# to where you have placed the brcorners folder.
# 
# If you want to add your own frames, simply cut out a square section of the 
# lower right corner of a picture frame whose dimensions are the thickness of 
# the frame and place it in the picframecorners directory with a name of 
# "brcorner#.png" where # is the next available integer. All picture corners 
# must be in PNG format.
# 
# Thanks to Anthony Thyssen for the shading technique. See 
# http://www.imagemagick.org/Usage/thumbnails/#frame_edge
# 
# CAVEAT: No guarantee that this script will work on all platforms, 
# nor that trapping of inconsistent parameters is complete and 
# foolproof. Use At Your Own Risk. 
# 
######
#

# set default values
# BE SURE TO SET THE FRAMEDIR FOLDER FOR YOUR ACTUAL LOCATION
framedir="/Users/fred/Applications/ImageMagick-Scripts/bin/picframecorners"
framedir="/var/www/christopherminson/httpdocs/openimage//wimages/picframecorners"
frameid=1
mattesize=0
mattecolor="cornsilk"
bordersize=0
bordercolor="black"
shade=0
adjust="100,100,100"

# count number of picture frame styles
maxframes=`ls -l $framedir | sed -n 's/\(.*\.png\)/\1/p' | wc -l`


# set directory for temporary files
dir="."    # suggestions are dir="." or dir="/tmp"

# set up functions to report Usage and Usage with Description
PROGNAME=`type $0 | awk '{print $3}'`  # search for executable on path
PROGDIR=`dirname $PROGNAME`            # extract directory of program
PROGNAME=`basename $PROGNAME`          # base name of program
usage1() 
	{
	echo >&2 ""
	echo >&2 "$PROGNAME:" "$@"
	sed >&2 -n '/^###/q;  /^#/!q;  s/^#//;  s/^ //;  4,$p' "$PROGDIR/$PROGNAME"
	}
usage2() 
	{
	echo >&2 ""
	echo >&2 "$PROGNAME:" "$@"
	sed >&2 -n '/^######/q;  /^#/!q;  s/^#*//;  s/^ //;  4,$p' "$PROGDIR/$PROGNAME"
	}


# function to report error messages
errMsg()
	{
	echo ""
	echo $1
	echo ""
	usage1
	exit 1
	}


# function to test for minus at start of value of second part of option 1 or 2
checkMinus()
	{
	test=`echo "$1" | grep -c '^-.*$'`   # returns 1 if match; 0 otherwise
    [ $test -eq 1 ] && errMsg "$errorMsg"
	}

# test for correct number of arguments and get values
if [ $# -eq 0 ]
	then
	# help information
   echo ""
   usage2
   exit 0
elif [ $# -gt 12 ]
	then
	errMsg "--- TOO MANY ARGUMENTS WERE PROVIDED ---"
else
	while [ $# -gt 0 ]
		do
			# get parameter values
			case "$1" in
		  -h|-help)    # help information
					   echo ""
					   usage2
					   exit 0
					   ;;
				-f)    # frameid
					   shift  # to get the next parameter - fuzzval
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID FRAMEID SPECIFICATION ---"
					   checkMinus "$1"
					   frameid=`expr "$1" : '\([0-9]*\)'`
					   [ "$frameid" = "" ] && errMsg "--- FRAMEID=$frameid MUST BE A POSITIVE INTEGER ---"
					   frameidtest=`echo "$frameid > $maxframes" | bc`
					   [ $frameidtest -eq 1 ] && errMsg "--- FRAMEID=$frameid MUST BE AN INTEGER BETWEEN 1 AND 12 ---"
					   ;;
				-m)    # mattesize
					   shift  # to get the next parameter - fuzzval
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID MATTESIZE SPECIFICATION ---"
					   checkMinus "$1"
					   mattesize=`expr "$1" : '\([0-9]*\)'`
					   [ "$mattesize" = "" ] && errMsg "--- MATTESIZE=$mattesize MUST BE A NON-NEGATIVE INTEGER ---"
					   ;;
				-c)    # get mattecolor
					   shift  # to get the next parameter - lineval
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID MATTECOLOR SPECIFICATION ---"
					   checkMinus "$1"
					   mattecolor="$1"
					   ;;
				-b)    # bordersize
					   shift  # to get the next parameter - fuzzval
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID BORDERSIZE SPECIFICATION ---"
					   checkMinus "$1"
					   bordersize=`expr "$1" : '\([0-9]*\)'`
					   [ "$bordersize" = "" ] && errMsg "--- BORDERSIZE=$bordersize MUST BE A NON-NEGATIVE INTEGER ---"
					   ;;
				-s)    # shade
					   shift  # to get the next parameter - shade
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID SHADE SPECIFICATION ---"
					   checkMinus "$1"
					   shade=`expr "$1" : '\([0-9]*\)'`
					   [ "$shade" = "" ] && errMsg "--- SHADE=$shade MUST BE A NON-NEGATIVE INTEGER ---"
				   	   shadetestA=`echo "$shade < 0" | bc`
				   	   shadetestB=`echo "$shade > 100" | bc`
				   	   [ $shadetestA -eq 1 -o $shadetestB -eq 1 ] && errMsg "--- SHADE=$shade MUST BE A NON-NEGATIVE INTEGER BETWEEN 0 AND 100 ---"					   
					   ;;
	   			-a)    # adjust
					   shift  # to get the next parameter - adjust
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID ADJUST SPECIFICATION ---"
					   checkMinus "$1"
		 			   adjust=`expr "$1" : '\([0-9]*,[0-9]*,[0-9]*\)'`
					   [ "$adjust" = "" ] && errMsg "--- ADJUST=$adjust MUST BE THREE NON-NEGATIVE INTEGERS SEPARATED BY COMMAS ---"
		 			   ;;
				 -)    # STDIN and end of arguments
					   break
					   ;;
				-*)    # any other - argument
					   errMsg "--- UNKNOWN OPTION ---"
					   ;;
		     	 *)    # end of arguments
					   break
					   ;;
			esac
			shift   # next option
	done
	#
	# get infile and outfile
	infile=$1
	outfile=$2
fi

# test that infile provided
[ "$infile" = "" ] && errMsg "NO INPUT FILE SPECIFIED"

# test that outfile provided
[ "$outfile" = "" ] && errMsg "NO OUTPUT FILE SPECIFIED"


# set directory for temporary files
dir="."    # suggestions are dir="." or dir="/tmp"


tmpA="$dir/picframe_$$.mpc"
tmpB="$dir/picframe_$$.cache"
tmp0="$dir/picframe_0_$$.png"
tmp1="$dir/picframe_1_$$.png"
tmp2="$dir/picframe_2_$$.png"
trap "rm -f $tmpA $tmpB $tmp0 $tmp1 $tmp2; exit 0" 0
trap "rm -f $tmpA $tmpB $tmp0 $tmp1 $tmp2; exit 1" 1 2 3 15

# test infile
if convert -quiet -regard-warnings "$infile" +repage "$tmp0"
	then
	w1=`identify -format %w $tmp0`
	h1=`identify -format %h $tmp0`
else
	errMsg "--- FILE $infile DOES NOT EXIST OR IS NOT AN ORDINARY FILE, NOT READABLE OR HAS ZERO SIZE ---"
fi


# get frame bottom right (SouthEast) corner image
brcorner="${framedir}/brcorner$frameid.png"
if convert -quiet -regard-warnings "$brcorner" +repage "$tmpA"
	then
	w2=`identify -format %w $tmpA`
	h2=`identify -format %h $tmpA`
else
	errMsg "--- FILE $infile DOES NOT EXIST OR IS NOT AN ORDINARY FILE, NOT READABLE OR HAS ZERO SIZE ---"
fi

#compute center section width and height
w3=`expr $w1 + 2 \* $bordersize + 2 \* $mattesize`
h3=`expr $h1 + 2 \* $bordersize + 2 \* $mattesize`

#compute outfile image size
w4=`expr $w3 + 2 \* $w2`
h4=`expr $h3 + 2 \* $h2`

# compute frame non-corner sections resize values
size1="${w3}x${h2}!"
size2="${w2}x${h3}!"


if [ "$adjust" = "100,100,100" ]
	then
	modulate=""
else
	modulate="-modulate $adjust"
fi
	
if [ $shade -eq 100 ]
	then
	convert -size ${w3}x${h3} xc:none -bordercolor none \
          -compose Dst -frame ${w2}x${h2}+0+${w2}  $outfile
elif [ $shade -lt 100 ]
	then
	convert \( -size ${w4}x${h4} xc:white \) \
		\( $tmpA -rotate 180 \) -gravity NorthWest -composite \
		\( $tmpA[1x${h2}+0+0] -filter point -resize $size1 -flip \) -gravity North -composite \
		\( $tmpA -flip \) -gravity NorthEast -composite \
		\( $tmpA[${w2}x1+0+0] -filter point -resize $size2 -flop \) -gravity West -composite \
		\( $tmpA[${w2}x1+0+0] -filter point -resize $size2 \) -gravity East -composite \
		\( $tmpA -flop \) -gravity SouthWest -composite \
		\( $tmpA[1x${h2}+0+0] -filter point -resize $size1 \) -gravity South -composite \
		\( $tmpA \) -gravity SouthEast -composite \
		$tmp1
	if [ $shade -eq 0 ]
		then
		convert \( $tmp1 $modulate \) \
		\( $tmp0 -bordercolor black -border ${bordersize}x${bordersize} -bordercolor $mattecolor -border ${mattesize}x${mattesize} \) -gravity Center -composite \
		$outfile
	else
		convert -size ${w3}x${h3} xc:none -bordercolor none \
			-compose Dst -frame ${w2}x${h2}+0+${w2} $tmp2
		composite -blend $shade% $tmp2 $tmp1 $tmp1
		convert \( $tmp1 -modulate $adjust \) \
		\( $tmp0 -bordercolor black -border ${bordersize}x${bordersize} -bordercolor $mattecolor -border ${mattesize}x${mattesize} \) -gravity Center -composite \
		$outfile
	fi
fi
exit 0
