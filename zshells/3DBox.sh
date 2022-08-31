#!/bin/bash
#
# Developed by Fred Weinhaus 9/21/2008 .......... revised 4/16/2009
#
# USAGE: 3Dbox option=value infile1 infile2 infile3 [infile4 infile5 infile6] outfile
# USAGE: 3Dbox [-h or -help]
#
# OPTIONS:  any one or more
#
# pan      value     rotation about output vertical centerline; 
#                    -360 to +360 (deg); default=0
# tilt     value     rotation about output horizontal centerline; 
#                    -360 to +360 (deg); default=0
# roll     value     rotation about the output center; 
#                    -360 to +360 (deg); default=0
# pef      value     perspective exaggeration factor; 
#                    0 to 3.19; default=1
# mode     value     mode for display of opposite faces to  
#                    image1, image2, image3;
#                    mode may be either mirror or rot180; default=mirror
# format   value     controls format of output image;
#                    format may be either auto or center; default=auto;
#                    auto sizes the output image to just contain the box;
#                    center creates an output image large enought to 
#                    hold any rotation of the box and keep the centroid
#                    of the box in the very center of the output image
# zoom     value     output zoom factor; where value > 1 means enlarge 
#                    and < 1 means shrink
# size     value     dwidth[xdheight] for the output image; options are: 
#                    dwidth, dwidthx, xdheight or dwidhtxdheight
# bgcolor  value     the background color value; any valid IM image 
#                    color specification (see -fill); default is black
# filter   value     any valid IM filter method; default is area resampled.
# 
# If infile4 infile5 infile6 are left off, then the first three infiles 
# will be used for their opposite sides. The dimensions of all the images 
# must be consistent with forming a box.
# 
###
#
# NAME: 3DBOX 
#
# PURPOSE: To generate a perspective view of a 3D box at any orientation 
# with pictures pasted on each of its sides.
#
# DESCRIPTION: 3DBOX generates a perspective view of a 3D box at any 
# orientation with pictures pasted on each of its sides. The use may  
# supplyeither 3 or 6 images. If only 3 images are provided, then these  
# will be either mirrored or rotated 180 degrees for use on the opposite 
# faces of the box. Note that the dimensions of all the images must be 
# consistent with forming a box.
# 
# 
# ARGUMENTS: 
# 
# PAN is a rotation of the box about the output image vertical 
# centerline -360 to +360 degrees. Positive rotations turn the 
# right side of the box away from the viewer and the left side 
# towards the viewer. If pan=0, tilt=0 and roll=0, the output 
# image will look straight onto the side of the box containing 
# the first image. The default is pan=0 degrees.
# 
# TILT is a rotation of the box about the output image horizontal 
# centerline -360 to +360 degrees. Positive rotations turn the top 
# of the box away from the viewer and the bottom towards the viewer. 
# If pan=0, tilt=0 and roll=0, the output image will look straight 
# onto the side of the box containing the first image. The default 
# is tilt=0 degrees.
# 
# ROLL is a rotation in the box in the plane of the output image
# -360 to +360 degrees. Positive values are clockwise and negative 
# values are counter-clockwise.  If pan=0, tilt=0 and roll=0, the  
# output image will look straight onto the side of the box containing 
# the first image. The default is roll=0 degrees.
# 
# PAN, TILT and ROLL are order dependent. If all three are provided, 
# then they will be done in whatever order specified.
# 
# PEF is the perspective exaggeration factor. It ranges from 0 to 3.19. 
# A normal perspective is achieved with a value of 1. As PEF is 
# increased from 1, the perspective effect moves towards that of 
# a wide angle lens (more distortion). If PEF is decreased from 1 
# the perspective effect moves towards a telephoto lens (less 
# distortion). A PEF=0, achieves an isometric (orthographic) view 
# (parallel lines remain parallel). The default is 0.5 so as to 
# avoid too much perspective exaggeration.
# 
# MODE identifies how to handle the images on the opposite faces to 
# those for infile1 infile2 and infile3. This is especially useful when 
# the other 3 images are not provided. In this case, it replicates  
# the first 3 images for use as the remaining 3 images. The choices 
# are either mirror or rot180. For mode=mirror, the opposite sides of 
# the box will be mirror images with the tops aligned, when the other 
# 3 images are not provide. When the other 3 images are provided, then 
# they will be used as supplied. For mode=rot180, the opposite sides 
# of the box will use 180 degree rotated versions of the first 3 images, 
# when the other 3 images are not provided. When the other 3 images 
# are provided, then they also will be rotated 180 degrees. The 
# choice of mode, especially when the last 3 images are not provided, 
# will be useful depending upon how the box is rotated and whether 
# one wants to see right-side up images or upside down images.
# The default is mirror.
# 
# FORMAT identifies how to set the output image size. The choices are 
# auto and center. When format=auto, the output image will be just large 
# enough to contain the box. When format=center, the output image 
# will be large enough to hold the box at any rotation, pef and zoom 
# and keep the centroid of the box in the very center of the output image.
# This is important when trying to generate animations in order to keep 
# the box properly rotating about a single centered point in the output image.
# 
# ZOOM is the output image zoom factor, which makes the output image
# larger or smaller in size. Values > 1 enlarge the output image; 
# whereas values < 1 shrink the output image. The default is 1. 
# The actual size of the image depends upon the box's rotation, zoom, 
# pef and the format parameter.
#
# SIZE is the dwidth[xdheight] for the output image. The choices are:
# dwidth, dwidthx, xdheight or dwidthxdheight. For format=auto, size will
# override zoom and determine the zoom factor to use to ensure the output
# is the desired size according to the following. If dwidth is provided,
# then it will be used to compute the zoom from the larger of the unzoomed
# output width and height. If dwidthx is provided, then it will be used to
# compute the zoom from the unzoomed output width. If xdheight is
# provided, it will be used to compute the zoom from the unzoomed output
# height. If dwidthxdheight is provided, dwidth will be used to compute
# the zoom from the unzoomed output width. The choice is basically
# determining which dimension will end up the desired size. For
# format=center, zoom and dwidth[xdheight] act independently. The default
# size is an approximaton and will be large enough to hold the box at any
# rotation, zoom and pef and may contain excess background. The size
# parameter, however, allows one to set a desired size. If either dwidth
# or dheight is missing, then the supplied value will be used for both the
# width and height of the output. The best way to determine the needed
# size is probably to generate an image with pan and tilt set to some
# multiple of 90 degrees and set the roll to 45 degrees so that output is
# looking right at the largest face turned so that the diagonals are
# horizontal and vertical. Then compare the size of this image to another
# generated with pan=45 degrees, tilt=-45 degrees and roll=0 so that one
# is viewing the box with its diagonals horizontal and vertical. Use the
# largest dimensions from each case. Basically, you can set dwidthxdheight
# such that the image will be larger than necessary as one can always crop
# the animation afterwards to remove excess background area.
# 
# SIZE is the dwidth[xdheight] for the output image. The choices are:
# dwidth, dwidthx, xdheight or dwidthxdheight. For format=auto, size will
# override zoom and determine the zoom factor to use to ensure the output
# is the desired size according to the following. If dwidth is provided,
# then it will be used to compute the zoom from the larger of the unzoomed
# output width and height. If dwidthx is provided, then it will be used to
# compute the zoom from the unzoomed output width. If xdheight is
# provided, it will be used to compute the zoom from the unzoomed output
# height. If dwidthxdheight is provided, dwidth will be used to compute
# the zoom from the unzoomed output width. The choice is basically
# determining which dimension will end up the desired size. For
# format=center, zoom and dwidth[xdheight] act independently. The default
# size is an approximaton and will be large enough to hold the box at any
# rotation, zoom and pef and may contain excess background. The size
# parameter, however, allows one to set a desired size. If either dwidth
# or dheight is missing, then the supplied value will be used for both the
# width and height of the output. The best way to determine the needed
# size is probably to generate an image with pan and tilt set to some
# multiple of 90 degrees and set the roll to 45 degrees so that output is
# looking right at the largest face turned so that the diagonals are
# horizontal and vertical. Then compare the size of this image to another
# generated with pan=45 degrees, tilt=-45 degrees and roll=0 so that one
# is viewing the box with its diagonals horizontal and vertical. Use the
# largest dimensions from each case. Basically, you can set dwidthxdheight
# such that the image will be larger than necessary as one can always crop
# the animation afterwards to remove excess background area.
#
# FILTER is any valid IM -filter value. The default is (EWA) area resampling.
# This may cause slight blurring. If you prefer the result to be more crisp, 
# but with the possibility of some aliasing, then use filter=point; otherwise 
# for a compromise, use filter=lanczos.
# 
# NOTE: the script is a bit slow due to all the necessary setup computations 
# prior to using +distort perspective to warp the images onto the box faces.
# 
# IMPORTANT: This script is limited to IM version 6.4.2-7 or higher to  
# support -fx scientific-notation and IM version 6.3.5-6 or higher to 
# conform to the current +distort perspective control point sequence.
#
# NOTE: This script is built upon the excellent box example first developed 
# by Anthony Thyssen at http://www.imagemagick.org/Usage/distorts/#box3d .
# I want to express my thanks to Anthony for his explainations of the 
# subtle points of +distort perspective, -layers merge, -crop -flatten 
# and the Unix eval expression. 
#
# CAVEAT: No guarantee that this script will work on all platforms, 
# nor that trapping of inconsistent parameters is complete and 
# foolproof. Use At Your Own Risk. 
# 
######
#

