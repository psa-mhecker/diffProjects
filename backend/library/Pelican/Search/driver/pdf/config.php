<?php
/**
 * @ignore
 */

/**
 * pdftohtml version 0.36 http://pdftohtml.sourceforge.net/, based on Xpdf version 2.02.
 */
$windows = "pdftohtml.exe";
$linux = "/usr/bin/pdftohtml";

$extension = ".html";

$output = " > ";

$options[] = "-q";
$options[] = "-i";
$options[] = "-noframes";
$options[] = "-stdout";
