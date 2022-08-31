#!/bin/bash
# 
# Developed by Fred Weinhaus 5/1/2009 .......... revised 5/9/2009
# 
# USAGE: cylinderize [-m mode] [-r radius] [-l length] [-w wrap] [-a angle] [-p pitch] [-v vpmethod] [-b bgcolor] [-t] infile outfile
# USAGE: cylinderize [-h or -help]
# 
# OPTIONS:
# 
# -m      mode           mode of orientation for cylinder axis; options are 
#                        horizontal (or h) or vertical (or v); default=horizontal
# -r      radius         radius of cylinder; float>0; default is one quarter
#                        of image width or height depending upon mode.
# -l      length         length of cylinder; lenght>0; default=width or height 
#                        depending upon mode and adjusted for the cylinder pitch angle.
# -w      wrap           portion of the cylinder circumference that is wrapped 
#                        with the image; options are half (or h) or full (or f); 
#                        default=half
# -a      angle          rotation angle in degrees about cylinder axis; 
#                        best used when wrap=full; float; -360<=angle<=360;
#                        default=0
# -p      pitch          pitch (tilt) angle of cylinder; float; -90<pitch<90;
#                        default=0
# -v      vpmethod       virtual-pixel method; default=black
# -b      bgcolor        background color for the case when vpmethod=background; 
#                        default=black
# -t                     trim background
# 
###
# 
# NAME: CYLINDERIZE 
# 
# PURPOSE: To apply a cylinder distortion to an image.
# 
# DESCRIPTION: CYLINDERIZE applies a cylinder distortion to an image so 
# that the image is wrapped about the cylinder. The image can be wrapped 
# either about the front half of the cylinder or all around the cylinder. 
# In the latter case, particularly, the image can be rotated about the cylinder.
# The cylinder can also be pitched (tilted).
# 
# 
# ARGUMENTS: 
# 
# -m mode ... MODE specifies the orientation for the cylinder axis. The 
# choices are horizontal (or h) or vertical (or v). The default is horizontal.
# 
# -r radius ... RADIUS is the radius of the cylinder in pixels. The values are 
# floats>0. The default is one quarter of the image width or height depending 
# upon the mode.
# 
# -l length ... Length is the length of the cylinder along its axis in pixels. 
# The values are floats with length>0. The default is either the width or height 
# depending upon mode and adjusted for the pitch of the cylinder. If a length 
# is provided, then the cylinder length will not be adjusted for pitch, but 
# the ends will still be adjusted for pitch.
# 
# -w wrap ... WRAP is the portion of the cylinder circumference that is wrapped with 
# the image. The choices are half (or h) or full (or f). The default is half.
# 
# -a angle ... ANGLE is the rotation the cylinder about its axis. This is 
# best used when wrap=full. The values are floats with -360<=angle<=360. 
# The default=0.
# 
# -p pitch ... PITCH (tilt) angle of the cylinder. Values are floats with 
# -90<pitch<90. Positive values move the top or left side towards the user 
# depending upon mode. The default=0.
# 
# -v vpmethod ... VPMETHOD is the virtual-pixel method to use. Any valid IM 
# virtual-pixel may be used. The background will be transparent if 
# vpmethod=transparent. The default is black. 
# 
# -b bgcolor ... BGCOLOR is the background color to use when vpmethod=background. 
# Any valid IM color is allowed. The background will be transparent if 
# bgcolor=none and vpmethod=background. The default is black.
# 
# -t ... TRIM the background.
# 
# NOTE: Thanks to Anthony Thyssen for the concept and basic equation for 
# achieving the tilted cylinder effect.
# 
# CAVEAT: No guarantee that this script will work on all platforms, 
# nor that trapping of inconsistent parameters is complete and 
# foolproof. Use At Your Own Risk. 
# 
######
# 