# set default value
# rotation angles and rotation matrix
pan=0
tilt=0
roll=0
R0=(1 0 0)
R1=(0 1 0)
R2=(0 0 1)

# perspective exaggeration factor
pef=0.5

# zoom
zoom=1

# init size
size=""

# format
format="auto"

# duplicate sides mode
mode="mirror"

# background color
#bgcolor="#E0E0E0"
bgcolor="white"

# resampling filter (default is area resampling when not specified)
filtermode=""

# set directory for temporary files
#tmpdir="/tmp"
tmpdir="/home/httpd/vhosts/ezimba/httpdocs/tmp";


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

# function to report error messages, usage and exit
errMsg()
	{
	echo ""
	echo $1
	echo ""
	usage1
	exit 1
	}

# function to do dot product of 2 three element vectors
function DP3
	{
	V0=($1)
	V1=($2)
	DP=`convert xc: -format "%[fx: (${V0[0]} * ${V1[0]}) + (${V0[1]} * ${V1[1]}) + (${V0[2]} * ${V1[2]})]" info:`
	}

# function to do 3x3 matrix multiply M x N where input are rows of each matrix; M1 M2 M3 N1 N2 N3
function MM3
	{
	[ $# -ne 6 ] && errMsg "--- NOT A VALID SET OF MATRIX PARAMETERS ---"
	M0=($1)
	M1=($2)
	M2=($3)
	N0=($4)
	N1=($5)
	N2=($6)
	[ ${#M0[*]} -ne 3 -a ${#M1[*]} -ne 3 -a ${#M2[*]} -ne 3 -a ${#N0[*]} -ne 3 -a ${#N1[*]} -ne 3 -a ${#N2[*]} -ne 3 ] && errMsg "--- NOT A VALID SET OF MATRIX ROWS ---"
	# extract columns n from rows N
	n0=(${N0[0]} ${N1[0]} ${N2[0]})
	n1=(${N0[1]} ${N1[1]} ${N2[1]})
	n2=(${N0[2]} ${N1[2]} ${N2[2]})
	DP3 "${M0[*]}" "${n0[*]}"
	P00=$DP
	DP3 "${M0[*]}" "${n1[*]}"
	P01=$DP
	DP3 "${M0[*]}" "${n2[*]}"
	P02=$DP
	DP3 "${M1[*]}" "${n0[*]}"
	P10=$DP
	DP3 "${M1[*]}" "${n1[*]}"
	P11=$DP
	DP3 "${M1[*]}" "${n2[*]}"
	P12=$DP
	DP3 "${M2[*]}" "${n0[*]}"
	P20=$DP
	DP3 "${M2[*]}" "${n1[*]}"
	P21=$DP
	DP3 "${M2[*]}" "${n2[*]}"
	P22=$DP
	P0=($P00 $P01 $P02)
	P1=($P10 $P11 $P12)
	P2=($P20 $P21 $P22)
	}

# function to project points from input to output domain
function forwardProject
	{
	ptx=`echo "$1" | cut -d, -f1`
	pty=`echo "$1" | cut -d, -f2`
	ptz=`echo "$1" | cut -d, -f3`
	if [ "$type" = "perspective" ]; then
		numu=`convert xc: -format "%[fx: $P00*$ptx + $P01*$pty + $P02*$ptz ]" info:`
		numv=`convert xc: -format "%[fx: $P10*$ptx + $P11*$pty + $P12*$ptz ]" info:`
		den=`convert xc: -format "%[fx: $P20*$ptx + $P21*$pty + $P22*$ptz + 1 ]" info:`
		uu=`convert xc: -format "%[fx: $zoom*$numu/$den ]" info:`
		vv=`convert xc: -format "%[fx: $zoom*$numv/$den ]" info:`
	elif [ "$type" = "isometric" ]; then
		uu=`convert xc: -format "%[fx: $zoom*($P00*$ptx + $P01*$pty + $P02*$ptz) ]" info:`
		vv=`convert xc: -format "%[fx: $zoom*($P10*$ptx + $P11*$pty + $P12*$ptz) ]" info:`
	fi
	}


# function to test if surface is front facing
# uses 3 consecutive world points ordered clockwise looking onto face from outside projected to output image
function isFrontFacing
	{
	# extract point components (u along x; v along -y in output)
	uu1=`echo "$1" | cut -d, -f1`
	vv1=`echo "$1" | cut -d, -f2`
	uu2=`echo "$2" | cut -d, -f1`
	vv2=`echo "$2" | cut -d, -f2`
	uu3=`echo "$3" | cut -d, -f1`
	vv3=`echo "$3" | cut -d, -f2`
	# get edge components
	eu1=`convert xc: -format "%[fx: $uu2-$uu1 ]" info:`
	ev1=`convert xc: -format "%[fx: $vv2-$vv1 ]" info:`
	eu2=`convert xc: -format "%[fx: $uu3-$uu2 ]" info:`
	ev2=`convert xc: -format "%[fx: $vv3-$vv2 ]" info:`
	# get cross product from determinant and if positive, 
	# the front facing and return 1
	# otherwise back facing and return 0
	ff=`convert xc: -format "%[fx: ($eu1*$ev2 - $eu2*$ev1)<=0?0:1 ]" info:`
	}


# function to test if entry is floating point number
function testFloat
	{
	test1=`expr "$1" : '^[0-9][0-9]*$'`  				# counts same as above but preceeded by plus or minus
	test2=`expr "$1" : '^[+-][0-9][0-9]*$'`  			# counts one or more digits
	test3=`expr "$1" : '^[0-9]*[\.][0-9]*$'`			# counts 0 or more digits followed by period followed by 0 or more digits
	test4=`expr "$1" : '^[+-][0-9]*[\.][0-9]*$'`		# counts same as above but preceeded by plus or minus
	floatresult=`expr $test1 + $test2 + $test3 + $test4`
#	[ $floatresult = 0 ] && errMsg "THE ENTRY $1 IS NOT A FLOATING POINT NUMBER"
	}

# get input image size
function imagesize
	{
	img=$1
	width=`identify -format %w $img`
	height=`identify -format %h $img`
	}

# test for correct number of arguments and get values
if [ $# -eq 0 ]
	then
	# help information
   echo ""
   usage2
   exit 0
elif [ $# -gt 17 ]
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
				 -)    # STDIN and end of arguments
					   break
					   ;;
				-*)    # any other - argument
					   errMsg "--- UNKNOWN OPTION ---"
					   ;;
		   pan[=]*)    # pan angle
					   arg="$1="
					   pan=`echo "$arg" | cut -d= -f2`
					   # function bc does not seem to like numbers starting with + sign, so strip off
					   pan=`echo "$pan" | sed 's/^[+]\(.*\)$/\1/'`
					   # pantest>0 if floating point number; otherwise pantest=0
					   testFloat "$pan"; pantest=$floatresult
					   pantestA=`echo "$pan < - 360" | bc`
					   pantestB=`echo "$pan > 360" | bc`
					   [ $pantest -eq 0 ] && errMsg "PAN=$pan IS NOT A NUMBER"
					   [ $pantestA -eq 1 -o $pantestB -eq 1 ] && errMsg "PAN=$pan MUST BE GREATER THAN OR EQUAL -360 AND LESS THAN +360"
					   sinpan=`convert xc: -format "%[fx: sin(pi*$pan/180) ]" info:`
					   sinpanm=`convert xc: -format "%[fx: -$sinpan ]" info:`
					   cospan=`convert xc: -format "%[fx: cos(pi*$pan/180) ]" info:`
					   Rp0=($cospan 0 $sinpan)
					   Rp1=(0 1 0)
					   Rp2=($sinpanm 0 $cospan)
					   # do matrix multiply to get new rotation matrix
					   MM3 "${Rp0[*]}" "${Rp1[*]}" "${Rp2[*]}" "${R0[*]}" "${R1[*]}" "${R2[*]}"
					   R0=(${P0[*]})
					   R1=(${P1[*]})
					   R2=(${P2[*]})
					   ;;
		  tilt[=]*)    # tilt angle
					   arg="$1="
					   tilt=`echo "$arg" | cut -d= -f2`
					   # function bc does not seem to like numbers starting with + sign, so strip off
					   tilt=`echo "$tilt" | sed 's/^[+]\(.*\)$/\1/'`
					   # tilttest>0 if floating point number; otherwise tilttest=0
					   testFloat "$tilt"; tilttest=$floatresult
					   tilttestA=`echo "$tilt < - 360" | bc`
					   tilttestB=`echo "$tilt > 360" | bc`
					   [ $tilttest -eq 0 ] && errMsg "tilt=$tilt IS NOT A NUMBER"
					   [ $tilttestA -eq 1 -o $tilttestB -eq 1 ] && errMsg "TILT=$tilt MUST BE GREATER THAN OR EQUAL -360 AND LESS THAN +360"
					   sintilt=`convert xc: -format "%[fx: sin(pi*$tilt/180) ]" info:`
					   sintiltm=`convert xc: -format "%[fx: -$sintilt ]" info:`
					   costilt=`convert xc: -format "%[fx: cos(pi*$tilt/180) ]" info:`
					   Rt0=(1 0 0)
					   Rt1=(0 $costilt $sintilt)
					   Rt2=(0 $sintiltm $costilt)
					   # do matrix multiply to get new rotation matrix
					   MM3 "${Rt0[*]}" "${Rt1[*]}" "${Rt2[*]}" "${R0[*]}" "${R1[*]}" "${R2[*]}"
					   R0=(${P0[*]})
					   R1=(${P1[*]})
					   R2=(${P2[*]})
					   ;;
		  roll[=]*)    # roll angle
					   arg="$1="
					   roll=`echo "$arg" | cut -d= -f2`
					   # function bc does not seem to like numbers starting with + sign, so strip off
					   roll=`echo "$roll" | sed 's/^[+]\(.*\)$/\1/'`
					   # rolltest>0 if floating point number; otherwise rolltest=0
					   testFloat "$roll"; rolltest=$floatresult
					   rolltestA=`echo "$roll < - 360" | bc`
					   rolltestB=`echo "$roll > 360" | bc`
					   [ $rolltest -eq 0 ] && errMsg "roll=$roll IS NOT A NUMBER"
					   [ $rolltestA -eq 1 -o $rolltestB -eq 1 ] && errMsg "ROLL=$roll MUST BE GREATER THAN OR EQUAL -360 AND LESS THAN +360"
					   sinroll=`convert xc: -format "%[fx: sin(pi*$roll/180) ]" info:`
					   sinrollm=`convert xc: -format "%[fx: -$sinroll ]" info:`
					   cosroll=`convert xc: -format "%[fx: cos(pi*$roll/180) ]" info:`
					   Rr0=($cosroll $sinroll 0)
					   Rr1=($sinrollm $cosroll 0)
					   Rr2=(0 0 1)
					   # do matrix multiply to get new rotation matrix
					   MM3 "${Rr0[*]}" "${Rr1[*]}" "${Rr2[*]}" "${R0[*]}" "${R1[*]}" "${R2[*]}"
					   R0=(${P0[*]})
					   R1=(${P1[*]})
					   R2=(${P2[*]})
					   ;;
		   pef[=]*)    # pef
					   arg="$1="
					   pef=`echo "$arg" | cut -d= -f2`
					   # function bc does not seem to like numbers starting with + sign, so strip off
					   pef=`echo "$pef" | sed 's/^[+]\(.*\)$/\1/'`
					   # peftest>0 if floating point number; otherwise peftest=0
					   testFloat "$pef"; peftest=$floatresult
					   peftestA=`echo "$pef < 0" | bc`
					   peftestB=`echo "$pef > 3.19" | bc`
					   [ $peftest -eq 0 ] && errMsg "PEF=$pef IS NOT A NUMBER"
					   [ $peftestA -eq 1 -o $peftestB -eq 1 ] && errMsg "ROLL=$roll MUST BE GREATER THAN OR EQUAL -360 AND LESS THAN +360"
					   ;;
		  zoom[=]*)    # output zoom
					   arg="$1="
					   zoom=`echo "$arg" | cut -d= -f2`
					   # function bc does not seem to like numbers starting with + sign, so strip off
					   zoom=`echo "$zoom" | sed 's/^[+]\(.*\)$/\1/'`
					   # zoomtest>0 if floating point number; otherwise zoomtest=0
					   testFloat "$zoom"; zoomtest=$floatresult
					   zoomtestA=`echo "$pef <= 0" | bc`
					   [ $zoomtest -eq 0 ] && errMsg "ZOOM=$zoom IS NOT A NUMBER"
					   [ $peftestA -eq 1 ] && errMsg "ZOOM=$zoom MUST BE GREATER THAN 0"
					   ;;
		  size[=]*)    # output size
					   arg="$1="
					   size=`echo "$arg" | cut -d= -f2`
					   test1=`expr "$size" : '^[0-9]*x[0-9]*$'`
					   test2=`expr "$size" : '^[0-9]*$'`
					   test3=`expr $test1 + $test2`
					   if [ $test3 -eq 0 ]; then
							errMsg "SIZE=$size MUST BE ONE OR TWO INTERGERS SEPARATED BY AN X"
					   else
					   	   if [ $test2 -ne 0 ]; then
							   dwidth=`echo "$size" | cut -dx -f1`
							   dheight=""
							   xflag="false"
					   	   else
							   dwidth=`echo "$size" | cut -dx -f1`
							   dheight=`echo "$size" | cut -dx -f2`
					   		   xflag="true"
						   fi
