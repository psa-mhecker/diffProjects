<?php
/**
 * Response adapter for Wiki.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
require_once pelican_path('Response.Adapter');

/**
 * Response adapter for Wiki.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Wiki extends Pelican_Response_Adapter
{
    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlHead = '';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_xmlnsString = '';

    /**
     * @see Pelican_Response_Adapter
     */
    protected $_docType = '';

    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'wiki';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        $text = preg_replace('#<h1>(.*?)<\/h1>#is', "=$1=", $text);
        $text = preg_replace('#<h2>(.*?)<\/h2>#is', "==$1==", $text);
        $text = preg_replace('#<h3>(.*?)<\/h3>#is', "===$1===", $text);
        $text = preg_replace('#<h4>(.*?)<\/h4>#is', "====$1====", $text);
        $text = preg_replace('#<h5>(.*?)<\/h5>#is', "=====$1=====", $text);
        $text = preg_replace('#<h6>(.*?)<\/h6>#is', "======$1======", $text);
        $text = preg_replace('#\xB6{2}<li#is', "\xB6<li", $text); // ie6 only ..
        $text = preg_replace('#(^|\xB6)<(u|o)l[^>]*?>\xB6#is', "$1", $text); // only outer level list start at BOL ...
        $text = preg_replace('#<dt>(.*?)<\/dt>[ \f\n\r\t\v]*<dd>#is', "; $1: ", $text);
        $text = preg_replace('#<\/blockquote>#is', "\"]", $text);
        $text = preg_replace('#<td class=\"?lft\"?>\xB6*[ ]?|<\/tr>#is', "|", $text); // ie6 only ..
        $text = preg_replace('#\xB6<tr(?:[^>]*?)>#is', "\xB6", $text);
        $text = preg_replace('#<td colspan=\"([0-9]+)\"(?:[^>]*?)>#is', "|$1>", $text);
        $text = preg_replace('#<td(?:[^>]*?)>#is', "|", $text);
        $text = preg_replace('#<table>#is', "[", $text);
        $text = preg_replace('#<\/table>#is', "]", $text);
        $text = preg_replace('#<tr(?:[^>]*?)>\xB6*|<\/td>\xB6*|<tbody>\xB6*|<\/tbody>#is', "", $text);
        $text = preg_replace('#<hr\/?>#is', "----", $text);
        $text = preg_replace('#<br\/?>#is', "\\\\", $text);
        $text = preg_replace('#(<p>|<(d|o|u)l[^>]*>|<\/(dl|ol|ul|p)>|<\/(li|dd)>)#is', "", $text);
        $text = preg_replace('#<strong[^>]*?>(.*?)<\/strong>#is', "*$1*", $text);
        $text = preg_replace('#<em[^>]*?>(.*?)<\/em>#is', "_$1_", $text);
        $text = preg_replace('#<i[^>]*?>(.*?)<\/i>#is', "_$1_", $text);
        $text = preg_replace('#<sup[^>]*?>(.*?)<\/sup>#is', "^$1^", $text);
        $text = preg_replace('#<sub[^>]*?>(.*?)<\/sub>#is', "~$1~", $text);
        $text = preg_replace('#<del[^>]*?>(.*?)<\/del>#is', "(-$1-)", $text);
        $text = preg_replace('#<abbr title=\"([^\"]*)\">(.*?)<\/abbr>#is', "?$2($1)?", $text);
        /*$text = preg_replace('#<(p|table)[^>]+(style=\"[^\"]*\")[^>]*>#is', function ($0,$1,$2) {return "<"+$1+">"+Wiky.invStyle($2);} , $text);*/
        /*$text = preg_replace('#<li class=\"?([^ >\"]*)\"?[^>]*?>([^<]*)#is', function ($0,$1,$2) {return $1.replace(/u/g,"*").replace(/([01aAiIg])$/,"$1.")+" "+$2;}, $text);  // list items ..*/
        //$text = preg_replace('#(<\/(?:dl|ol|ul|p)>[ \xB6]*<(?:p)>)/gi, "\xB6\xB6" , $text);
        //$text = preg_replace('#<blockquote([^>]*)>#is', function ($0,$1) {return Wiky.store("["+Wiky.invStyle($1)+Wiky.invAttr($1,["cite","title"])+"\"");} , $text);
        /*$text = preg_replace('#<a href=\"([^\"]*)\"[^>]*?>(.*?)<\/a>#is', function ($0,$1,$2) {return $1==$2?$1:"["+$1+","+$2+"]";}, $text);*/
        /*$text = preg_replace('#<img([^>]*)\/>#is', function ($0,$1) {var a=Wiky.attrVal($1,"alt"),h=Wiky.attrVal($1,"src"),t=Wiky.attrVal($1,"title"),s=Wiky.attrVal($1,"style");return s||(t&&h!=t)?("["+Wiky.invStyle($1)+"img:"+h+(t&&(","+t))+"]"):h;}, $text);*/
        parent::process($text);
        $this->setHead('');
    }

    /**
     * __DESC__.
     *
     * @access public
     *
     * @param __TYPE__ $text __DESC__
     *
     * @return __TYPE__
     */
    protected function _wiki2html($text)
    {
        $text = preg_replace('/&lt;source lang=&quot;(.*?)&quot;&gt;(.*?)&lt;\/source&gt;/', '<pre lang="$1">$2</pre>', $text);
        $text = preg_replace('/======(.*?)======/', '<h5>$1</h5>', $text);
        $text = preg_replace('/=====(.*?)=====/', '<h4>$1</h4>', $text);
        $text = preg_replace('/====(.*?)====/', '<h3>$1</h3>', $text);
        $text = preg_replace('/===(.*?)===/', '<h2>$1</h2>', $text);
        $text = preg_replace('/==(.*?)==/', '<h1>$1</h1>', $text);
        $text = preg_replace("/'''(.*?)'''/", '<strong>$1</strong>', $text);
        $text = preg_replace("/''(.*?)''/", '<em>$1</em>', $text);
        $text = preg_replace('/&lt;s&gt;(.*?)&lt;\/s&gt;/', '<strike>$1</strike>', $text);
        $text = preg_replace('/\[\[Image:(.*?)\|(.*?)\]\]/', '<img src="$1" alt="$2" title="$2" />', $text);
        $text = preg_replace('/\[(.*?) (.*?)\]/', '<a href="$1" title="$2">$2</a>', $text);
        $text = preg_replace('/&gt;(.*?)\n/', '<blockquote>$1</blockquote>', $text);
        $text = preg_replace('/\* (.*?)\n/', '<ul><li>$1</li></ul>', $text);
        $text = preg_replace('/<\/ul><ul>/', '', $text);
        $text = preg_replace('/# (.*?)\n/', '<ol><li>$1</li></ol>', $text);
        $text = preg_replace('/<\/ol><ol>/', '', $text);
        $text = str_replace("\r\n\r\n", '</p><p>', $text);
        $text = str_replace("\r\n", '<br/>', $text);
        $text = '<p>'.$text.'</p>';
        parent::process($text);
        $this->setHead('');
    }
}
