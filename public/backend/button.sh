#!/bin/sh

# Configure your button below
outer_size=72 # size of the image
inner_size=62 # size occupied by button inside image
middle_size=6 # the height of the gradient in the middle of background
text_size=25  # size of text inside the button
roundness=10   # size of round corners
text="J"      # text to write in button

gradient0=\#CCCCCC
gradient1=\#CCCCCC # gradient middle top color
gradient2=\#444444 # gradient middle bottom color
gradient3=\#222222 # gradient bottom color

#---------------------------------------
# DO NOT EDIT BELOW UNLESS YOU KNOW
#---------------------------------------
topbottom_size=`expr \( ${inner_size} - ${middle_size} \) / 2`

convert \
	-size ${inner_size}x${topbottom_size} gradient:${gradient0}-${gradient1} \
	-size ${inner_size}x${middle_size} gradient:${gradient1}-${gradient2} \
	-size ${inner_size}x${topbottom_size} gradient:${gradient2}-${gradient3} -append \
    \( +clone  -threshold -1 \
       -draw "fill black polygon 0,0 0,$roundness $roundness,0 fill white circle $roundness,$roundness $roundness,0" \
       \( +clone -flip \) -compose Multiply -composite \
       \( +clone -flop \) -compose Multiply -composite \
   	\) +matte -compose CopyOpacity -composite \
	-size ${outer_size}x${outer_size} xc:transparent \
	+swap\
	-gravity center -compose src-over -composite \
	\( -clone 0 -alpha extract -shade  90x0 -alpha on +level 0%,80% \)\
	-compose  lighten -composite \
	\(\
		-background none -stroke none -fill white  -size ${text_size}x${text_size} -gravity center label:"${text}" \
		\( +clone -background none -shadow 100x0+0-2 \) \
		+swap -layers merge +repage \
	\)\
	-geometry +0-3 \
	-gravity center -composite \
	result.png