echo "dwidth=$dwidth; dheight=$dheight; xflag=$xflag"
					   fi
					   ;;
	   bgcolor[=]*)    # output background color
					   arg="$1="
					   bgcolor=`echo "$arg" | cut -d= -f2`
					   ;;
	      mode[=]*)    # opposite side image mode
					   arg="$1="
					   mode=`echo "$arg" | cut -d= -f2`
					   [ "$mode" != "mirror" -a "$mode" != "rot180" ] && errMsg "MODE=$mode IS NOT A VALID VALUE"
					   ;;
	    filter[=]*)    # resampling filter
					   arg="$1="
					   filtermode=`echo "$arg" | cut -d= -f2`
					   ;;
	    format[=]*)    # output size control
					   arg="$1="
					   format=`echo "$arg" | cut -d= -f2`
					   [ "$format" != "auto" -a "$format" != "center" ] && errMsg "FORMAT=$format IS NOT A VALID VALUE"
					   ;;
		     *[=]*)    # not valid
					   errMsg "$1 IS NOT A VALID ARGUMENT"
					   ;;
		     	 *)    # end of arguments
					   break
					   ;;
			esac
			shift   # next option
	done
	#
	# get infiles and outfile
	numimg=$#
	if [ $numimg -eq 4 ]; then
		infile1=$1
		infile2=$2
		infile3=$3
		outfile=$4
	elif [ $numimg -eq 7 ]; then
		infile1=$1
		infile2=$2
		infile3=$3
		infile4=$4
		infile5=$5
		infile6=$6
		outfile=$7
	else
		errMsg "--- NUMBER OF INPUT IMAGES MUST BE EITHER 3 OR 6 ---"
	fi	
