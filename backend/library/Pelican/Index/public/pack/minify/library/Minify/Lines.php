<?php
/**
 * Class Minify_Lines.
 */

/**
 * Add line numbers in C-style comments for easier debugging of combined content.
 *
 * @author Stephen Clay <steve@mrclay.org>
 * @author Adam Pedersen (Issue 55 fix)
 */
class Minify_Lines
{
    /**
     * Add line numbers in C-style comments.
     *
     * This uses a very basic parser easily fooled by Pelican_Index_Comment tokens inside
     * strings or regexes, but, otherwise, generally clean code will not be
     * mangled.
     *
     * @param string $content
     * @param array  $options available options:
     *
     * 'id': (optional) string to identify file. E.g. file name/path
     *
     * @return string
     */
    public static function minify($content, $options = array())
    {
        $id = (isset($options['id']) && $options['id'])
            ? $options['id']
            : '';
        $content = str_replace("\r\n", "\n", $content);
        $lines = explode("\n", $content);
        $numLines = count($lines);
        // determine left padding
        $padTo = strlen($numLines);
        $inComment = false;
        $i = 0;
        $newLines = array();
        while (null !== ($line = array_shift($lines))) {
            if (('' !== $id) && (0 == $i % 50)) {
                array_push($newLines, '', "/* {$id} */", '');
            }
            ++$i;
            $newLines[] = self::_addNote($line, $i, $inComment, $padTo);
            $inComment = self::_eolInComment($line, $inComment);
        }

        return implode("\n", $newLines)."\n";
    }

    /**
     * Is the parser within a C-style Pelican_Index_Comment at the end of this line?
     *
     * @param string $line      current line of code
     * @param bool   $inComment was the parser in a Pelican_Index_Comment at the
     *                          beginning of the line?
     *
     * @return bool
     */
    private static function _eolInComment($line, $inComment)
    {
        while (strlen($line)) {
            $search = $inComment
                ? '*/'
                : '/*';
            $pos = strpos($line, $search);
            if (false === $pos) {
                return $inComment;
            } else {
                if ($pos == 0
                    || ($inComment
                        ? substr($line, $pos, 3)
                        : substr($line, $pos-1, 3)) != '*/*') {
                    $inComment = ! $inComment;
                }
                $line = substr($line, $pos + 2);
            }
        }

        return $inComment;
    }

    /**
     * Prepend a Pelican_Index_Comment (or note) to the given line.
     *
     * @param string $line      current line of code
     * @param string $note      content of note/comment
     * @param bool   $inComment was the parser in a Pelican_Index_Comment at the
     *                          beginning of the line?
     * @param int    $padTo     minimum width of comment
     *
     * @return string
     */
    private static function _addNote($line, $note, $inComment, $padTo)
    {
        return $inComment
            ? '/* '.str_pad($note, $padTo, ' ', STR_PAD_RIGHT).' *| '.$line
            : '/* '.str_pad($note, $padTo, ' ', STR_PAD_RIGHT).' */ '.$line;
    }
}
