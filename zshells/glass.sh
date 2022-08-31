#!/bin/bash
#
# Developed by Fred Weinhaus 6/12/2010 .......... 6/14/2010
#
# USAGE: stainedglass [-k kind] [-s size] [-o offset] [-n ncolors] [-b bright] [-e ecolor] [-t thick] [-r rseed] infile outfile
# USAGE: stainedglass [-h or -help]
#
# OPTIONS:
#
# -k      kind         kind of stainedglass cell shape; choices are: square 
#                      (or s),  hexagon (or h), random (or r); default=random
# -s      size         size of cell; integer>0; default=16 
# -o      offset       random offset amount; integer>=0; default=6; 
#                      only applies to kind=random
# -n      ncolors      number of desired reduced colors for the output; 
#                      integer>1; default is no color reduction
# -b      bright       brightness value in percent for output image; 
#                      integer>=0; default=100
# -e      ecolor       color for edge or border around each cell; any valid 
#                      IM color; default=black
# -t      thick        thickness for edge or border around each cell; 
#                      integer>=0; default=1; zero means no edge or border
# -r      rseed        random number seed value; integer>=0; if seed provided, 
#                      then image will reproduce; default is no seed, so that 
#                      each image will be randomly different; only applies 
#                      to kind=random
#
###
#
# NAME: STAINEDGLASS 
# 
# PURPOSE: Applies a stained glass cell effect to an image.
# 
# DESCRIPTION: STAINEDGLASS applies a stained glass cell effect to an image. The 
# choices of cell shapes are hexagon, square and randomized square. The cell 
# size and border around the cell can be specified.
# 
# 
# OPTIONS: 
# 
# -k kind ... KIND of stainedglass cell shape; choices are: square (or s), 
# hexagon (or h), random (or r). The latter is a square with each corner 
# randomly offset. The default=random.
#
# -s size ... SIZE of stained glass cells. Values are integers>=0. The
# default=16.
#
# -o offset ... OFFSET is the random offset amount for the case of kind=random. 
# Values are integers>=0. The default=6.
# 
# -n ncolors ... NCOLORS is the number of desired reduced colors in the output. 
# Values are integers>1. The default is no color reduction. Larger number of 
# colors takes more time to color reduce.
# 
# -b bright ... BRIGHTNESS value in percent for the output image. Values are
# integers>=0. The default=100 means no change in brightness.
# 
# -e ecolor ... ECOLOR is the color for the edge or border around each cell. 
# Any valid IM color is allowed. The default=black.
#
# -t thick ... THICK is the thickness for the edge or border around each cell. 
# Values are integers>=0. The default=1. A value of zero means no edge or 
# border will be included.
# 
# -r rseed ... RSEED is the random number seed value for kind=random. Values 
# are integers>=0. If a seed is provided,  then the resulting image will be 
# reproducable. The default is no seed. In that case, each resulting image 
# will be randomly different.
# 
# Thanks to Anthony Thyssen for critiqing the original version and for 
# several useful suggestions for improvement.
# 
# NOTE: This script will be slow due to the need to extract color values for 
# each cell corner point across the input image. A progress meter is therefore 
# provided to the terminal.
# 
# CAVEAT: No guarantee that this script will work on all platforms, 
# nor that trapping of inconsistent parameters is complete and 
# foolproof. Use At Your Own Risk. 
# 
######
#

