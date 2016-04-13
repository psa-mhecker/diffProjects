<?php
/**
 * Response adapter for Stb Browser
 *
 * @package Pelican
 * @subpackage Response
 * @author RaphaÃ«l Carles <raphael.carles@businessdecision.com>
 */
require_once (pelican_path ( 'Response.Adapter' ));

/**
 * Response adapter for Stb Browser
 *
 * @package Pelican
 * @subpackage Response
 * @author RaphaÃ«l Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Stb extends Pelican_Response_Adapter
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
        return 'cehtml';
    }

    /**
     * @see Pelican_Response_Adapter
     */
    public function process($text)
    {
        $this->stbType = Pelican_Request::getInstance ()->getUserAgentFeature ( 'stb' );
        $this->stbModel = Pelican_Request::getInstance ()->getUserAgentFeature ( 'model' );

        pelican_import ( 'Response.Adapter.Stb.' . ucfirst ( $this->stbType ) );
        $STB = Pelican_Factory::getInstance ( 'Response.Adapter.Stb.' . ucfirst ( $this->stbType ) );

        // extract the Stb tag
        $tags = $this->extractTags ( $text );

        // remplacement de tous les tags trouvés par les pattern associé à l'attribut "type" dans l'adapter adequat
        $text = $STB->replaceTag ( $text, $tags );

        // replace with dedicated STB patterns
        $this->setBody ( $text );
    }

    public function extractTags($html, $charset = 'ISO-8859-1')
    {
        $tag = 'stb';

        $tag_pattern = '@<(?P<tag>' . $tag . ')           # <tag
            (?P<attributes>\s[^>]+)?       # attributes, if any
            \s*/?>                   # /> or just >, being lenient here
            @xsi';

        $attribute_pattern = '@
        (?P<name>\w+)                         # attribute name
        \s*=\s*
        (
            (?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)    # a quoted value
            |                           # or
            (?P<value_unquoted>[^\s"\']+?)(?:\s+|$)           # an unquoted value (terminated by whitespace or EOF)
        )
        @xsi';

        //Find all tags
        if (! preg_match_all ( $tag_pattern, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE )) {
            //Return an empty array if we didn't find anything
            return array ();
        }

        $tags = array ();
        foreach ($matches as $match) {

            //Parse tag attributes, if any
            $attributes = array ();
            if (! empty ( $match ['attributes'] [0] )) {

                if (preg_match_all ( $attribute_pattern, $match ['attributes'] [0], $attribute_data, PREG_SET_ORDER )) {
                    //Turn the attribute data into a name->value array
                    foreach ($attribute_data as $attr) {
                        if (! empty ( $attr ['value_quoted'] )) {
                            $value = $attr ['value_quoted'];
                        } elseif (! empty ( $attr ['value_unquoted'] )) {
                            $value = $attr ['value_unquoted'];
                        } else {
                            $value = '';
                        }

                        //Passing the value through html_entity_decode is handy when you want
                        //to extract link URLs or something like that. You might want to remove
                        //or modify this call if it doesn't fit your situation.
                        $value = html_entity_decode ( $value, ENT_QUOTES, $charset );

                        $attributes [$attr ['name']] = $value;
                    }
                }

            }

            $tag = array ('tag_name' => $match ['tag'] [0], 'offset' => $match [0] [1], 'contents' => ! empty ( $match ['contents'] ) ? $match ['contents'] [0] : '', //empty for self-closing tags
'attributes' => $attributes );
            $tag ['full_tag'] = $match [0] [0];

            $tags [] = $tag;
        }

        return $tags;
    }
}
