<?php
/**
 * Génération de tags HTML.
 *
 * @copyright Copyright (c) 2001-2012 Business&Decision
 * @license http://www.interakting.com /license/phpfactory
 *
 * @link http://www.interakting.com
 */
define('CRLF', '');
define('NBSP', '&nbsp;');
define('XHTML', true);
define('ATTR', 'align:alt:bgcolor:border:bordercolor:cellspacing:checked:colspan:height:href:id:label:more_attr:name:nobreak:nowrap:onchange:onclick:ondblclick:onkeydown:onkeypressed:onmouseover:rel:rowspan:selected:src:style:title:type:valign:value:width:content:media:marginwidth:marginheight:frameborder:size:limit:action:method:cellpadding:multiple:maxlength:target:enctype:onsubmit:summary:hspace:vspace:onmouseout:sprite:placeholder');
define('ALONEATTR', 'checked:selected:nowrap:nobreak');
// support attr-name without quotes: self::img(array(src=>$url,alt=>''));
// only class is a reserved php name.
foreach (explode(':', ATTR) as $attr) {
    if (!defined($attr)) {
        define($attr, $attr);
    }
}

/**
 * Simple functional Pelican_Html Element library in spirit to the perl CGI or
 * self::Element modules.
 *
 * Url:    http://xarch.tu-graz.ac.at/home/rurban/software/html-class.php.gz
 * http://xarch.tu-graz.ac.at/home/rurban/software/html-class.phps
 * http://xarch.tu-graz.ac.at/home/rurban/software/html-class.example.phps
 *
 * Features:
 * Ensures proper nesting of Pelican_Html tags (esp. with emacs),
 * XHTML, XML compliant
 * If the constant XHTML is defined, XHTML conforming tags are returnd, otherwise
 * HTML4.
 * Variable number of content arguments for all tags, not only containers.
 * Does no attribute and proper nesting checking. (is_contained_in(),
 * may_contain(), is_valid_attribute())
 *
 * Requires: At least 4.1.0, using call_user_func_array() object method as
 * callback.
 *
 * See also Jeff Dairiki's XmlElement/HtmlElement library within phpwiki,
 * which is more like self::Element, using validition.
 *
 * Todo: Improve self::attr_default().
 * Better support for attribute defaults to help with user_defined tags
 * (class='myclass', ...)
 *
 * This is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This software is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhpWiki; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @author Reini Urban <rurban@x-ray.at>
 *
 * @since 08/04/2002
 *
 * @version 2.0
 * @update 12/05/2004
 */
