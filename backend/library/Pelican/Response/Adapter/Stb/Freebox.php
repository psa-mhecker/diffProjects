<?php
pelican_import('Response.Adapter.Stb.Abstract');
class Pelican_Response_Adapter_Stb_Freebox extends Pelican_Response_Adapter_Stb_Abstract
{
    public function getImage()
    {
        return '<table cellspacing="0" cellpadding="0" border="0" width="{$width}" height="{$height}">
    <tr>
        <td><a href="{$href}">
        <table border="2" width="{$width}" height="{$height}" cellpadding="0"
            cellspacing="0" bordercolor="{$bgcolorborderbouton}" valign="middle">
            <tr>
                <td bgcolor="{$bgcolor}" height="{$height}" align="{$align}" valign="middle">
                <font size="2" color="{$color}">{$label}</font>
                </td>
            </tr>
        </table>
        </a></td>
    </tr>
</table>';
    }
}
