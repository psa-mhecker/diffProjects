<?php
/**
 * __DESC__.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
pelican_import('Response.Adapter.Mobile.Html5');

/**
 * __DESC__.
 *
 * @author Raphaël Carles <raphael.carles@businessdecision.com>
 */
class Pelican_Response_Adapter_Webkit extends Pelican_Response_Adapter_Html5
{
    /**
     * @see Pelican_Response_Adapter
     */
    public function getMarkup()
    {
        return 'webkit';
    }
}
