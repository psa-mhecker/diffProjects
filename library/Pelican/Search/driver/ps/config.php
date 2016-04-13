<?php
/**
 * ps2ascii [ input.ps [ output.txt ] ]
 * ps2ascii input.pdf [ output.txt ]
 * http://www.research.digital.com/SRC/virtualpaper/pstotext.html
 * *
 * Usage: gs [switches] [file1.ps file2.ps ...]
Most frequently used switches: (you can use # in place of =)
 -dNOPAUSE           no pause after page   | -q       `quiet', fewer messages
 -g<width>x<height>  page size in pixels   | -r<res>  pixels/inch resolution
 -sDEVICE=<devname>  select device         | -dBATCH  exit after last file
 -sOutputFile=<file> select output file: - for stdout, |command for pipe,
                                         embed %d or %ld for page
#Input formats: PostScript PostScriptLevel1 PostScriptLevel2 PDF

*/

$linux = "/usr/bin/ps2ascii";

$extension = ".txt";

$output = ">";
