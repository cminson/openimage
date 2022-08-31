
infile=$1
outfile=$2

command='convert -delay 20 $infile'
command="$command -background black -gravity center -compose Src"

for i in `seq 10 10 360`; do
  command="$command \\( -clone 0 -background white -rotate $i -clone 0 +swap -composite \\)"
done

command="$command -loop 0 $outfile"

eval $command
