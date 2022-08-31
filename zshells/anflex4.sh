
infile=$1
outfile=$2


command='convert -delay 10 $infile'
command="$command -background white -bordercolor white -border 0x10"

for i in `seq 15 -2 -12; seq -15 2 12`; do
  command="$command \\( -clone 0 -wave ${i}x225 \\)"
done

# remove original image
# center all the image frames vertically by center cropping
command="$command -delete 0 +repage -gravity center -crop 0x98+0+0"
command="$command +repage -loop 0 $outfile"

eval $command
