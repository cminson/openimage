#!/bin/bash
#
# Developed by Fred Weinhaus 1/19/2009 .......... revised 2/11/2009
#
# USAGE: ripples [-t type ] [-a amplitude] [-w width] [-o offset] [-r rmin] [-c center] [-p power ] [-s shadeval] infile outfile
# USAGE: ripples [-h or -help]
#
# OPTIONS:
#
# -t      type               type of circular ripple effect; displace (or d), modulate (or m) 
#                            blend (or b); default=displace
# -a      amplitude          amplitude or height of displace ripple; integer>=0; default=20
# -w      width              width of single ripple; integer>0; default=25
# -o      offset             offset or extra spacing between ripples; integer>=0; default=0
# -r      rmin               minimum distance from center to first ripple; integer>=0; default=25
# -c      center             center point for circular ripples; center=cx,cy; integer>=0
# -p      power              power or exponent controlling tapering of ripples; float>=0; 
#                            power=1 is linear taper; default=0 or no taper
# -s      shadeval			 shading effect; shadeval=AzimuthxElevation angles; 
#                            integers; 0<=azimuth<=360; 0<=elevation<=90; default is no shading;
#
###
#
# NAME: RIPPLES 
# 
# PURPOSE: To apply various circular ripple effects to an image.
# 
# DESCRIPTION: RIPPLES applies various circular ripple effects to an image.
# The effects are displacement which produces water-like ripples, modulation 
# and blend. The latter are more suited for creating wavy patterns.
# 
# OPTIONS: 
#
# -t type ... TYPE of circular ripple/wave effect. Choices are: displace or (d), 
# modulate (or m) and blend (or b). The displace option produces water-like ripples. 
# The modulate and blend options are better at producing wavy patterns. The 
# default is displace.
# 
# -a amplitude ... AMPLITUDE or height of ripple. Values are integers>=0.
# The default=20. Types of modulate and blend are not sensitive to this 
# parameter.
#
# -w width ... WIDTH is the width or wavelength of a single ripple. Values are 
# integers>0. The default=25.
#
# -r rmin ... RMIN is the spacing from the center to the first ripple. 
# Values are integers>=0. The default=25.
# 
# -c center ... CENTER=cx,cy are the comma separated coordinates in the image 
# from where the circular ripples eminate. Values are integers>=0. The default 
# is the center of the image.
# 
# -p power ... POWER is the exponent that controls the tapering of the ripples/waves. 
# Values are floats>=0. Power=0 is no taper. Power=1 is linear taper. The 
# default=1
# 
# -s shadeval ... SHADEVAL=AZIMUTHxELEVATION are the optional x separated
# shading angles of azimulth (around) and elevation (up) for the lighting effect. 
# Values are integers, 0<=azimuth<=360 degree and 0<=elevation<=90 degrees. 
# See -shade for more details.
# 
# NOTE: Requires IM 6.4.2-8 or higher due to the use of -distort polar/depolar.
# 
# CAVEAT: No guarantee that this script will work on all platforms, 
# nor that trapping of inconsistent parameters is complete and 
# foolproof. Use At Your Own Risk. 
# 
######
#

# set default values
type=displace		# displace, modulate or blend
amplitude=20
width=25
offset=0
rmin=25
center=""
power=1
shadeval=""

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
elif [ $# -gt 18 ]
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
				-t)	   # get type
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID TYPE SPECIFICATION ---"
					   checkMinus "$1"
					   type=`echo "$1" | tr '[A-Z]' '[a-z]'`
					   case "$type" in 
					   		displace|d) type="displace";;
					   		modulate|m) type="modulate";;
					   		blend|b) type="blend";;
					   		*) errMsg "--- TYPE=$type IS AN INVALID VALUE ---" 
					   	esac
					   ;;					   
				-a)    # get amplitude
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID AMPLITUDE SPECIFICATION ---"
					   checkMinus "$1"
					   amplitude=`expr "$1" : '\([0-9]*\)'`
					   [ "$amplitude" = "" ] && errMsg "AMPLITUDE=$spread MUST BE AN INTEGER"