# set default values
mode="vertical"		# vertical or horizontal
wrap="half"			# half or full
radius=""			# default=1/4 width or height
length=""			# default=width or height
angle=0				# cylinder rotation
pitch=0				# cylinder pitch (tilt)
vpmethod="black"	# virtual pixel method
bgcolor="black"		# background color for vp method=background
trim="no"           # trim background: yes or no

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
elif [ $# -gt 19 ]
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
				-m)    # get  mode
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID MODE SPECIFICATION ---"
					   checkMinus "$1"
					   mode="$1"
					   mode=`echo "$mode" | tr "[:upper:]" "[:lower:]"`
					   case "$mode" in
							horizontal) mode="horizontal" ;;
									 h) mode="horizontal" ;;
							  vertical) mode="vertical" ;;
									 v) mode="vertical" ;;
									 *) errMsg "--- MODE=$mode IS NOT A VALID VALUE ---" ;;
					   esac
					   ;;
				-r)    # get radius
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID RADIUS SPECIFICATION ---"
					   checkMinus "$1"
					   radius=`expr "$1" : '\([.0-9]*\)'`
					   [ "$radius" = "" ] && errMsg "--- RADIUS=$radius MUST BE A NON-NEGATIVE FLOAT ---"
					   radtestA=`echo "$radius <= 0" | bc`
					   [ $radtestA -eq 1 ] && errMsg "--- RADIUS=$radius MUST BE A FLOAT GREATER THAN 0 ---"
					   ;;
				-l)    # get length
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID LENGTH SPECIFICATION ---"
					   checkMinus "$1"
					   length=`expr "$1" : '\([.0-9]*\)'`
					   [ "$length" = "" ] && errMsg "--- LENGTH=$length MUST BE A NON-NEGATIVE FLOAT ---"
					   lengthtestA=`echo "$length <= 0" | bc`
					   [ $lengthtestA -eq 1 ] && errMsg "--- LENGTH=$length MUST BE A FLOAT GREATER THAN 0 ---"
					   ;;
				-w)    # get wrap
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID WRAP SPECIFICATION ---"
					   checkMinus "$1"
					   wrap="$1"
					   wrap=`echo "$wrap" | tr "[:upper:]" "[:lower:]"`
					   case "$wrap" in
							half) wrap="half" ;;
							   h) wrap="half" ;;
							full) wrap="full" ;;
							   f) wrap="full" ;;
							   *) errMsg "--- MODE=$mode IS NOT A VALID VALUE ---" ;;
					   esac
					   ;;
				-a)    # get angle
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID ANGLE SPECIFICATION ---"
					   #checkMinus "$1"
					   angle=`expr "$1" : '\([-.0-9]*\)'`
					   [ "$angle" = "" ] && errMsg "--- ANGLE=$angle MUST BE A NON-NEGATIVE FLOAT ---"
					   angtestA=`echo "$angle < -360" | bc`
					   angtestB=`echo "$angle > 360" | bc`
					   [ $angtestA -eq 1 -o $angtestB -eq 1 ] && errMsg "--- ANGLE=$angle MUST BE A FLOAT BETWEEN -360 AND 360 ---"
					   ;;
				-p)    # get pitch
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID PITCH SPECIFICATION ---"
					   #checkMinus "$1"
					   pitch=`expr "$1" : '\([-.0-9]*\)'`
					   [ "$pitch" = "" ] && errMsg "--- PITCH=$pitch MUST BE A NON-NEGATIVE FLOAT ---"
					   pitchtestA=`echo "$pitch <= -90" | bc`
					   pitchtestB=`echo "$pitch >= 90" | bc`
					   [ $pitchtestA -eq 1 -o $pitchtestB -eq 1 ] && errMsg "--- PITCH=$pitch MUST BE A FLOAT BETWEEN -90 AND 90 ---"
					   ;;
				-v)    # get  vpmethod
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID VIRTUAL-PIXEL METHOD SPECIFICATION ---"
					   checkMinus "$1"
					   vpmethod="$1"
					   ;;
				-b)    # get  bgcolor
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID BACKGROUND COLOR SPECIFICATION ---"
					   checkMinus "$1"
					   bgcolor="$1"
					   ;;
				-t)    # get  trim
					   trim="yes"
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