fi

#echo "numimg=$numimg"

# test that infile provided
[ "$infile1" = "" ] && errMsg "NO INPUT FILE 1 SPECIFIED"
[ "$infile2" = "" ] && errMsg "NO INPUT FILE 2 SPECIFIED"
[ "$infile3" = "" ] && errMsg "NO INPUT FILE 3 SPECIFIED"

if [ $numimg -eq 7 ]; then
	[ "$infile4" = "" ] && errMsg "NO INPUT FILE 4 SPECIFIED"
	[ "$infile5" = "" ] && errMsg "NO INPUT FILE 5 SPECIFIED"
	[ "$infile6" = "" ] && errMsg "NO INPUT FILE 6 SPECIFIED"
fi

# test that outfile provided
[ "$outfile" = "" ] && errMsg "NO OUTPUT FILE SPECIFIED"

# Setup directory for temporary files
# On exit remove ALL the whole directory of temporary images
dir="$tmpdir/$PROGNAME.$$"
trap "rm -rf $dir; exit 0" 0
trap "rm -rf $dir; exit 1" 1 2 3 15
mkdir "$dir" || {
  echo >&2 "$PROGNAME: UNABLE TO CREATE WORKING DIR \"$dir\" -- ABORTING"
  exit 10
}

convert -quiet -regard-warnings "$infile1" +repage "$dir/tmp1.mpc" || 
errMsg "--- FAILED TO READ \"$infile1\" ---"

convert -quiet -regard-warnings "$infile2" +repage "$dir/tmp2.mpc" || 
errMsg "--- FAILED TO READ \"$infile2\" ---"

convert -quiet -regard-warnings "$infile3" +repage "$dir/tmp3.mpc" || 
errMsg "--- FAILED TO READ \"$infile3\" ---"

if [ $numimg -eq 7 ]; then
	convert -quiet -regard-warnings "$infile4" +repage "$dir/tmp4.mpc" || 
	errMsg "--- FAILED TO READ \"$infile4\" ---"
	
	convert -quiet -regard-warnings "$infile5" +repage "$dir/tmp5.mpc" || 
	errMsg "--- FAILED TO READ \"$infile5\" ---"
	
	convert -quiet -regard-warnings "$infile6" +repage "$dir/tmp6.mpc" || 
	errMsg "--- FAILED TO READ \"$infile6\" ---"
fi

# test for minimum IM version required
# IM 6.3.5.6 or higher to conform to current -distort perspective control point sequence
im_version=`convert -list configure | \
	sed '/^LIB_VERSION_NUMBER /!d; s//,/;  s/,/,0/g;  s/,0*\([0-9][0-9]\)/\1/g'`
