#!/bin/bash

# Provide the input and output names
# output name must be .gif
ifile1=mandril.jpg
ifile2=logo3.gif
ifile3=zelda3.jpg
ofile=box_animation.gif

# provide the frame delay in the gif animation
delay=10

# set initial angles and angle increment
tiltvalue=-45
panvalue=45
anginc=10
num=36

# define temp
tmp1="box_animate_1_$$.miff"
tmp2="box_animate_2_$$.miff"
tmp3="box_animate_3_$$.miff"
trap "rm -f $tmp1 $tmp2 $tmp3; exit 0" 0
trap "rm -f $tmp1 $tmp2 $tmp3; exit 1" 1 2 3 15

i=0
while [ $i -lt $num ]
	do
	if [ $panvalue -gt 360 ]; then
		panvalue=`expr $panvalue - 360`
	fi
	echo "frame $i; pan=$panvalue; tilt=$tiltvalue"
	3Dbox pan=$panvalue tilt=$tiltvalue pef=0.5 filter="lanczos" format="center" $ifile1 $ifile2 $ifile3 $tmp1
	if [ $i -eq 0 ]
		then
		convert $tmp1 -delay $delay $tmp2
	else
		convert -delay $delay $tmp2 $tmp1 $tmp2
	fi
	panvalue=`expr $panvalue + $anginc`
	i=`expr $i + 1`
done
convert -loop 0 $tmp2 $ofile 
	
