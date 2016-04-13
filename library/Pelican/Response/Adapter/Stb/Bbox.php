<?php
pelican_import ( 'Response.Adapter.Stb.Abstract' );
class Pelican_Response_Adapter_Stb_Bbox extends Pelican_Response_Adapter_Stb_Abstract
{
    public function getHeader()
    {
        return '    <script language="javascript">

function fctLocalInit()
{
    parent.fctTopExterneFocusPortail();
}

function fctLocalPortailBindkey()
{
    parent.fctTopExterneBkyBindKey("K_RIGHT","frames[\'portail\'].fctLocalGoToPaginationReglage(\'droit\');");
    parent.fctTopExterneBkyBindKey("K_LEFT","frames[\'portail\'].fctLocalGoToPaginationReglage(\'gauche\');");
    parent.fctTopExterneBkyBindKey("K_UP","frames[\'portail\'].fctLocalGoToPaginationReglage(\'haut\');");
    parent.fctTopExterneBkyBindKey("K_DOWN","frames[\'portail\'].fctLocalGoToPaginationReglage(\'bas\');");
    parent.fctTopExterneBkyBindKey("K_OK","frames[\'portail\'].fctLocalGoToPaginationReglage(\'ok\');");

    parent.fctTopExterneBkyBindKey("K_1","window.location.reload();");

}
</script>

<script language="javascript">
function fctLocalInitOnLoadReglage()
{
    parent.fctTopExterneGtfInit();
}

function fctLocalGoToPaginationReglage(action)
{
    if (action == "gauche") {
        parent.fctTopExterneGtfButtonDo("gauche");
    }
    if (action == "droit") {
        parent.fctTopExterneGtfButtonDo("droit");
    }
    if (action =="bas") {
        parent.fctTopExterneGtfButtonDo("bas");
    }
    if (action =="haut") {
        parent.fctTopExterneGtfButtonDo("haut");
    }
    if (action == "ok") {
        parent.fctTopExterneGtfButtonDo("ok");
    }

    return false;
}
</script>
<script language="javascript">
function fctLocalSetTimeout(val)
{
      eval(val);
}

function fctLocalSetTimeoutDelai(val,delai)
{
      return setTimeout(val,delai);
}
</script>    ';
    }

    public function getFooter()
    {
        return '';
    }

    public function getImage()
    {
        return '<table cellspacing="0" cellpadding="0" border=0>
    <tr>
        <td colspan=3 bgcolor="{$bgcolorborderbouton}"><img width="100%"
            height="2" border="0" src="images/{$src}"
            name="haut{$name}"></td>
    </tr>
    <tr>
        <td bgcolor="{$bgcolorborderbouton}"><img width="2"
            height="{$height}" src="images/{$src}"
            name="gauche{$name}"></td>
        <td bgcolor="{$bgcolor}" width="width"
            align="{$align}" style="color: {$color}" class="{$classbouton}">{$label}</td>
        <td bgcolor="{$bgcolorborderbouton}"><img width="2"
            height="{$height}" src="images/{$src}"
            name="droit{$name}"></td>
    </tr>
    <tr>
        <td colspan=3 bgcolor="{$bgcolorborderbouton}"><img width="100%"
            height="2" src="images/{$src}" name="bas{$name}">
        </td>
    </tr>
</table>
<script>
var ArrayAction = new Array();
ArrayAction["ok"] = "{$href}";
ArrayAction["name"] = "{$name}";
ArrayAction["haut"] = "{$haut}";
ArrayAction["bas"] = "{$bas}";
ArrayAction["gauche"] = "{$gauche}";
ArrayAction["droit"] = "{$droit}";
ArrayAction["onfocus"] = "{$onfocus}";
ArrayAction["imageFocus"] = "{$imagefocus}";
ArrayAction["imageNoFocus"] = "{$imagenofocus}";
ArrayAction["onblur"] = "{$onblur}";
ArrayAction["imageCenterFocus"] = "{$imagecenterfocus}";
ArrayAction["imageCenterNoFocus"] = "{$imagecenternofocus}";
parent. fctTopExterneGtfAddButton ("{$posx}","{$posy}",ArrayAction);
ou parent.fctGtfAddButton("0","5",ArrayAction);
</script>';
    }

}
