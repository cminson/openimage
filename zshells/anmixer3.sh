
infile=$1
outfile=$2

command='convert -delay 20 $infile'

for i in `seq 10 40 40; seq 40 -40 -40; seq -40 40 0`; do
  command="$command \\( -clone 0 -swirl $i \\)"
done

command="$command -loop 0 $outfile"

eval $command