# set default values
kind="random"		# random, square, hexagon
size=16				# cell size
offset=6			# pixel amount to randomly add or subtract to square corners
ncolors=""			# number of output colors
bright=100			# brightness adjust
ecolor="black"		# edge color
thick=1				# edge thickness
rseed=""			# seed for random

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
		     -help)    # help information
					   echo ""
					   usage2
					   exit 0
					   ;;
				-k)    # get  kind
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID KIND SPECIFICATION ---"
					   checkMinus "$1"
					   kind=`echo "$1" | tr '[A-Z]' '[a-z]'`
					   case "$kind" in 
					   		hexagon|h) kind="hexagon" ;;
					   		square|s) kind="square" ;;
					   		random|r) kind="random" ;;
					   		*) errMsg "--- KIND=$kind IS AN INVALID VALUE ---" ;;
					   	esac
					   ;;
				-s)    # get size
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID SIZE SPECIFICATION ---"
					   checkMinus "$1"
					   size=`expr "$1" : '\([0-9]*\)'`
					   [ "$size" = "" ] && errMsg "--- SIZE=$size MUST BE A NON-NEGATIVE INTEGER (with no sign) ---"
					   test1=`echo "$size < 1" | bc`
					   [ $test1 -eq 1 ] && errMsg "--- SIZE=$size MUST BE AN GREATER THAN 0 ---"
					   ;;
				-o)    # get offset
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID OFFSET SPECIFICATION ---"
					   checkMinus "$1"
					   offset=`expr "$1" : '\([0-9]*\)'`
					   [ "$offset" = "" ] && errMsg "--- OFFSET=$offset MUST BE A NON-NEGATIVE INTEGER ---"
					   ;;
				-n)    # get ncolors
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID NCOLORS SPECIFICATION ---"
					   checkMinus "$1"
					   ncolors=`expr "$1" : '\([0-9]*\)'`
					   [ "$ncolors" = "" ] && errMsg "--- NCOLORS=$ncolors MUST BE A NON-NEGATIVE INTEGER (with no sign) ---"
					   test1=`echo "$ncolors < 2" | bc`
					   [ $test1 -eq 1 ] && errMsg "--- NCOLORS=$ncolors MUST BE AN GREATER THAN 1 ---"
					   ;;
				-b)    # get bright
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID BRIGHT SPECIFICATION ---"
					   checkMinus "$1"
					   bright=`expr "$1" : '\([0-9]*\)'`
					   [ "$bright" = "" ] && errMsg "--- BRIGHT=$bright MUST BE A NON-NEGATIVE INTEGER ---"
					   ;;
				-e)    # get ecolor
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID ECOLOR SPECIFICATION ---"
					   checkMinus "$1"
					   ecolor="$1"
					   ;;
				-t)    # get thick
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID THICK SPECIFICATION ---"
					   checkMinus "$1"
					   thick=`expr "$1" : '\([0-9]*\)'`
					   [ "$thick" = "" ] && errMsg "--- THICK=$thick MUST BE A NON-NEGATIVE INTEGER ---"
					   ;;
				-r)    # get rseed
					   shift  # to get the next parameter
					   # test if parameter starts with minus sign 
					   errorMsg="--- INVALID RSEED SPECIFICATION ---"
					   checkMinus "$1"
					   rseed=`expr "$1" : '\([0-9]*\)'`
					   [ "$rseed" = "" ] && errMsg "--- RSEED=$rseed MUST BE A NON-NEGATIVE INTEGER ---"
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


# setup temporary images and auto delete upon exit
tmpA1="$dir/stainedglass_1_$$.mpc"
tmpB1="$dir/stainedglass_1_$$.cache"
tmpA2="$dir/stainedglass_2_$$.mpc"
tmpB2="$dir/stainedglass_2_$$.cache"
tmpA3="$dir/stainedglass_3_$$.mpc"
tmpB3="$dir/stainedglass_3_$$.cache"
tmpC="$dir/stainedglass_C_$$.txt"
tmpG="$dir/stainedglass_G_$$.txt"
trap "rm -f $tmpA1 $tmpB1 $tmpA2 $tmpB2 $tmpA3 $tmpB3 $tmpC $tmpG; exit 0" 0
trap "rm -f $tmpA1 $tmpB1 $tmpA2 $tmpB2 $tmpA3 $tmpB3 $tmpC $tmpG; exit 1" 1 2 3 15

# set up color reduction
if [ "$ncolors" != "" ]; then
	reduce="-monitor +dither -colors $ncolors +monitor"
	echo ""
	echo "Reducing Colors:"
else
	reduce=""
fi

# read the input image and filter image into the temp files and test validity.
convert -quiet -regard-warnings "$infile" $reduce +repage "$tmpA1" ||
	errMsg "--- FILE $infile DOES NOT EXIST OR IS NOT AN ORDINARY FILE, NOT READABLE OR HAS ZERO SIZE  ---"


ww=`convert $tmpA1 -ping -format "%w" info:`
hh=`convert $tmpA1 -ping -format "%h" info:`
ww1=$(($ww-1))
hh1=$(($hh-1))
ww2=`convert xc: -format "%[fx:$ww1+round($size/2)]" info:`
hh2=`convert xc: -format "%[fx:$hh1+round($size/2)]" info:`
#echo "ww=$ww; hh=$hh; ww1=$ww1; hh1=$hh1; ww2=$ww2; hh2=$hh2;"


# get qrange
qrange=`convert xc: -format "%[fx:quantumrange]" info:`

# init colors file
echo "# ImageMagick pixel enumeration: $ww,$hh,255,rgb" > $tmpC

# init increment grays file
touch $tmpG

