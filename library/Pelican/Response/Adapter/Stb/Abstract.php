<?php
abstract class Pelican_Response_Adapter_Stb_Abstract
{
    public static $methods;

    public function __construct()
    {
    }

    public function replaceTag($text, $tags)
    {
        $return = $text;
        foreach ($tags as $tag) {
            $result [$tag ['full_tag']] = $this->getDedicatedTag ( $tag );
        }
        if (is_array ( $result )) {
            $return = strtr ( $text, $result );
        }

        return $return;
    }

    public function getDedicatedTag($tag)
    {
        $method = 'get' . ucfirst ( $tag ['attributes'] ['type'] );
        if (in_array ( $method, $this->getMethods () )) {
            $tpl = call_user_func_array ( array ($this, $method ), array () );
            $return = $tpl;
            foreach ($tag ['attributes'] as $key => $value) {
                $return = str_replace ( '{$' . $key . '}', $value, $return );
            }
        } else {
            $return = '';
        }

        return $return;
    }

    public function getMethods()
    {
        if (empty ( $this->methods )) {
            $this->methods = get_class_methods ( $this );
        }

        return $this->methods;
    }
}