# set up temporary images
tmpA="$dir/cylinderize_$$.mpc"
tmpB="$dir/cylinderize_$$.cache"
tmp0="$dir/cylinderize_0_$$.png"
tmp1="$dir/cylinder_1_$$.png"
trap "rm -f $tmpA $tmpB $tmp0 $tmp1; exit 0" 0
trap "rm -f $tmpA $tmpB $tmp0 $tmp1; exit 1" 1 2 3 15

# get image dimensions
width=`identify -ping -format %w $infile`
height=`identify -ping -format %h $infile`

# set sign of angle for use below in sign of -roll arguments
sign=`convert xc: -format "%[fx:sign($angle)]" info:`
[ $sign -lt 0 ] && sign="-" || sign="+"

# read the input image into the TMP cached image.
if [ "$mode" = "vertical" -a "$angle" != "0" ]; then
	rollx=`convert xc: -format "%[fx:abs($angle)*$width/360]" info:`
	convert -quiet -regard-warnings "$infile" +repage -roll ${sign}${rollx}+0 "$tmpA" ||
		errMsg "--- FILE $infile NOT READABLE OR HAS ZERO SIZE ---"
elif [ "$mode" = "horizontal" -a "$angle" != "0" ]; then
	rolly=`convert xc: -format "%[fx:abs($angle)*$height/360]" info:`
	convert -quiet -regard-warnings "$infile" +repage -roll +0${sign}${rolly} "$tmpA" ||
		errMsg "--- FILE $infile NOT READABLE OR HAS ZERO SIZE ---"
else
	convert -quiet -regard-warnings "$infile" +repage "$tmpA" ||
		errMsg "--- FILE $infile NOT READABLE OR HAS ZERO SIZE ---"
fi

: '
# test that length<=width or height
if [ "$mode" = "vertical" -a "$length" != "" ]; then
	test=`echo "$length <= $height" | bc`
	[ $test -ne 1 ] && errMsg "--- LENGTH MUST NOT BE GREATER THAN HEIGHT ---"
elif [ "$mode" = "horizontal" -a "$length" != "" ]; then
	test=`echo "$length <= $width" | bc`
	[ $test -ne 1 ] && errMsg "--- LENGTH MUST NOT BE GREATER THAN WIDTH ---"
fi
'

# compute center coords
xc=`convert xc: -format "%[fx:($width-1)/2]" info:`
yc=`convert xc: -format "%[fx:($height-1)/2]" info:`

# get arcsin factor depending upon wrap
if [ "$wrap" = "half" ]; then
	factor=`convert xc: -format "%[fx:2/pi]" info:`
elif [ "$wrap" = "full" ]; then
	factor=`convert xc: -format "%[fx:1/pi]" info:`
fi

# get default radius and length
if [ "$mode" = "vertical" -a "$radius" = "" ]; then
	radius=`convert xc: -format "%[fx:$width/4]" info:`
elif [ "$mode" = "horizontal" -a "$radius" = "" ]; then
	radius=`convert xc: -format "%[fx:$height/4]" info:`
fi
if [ "$mode" = "vertical" -a "$length" = "" ]; then
	length1=`convert xc: -format "%[fx:$height]" info:`
elif [ "$mode" = "horizontal" -a "$length" = "" ]; then
	length1=`convert xc: -format "%[fx:$width]" info:`
else
	length1=$length
fi

# get tilted dimensions
if [ "$length" = "" ]; then
	length1=`convert xc: -format "%[fx:$length1*cos(pi*$pitch/180)]" info:`
fi
radius1=`convert xc: -format "%[fx:$radius*sin(pi*$pitch/180)]" info:`
if [ "$mode" = "vertical" ]; then
	height1=`convert xc: -format "%[fx:$length1+$radius1]" info:`
else
	width1=`convert xc: -format "%[fx:$length1+$radius1]" info:`
fi

# set up for transparency
if [ "$vpmethod" = "transparent" -o "$bgcolor" = "none" ]; then
	channels="-channel rgba -alpha on"
