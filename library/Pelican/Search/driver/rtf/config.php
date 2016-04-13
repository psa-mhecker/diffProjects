<?php
/**
 * @ignore
* @package Pelican
* @subpackage Search
 * Usage: unrtf [--version] [--verbose] [--help] [--nopict|-n] [--noremap] [--html] [--text] [--vt] [--latex] [-t html|text|vt|latex] <filename>
*/
//$windows = "rtf2.exe";
//$linux = "rtftohtml";
$linux = "/usr/bin/unrtf";

$options[] = "--text";

$extension = ".txt";

$output = ">";
