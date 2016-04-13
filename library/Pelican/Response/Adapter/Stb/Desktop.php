<?php
pelican_import ( 'Response.Adapter.Stb.Abstract' );
class Pelican_Response_Adapter_Stb_Desktop extends Pelican_Response_Adapter_Stb_Abstract
{
    public function getImage()
    {
        return '<a href="{$href}"><img style="width:{$width};height:{$height} src="{$src}" /></a>';
    }

}
