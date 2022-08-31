#!/bin/bash

# Provide the input and output names
# output name must be .gif
ifile1=$1
ifile2=$2
ifile3=$3
ifile4=$4
ifile5=$5
ifile6=$6
ofile=$7

# provide the frame delay in the gif animation
delay=10

# set initial angles and angle increment
tiltvalue=-45
panvalue=45
num=36
anginc=10

# define temp
tmp1="box_animate_1_$$.miff"
tmp2="box_animate_2_$$.miff"
tmp3="box_animate_3_$$.miff"
trap "rm -f $tmp1 $tmp2 $tmp3; exit 0" 0
trap "rm -f $tmp1 $tmp2 $tmp3; exit 1" 1 2 3 15

i=0
while [ $i -lt $num ]
	do
	if [ $tiltvalue -lt -360 ]; then
		tiltvalue=`expr $tiltvalue + 360`
	fi
	echo "frame $i; pan=$panvalue; tilt=$tiltvalue"
	 ../zshells/n3Dbox.sh pan=$panvalue tilt=$tiltvalue pef=0.5 filter="lanczos" format="center" $ifile1 $ifile2 $ifile3 $ifile4 $ifile5 $ifile6 $tmp1
	if [ $i -eq 0 ]
		then
		convert $tmp1 -delay $delay $tmp2
	else
		convert -delay $delay $tmp2 $tmp1 $tmp2
	fi
	# tiltvalue=`expr $tiltvalue - $anginc`
	panvalue=`expr $panvalue - $anginc`
	i=`expr $i + 1`
done
convert -loop 0 $tmp2 $ofile 
	
