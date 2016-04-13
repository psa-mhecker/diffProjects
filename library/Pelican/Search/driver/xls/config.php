<?php
/**
 * @ignore
* @package Pelican
* @subpackage Search
*/

/**
 * xlhtml 0.4.9.2 converts excel files (.xls) to Html.
Copyright (c) 1999-2001, Charles Wyble. Released under GPL.
Usage: xlhtml [-xp:# -xc:#-# -xr:#-# -bc###### -bi???????? -tc######] <FILE>
 -a:  aggressive Pelican_Html optimization
 -asc ascii output for -dp & -x? options
 -csv comma separated value output for -dp & -x? options
 -xml XML output
 -bc: Set default background color - default white
 -bi: Set background image path
 -c:  Center justify tables
 -dp: Dumps page count and max rows & colums per page
 -v:  Prints program version number
 -fw: Suppress formula warnings
 -m:  No encoding for multibyte
 -nc: No Colors - black & white
 -nh: No Html Headers
 -tc: Set default text color - default black
 -te: Trims empty rows & columns at the edges of a worksheet
 -xc: Columns (separated by a dash) for extraction (zero based)
 -xp: Page extracted (zero based)
 -xr: Rows (separated by a dash) to be extracted (zero based)
 */

$windows = "xlhtml.exe";
$linux = "/usr/bin/xlhtml";

$extension = ".html";

$output = ">";

$options[] = "-bcffffff";
$options[] = "-fw ";
$options[] = "-a";
$options[] = "-xml";

/** pb d'encodage utf8 avec xlhtml, la sortie est en utf8 mais en partie */
function postClean($value)
{
    return(strtr(trim($value),
    'àáâãäåòóôõöøèéêëçìíîïùúûü«»',
    'aaaaaaooooooeeeeciiiiuuuu""' ) );
}