class Pelican_Html
{
    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function nbsp()
    {
        return '&nbsp;';
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $value (option) __DESC__
     *
     * @return string
     */
    public static function comment($value = '')
    {
        return '<!--'.$value.'-->';
    }


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function br()
    {
        $args = func_get_args();

        return self::_tag0('br', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function embed()
    {
        $args = func_get_args();

        return self::_tag1('embed', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function nobr()
    {
        $args = func_get_args();

        // pas w3c compliant  return self::_tag1('nobr', $args);
        return self::span(array('style' => 'white-space:nowrap;'), $args[0]);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function center()
    {
        $args = func_get_args();

        return self::_tag1('center', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function hr()
    {
        $args = func_get_args();

        return self::_tag0('hr', $args);
    }
    //public static function img () { $args = func_get_args(); return self::_tag0('img',$args); } // see below
    // Top-Level Elements


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function html()
    {
        $args = func_get_args();

        return self::_tag1('html', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function head()
    {
        $args = func_get_args();

        return self::_tag1('head', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function title()
    {
        $args = func_get_args();

        return self::_tag1('title', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function body()
    {
        $args = func_get_args();

        return self::_tag1('body', $args);
    }
    // Head Elements


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function meta()
    {
        $args = func_get_args();

        return self::_tag0('meta', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function style()
    {
        $args = func_get_args();
        $args = self::correctArgs($args);
        if (isset($args[0]["type"])) {
            if (!$args[0]["type"]) {
                $args[0]["type"] = "text/css";
            }
        }

        return self::_tag1('style', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function link()
    {
        $args = func_get_args();

        return self::_tag0('link', $args);
    }
    // Font Style Elements
    /*
    B - Bold text
    BIG - Large text
    I - Italic text
    S - Strike-through text (non-strict)
    SMALL - Small text
    STRIKE - Strike-through text (non-strict)
    TT - Teletype text
    U - Underlined text  (non-strict)
    */

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function b()
    {
        $args = func_get_args();

        return self::_tag1('b', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function i()
    {
        $args = func_get_args();

        return self::_tag1('i', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function u()
    {
        $args = func_get_args();

        return self::_tag1('u', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function strike()
    {
        $args = func_get_args();

        return self::_tag1('strike', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function small()
    {
        $args = func_get_args();

        return self::_tag1('small', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function big()
    {
        $args = func_get_args();

        return self::_tag1('big', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function sub()
    {
        $args = func_get_args();

        return self::_tag1('sub', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function sup()
    {
        $args = func_get_args();

        return self::_tag1('sup', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function tt()
    {
        $args = func_get_args();

        return self::_tag1('tt', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function font()
    {
        $args = func_get_args();

        return self::_tag1('font', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function button()
    {
        $args = func_get_args();

        return self::_tag1('button', $args);
    }
    // Phrase Elements
    /*
    ABBR - Abbreviation
    ACRONYM - Acronym
    CITE - Citation
    CODE - Computer code
    DEL - Deleted text
    DFN - Defined term
    EM - Emphasis
    INS - Inserted text
    KBD - Text to be input
    SAMP - Sample output
    STRONG - Strong emphasis
    VAR - Variable
    */

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function abbr()
    {
        $args = func_get_args();

        return self::_tag1('abbr', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function acronym()
    {
        $args = func_get_args();

        return self::_tag1('acronym', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function cite()
    {
        $args = func_get_args();

        return self::_tag1('cite', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function code()
    {
        $args = func_get_args();

        return self::_tag1('code', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function del()
    {
        $args = func_get_args();

        return self::_tag1('del', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function dfn()
    {
        $args = func_get_args();

        return self::_tag1('dfn', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function em()
    {
        $args = func_get_args();

        return self::_tag1('em', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function ins()
    {
        $args = func_get_args();

        return self::_tag1('ins', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function kbd()
    {
        $args = func_get_args();

        return self::_tag1('kbd', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function samp()
    {
        $args = func_get_args();

        return self::_tag1('samp', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function strong()
    {
        $args = func_get_args();

        return self::_tag1('strong', $args);
    }
    //public static function var ()   { $args = func_get_args(); return self::_tag1('var',$args); }
    // Generic Block-level Elements
    /*
    ADDRESS - Address
    BLOCKQUOTE - Block quotation
    DEL - Deleted text
    DIV - Generic block-level container
    H1 - Level-one heading
    H2 - Level-two heading
    H3 - Level-three heading
    H4 - Level-four heading
    H5 - Level-five heading
    H6 - Level-six heading
    HR - Horizontal rule
    INS - Inserted text
    NOSCRIPT - Alternate script content
    P - Paragraph
    PRE - Preformatted text
    */

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function div()
    {
        $args = func_get_args();

        return self::_tag1('div', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function pre()
    {
        $args = func_get_args();

        return self::_tag1('pre', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function p()
    {
        $args = func_get_args();

        return self::_tag1('p', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function h1()
    {
        $args = func_get_args();

        return self::_tag1('h1', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function h2()
    {
        $args = func_get_args();

        return self::_tag1('h2', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function h3()
    {
        $args = func_get_args();

        return self::_tag1('h3', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function h4()
    {
        $args = func_get_args();

        return self::_tag1('h4', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function h5()
    {
        $args = func_get_args();

        return self::_tag1('h5', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function h6()
    {
        $args = func_get_args();

        return self::_tag1('h6', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function address()
    {
        $args = func_get_args();

        return self::_tag1('address', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function blockquote()
    {
        $args = func_get_args();

        return self::_tag1('blockquote', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function noscript()
    {
        $args = func_get_args();

        return self::_tag1('noscript', $args);
    }
    // Tables


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function table()
    {
        $args = func_get_args();

        return self::_tag1('table', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function tr()
    {
        $args = func_get_args();

        return self::_tag1('tr', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function th()
    {
        $args = func_get_args();

        return self::_tag1('th', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function td()
    {
        $args = func_get_args();

        return self::_tag1('td', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function thead()
    {
        $args = func_get_args();

        return self::_tag1('thead', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function tfoot()
    {
        $args = func_get_args();

        return self::_tag1('tfoot', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function tbody()
    {
        $args = func_get_args();

        return self::_tag1('tbody', $args);
    }
    // Lists


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function ul()
    {
        $args = func_get_args();

        return self::_tag1('ul', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function ol()
    {
        $args = func_get_args();

        return self::_tag1('ol', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function dl()
    {
        $args = func_get_args();

        return self::_tag1('dl', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function dd()
    {
        $args = func_get_args();

        return self::_tag1('dd', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function dt()
    {
        $args = func_get_args();

        return self::_tag1('dt', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function li()
    {
        $args = func_get_args();

        return self::_tag1('li', $args);
    }
    // Special Inline Elements
    /*
    A - Anchor
    APPLET - Java applet (non-strict)
    BASEFONT - Base font change (non-strict)
    BDO - BiDi override
    BR - Line break
    FONT - Font change (non-strict)
    IFRAME - Inline frame (non-strict)
    IMG - Inline image
    MAP - Image map
    AREA - Image map region
    OBJECT - Object
    PARAM - Object parameter
    Q - Short quotation
    SCRIPT - Client-side script
    SPAN - Generic inline container
    SUB - Subscript
    SUP - Superscript
    */

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function a()
    {
        $args = func_get_args();

        return self::_tag1('a', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function bdo()
    {
        $args = func_get_args();

        return self::_tag1('bdo', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function applet()
    {
        $args = func_get_args();

        return self::_tag1('applet', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function iframe()
    {
        $args = func_get_args();

        return self::_tag1('iframe', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function map()
    {
        $args = func_get_args();

        return self::_tag1('map', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function area()
    {
        $args = func_get_args();

        return self::_tag1('area', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function object()
    {
        $args = func_get_args();

        return self::_tag1('object', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function param()
    {
        $args = func_get_args();

        return self::_tag1('param', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function q()
    {
        $args = func_get_args();

        return self::_tag1('q', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function span()
    {
        $args = func_get_args();

        return self::_tag1('span', $args);
    }
    // Forms


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function form()
    {
        $args = func_get_args();

        return self::_tag1('form', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function select()
    {
        $args = func_get_args();

        return self::_tag1('select', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function option()
    {
        $args = func_get_args();

        return self::_tag1('option', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function col()
    {
        $args = func_get_args();

        return self::_tag1('col', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function optgroup()
    {
        $args = func_get_args();

        return self::_tag1('optgroup', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function colgroup()
    {
        $args = func_get_args();

        return self::_tag1('colgroup', $args);
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function input()
    {
        $args = func_get_args();

        return self::_tag0('input', $args);
    }
    // ROWS=Number COLS=Number DISABLED READONLY ACCESSKEY


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function textarea()
    {
        $args = func_get_args();

        return self::_tag1('textarea', $args);
    }


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function fieldset()
    {
        $args = func_get_args();

        return self::_tag1('fieldset', $args);
    }
    // ACCESSKEY


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function legend()
    {
        $args = func_get_args();

        return self::_tag1('legend', $args);
    }
    // FOR=IDREF (associated form field) ACCESSKEY (shortcut key) ONFOCUS ONBLUR


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function label()
    {
        $args = func_get_args();

        return self::_tag1('label', $args);
    }
    // functions with attr overrides, forcing certain attributes to be set.
    //   array(name=>value), [attr] rest...
    // functions with attr defaults:
    // these take optionally no array as first parameter, instead
    // the default attribute only.


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function img()
    {
        $alt ='';
        $strict = array();
        $args = func_get_args();
        $tag = 'img';
        if (empty($args)) {
            return '<'.$tag.' />';
        } elseif (is_array($args[0])) {
            $attr = array_shift($args);
            /**CSS Sprite*/
            if (valueExists($attr, 'sprite')) {
                $pathinfo = pathinfo($attr['src']);
                $attr['src'] = "/library/public/images/xtrans.gif";
                $attr['class'] = trim(
                    $attr['class']." sprite-".$attr['sprite']." sprite-".str_replace(
                        ".".$pathinfo['extension'],
                        "",
                        strtolower($pathinfo['basename'])
                    )
                );
                //return '<span class="'.$attr['class'].'"></span>';
            }

            /* XHTML STRICT */
            if (valueExists($attr, 'hspace')) {
                $strict[] = "margin-left:".(int) $attr['hspace']."px;margin-right:".(int) $attr['hspace']."px";
                unset($attr['hspace']);
            }
            if (valueExists($attr, 'vspace')) {
                $strict[] = "margin-top:".$attr['vspace']."px;margin-bottom:".$attr['vspace']."px";
                unset($attr['vspace']);
            }
            if (valueExists($attr, 'align')) {
                if ($attr['align'] == "center") {
                    $strict[] = "vertical-align:middle";
                } elseif ($attr['align'] == "middle" || $attr['align'] == "top" || $attr['align'] == "bottom") {
                    $strict[] = "vertical-align:".$attr['align'];
                } else {
                    $strict[] = "float:".$attr['align'];
                }
                unset($attr['align']);
            }
            if ($strict) {
                if (!isset($attr['style'])) {
                    $attr['style'] = "";
                }
                $attr['style'] = implode(";", $strict).";".$attr['style'];
            }

            /* FIN XHTML STRICT */
            $attr_str = trim(self::_attr($attr));
            if (!isset($attr['alt'])) {
                $attr['alt'] = '';
            }
            if (!isset($attr['name']) && isset($attr['id'])) {
                $attr['name'] = $attr['id'];
            }
            // on empty attr strip the first space
            if (empty($args)) {
                return '<'.$tag.(strlen($attr_str) ? ' '.$attr_str : '').(XHTML ? ' />' : '>');
            } else {
                return '<'.$tag.(strlen($attr_str) ? ' '.$attr_str.'>' : '>').implode(
                    "\n",
                    $args
                )."</$tag>".CRLF;
            }
        } else {
            if (count($args) > 0) {
                $attr['src'] = $args[0];
            }
            if (count($args) > 1) {
                $attr['alt'] = $args[1];
            }
            if (count($args) > 2) {
                $attr['width'] = $args[2];
            }
            if (count($args) > 3) {
                $attr['height'] = $args[3];
            }

            if (empty($attr['alt'])) {
                $alt = " alt=\"\"";
            }

            return '<'.$tag.' '.self::_attr($attr).$alt." />";
        }
    }


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function a_href()
    {
        $args = func_get_args();
        $attr = array_shift($args);

        return self::_attr_default('a', 'href', $attr, $args);
    }
    // a_name($name,$text...) <=> a(array('name'=>$name),$text...);
    //   a_name($name,$text...) or
    //   a_name(array(name=>$name,...),$text...)


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function a_name()
    {
        $args = func_get_args();
        $attr = array_shift($args);

        return self::_attr_default('a', 'name', $attr, $args);
    }
    // label_for($id,...), label_for(array(),$id)
    //   FOR=IDREF (associated form field) ACCESSKEY (shortcut key) ONFOCUS ONBLUR


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function label_for()
    {
        $args = func_get_args();
        $attr = array_shift($args);

        return self::_attr_default('label', 'for', $attr, $args);
    }
    // other special functions:


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function script()
    {
        $args = func_get_args();

        $return = self::_tag1('script', $args);

        return $return;
    }
    // like the plain script, but forces the type to be set,
    // adds newlines and comments for older, non-jscript browsers.


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function jscript()
    {
        $args = func_get_args();

        $return = self::_tag1('script', $args);

        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function flash()
    {
        $args = func_get_args();
        $return = '';
        $param = null;
        if (is_array($args[0])) {
            $attr = array_shift($args);

            /* attributs par défaut */
            if (!$attr['classid']) {
                $attr['classid'] = "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000";
            }
            if (!$attr['codebase']) {
                $attr['codebase'] = "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0";
            }
            if (!$attr['quality']) {
                $attr['quality'] = "high";
            }

            if (!$attr['scale']) {
                $attr['scale'] = "noscale";
            }
            if (!$attr['wmode']) {
                $attr['wmode'] = "transparent";
            }
            $attr2 = $attr;
            $attr2['type='] = "application/x-shockwave-flash";
            $attr2['pluginspage'] = "http://www.macromedia.com/go/getflashplayer";
            if ($attr['src']) {
                $param .= self::_tag0('param', array(array("name" => 'movie', "value" => $attr['src'])));
                unset($attr['src']);
            }
            if ($attr['quality']) {
                $param .= self::_tag0('param', array(array("name" => 'quality', "value" => $attr['quality'])));
                unset($attr['quality']);
            }
            if ($attr['bgcolor']) {
                $param .= self::_tag0('param', array(array("name" => 'bgcolor', "value" => $attr['bgcolor'])));
                unset($attr['bgcolor']);
            }
            if ($attr['scale']) {
                $param .= self::_tag0('param', array(array("name" => 'scale', "value" => $attr['src'])));
                unset($attr['scale']);
            }
            if ($attr['wmode']) {
                $param .= self::_tag0('param', array(array("name" => 'wmode', "value" => $attr['src'])));
                unset($attr['wmode']);
            }
            $embed = self::embed($attr2);

            $return = self::object($attr, $param.$embed);
        }
        return $return;
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param string $id __DESC__
     * @param string $file __DESC__
     * @param string $width __DESC__
     * @param string $height __DESC__
     * @param array|string $params
     * @param string $comment
     *
     * @return string
     */
    public static function swfObject($id, $file, $width, $height, $params = array(), $comment = "Lecteur Flash")
    {
        $script = '   var so = new SWFObject("'.$file.($params ? '?'.http_build_query(
                    $params
                ) : '').'", "'.$id.'_player", "'.$width.'", "'.$height.'", "7", "#ffffff");
   so.addParam("quality", "high");
   so.addParam("wmode", "transparent");
   so.write("swfObj_'.$id.'");';
        $return = self::div(
            array('id' => "swfObj_".$id, 'style' => "height:".$height."px;width:".$width."px;text-align:center;"),
            $comment
        );
        $return .= self::script($script);

        return $return;
    }


    /**
     * @param string $name
     * @param string $value
     * @param array $attr
     *
     * @return string
     */
    public static function hidden($name, $value, $attr = array())
    {
        return self::_attr_overrides('input',array('type' => 'hidden','name' => $name, 'value' => $value), array($attr));
    }
    // internal functions


    /**
     * __DESC__.
     *
     * @access private
     *
     * @param string $attr __DESC__
     *
     * @return string
     */
    public static function _attrXHTMLStrict($attr)
    {
        return $attr;
    }
    // no attributes, just content


    /**
     * __DESC__.
     *
     * @access private
     *
     * @param string $tag
     * @param array|bool   $args (option)
     *
     * @return string
     */
    public static function _tag0($tag, $args = false)
    {

        if (empty($args)) {
            $return = XHTML ? "<$tag />" : "<$tag>";
        } elseif (is_array($args[0])) {
            $attr = array_shift($args);
            $attr_str = trim(self::_attr($attr));
            // on empty attr strip the first space
            if (empty($args)) {
                $return =  '<'.$tag.(strlen($attr_str) ? ' '.$attr_str : '').(XHTML ? ' />' : '>');
            } else {
                $return = '<'.$tag.(strlen($attr_str) ? ' '.$attr_str.'>' : '>').implode(
                    "\n",
                    $args
                )."</$tag>".CRLF;
            }
        } else {
            $return = "<$tag>".implode('', $args)."</$tag>".CRLF;
        }

        return $return;
    }
    // with optional attributes-array as first parameter


    /**
     * __DESC__.
     *
     * @access private
     *
     * @param string $tag   __DESC__
     * @param array     $args  (option) __DESC__
     * @param bool   $cdata (option) __DESC__
     *
     * @return string
     */
    public static function _tag1($tag, $args = null, $cdata = false)
    {
        if (empty($args)) {
            return '<'.$tag.'></'.$tag.'>';
        } else {
            if (is_array($args[0])) {
                $attr = array_shift($args);
                $attr_str = trim(self::_attr($attr));
                $return = implode(CRLF, $args);
                if ($cdata) {
                    $return = CRLF.'/* <![CDATA[ */'.CRLF.$return.CRLF.'/* ]]> */'.CRLF;
                }
                $return = '<'.$tag.(strlen($attr_str) ? ' '.$attr_str.'>' : '>').$return.'</'.$tag.'>';
            } else {
                $return = implode('', $args);
                if ($cdata) {
                    $return = CRLF.'/* <![CDATA[ */'.CRLF.$return.CRLF.'/* ]]> */'.CRLF;
                }
                $return = '<'.$tag.'>'.$return.'</'.$tag.'>';
            }
        }

        return $return;
    }
    // with attributes-array and rest parameters


    /**
     * __DESC__.
     *
     * @access private
     *
     * @param string $tag  __DESC__
     * @param string $attr __DESC__
     * @param bool     $args (option) __DESC__
     *
     * @return string
     */
    public static function _tag2($tag, $attr, $args = false)
    {
        if (empty($attr)) {
            $attr_str = '';
        } else {
            $attr_str = trim(self::_attr($attr));
        }
        if ($args) {
            $args = implode("\n", $args);

            return '<'.$tag.(strlen($attr_str) ? ' '.$attr_str.'>' : '>')."$args</$tag>".CRLF;
        } else {
            return '<'.$tag.(strlen($attr_str) ? ' '.$attr_str : '').(XHTML ? ' />' : '>');
        }
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @param array $attr
     *
     * @return string
     */
    public static function _attr($attr)
    {
        $tag = '';
        if (!empty($attr)) {

            foreach ($attr as $key => $value) {
                if (XHTML) {
                    $key = strtolower($key);
                }
                if ($key == 'more_attr') {
                    $tag .= ($value." ");
                } elseif (in_array($key, explode(':', ALONEATTR))) {
                    if ($value) {
                        $tag .= $key.'="'.$key.'" ';
                    }
                } else {
                    if ($value > "" || $value === 0 || $key == "value" || $key == "alt") {
                        if ($key != "language") {
                            $tag .= ($key.'="'.$value.'" ');
                        }
                    }
                }
            }
        }

        return $tag;
    }
    // provide default attribute name=>value pair if no attrs were defined
    // for a_name, a_href, ...


    /**
     * __DESC__.
     *
     * @access private
     *
     * @param string $tag      __DESC__
     * @param string $def_name __DESC__
     * @param array $attr     __DESC__
     * @param bool     $args     (option) __DESC__
     *
     * @return string
     */
    public static function _attr_default($tag, $def_name, $attr, $args = false)
    {
        if (!empty($attr) and is_array($attr)) {
            return self::_tag2($tag, $attr, $args);
        } else {
            return self::_tag2($tag, array($def_name => $attr), $args);
        }
    }

    /**
     * __DESC__.
     *
     * @access private
     *
     * @param string $tag       __DESC__
     * @param array $overrides __DESC__
     * @param array     $args      (option) __DESC__
     *
     * @return string
     */
    public static function _attr_overrides($tag, $overrides, $args = null)
    {
        if (!empty($args) and is_array($args[0])) {

            $attr = array_shift($args);
            return self::_tag2($tag, array_merge($attr, $overrides), $args);
        } else {
            // no attr
            return self::_tag2($tag, $overrides, $args);
        }
    }
    // not yet used.
    // to apply defaults and overrides


    /**
     * __DESC__.
     *
     * @access private
     *
     * @param string $args __DESC__
     *
     * @return string
     */
    public static function _attr_split($args)
    {
        if (!empty($args) and is_array($args[0])) {
            // and !isset($args[0][0])) { //
            $attr = array_shift($args);

            return array($attr, $args);
        } else {
            // no attr
            return array(array(), $args);
        }
    }

    /**
     * __DESC__.
     *
     * @param array $args __DESC__
     *
     * @return array
     */
    function correctArgs($args)
    {
        if (count($args) == 1) {
            $temp = $args[0];
            $args = array();
            $args[] = array();
            $args[] = $temp;
        }

        return $args;
    }
}

/**
 * Simple functional Pelican_Html INPUT Element library in spirit to the perl CGI
 * or
 * self::Element modules.
 *
 * INPUT type= "text | Pelican_Security_Password | checkbox | radio | submit |
 * reset | file |
 * hidden | image | button"
 *
 * @author Reini Urban <rurban@x-ray.at>
 *
 * @since 08/04/2002
 *
 * @version 2.0
 * @update 12/05/2004
 */
class Pelican_Html_Input extends Pelican_Html
{
    // INPUT type= "text | Pelican_Security_Password | checkbox | radio | submit | reset | file | hidden | image | button"
    // Pelican_Html_Input::checkbox(array(name=>$name,value=$value))


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function checkbox()
    {
        $args = func_get_args();

        return call_user_func_array(
            array(__CLASS__, '_attr_overrides'),
            array('input', array('type' => 'checkbox'), $args)
        );
    }
    // Pelican_Html_Input::radio(array(name=>$name,value=$value,checked=>true))


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function radio()
    {
        $args = func_get_args();

        return call_user_func_array(array(__CLASS__, '_attr_overrides'), array('input', array('type' => 'radio'), $args));
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     *
     * @param string $name
     * @param string $value
     * @param array $attr
     *
     * @return string
     */
    public static function hidden($name= null, $value= null, $attr= array())
    {
        return parent::hidden($name, $value, $attr);
    }


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function text()
    {
        $args = func_get_args();

        return call_user_func_array(
            array('Pelican_Html', '_attr_overrides'),
            array('input', array('type' => 'text'), $args)
        );
    }
    // Pelican_Html_Input::password(array(name=>$name,value=$value,size=>$size,'class'=>$class,...))


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function Pelican_Security_Password()
    {
        $args = func_get_args();

        return call_user_func_array(
            array('Pelican_Html', '_attr_overrides'),
            array('input', array('type' => 'password'), $args)
        );
    }
    // Pelican_Html_Input::submit(array(name=>$name,value=$value,'class'=>$class,...))


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function submit()
    {
        $args = func_get_args();

        return call_user_func_array(
            array('Pelican_Html', '_attr_overrides'),
            array('input', array('type' => 'submit'), $args)
        );
    }
    // Pelican_Html_Input::reset(array(value=$value,'class'=>$class,...))


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function reset()
    {
        $args = func_get_args();

        return call_user_func_array(
            array('Pelican_Html', '_attr_overrides'),
            array('input', array('type' => 'reset'), $args)
        );
    }
    // Pelican_Html_Input::file(array(name=>$name,accept=>'text/html',...))


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function file()
    {
        $args = func_get_args();

        return call_user_func_array(
            array('Pelican_Html', '_attr_overrides'),
            array('input', array('type' => 'file'), $args)
        );
    }
    // Pelican_Html_Input::image(array(name=>$name,value=$value,src=>$url,alt=$name,'usemap'=>'#mapname','class'=>$class,...))


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function image()
    {
        $args = func_get_args();

        return call_user_func_array(
            array('Pelican_Html', '_attr_overrides'),
            array('input', array('type' => 'image'), $args)
        );
    }
    // Pelican_Html_Input::button(array(value=$value,id=>$id,'onClick'=>"jsfunc()",'class'=>$class,...))


    /**
     * __DESC__.
     *
     * @access public
     *
     * @return string
     */
    public static function button()
    {
        $args = func_get_args();

        return call_user_func_array(
            array('Pelican_Html', '_attr_overrides'),
            array('input', array('type' => 'button'), $args)
        );
    }
}

/*
public static function is_valid_email_address($email){
$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
$quoted_pair = '\\x5c\\x00-\\x7f';
$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";
$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";
$domain_ref = $atom;
$sub_domain = "($domain_ref|$domain_literal)";
$word = "($atom|$quoted_string)";
$domain = "$sub_domain(\\x2e$sub_domain)*";
$local_part = "$word(\\x2e$word)*";
$addr_spec = "$local_part\\x40$domain";
return preg_match("!^$addr_spec$!", $email) ? 1 : 0;
}
*/
if (!function_exists('http_build_query')) {
    function http_build_query($formdata, $numeric_prefix = "")
    {
        $arr = array();
        foreach ($formdata as $key => $val) {
            $arr[] = rawurlencode($numeric_prefix.$key)."=".rawurlencode($val);
        }

        return implode($arr, "&");
    }
}