if [ "$im_version" -lt "06030506" ]
	then
	errMsg "--- REQUIRES IM VERSION 6.3.5-6 OR HIGHER ---"
fi

# get input image last pixels
imagesize $dir/tmp1.mpc
lastwidth1=`expr $width - 1`
lastheight1=`expr $height - 1`
imagesize $dir/tmp2.mpc
lastwidth2=`expr $width - 1`
lastheight2=`expr $height - 1`
imagesize $dir/tmp3.mpc
lastwidth3=`expr $width - 1`
lastheight3=`expr $height - 1`
if [ $numimg -eq 4 ]; then
	lastwidth4=$lastwidth1
	lastheight4=$lastheight1
	lastwidth5=$lastwidth2
	lastheight5=$lastheight2
	lastwidth6=$lastwidth3
	lastheight6=$lastheight2	
elif [ $numimg -eq 7 ]; then
	imagesize $dir/tmp4.mpc
	lastwidth4=`expr $width - 1`
	lastheight4=`expr $height - 1`
	imagesize $dir/tmp5.mpc
	lastwidth5=`expr $width - 1`
	lastheight5=`expr $height - 1`
	imagesize $dir/tmp6.mpc
	lastwidth6=`expr $width - 1`
	lastheight6=`expr $height - 1`
fi	

# test for size consistency
[ $lastwidth1 -ne $lastwidth3 ] && errMsg "--- Image 1 And 3 Dimensions Do Not Match ---"
[ $lastheight1 -ne $lastheight2 ] && errMsg "--- Image 1 And 2 Dimensions Do Not Match ---"
[ $lastwidth2 -ne $lastheight3 ] && errMsg "--- Image 2 And 3 Dimensions Do Not Match ---"
if [ $numimg -eq 7 ]; then
	[ $lastwidth1 -ne $lastwidth4 ] && errMsg "--- Image 1 And 4 Dimensions Do Not Match ---"
	[ $lastheight1 -ne $lastheight4 ] && errMsg "--- Image 1 And 4 Dimensions Do Not Match ---"
	[ $lastwidth2 -ne $lastwidth5 ] && errMsg "--- Image 2 And 5 Dimensions Do Not Match ---"
	[ $lastheight2 -ne $lastheight5 ] && errMsg "--- Image 2 And 5 Dimensions Do Not Match ---"
	[ $lastwidth3 -ne $lastwidth6 ] && errMsg "--- Image 3 And 6 Dimensions Do Not Match ---"
	[ $lastheight3 -ne $lastheight6 ] && errMsg "--- Image 3 And 6 Dimensions Do Not Match ---"
fi


# define box coordinates from first 2 image
# define origin at box centroid
# Image1 = orthogonal to Z sides
# Image2 = orthogonal to X sides
# Image3 = orthogonal to Y sides
# top upper left (parallel to X-Y plane ordered clockwise from outside)
px[0]=`convert xc: -format "%[fx:-$lastwidth1/2]" info:`
py[0]=`convert xc: -format "%[fx:$lastheight1/2]" info:`
pz[0]=`convert xc: -format "%[fx:$lastwidth2/2]" info:`
# top upper right (parallel to X-Y plane ordered clockwise from outside)
px[1]=`convert xc: -format "%[fx:$lastwidth1/2]" info:`
py[1]=`convert xc: -format "%[fx:$lastheight1/2]" info:`
pz[1]=`convert xc: -format "%[fx:$lastwidth2/2]" info:`
# top lower right (parallel to Y-Z plane ordered clockwise from outside)
px[2]=`convert xc: -format "%[fx:$lastwidth1/2]" info:`
py[2]=`convert xc: -format "%[fx:-$lastheight1/2]" info:`
pz[2]=`convert xc: -format "%[fx:$lastwidth2/2]" info:`
# top lower left (parallel to Y-Z plane ordered clockwise from outside)
px[3]=`convert xc: -format "%[fx:-$lastwidth1/2]" info:`
py[3]=`convert xc: -format "%[fx:-$lastheight1/2]" info:`
pz[3]=`convert xc: -format "%[fx:$lastwidth2/2]" info:`
# bottom upper left (parallel to X-Y plane ordered clockwise from outside)
px[4]=`convert xc: -format "%[fx:$lastwidth1/2]" info:`
py[4]=`convert xc: -format "%[fx:$lastheight1/2]" info:`
pz[4]=`convert xc: -format "%[fx:-$lastwidth2/2]" info:`
# bottom upper right (parallel to X-Y plane ordered clockwise from outside)
px[5]=`convert xc: -format "%[fx:-$lastwidth1/2]" info:`
py[5]=`convert xc: -format "%[fx:$lastheight1/2]" info:`
pz[5]=`convert xc: -format "%[fx:-$lastwidth2/2]" info:`
# bottom lower right (parallel to Y-Z plane ordered clockwise from outside)
px[6]=`convert xc: -format "%[fx:-$lastwidth1/2]" info:`
py[6]=`convert xc: -format "%[fx:-$lastheight1/2]" info:`
pz[6]=`convert xc: -format "%[fx:-$lastwidth2/2]" info:`
# bottom lower left (parallel to Y-Z plane ordered clockwise from outside)
px[7]=`convert xc: -format "%[fx:$lastwidth1/2]" info:`
py[7]=`convert xc: -format "%[fx:-$lastheight1/2]" info:`
pz[7]=`convert xc: -format "%[fx:-$lastwidth2/2]" info:`

# create world X,Y,Z points from box corners (with origin at centroid)
pt0=(${px[0]},${py[0]},${pz[0]})
pt1=(${px[1]},${py[1]},${pz[1]})
pt2=(${px[2]},${py[2]},${pz[2]})
pt3=(${px[3]},${py[3]},${pz[3]})
pt4=(${px[4]},${py[4]},${pz[4]})
pt5=(${px[5]},${py[5]},${pz[5]})
pt6=(${px[6]},${py[6]},${pz[6]})
pt7=(${px[7]},${py[7]},${pz[7]})


# find max distance = max diagonal
i=0
maxdist=0
while [ $i -lt 8 ]; do
	xx=${px[$i]}
	yy=${py[$i]}
	zz=${pz[$i]}
	dist=`convert xc: -format "%[fx:sqrt($xx*$xx + $yy*$yy + $zz*$zz)]" info:`
	maxdist=`convert xc: -format "%[fx:max($maxdist,$dist)]" info:`
	i=`expr $i + 1`
done
#echo "maxdist=$maxdist"

