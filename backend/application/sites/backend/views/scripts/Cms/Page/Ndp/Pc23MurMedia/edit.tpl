<script type="text/javascript" src="{$jsMurMedia}"></script>
{$form}
<div style="display: none;" id="error-structure">{$msgErreurStructureIncomplete}</div>
<div style="display: none;" id="error-no-medias">{$msgErreurNoMediasShowroom}</div>
<tbody>
<tr>
    <td id="mur-media-{$multi}" colspan="2">
        <div class="head">
            <h2>{'NDP_LIST_STRUCTURE'|t}</h2>
            <small>{'NDP_MSG_ADD_STRUCTURE_DRAG_DROP'|t}</small>
            <ul id="catalogue-{$multi}" class="catalogue">
                {foreach $structures as $structure  }
                    <li class="structure-model" data-id="tpl-{$structure->getName()}-{$multi}">
                        <figure>
                            <figcaption class="structure-name">
                                {$structure->getType()|t} - {$structure->getLabel()}
                                <small>{if $structure->getType() == 'NDP_SQUARE'} {'NDP_IMAGES_ONLY'|t} {else} {'NDP_IMAGES_AND_VIDEOS'|t} {/if}</small>
                            </figcaption>
                            <img src="/images/murmedia/{$structure->getName()}.png"/>

                        </figure>
                    </li>
                {/foreach}
            </ul>
            <div style="clear:both"></div>
        </div>
        <div class="columnable">
            <div id="structure-{$multi}" class="structure-container">
                <h2>{'NDP_MEDIA_WALL'|t}</h2>
                <ol>

                </ol>
            </div>
            <div id="image-{$multi}" class="image-container">
                <h2>{'NDP_DISPONIBLES'|t} <!--<span class="add-image">+</span> --></h2>
                <div class="list-images">

                </div>
            </div>
            <div style="clear:both"></div>
        </div>
    </td>