#		   			   amplitudetest=`echo "$amplitude < 1" | bc`
#					   [ $amplitudetest -eq 1 ] && errMsg "--- AMPLITUDE=$amplitude MUST BE A POSITIVE INTEGER ---"
					   ;;
				-w)    # get width
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID WIDTH SPECIFICATION ---"
					   checkMinus "$1"
					   width=`expr "$1" : '\([0-9]*\)'`
					   [ "$width" = "" ] && errMsg "WIDTH=$width MUST BE AN INTEGER"
		   			   widthtest=`echo "$width < 1" | bc`
					   [ $widthtest -eq 1 ] && errMsg "--- WIDTH=$width MUST BE A POSITIVE INTEGER ---"
					   ;;
				-o)    # get offset
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID OFFSET SPECIFICATION ---"
					   checkMinus "$1"
					   offset=`expr "$1" : '\([0-9]*\)'`
					   [ "$offset" = "" ] && errMsg "OFFSET=$offset MUST BE A NON-NEGATIVE INTEGER"
					   ;;
				-r)    # get rmin
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID RMIN SPECIFICATION ---"
					   checkMinus "$1"
					   rmin=`expr "$1" : '\([0-9]*\)'`
					   [ "$rmin" = "" ] && errMsg "RMIN=$rmin MUST BE A NON-NEGATIVE INTEGER"
					   ;;
				-p)    # get power
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID POWER SPECIFICATION ---"
					   checkMinus "$1"
					   power=`expr "$1" : '\([.0-9]*\)'`
					   [ "$power" = "" ] && errMsg "POWER=$power MUST BE A NON-NEGATIVE FLOAT"
					   ;;
				-c)    # get center
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID CENTER SPECIFICATION ---"
					   checkMinus "$1"
					   test=`echo "$1" | tr "," " " | wc -w`
					   [ $test -eq 1 -o $test -gt 2 ] && errMsg "--- INCORRECT NUMBER OF COORDINATES SUPPLIED ---"
					   center=`expr "$1" : '\([0-9]*,[0-9]*\)'`
					   [ "$center" = "" ] && errMsg "--- CENTER=$coords MUST BE A PAIR OF NON-NEGATIVE INTEGERS SEPARATED BY A COMMA ---"
					   center="$1,"
		   			   cx=`echo "$center" | cut -d, -f1`
		   			   cy=`echo "$center" | cut -d, -f2`
					   ;;
				-s)    # get shadeval
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID SHADEVAL SPECIFICATION ---"
					   checkMinus "$1"
					   test=`echo "$1" | tr "x" " " | wc -w`
					   [ $test -eq 1 -o $test -gt 2 ] && errMsg "--- INCORRECT NUMBER OF ANGLES SUPPLIED ---"
					   shadeval=`expr "$1" : '\([0-9]*x[0-9]*\)'`
					   [ "$shadeval" = "" ] && errMsg "--- SHADEVAL=$shadeval MUST BE A PAIR OF NON-NEGATIVE INTEGERS SEPARATED BY AN X ---"
					   shadeval="$1x"
		   			   azimuth=`echo "$shadeval" | cut -dx -f1`
		   			   elevation=`echo "$shadeval" | cut -dx -f2`
		   			   azimuthtest=`echo "$azimuth < 0 || $azimuth > 360" | bc`
					   [ $azimuthtest -eq 1 ] && errMsg "--- AZIMUTH=$azimuth MUST BE AN INTEGER BETWEEN 0 AND 360 ---"
		   			   elevationtest=`echo "$elevation < 0 || $elevation > 90" | bc`
					   [ $elevationtest -eq 1 ] && errMsg "--- ELEVATION=$elevation MUST BE AN INTEGER BETWEEN 0 AND 90 ---"	   			   
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

# setup temporary images
tmpA1="$dir/ripples_1_$$.mpc"
tmpA2="$dir/ripples_1_$$.cache"
tmp1="$dir/ripples_1_$$.miff"
trap "rm -f $tmpA1 $tmpA2 $tmp1; exit 0" 0
trap "rm -f $tmpA1 $tmpA2 $tmp1; exit 1" 1 2 3 15


# read input and make sure OK
if /usr/bin/convert -quiet -regard-warnings "$infile" +repage "$tmpA1"
	then
	: ' do nothing '
else
	errMsg "--- FILE $infile DOES NOT EXIST OR IS NOT AN ORDINARY FILE, NOT READABLE OR HAS ZERO SIZE ---"
fi

# get center coords
if [ "$center" = "" ]; then
cx=`/usr/bin/convert $tmpA1 -format "%[fx:(w-1)/2]" info:`
cy=`/usr/bin/convert $tmpA1 -format "%[fx:(h-1)/2]" info:`
fi

#setup shade
if [ "$shadeval" != "" ]; then
	shade="-shade $shadeval -contrast-stretch 0"
else
	shade=""
fi

# get image dimensions
ww=`/usr/bin/convert $tmpA1 -format %w info:`
hh=`/usr/bin/convert $tmpA1 -format %h info:`
hmr=`/usr/bin/convert xc: -format "%[fx:$hh-$rmin]" info:`

# process linear gradient into vertical ripple pattern
if [ "$offset" = "0" -o "$offset" = "0.0" ]; then
/usr/bin/convert \( -size 1x${hmr} gradient: -negate \) \
	-channel G \
	-fx "-pow(($hmr-j)/$hh,$power)*0.5*sin(2*pi*u*$hh/$width)+0.5" \
	-separate +channel \
	\( -size 1x${rmin} xc:"gray(50%)" \) \
	+swap -append \
	-scale ${ww}x${hh}! \
	$tmp1
else
/usr/bin/convert \( -size 1x${width} gradient: -negate \) \
	\( -size 1x${offset} xc:"gray(50%}" \) \
	-append miff:- | \
	/usr/bin/convert \( -size 1x${hmr} tile:- \) \
	\( -size 1x${rmin} xc:"gray(50%)" \) \
	+swap -append \
	-channel G -fx "-pow(($hh-j)/$hh,$power)*0.5*sin(2*pi*u)+0.5" -separate +channel \
	-scale ${ww}x${hh}! \
	$tmp1
fi


# process image
if [ "$type" = "displace" ]; then
# /usr/bin/convert image to polar coords
# do composite displace with tmp1
# /usr/bin/convert composite back to rectangular coords
/usr/bin/convert $tmpA1 -distort depolar -1,0,$cx,$cy $tmpA1
/usr/bin/composite $tmp1 $tmpA1 -displace 0x${amplitude} $tmpA1
/usr/bin/convert $tmpA1 -distort polar -1,0,$cx,$cy $outfile

elif [ "$type" = "modulate" ]; then
# /usr/bin/convert ripple image from polar to rectangular coords
# composite with multiply
/usr/bin/convert $tmp1 -distort polar -1,0,$cx,$cy $shade $tmp1
/usr/bin/convert $tmpA1 $tmp1 \
	-compose multiply -composite $outfile

elif [ "$type" = "blend" ]; then
# /usr/bin/convert ripple image from polar to rectangular coords
# special fx blend
/usr/bin/convert $tmp1 -distort polar -1,0,$cx,$cy $shade $tmp1
/usr/bin/convert $tmpA1 $tmp1 \
	-fx "(v-0.5)+(0.5*u)" $outfile
fi

exit 0
