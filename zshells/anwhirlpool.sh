
infile=$1
outfile=$2

command='convert -delay 10 $infile'
command="$command -bordercolor white -border 40x40"

#for i in `seq 1 72`; do
for i in `seq 1 24`; do
#  s_arg=`echo "$i * 15" | bc`
  s_arg=`echo "$i * 45" | bc`
  i_arg=`echo "scale=2; $i / 54" | bc`
  command="$command \\( -clone 0 -swirl $s_arg -implode $i_arg \\)"
done

command="$command -shave 40x40 +repage -loop 0 $outfile"

eval $command
