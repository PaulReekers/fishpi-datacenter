#!/bin/bash
echo "Removing previous thumb $1"
rm $1
echo "Try to fetch youtube URL $2"
input=$(youtube-dl -f 95 -g $2)
echo "URL of youtube: $input"
ffmpeg -i $input -vf "thumbnail,scale=1280:720" -frames:v 1 $1
echo "thumb created"
