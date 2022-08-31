

infile=$1
outfile=$2

command='convert -delay 50 $infile '
command="$command -bordercolor white -border 60x60"  # enlarge image
#center='point 97,97'   # the pixels that control the explosion color
center='rectangle 97,97 98,98'   # the pixels that control the explosion color

for i in `seq 1 2 20`; do
  #c_arg="white"
  c_arg=`perl -e '$i=1-'$i'/12; $i=$i>0?($i*400):0; $i=$i>255?255:$i; \
              printf "rgb(%d,%d,%d)", $i, $i, $i;'`
  command="$command \\( -clone 0 -draw 'fill $c_arg $center'"
  command="$command -implode -$i -set delay 5 \\)"
done

command="$command -shave 60x60 +repage -loop 0 $outfile"

eval $command
