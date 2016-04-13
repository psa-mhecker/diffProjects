<?php
/**
 * @ignore
* @package Pelican
* @subpackage Search
*/

/**
 * pdftohtml version 0.36 http://pdftohtml.sourceforge.net/, based on Xpdf version 2.02
Copyright 1999-2003 Gueorgui Ovtcharov and Rainer Dorsch
Copyright 1996-2003 Glyph & Cog, LLC

Usage: pdftohtml [options] <PDF-file> [<html-file> <xml-file>]
  -f <int>          : first page to convert
  -l <int>          : last page to convert
  -q                : don't print any messages or errors
  -h                : print usage information
  -help             : print usage information
  -p                : exchange .pdf links by .html
  -c                : generate complex document
  -i                : ignore images
  -noframes         : generate no frames
  -stdout           : use standard output
  -zoom <fp>        : zoom the pdf document (default 1.5)
  -xml              : output for XML post-processing
  -hidden           : output hidden text
  -nomerge          : do not merge paragraphs
  -enc <string>     : output text encoding name
  -dev <string>     : output device name for Ghostscript (png16m, jpeg etc)
  -v                : print copyright and version info
  -opw <string>     : owner Pelican_Security_Password (for encrypted files)
  -upw <string>     : user Pelican_Security_Password (for encrypted files)
 */

$windows = "pdftohtml.exe";
$linux = "/usr/bin/pdftohtml";

$extension = ".html";

$output = " > ";

$options[] = "-q";
$options[] = "-i";
$options[] = "-noframes";
$options[] = "-stdout";