if [ "$kind" = "random" ]; then
	# need to add 1 to offset as awk rand is exclusive between 0 and 1
	offset=$(($offset+1))
	
	awk -v size="$size" -v ww1="$ww1" -v hh1="$hh1" -v ww2="$ww2" -v hh2="$hh2" -v offset="$offset" -v rseed="$rseed" '
		BEGIN { if (rseed=="") {srand();} else {srand(rseed);} y=0; while ( y < hh2 ) 
		{ x=0; while (x < ww2 ) 
		{ if (x>ww1) {x=ww1;} if (y>hh1) {y=hh1;} 
		rx=rand(); if (rx<0.5) {signx=-1;} else {signx=1;}
		offx=int(signx*rand()*offset); 
		xx=x+offx; if (xx<0) {xx=0}; if (xx>ww1) {xx=ww1};
		ry=rand(); if (ry<0.5) {signy=-1;} else {signy=1;}
		offy=int(signy*rand()*offset); 	
		yy=y+offy; if (yy<0) {yy=0}; if (yy>hh1) {yy=hh1};
		print xx","yy": (255,255,255)"; x=x+size; } 
		y=y+size; } }' >> $tmpC
	
	awk -v size="$size" -v ww1="$ww1" -v hh1="$hh1" -v ww2="$ww2" -v hh2="$hh2" -v qrange="$qrange" -v offset="$offset" -v rseed="$rseed" '
		BEGIN { if (rseed=="") {srand();} else {srand(rseed);} k=0; y=0; while ( y < hh2 ) 
		{ x=0; while (x < ww2 ) 
		{ if (x>ww1) {x=ww1;} if (y>hh1) {y=hh1;} 
		rx=rand(); if (rx<0.5) {signx=-1;} else {signx=1;}
		offx=int(signx*rand()*offset); 
		xx=x+offx; if (xx<0) {xx=0}; if (xx>ww1) {xx=ww1};
		ry=rand(); if (ry<0.5) {signy=-1;} else {signy=1;}
		offy=int(signy*rand()*offset); 	
		yy=y+offy; if (yy<0) {yy=0}; if (yy>hh1) {yy=hh1};
		g=100*k/qrange; 
		print xx,yy" gray("g"%)"; k++; x=x+size; } 
		y=y+size; } }' >> $tmpG

elif [ "$kind" = "hexagon" ]; then
	awk -v size="$size" -v ww1="$ww1" -v hh1="$hh1" -v ww2="$ww2" -v hh2="$hh2" '
		BEGIN { j=0; y=0; while ( y < hh2 ) 
		{ if (j%2==0) {x=int((size+0.5)/2);} else {x=0;} while (x < ww2 ) 
		{ if (x>ww1) {x=ww1;} if (y>hh1) {y=hh1;} print x","y": (255,255,255)"; x=x+size; } 
		j++; y=y+size; } }' >> $tmpC
		
	awk -v size="$size" -v ww1="$ww1" -v hh1="$hh1" -v ww2="$ww2" -v hh2="$hh2" -v qrange="$qrange" '
		BEGIN { j=0; k=0; y=0; while ( y < hh2 ) 
		{ if (j%2==0) {x=int((size+0.5)/2);} else {x=0;} while (x < ww2 ) 
		{ if (x>ww1) {x=ww1;} if (y>hh1) {y=hh1;} g=100*k/qrange; print x,y" gray("g"%)"; k++; x=x+size; } 
		j++; y=y+size; } }' >> $tmpG

elif [ "$kind" = "square" ]; then
	awk -v size="$size" -v ww1="$ww1" -v hh1="$hh1" -v ww2="$ww2" -v hh2="$hh2" '
		BEGIN { y=0; while ( y < hh2 ) 
		{ x=0; while (x < ww2 ) 
		{ if (x>ww1) {x=ww1;} if (y>hh1) {y=hh1;} print x","y": (255,255,255)"; x=x+size; } 
		y=y+size; } }' >> $tmpC
	
	awk -v size="$size" -v ww1="$ww1" -v hh1="$hh1" -v ww2="$ww2" -v hh2="$hh2" -v qrange="$qrange" '
		BEGIN { k=0; y=0; while ( y < hh2 ) 
		{ x=0; while (x < ww2 ) 
		{ if (x>ww1) {x=ww1;} if (y>hh1) {y=hh1;} g=100*k/qrange; print x,y" gray("g"%)"; k++; x=x+size; } 
		y=y+size; } }' >> $tmpG

fi

echo ""
echo "Progress:"
echo ""
if [ "$thick" = "0" ]; then
	convert $tmpA1 \( -background black $tmpC \) \
		-alpha off -compose copy_opacity -composite -monitor \
		txt:- |\
		sed '1d; / 0) /d; s/:.* /,/;' |\
		convert -size ${ww}x${hh} xc: -sparse-color voronoi '@-' \
		-modulate ${bright},100,100 +dither \
		$outfile
else
	convert $tmpA1 \( -background black $tmpC \) \
		-alpha off -compose copy_opacity -composite -monitor \
		txt:- |\
		sed '1d; / 0) /d; s/:.* /,/;' |\
		convert -size ${ww}x${hh} xc: -sparse-color voronoi '@-' \
			-modulate ${bright},100,100 $tmpA2
	convert -size ${ww}x${hh} xc: -sparse-color voronoi "@$tmpG" \
		 -morphology edge diamond:$thick -threshold 0 -negate $tmpA3
	#convert -size ${ww}x${hh} xc: -sparse-color voronoi "@$tmpG" \
	#	-auto-level -morphology edge diamond:$thick -threshold 0 -negate $tmpA3
	convert $tmpA2 $tmpA3 -alpha off -compose copy_opacity -composite \
		-compose over -background $ecolor -flatten +dither $outfile
fi
echo ""

exit 0
