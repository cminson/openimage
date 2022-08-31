
infile=$1
outfile=$2

command='convert -delay 20 $infile'
command="$command -background white -bordercolor white -border 5x2"

for i in `seq 100 -4 0;`; do
  command="$command \\( -clone 0 -splice ${i}x0+0+0 "
  command="$command -wave 30x200 -chop ${i}x0+0+0 \\)"
done

# remove page offsets and delet the original image
command="$command +repage -delete 0 -loop 0 $outfile"

eval $command

