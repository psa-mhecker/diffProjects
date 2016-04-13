#/bin/sh
wget -N --header="If-Modified-Since: `date -r wurfl.zip --utc --rfc-2822 2>/dev/null || date --utc --rfc-2822 --date='1 week ago'`" http://www.scientiamobile.com/wurfl/nhure/wurfl.zip

unzip -f wurfl.zip