</tr>
<tr>
    <td colspan="2">
        {literal}
        <script type="text/javascript">

            var config = {};
            config.multi = "{/literal}{$multi}{literal}";
            // récup  de la liste des médias
            config.medias = JSON.parse('{/literal}{$medias}{literal}'.replace(/&quot;/g, '"'));
            config.formats = JSON.parse('{/literal}{$jsonFormat}{literal}'.replace(/&quot;/g, '"'));
            config.structuresValues = JSON.parse('{/literal}{$structuresValues}{literal}'.replace(/&quot;/g, '"'));
            config.msgAlertVideo = "{/literal}{$msgAlertVideo}{literal}";
            config.msgAlertFormat = "{/literal}{$msgAlertFormat}{literal}";

            $(document).ready(function () { // cas  chargement de la zone en ajax
                if (window.initMurMedia) {
                    initMurMedia(config);
                }
            });
            $(window).load(function () { // cas du chargement de la zone apres une sauvegarde
                initMurMedia(config);
            });
        </script>
        {/literal}
        {foreach $structures as $code=>$structure}

            {foreach $structure->getImages() as $idx=>$format}
                <script id="tpl-{$structure->getName()}-{$multi}-{$idx}" type="text/template">
                    {foreach $format['size'] as $size}
                        <div class="crop-container"  data-crop="{$size['formatName']}">
                            <span>{$size['label']}<br/><em>{$size['dim']}</em></span>
                            <a class="first" id="imgdivMEDIA_ID_{ldelim}compteur{rdelim}-{$size['formatId']}"  data-original="" href="" target="_blank">
                                <img src="" style="border : 1px solid #CCCCCC" alt=""/>
                                <small>({ldelim}size{rdelim})</small>
                            </a> &nbsp; &nbsp;
                            <a href="javascript:void(0)"
                               data-path="/library/Pelican/Media/public"
                               data-format="{$size['formatId']}"
                               data-caller-id="imgdivMEDIA_ID_{ldelim}compteur{rdelim}-{$size['formatId']}"
                               class="js-media-editor">
                                <img class="crop-button" src="/library/Pelican/Media/public/images/tool.gif" alt="Modifier l'image" border="0" align="middle">
                            </a>
                        </div>
                    {/foreach}
                </script>
            {/foreach}
        {/foreach}
        {foreach $structures as $code=>$structure}
            <script id="tpl-{$structure->getName()}-{$multi}" type="text/template">

                <li class="structure {$structure->getType()|strtolower} {$structure->getName()}"
                    data-name="{ldelim}multi{rdelim}STRUCTURE[{ldelim}idx{rdelim}]">
                    <h3>{$structure->getType()|t} - {$structure->getLabel()} <span class="delete">X</span></h3>

                    <ul>
                        {foreach $structure->getImages() as $idx=>$format}
                            <li data-container-id="tpl-{$structure->getName()}-{$multi}-{$idx}"></li>
                        {/foreach}
                    </ul>
                    <input type="hidden" name="{ldelim}multi{rdelim}STRUCTURE[{ldelim}idx{rdelim}][type]"
                           value="{$structure->getName()}"/>
                    <input class="structure-order" type="hidden"
                           name="{ldelim}multi{rdelim}STRUCTURE[{ldelim}idx{rdelim}][order]" value="1"/>
                </li>
            </script>
        {/foreach}

        <script id="tpl-video-{$multi}" type="text/template">
            {literal}
                <div class="media-model video-model" id="image-{multi}-{id}">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td id="{multi}divMEDIA_ID_{compteur}" class="first">
                                <a id="imgdivMEDIA_ID_{compteur}" href="{url}" target="_blank"><img src="{cover}"
                                                                                                    style="border : 1px solid #CCCCCC"
                                                                                                    alt="{name}"/></a>&nbsp;&nbsp;
                            </td>
                            <td style="vertical-align:top;">
                                <b>{msgAlertVideo}</b>
                                <input type="hidden" class="image-value" id="{multi}MEDIA_ID_{compteur}"
                                       name="{multi}MEDIA[{compteur}][ID]" value="{id}"/>
                            </td>
                        </tr>
                    </table>
                </div>
            {/literal}
        </script>

        <script id="tpl-media-{$multi}" type="text/template">
            {literal}
                <div class="media-model" id="image-{multi}-{id}">
                    <div id="{multi}divMEDIA_ID_{compteur}">
                        <span class="media-error"></span>
                        <div class="container-block"></div>
                        <div class="original-img">
                            <a id="imgdivMEDIA_ID_{compteur}" data-compteur="{compteur}" data-size="{width}x{height}" href="{url}" data-original="{url}"
                               target="_blank"><img src="{url}" style="border : 1px solid #CCCCCC"
                                                    alt="{name}"/></a>
                            <small> ({width}x{height})</small>
                        </div>
                        <input type="hidden" class="image-value" id="{multi}MEDIA_ID_{compteur}"
                               name="{multi}MEDIA[{compteur}][ID]" value="{id}"/>
                    </div>
                </div>
            {/literal}
        </script>
        <script id="tpl-empty-media-{$multi}" type="text/template">
            {literal}
                <div class="media-model">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td id="{multi}divMEDIA_ID_{compteur}" class="first"></td>
                            <td style="vertical-align:top;">
                                <input type="button" class="button" value="Ajouter"
                                       onclick="popupMedia(this, 'image', '/library/Pelican/Media/public/', document.getElementById('{multi}MEDIA_ID_{compteur}'), '{multi}divMEDIA_ID_{compteur}', '','http:\/\/media.psa-ndp.com\/','',true);"/>
                                &nbsp;
                                <input type="button" class="button" value="Supprimer"
                                       onclick="if(confirm('Confirmez-vous cette suppression ?')) {document.getElementById('{multi}MEDIA_ID_{compteur}').value=''; document.getElementById('{multi}divMEDIA_ID_{compteur}').innerHTML = '';}"/>
                                <input type="hidden" class="image-value" id="{multi}MEDIA_ID_{compteur}"
                                       name="{multi}MEDIA[{compteur}][ID]"/>
                            </td>
                        </tr>
                    </table>
                </div>
            {/literal}
        </script>
    </td>
</tr>
</tbody>

