!/bin/bash
sudo raspivid -o - -t 50400000 -w 1280 -h 720 -fps 25 -b 4000000 -g 50 | sudo ffmpeg -t 14:00:00 -re -ar 44100 -ac 2 -acodec pcm_s16le -f s16le -ac 2 -i /dev/zero -f h264 -i - -vcodec copy -acodec aac -ab 12$
