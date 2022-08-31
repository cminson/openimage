convert -gaussian $1 $2 - | composite -compose overlay $2 - $3