else
	channels=""
fi

# set up for background color
if [ "$vpmethod" = "background" ]; then
	backgroundcolor="-background $bgcolor"
elif [ "$vpmethod" = "black" ]; then
	backgroundcolor="-background black"
elif [ "$vpmethod" = "white" ]; then
	backgroundcolor="-background white"
elif [ "$vpmethod" = "gray" ]; then
	backgroundcolor="-background gray"
elif [ "$vpmethod" = "transparent" ]; then
	backgroundcolor="-background none"
else
	backgroundcolor=""
fi

# process image
if [ "$mode" = "vertical" ]; then
	# Slow method using fx
	#	convert $tmpA -virtual-pixel $vpmethod -fx \
	#		"xd=(i-$xc)/$radius; $ffx xs=$xc*ffx+$xc; u.p{xs,j}" \
	#		$outfile
	# Equivalent displace map must be relative to i and range from 0 to 1, 
	# but slow fx method above is relative to $xc and 
	# ffx ranges from -1 to 1 (for wrap=half)
	# Thus we must modify the conversion to a displament map
	# from simply 0.5*(ffx-0.5) [which scales ffx from -1,1 to 0,1]
	# to 0.5*(ffx+($xc-i)/$xc)+0.5 [to account for the change from i to $xc]

# create horizontal cylinder map
	ffx="ffx=$factor*asin(xd);"

	convert -size ${width}x1 xc: -virtual-pixel black -fx \
		"xd=(i-$xc)/$radius; $ffx xs=0.5*(ffx+($xc-i)/($xc))+0.5; xd>1?1:xs" \
		-scale ${width}x${height1}! \
		$tmp0

# create vertical tilted map
	ffx="ffx=-sqrt(1-(xd)^2);"
	convert -size ${width}x1 xc: -virtual-pixel black -fx \
		"xd=(i-$xc)/$radius; $ffx xs=0.5*(ffx)+0.5; abs(xd)>1?0.5:xs" \
		-scale ${width}x${height1}! \
		$tmp1

# apply displacement
	# convert length1 to percentage of height
	length2=`convert xc: -format "%[fx:100*($length1)/$height]" info:`
	convert $tmpA -resize 100x${length2}% \
		$backgroundcolor -gravity north -extent ${width}x${height1} $tmpA
	composite $tmp0 $tmpA $tmp1 $channels -virtual-pixel $vpmethod \
		$backgroundcolor -displace ${xc}x${radius1} $tmpA
	if [ "$trim" = "yes" ]; then
		convert $tmpA -fill $bgcolor -trim $outfile
	else
		convert $tmpA $outfile
	fi

elif [ "$mode" = "horizontal" ]; then

# create vertical cylinder map
	ffy="ffy=$factor*asin(yd);"

	convert -size 1x${height} xc: -virtual-pixel black -fx \
		"yd=(j-$yc)/$radius; $ffy ys=0.5*(ffy+($yc-j)/($yc))+0.5; yd>1?1:ys" \
		-scale ${width1}x${height}! \
		$tmp0

# create horizontal tilted map
	ffy="ffy=-sqrt(1-(yd)^2);"
	convert -size 1x${height} xc: -virtual-pixel black -fx \
		"yd=(j-$yc)/$radius; $ffy ys=0.5*(ffy)+0.5; abs(yd)>1?0.5:ys" \
		-scale ${width1}x${height}! \
		$tmp1

# apply displacement
	# convert length1 to percentage of height
	length2=`convert xc: -format "%[fx:100*($length1)/$width]" info:`
	convert $tmpA -resize ${length2}x100% \
		$backgroundcolor -gravity west -extent ${width1}x${height} $tmpA
	composite $tmp1 $tmpA $tmp0 $channels -virtual-pixel $vpmethod \
		$backgroundcolor -displace ${radius1}x${yc} $tmpA
	if [ "$trim" = "yes" ]; then
		convert $tmpA -fill $bgcolor -trim $outfile
	else
		convert $tmpA $outfile
	fi

fi

exit 0