# Let world coordinates be Z pointing towards the viewer, X pointing 
# to the right and Y pointing up, both as seen by the view
# Consider the box centroid placed at Z=0 plane and the camera a distance
# Zc=f from the box centroid looking straight along Z at the box centroid.
# Now the perspective equations (in 3-D) are defined as (x,y,f) = M (X',Y',Z'),
# where the camera orientation matrix M is the identity matrix but with M22=-1
# because the camera is looking straight down along -Z. 
# Thus a reflection transformation relative to the ground plane coordinates.
# Let the camera position Zc=f=(max dist of box points from origin)/(2 tan(fov/2))
# but we want to add a perspective exaggeration factor, pef, times fov
# Now we want to rotate the ground points corresponding to the picture corners.
# The basic rotation is (X',Y',Z') = R (X,Y,Z), where R is the rotation matrix
# involving pan, tilt and roll.
# (x,y,f) = M (X',Y',Z'-Zc) = M (X',Y',Z') - M (0,0,f)
# (x,y,f) = MR (X,Y,Z) - M (0,0,f)
# But we need to allow for offset of the output coordinates and
# conversion from (x,y,f) to (u,v,1), where v increases downward.
# Thus (x,y,f)=A(u,v,1) where v increases downward (x=u,y=-v)
# Thus
# A00=1, A11=-1, A22=f and other terms are 0.
# Thus the forward transformation becomes AO=MRW or O=A'MRI or O=PW, 
# where prime means inverse and O=output picture (u,v), W=world (X,Y,Z)
# But we will merge A'M into Aim
# As AA'=I identity matrix, it is easy to see that
# A'00=1, A'11=1, A'22=1/f
# M00=1, M11=1, M22=-1
# Thus Aim=A'M becomes
# Aim00=1, Aim11=-1, Aim22=-1/f
# Also M (0,0,f) = (0,0,-1)
# Thus O=PI, where
# P=AimR(X,Y,Z) + (0 0 1)
# A=output conversion matrix
# M=camera orientation matrix
# R=image rotation matrix Rroll Rtilt Rpan
# O=output coords vector (i,j,1)
# W=world coords vector (X,Y,Z)
# P=forward perspective transformation matrix

# For a 35 mm camera whose film format is 36mm wide and 24mm tall, when the focal 
# length is equal to the diagonal, the field of view is 43.27 degrees. A 50mm lens 
# with 35 mm film produces a fov of 47 degrees which is close to 53 degrees which is 
# considered a normal view equivalent to the human eye.
# See http://www.panoramafactory.com/equiv35/equiv35.html
# Max limit on dfov is slightly less than 180 degrees.
# Min limit on dfov seems to be slightly greater than zero degrees.


if [ `echo "$pef < .01" | bc` -eq 1 ]; then
	type="isometric"
else
	pfact=$pef
	type="perspective"
fi

if [ "$type" = "perspective" ]; then
	# set dfov and compute pfact from pef
	#dfov=`convert xc: -format "%[fx: 180*atan(36/24)/pi ]" info:`
	dfov=53
	# compute new field of view based upon pef (pfact) and focal
	dfov=`convert xc: -format "%[fx: $pfact*$dfov ]" info:`
	focal=`convert xc: -format "%[fx: $maxdist/(tan(pi*$dfov/360)) ]" info:`
	mfocalinv=`convert xc: -format "%[fx: -1/$focal ]" info:`
#	echo "pef=$pef; pfact=$pfact; dfov=$dfov; focal=$focal; mfocalinv=$mfocalinv"
	
	# define the output (inverse) conversion matrix Ai=A' merged with M as Aim
	# A0=(1 0 0)
	# A1=(0 -1 0)
	# A2=(0 0 f)
	# Ai0=(1 0 0)
	# Ai1=(0 -1 0)
	# Ai2=(0 0 1/f)
	# M0=(1 0 0)
	# M1=(0 1 0)
	# M2=(0 0 -1)
	Aim0=(1 0 0)
	Aim1=(0 -1 0)
	Aim2=(0 0 $mfocalinv)
	
	# multiply Aim x R = P
	MM3 "${Aim0[*]}" "${Aim1[*]}" "${Aim2[*]}" "${R0[*]}" "${R1[*]}" "${R2[*]}"
	
	# the resulting P matrix is now the perspective coefficients for the forward transformation
	P00=${P0[0]}
	P01=${P0[1]}
	P02=${P0[2]}
	P10=${P1[0]}
	P11=${P1[1]}
	P12=${P1[2]}
	P20=${P2[0]}
	P21=${P2[1]}
	P22=${P2[2]}

elif [ "$type" = "isometric" ]; then
	# A0=(1 0 0)
	# A1=(0 -1 0)
	# A2=(0 0 1)
	# Ai0=(1 0 0)
	# Ai1=(0 -1 0)
	# Ai2=(0 0 1)
	# M0=(1 0 0)
	# M1=(0 1 0)
	# M2=(0 0 -1)
	Aim0=(1 0 0)
	Aim1=(0 -1 0)
	Aim2=(0 0 -1)
	
	# multiply Aim x R = P
	MM3 "${Aim0[*]}" "${Aim1[*]}" "${Aim2[*]}" "${R0[*]}" "${R1[*]}" "${R2[*]}"
	
	# the resulting P matrix is now the isometric coefficients for the forward transformation
	P00=${P0[0]}
	P01=${P0[1]}
	P02=${P0[2]}
	P10=${P1[0]}
	P11=${P1[1]}
	P12=${P1[2]}
	P20=${P2[0]}
	P21=${P2[1]}
	P22=${P2[2]}
fi

if false; then
# debugging - show echo results if set to true
echo ""
echo "R0=${R0[*]}"
echo "R1=${R1[*]}"
echo "R2=${R2[*]}"
echo ""
echo "P0=$P00 $P01 $P02"
echo "P1=$P10 $P11 $P12"
echo "P2=$P20 $P21 $P22"
echo ""
fi

# reset zoom=1 if format = auto and size != "", so that width will determine zoom
[ "$format" = "auto" -a "$size" != "" ] && zoom=1

# project box corners to output domain
forwardProject "$pt0"
u0=$uu
v0=$vv
forwardProject "$pt1"
u1=$uu
v1=$vv
forwardProject "$pt2"
u2=$uu
v2=$vv
forwardProject "$pt3"
u3=$uu
v3=$vv
forwardProject "$pt4"
u4=$uu
v4=$vv
forwardProject "$pt5"
u5=$uu
v5=$vv
forwardProject "$pt6"
u6=$uu
v6=$vv
forwardProject "$pt7"
u7=$uu
v7=$vv

if false; then
# debugging - show echo results if set to true
echo "pt0=$pt0; u0=$u0,v0=$v0"
echo "pt1=$pt1; u1=$u1,v1=$v1"
echo "pt2=$pt2; u2=$u2,v2=$v2"
echo "pt3=$pt3; u3=$u3,v3=$v3"
echo "pt4=$pt4; u4=$u4,v4=$v4"
echo "pt5=$pt5; u5=$u5,v5=$v5"
echo "pt6=$pt6; u6=$u6,v6=$v6"
echo "pt7=$pt7; u7=$u7,v7=$v7"
fi

# compute diagonal face on largest face bounding box
if [ "$format" = "center" ]; then
	# get largest image half diagonal
	diag1=`convert xc: -format "%[fx: 0.5*hypot($lastwidth1+1,$lastheight1+1) ]" info:`
	diag2=`convert xc: -format "%[fx: 0.5*hypot($lastwidth2+1,$lastheight2+1) ]" info:`
	diag3=`convert xc: -format "%[fx: 0.5*hypot($lastwidth3+1,$lastheight3+1) ]" info:`
	if [ $numimg -eq 7 ]; then
		diag4=`convert xc: -format "%[fx: 0.5*hypot($lastwidth4+1,$lastheight4+1) ]" info:`
		diag5=`convert xc: -format "%[fx: 0.5*hypot($lastwidth5+1,$lastheight5+1) ]" info:`
		diag6=`convert xc: -format "%[fx: 0.5*hypot($lastwidth6+1,$lastheight6+1) ]" info:`
	fi
	maxdiag=`convert xc: -format "%[fx: max($diag3,max($diag2,max($diag1,0))) ]" info:`
	if [ $numimg -eq 7 ]; then
		maxdiag=`convert xc: -format "%[fx: max($diag6,max($diag5,max($diag4,$maxdiag))) ]" info:`
	fi
	
	# get lower right corner relative to 0,0,0
	pw="$maxdiag,-$maxdiag,$maxdiag"
	# set perspective matrix P=Aim, so look straight onto (Z plus) face
	P00=1
	P01=0
	P02=0
	P10=0
	P11=-1
	P12=0
	P20=0
	P21=0
	P22=$mfocalinv
	#project to image space to get half bounding box
	forwardProject "$pw"
	uw=$uu
	vw=$vv	
#echo "maxdiag=$maxdiag; pw=$pw; uw=$uw; vw=$vw"
fi


# use auto output bounding box and width or height to compute zoom and then scale projected points
if [ "$format" = "auto" -a "$size" != "" ]; then
	umin=`convert xc: -format "%[fx: min($u7,min($u6,min($u5,min($u4,min($u3,min($u2,min($u1,min($u0,1000000)))))))) ]" info:`
	vmin=`convert xc: -format "%[fx: min($v7,min($v6,min($v5,min($v4,min($v3,min($v2,min($v1,min($v0,1000000)))))))) ]" info:`
	umax=`convert xc: -format "%[fx: max($u7,max($u6,max($u5,max($u4,max($u3,max($u2,max($u1,max($u0,-1000000)))))))) ]" info:`
	vmax=`convert xc: -format "%[fx: max($v7,max($v6,max($v5,max($v4,max($v3,max($v2,max($v1,max($v0,-1000000)))))))) ]" info:`
	ww=`convert xc: -format "%[fx: ceil($umax-$umin) ]" info:`
	hh=`convert xc: -format "%[fx: ceil($vmax-$vmin) ]" info:`
	# get largest dimension
	dim=`convert xc: -format "%[fx: max($ww,$hh) ]" info:`
	# compute zoom
	if [ "$dwidth" != "" -a "$dheight" = "" -a "$xflag" = "false" ]; then
		# scale dwidth with max dimension if xflag false
		zoom=`convert xc: -format "%[fx: $dwidth/$dim ]" info:`
	elif [ "$dwidth" != "" -a "$dheight" = "" -a "$xflag" = "true" ]; then
		# scale dwidth with width dimension if xflag true
		zoom=`convert xc: -format "%[fx: $dwidth/$ww ]" info:`
	elif [ "$height" != "" -a "$dwidth" = "" ]; then
		# scale dheight with height dimension
		zoom=`convert xc: -format "%[fx: $dheight/$hh ]" info:`
	elif [ "$dwidth" != "" -a "$dheight" != "" ]; then
		# scale dwidth with width dimension if both dwidth and dheight given
		zoom=`convert xc: -format "%[fx: $dwidth/$ww ]" info:`
	else
		errMsg "WIDTH AND HEIGHT CANNOT BOTH BE EMPTY"
	fi
	# scale projected points
	u0=`convert xc: -format "%[fx: $zoom*$u0 ]" info:`
	v0=`convert xc: -format "%[fx: $zoom*$v0 ]" info:`
	u1=`convert xc: -format "%[fx: $zoom*$u1 ]" info:`
	v1=`convert xc: -format "%[fx: $zoom*$v1 ]" info:`
	u2=`convert xc: -format "%[fx: $zoom*$u2 ]" info:`
	v2=`convert xc: -format "%[fx: $zoom*$v2 ]" info:`
	u3=`convert xc: -format "%[fx: $zoom*$u3 ]" info:`
	v3=`convert xc: -format "%[fx: $zoom*$v3 ]" info:`
	u4=`convert xc: -format "%[fx: $zoom*$u4 ]" info:`
	v4=`convert xc: -format "%[fx: $zoom*$v4 ]" info:`
	u5=`convert xc: -format "%[fx: $zoom*$u5 ]" info:`
	v5=`convert xc: -format "%[fx: $zoom*$v5 ]" info:`
	u6=`convert xc: -format "%[fx: $zoom*$u6 ]" info:`
	v6=`convert xc: -format "%[fx: $zoom*$v6 ]" info:`
	u7=`convert xc: -format "%[fx: $zoom*$u7 ]" info:`
	v7=`convert xc: -format "%[fx: $zoom*$v7 ]" info:`
#echo "ww=$ww; hh=$hh; dim=$dim; dwidth=$dwidth; dheight=$dheight xflag=$xflag zoom=$zoom"
fi


#set resampling filter for perspective transform
if [ "$filtermode" = "" ]; then
	filter=""
else
	filter="-filter $filtermode"
fi
#echo "filter=$filter"

#set rotate 180 or mirror from mode
if [ "$mode" = "mirror" ]; then
	rotate=""
elif [ "$mode" = "rot180" ]; then
	rotate="-rotate 180"
fi
#echo "mode=$mode"

# define surface transformation pairs i,j u,v for front faces only
# use +distort perspective to allow the use of negative output image coords

# face orthogonal to Z (plus): image1
isFrontFacing "$u0,$v0" "$u1,$v1" "$u2,$v2"
if [ $ff -eq 1 ]; then
	s1="0,0 $u0,$v0  $lastwidth1,0 $u1,$v1  $lastwidth1,$lastheight1 $u2,$v2  0,$lastheight1 $u3,$v3"
#	echo "s1=$s1"
	proc1='$dir/tmp1.mpc -matte $filter +distort Perspective "$s1"'
else
	proc1=""
fi
#echo "s1=$s1"
#echo "proc1=$proc1"

# face othogonal to X (minus): image2
isFrontFacing "$u5,$v5" "$u0,$v0" "$u3,$v3"
if [ $ff -eq 1 ]; then
	s2="0,0 $u5,$v5  $lastwidth2,0 $u0,$v0  $lastwidth2,$lastheight2 $u3,$v3  0,$lastheight2 $u6,$v6"
#	echo "s2=$s2"
	proc2='$dir/tmp2.mpc -matte $filter +distort Perspective "$s2"'
else
	proc2=""
fi
#echo "s2=$s2"
#echo "proc2=$proc2"

# face othogonal to Y (plus): image3
isFrontFacing "$u5,$v5" "$u4,$v4" "$u1,$v1"
if [ $ff -eq 1 ]; then
	s3="0,0 $u5,$v5  $lastwidth3,0 $u4,$v4  $lastwidth3,$lastheight3 $u1,$v1  0,$lastheight3 $u0,$v0"
#	echo "s3=$s3"
	proc3='$dir/tmp3.mpc -matte $filter +distort Perspective "$s3"'
else
	proc3=""
fi
#echo "s3=$s3"
#echo "proc3=$proc3"

# face orthogonal to Z (minus): image4
isFrontFacing "$u4,$v4" "$u5,$v5" "$u6,$v6"
if [ $ff -eq 1 ]; then
	s4="0,0 $u4,$v4  $lastwidth4,0 $u5,$v5  $lastwidth4,$lastheight4 $u6,$v6  0,$lastheight4 $u7,$v7"
#	echo "s4=$s4"
	if [ $numimg -eq 4 ]; then
		proc4='$dir/tmp1.mpc $rotate -matte $filter +distort Perspective "$s4"'
	elif [ $numimg -eq 7 ]; then
		proc4='$dir/tmp4.mpc $rotate -matte $filter +distort Perspective "$s4"'
	fi
else
	proc4=""
fi
#echo "s4=$s4"
#echo "proc4=$proc4"

#face othogonal to X (plus): image5
isFrontFacing "$u1,$v1" "$u4,$v4" "$u7,$v7"
if [ $ff -eq 1 ]; then
	s5="0,0 $u1,$v1  $lastwidth5,0 $u4,$v4  $lastwidth5,$lastheight5 $u7,$v7  0,$lastheight5 $u2,$v2"
#	echo "s5=$s5"
	if [ $numimg -eq 4 ]; then
		proc5='$dir/tmp2.mpc $rotate -matte $filter +distort Perspective "$s5"'
	elif [ $numimg -eq 7 ]; then
		proc5='$dir/tmp5.mpc $rotate -matte $filter +distort Perspective "$s5"'
	fi
else
	proc5=""
fi
#echo "s5=$s5"
#echo "proc5=$proc5"

#face othogonal to Y (minus): image6
isFrontFacing "$u7,$v7" "$u6,$v6" "$u3,$v3"
if [ $ff -eq 1 ]; then
	s6="0,0 $u7,$v7  $lastwidth6,0 $u6,$v6  $lastwidth6,$lastheight6 $u3,$v3  0,$lastheight6 $u2,$v2"
#	echo "s6=$s6"
	if [ $numimg -eq 4 ]; then
		proc6='$dir/tmp3.mpc $rotate -matte $filter +distort Perspective "$s6"'
	elif [ $numimg -eq 7 ]; then
		proc6='$dir/tmp6.mpc $rotate -matte $filter +distort Perspective "$s6"'
	fi
else
	proc6=""
fi
#echo "s6=$s6"
#echo "proc6=$proc6"


# use -layers merge +repage (IM 6.3.6-2 or higher) to auto set the output size to the bounding box of the output coords
# use -crop WxH-X-Y! -flatten or -repage WxH+X+Y! -flatten (prior to IM 6.3.6-2) to auto set the output size to the bounding box of the output coords
# use -crop WxH-X-Y! -flatten to set the image size to the dim for use with animations

if [ "$format" = "auto" -a "$im_version" -ge "06030506" ]; then
	merge="-background $bgcolor -layers merge +repage"
elif [ "$format" = "auto" -a "$im_version" -lt "06030506" ]; then
	# compute bounding box
	umin=`convert xc: -format "%[fx: min($u7,min($u6,min($u5,min($u4,min($u3,min($u2,min($u1,min($u0,1000000)))))))) ]" info:`
	vmin=`convert xc: -format "%[fx: min($v7,min($v6,min($v5,min($v4,min($v3,min($v2,min($v1,min($v0,1000000)))))))) ]" info:`
	umax=`convert xc: -format "%[fx: max($u7,max($u6,max($u5,max($u4,max($u3,max($u2,max($u1,max($u0,-1000000)))))))) ]" info:`
	vmax=`convert xc: -format "%[fx: max($v7,max($v6,max($v5,max($v4,max($v3,max($v2,max($v1,max($v0,-1000000)))))))) ]" info:`
	bbwidth=`convert xc: -format "%[fx: ceil($umax - $umin) ]" info:`
	bbheight=`convert xc: -format "%[fx: ceil($vmax - $vmin) ]" info:`
	bbminx=`convert xc: -format "%[fx: -$umin ]" info:`
	bbminy=`convert xc: -format "%[fx: -$vmin ]" info:`	
#	merge="-background $bgcolor -repage ${bbwidth}x${bbheight}+${bbminx}+${bbminy}! -flatten"
	merge="-background $bgcolor -crop ${bbwidth}x${bbheight}-${bbminx}-${bbminy}! -flatten"
elif [ "$format" = "center" -a "$size" = "" ]; then
	ww=`convert xc: -format "%[fx: ceil($zoom*2*max($uw,$maxdist)) ]" info:`
	hh=`convert xc: -format "%[fx: ceil($zoom*2*max($vw,$maxdist)) ]" info:`
	offx=`convert xc: -format "%[fx: 0.5*($ww-1) ]" info:`
	offy=`convert xc: -format "%[fx: 0.5*($hh-1) ]" info:`
	merge="-crop ${ww}x${hh}-${offx}-${offy}! -background $bgcolor -flatten"
elif [ "$format" = "center" -a "$size" != "" ]; then
	[ "$dwidth" = "" ] && dwidth=$dheight
	[ "$dheight" = "" ] && dheight=$dwidth	
	offx=`convert xc: -format "%[fx: 0.5*($dwidth-1) ]" info:`
	offy=`convert xc: -format "%[fx: 0.5*($dheight-1) ]" info:`
	merge="-crop ${dwidth}x${dheight}-${offx}-${offy}! -background $bgcolor -flatten"
fi
#echo "merge=$merge"

# draw box
eval 'convert -virtual-pixel transparent -mattecolor none \
	\( '"$proc1"' \) \
	\( '"$proc2"' \) \
	\( '"$proc3"' \) \
	\( '"$proc4"' \) \
	\( '"$proc5"' \) \
	\( '"$proc6"' \) \
	$merge \
	$outfile'

exit 0
