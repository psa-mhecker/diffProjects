<!DOCTYPE html>
<!--[if lt IE 8]><html lang="fr" class="ie ie7 no-js"><![endif]-->
<!--[if IE 8]><html lang="fr" class="ie ie8 no-js"><![endif]-->
<!--[if IE 9]><html lang="fr" class="ie ie9 no-js"><![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <title>Titre par défaut</title>
    <meta name="description" content="Meta description par défaut">
    <meta name="keywords" content="Meta keywords par défaut">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0" />
    <link rel="shortcut icon" href="{$path_resources_ds}/img/favicon.ico">
    <link rel="stylesheet" href="{$path_resources_ds}/bower_components/slick-carousel/slick/slick.css">
    <link rel="stylesheet" href="{$path_resources_ds}/bower_components/slick-carousel/slick/slick-theme.css">
    <link rel="stylesheet" href="{$path_resources_ds}/css/main.css">
    <!--[if gte  IE 9]>
    <script type="text/javascript" src="{$path_resources_ds}/bower_components/respond/dest/respond.min.js"></script>
    <script type="text/javascript" src="{$path_resources_ds}/bower_components/classlist/classList.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="{$path_resources_ds}/bower_components/modernizer/modernizr.js"></script>
</head>
<body>

<div class="container">
    <div class="content">
        <div class="row addpadding">
            <div class="col-md-12">
                <div id="dynamic-content">
                    <div class="slice-df41">
                        {if $notification_text}
                            <div class="row nopadding">
                                <div class="col-md-12 notification btnRight">
                                    <div class="col-md-9">
                                        <span class="notification-txt">{'CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION'|t|replace:'##TEINTE##':$teinteDefault.label}</span>
                                    </div>
                                    <div class="col-md-3 notBtnright">
                                        <a href="#{$teinteDefault.id}-ancre" class="cta btn cta-default cta-xs ancre">
                                            <span>{'CONFIGURATOR_DF41_NOTIFICATION_PRE_SELECTION_SEE_BTN'|t}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {/if}

                        <p class="nb">
                            <span class="kit-color">{$nbTeintes}</span>
                            {if $nbTeintes == 1}{'CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLOR'|t}{else}{'CONFIGURATOR_DF41_NUMBER_OF_AVALAIBLE COLORS'|t}{/if}
                        </p>

                        <div class="expands">
                            {foreach $teintesCompatibles key=kcategory item=lookFeature}
                                {assign var=nb_element_tosee value=$lookFeature|count}
                                {assign var=offset_tosee value=0}

                                {while $offset_tosee < ($lookFeature|count)}
                                <div class="vignettes-toggle">
                                    <p class="title-type">{"CONFIGURATOR_DF41_LOOK_FEATURE_$kcategory"|t}</p>
                                    <div class="row">

                                        {for $var=0 to 3}
                                        {if isset($lookFeature[$offset_tosee + $var])}

                                            <div class="col-md-3 {$lookFeature[$offset_tosee + $var].id}-ancre">
                                                <div class="vignette_bloc pos{$lookFeature[$offset_tosee + $var].id} ">
                                                    <div class="img-vignette">
                                                        <img src="{$base_url_v3d}{$lookFeature[$offset_tosee + $var].id}.png" class="img-sprite-vign" alt="img">
                                                    </div>

                                                    <div class="badges_vi">
                                                        {if $lookFeature[$offset_tosee + $var].isNew == '1'}<div class="badge badge-nouveau marg">{'CONFIGURATOR_DF41_BADGE_NEW'|t}</div>{/if}
                                                    </div>

                                                    <div class="title_vignette">
                                                          <span class="text-vignette-int">
                                                            {$lookFeature[$offset_tosee + $var].label}
                                                          </span>
                                                    </div>

                                                    <div class="bloc-marg"></div>

                                                    <div class="elemnt_input input-radio">
                                                        <input type="radio"  name="speciale" value="" id="radio{$lookFeature[$offset_tosee + $var].id}" class="form-control">
                                                        <label for="radio{$lookFeature[$offset_tosee + $var].id}" class="selections">
                                                            <div>
                                                                <span class="label">{'CONFIGURATOR_DF41_COLOR_SELECT_CHECKBOX'|t}</span>
                                                                <span class="radio_btn"><i class="input--check"></i></span>
                                                            </div>
                                                        </label>
                                                    </div>

                                                    <div class="price_check text">
                                                        <p>
                                                          <span>{if $lookFeature[$offset_tosee + $var].Price.basePrice != '0.00'}{t('CONFIGURATOR_CF41_A_PARTIR_DE')}{/if}</span><br/>
                                                          {if $lookFeature[$offset_tosee + $var].Price.basePrice == '0.00'} {t('CONFIGURATOR_CF41_INCLUS')} {else}{$lookFeature[$offset_tosee + $var].Price.basePrice}{$paramsGlocal.DEVISE_PAYS} {if $paramsGlocal.ACTIVATION_PRIX_MENSUALISE}/{t('CONFIGURATOR_CF41_MOIS')}{/if}{$paramsGlocal.TYPE_TAXE}<sup>*</sup>{/if}

                                                        </p>
                                                    </div>

                                                    {if $biton == '1'}
                                                        <div class="color-list" style="background-color:#ddd;color:#ccc">
                                                            <p>TODO {'CONFIGURATOR_DF41_ROOF_AVALAIBLE'|t} (from GDV)</p>
                                                            <ul>
                                                                <li data-for="teinte-0" class="on teinte-0">
                                                                    <img alt="" src="http://dummyimage.com/180x135/f38330&amp;text=+">
                                                                </li>
                                                                <li data-for="teinte-1" class=" teinte-1">
                                                                    <img alt="" src="http://dummyimage.com/180x135/0d5364&amp;text=+">
                                                                </li>
                                                                <li data-for="teinte-2" class=" teinte-2">
                                                                    <img alt="" src="http://dummyimage.com/180x135/dcdcdc&amp;text=+">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    {else}
                                                        <div class="color-list">
                                                            <p>{'CONFIGURATOR_DF41_ROOF_AVALAIBLE'|t}</p>
                                                            <ul>
                                                                <li class="on teinte-0" data-for="teinte-0">
                                                                    <img src="http://dummyimage.com/180x135/f38330&text=+" alt="" />
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    {/if}

                                                    <div class="cta cta-expand-2 cta-details">
                                                        <span class="span_detail"> {'CONFIGURATOR_DF41_COLOR_MORE_DETAILS'|t}</span>
                                                        <span class="tit-toggle"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}
                                        {/for}
                                    </div>
                                    <div class="row list-vignette-toggle">

                                        {for $var=0 to 3}
                                        {if isset($lookFeature[$offset_tosee + $var])}

                                            <div class="col-md-12">
                                                <div class="cont-toggle  arrow_top pos{ $var + 1}" data-index-toggle="pos{$lookFeature[$offset_tosee + $var].id}">
                                                    <div class="clearfix">
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <figure class="pictures">
                                                                    <img src="{$base_url_v3d_image}&view={$angleView}&color={$lookFeature[$offset_tosee + $var].id}" alt="Blanc perle">
                                                                </figure>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <p class="sideTitle">{'CONFIGURATOR_DF41_CAR_BASE_COLOR'|t}</p>
                                                                <p class="sideLabel">{$lookFeature[$offset_tosee + $var].label}</p>
                                                                <div class="price_check text">
                                                                    <p>
                                                                      <span>{if $lookFeature[$offset_tosee + $var].Price.basePrice != '0.00'}{t('CONFIGURATOR_CF41_A_PARTIR_DE')}{/if}</span><br/>
                                                                      {if $lookFeature[$offset_tosee + $var].Price.basePrice == '0.00'} {t('CONFIGURATOR_CF41_INCLUS')} {else}{$lookFeature[$offset_tosee + $var].Price.basePrice}{$paramsGlocal.DEVISE_PAYS} {if $paramsGlocal.ACTIVATION_PRIX_MENSUALISE}/{t('CONFIGURATOR_CF41_MOIS')}{/if}{$paramsGlocal.TYPE_TAXE}<sup>*</sup>{/if}

                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        {if $biton == '1'}
                                                            <div class="row with-slide with-select">
                                                                <div class="col-md-8">
                                                                    <p class="slideTitle">{'CONFIGURATOR_DF41_ROOF_AVALAIBLE'|t} :</p>

                                                                    <div class="x-bloc_carroussel">
                                                                        <ul class="multiple-items slideOn">
                                                                            <li class="item-slide on teinte-0" data-for="teinte-0"><img data-slide-index="teinte-0" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view={$angleView}" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view={$angleView}" alt="img"></li>
                                                                            <li class="item-slide  teinte-1" data-for="teinte-1"><img data-slide-index="teinte-1" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=002" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=002" alt="img"></li>
                                                                            <li class="item-slide  teinte-2" data-for="teinte-2"><img data-slide-index="teinte-2" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=003" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=003" alt="img"></li>
                                                                            <li class="item-slide  teinte-3" data-for="teinte-3"><img data-slide-index="teinte-3" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=004" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=004" alt="img"></li>
                                                                            <li class="item-slide  teinte-4" data-for="teinte-4"><img data-slide-index="teinte-4" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=005" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=005" alt="img"></li>

                                                                        </ul>
                                                                    </div>


                                                                </div>
                                                                <div class="col-md-4 plus">
                                                                    <p class="sideTitle">{'CONFIGURATOR_DF41_ROOF_COLOR'|t}</p>
                                                                    <p class="sideLabel">{$lookFeature[$offset_tosee + $var].label}</p>
                                                                    <div class="price_check text">
                                                                        <p>
                                                                            {'CONFIGURATOR_DF41_PRICE_INCLUDED'|t} <sup></sup>                              </p>
                                                                    </div>
                                                                    <div class="cta cta-primary cta-sm selections">
                                                                        <span>{'CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN'|t}</span>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        {else}
                                                            <div class="row with-slide with-select">
                                                                <div class="col-md-8">
                                                                    <p class="slideTitle">{'CONFIGURATOR_DF41_ROOF_AVALAIBLE'|t} :</p>

                                                                    <div class="x-bloc_carroussel">
                                                                        <ul class="multiple-items slideOn">
                                                                            <li class="item-slide on teinte-0" data-for="teinte-0"><img data-slide-index="teinte-0" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view={$angleView}" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view={$angleView}" alt="img"></li>
                                                                            <li class="item-slide  teinte-1" data-for="teinte-1"><img data-slide-index="teinte-1" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=002" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=002" alt="img"></li>
                                                                            <li class="item-slide  teinte-2" data-for="teinte-2"><img data-slide-index="teinte-2" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=003" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=003" alt="img"></li>
                                                                            <li class="item-slide  teinte-3" data-for="teinte-3"><img data-slide-index="teinte-3" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=004" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=004" alt="img"></li>
                                                                            <li class="item-slide  teinte-4" data-for="teinte-4"><img data-slide-index="teinte-4" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=005" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=005" alt="img"></li>

                                                                        </ul>
                                                                    </div>


                                                                </div>
                                                                <div class="col-md-4 plus">
                                                                    <p class="sideTitle">{'CONFIGURATOR_DF41_ROOF_COLOR'|t}</p>
                                                                    <p class="sideLabel">{$lookFeature[$offset_tosee + $var].label}</p>
                                                                    <div class="price_check text">
                                                                        <p>
                                                                            {'CONFIGURATOR_DF41_PRICE_INCLUDED'|t} <sup></sup>                              </p>
                                                                    </div>
                                                                    <div class="cta cta-primary cta-sm selections">
                                                                        <span>{'CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN'|t}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        {/if}

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vehicula iaculis vulputate. Sed nulla velit, maximus et diam tincidunt, ornare vestibulum dui. Integer imperdiet, massa ac dapibus mollis, ligula velit ornare eros, quis hendrerit
                                                                    tellus purus et est. Suspendisse potenti.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        {/if}
                                        {/for}
                                        {assign var=offset_tosee value=$offset_tosee+4}
                                    </div>
                                </div>
                                {/while}
                            {/foreach}

                        </div>

                        <div class="notification ">
                            <span class="notification-txt t-incompatible">{'CONFIGURATOR_DF41_NOTIFICATION_INCOMPATIBLE_COLOR'|t}</span>
                        </div>

                        <div class="more-choice">
                            <div class="more-choice-head">
                                {'CONFIGURATOR_DF41_OTHER_EXISTING_COLORS'|t}
                                <span class="tit-toggle"></span>
                            </div>
                            <div class="more-choice-content expands">
                              {foreach $teintesIncompatibles key=kcategory item=lookFeature}
                              {assign var=nb_element_tosee value=$teintesIncompatibles|count}
                              {assign var=offset_tosee value=0}

                              {while $offset_tosee < ($lookFeature|count)}

                              <div class="vignettes-toggle">
                                  <p class="title-type">{"CONFIGURATOR_DF41_LOOK_FEATURE_$kcategory"|t}</p>
                                  <div class="row">
                                      {for $var=0 to 3}
                                      {if isset($lookFeature[$offset_tosee + $var])}
                                          <div class="col-md-3 {$lookFeature[$offset_tosee + $var].id}">
                                              <div class="vignette_bloc pos{$lookFeature[$offset_tosee + $var].id} ">
                                                  <div class="img-vignette">
                                                      <img src="{$base_url_v3d}{$lookFeature[$offset_tosee + $var].id}.png" class="img-sprite-vign" alt="Tissu Mica Grey Ambiance Stone Grey">
                                                  </div>

                                                  <div class="badges_vi">
                                                  </div>

                                                  <div class="title_vignette">
                                                        <span class="text-vignette-int">
                                                          {$lookFeature[$offset_tosee + $var].label}
                                                        </span>
                                                  </div>

                                                  <div class="bloc-marg"></div>

                                                  <div class="elemnt_input input-radio">
                                                      <input type="radio"  name="speciale" value="" id="radio0010c" class="form-control">
                                                      <label for="radio0010c" class="selections">
                                                          <div>
                                                              <span class="label">{'CONFIGURATOR_DF41_COLOR_SELECT_CHECKBOX'|t}</span>
                                                              <span class="radio_btn"><i class="input--check"></i></span>
                                                          </div>
                                                      </label>
                                                  </div>

                                                  <div class="price_check text">
                                                      <p>
                                                          {'CONFIGURATOR_DF41_PRICE_INCLUDED'|t} <sup></sup>							</p>
                                                  </div>


                                                  <div class="cta cta-expand-2 cta-details">
                                                      <span class="span_detail"> {'CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN'|t}</span>
                                                      <span class="tit-toggle"></span>
                                                  </div>
                                              </div>
                                          </div>
                                      {/if}
                                      {/for}
                                  </div>
                                  <div class="row list-vignette-toggle">
                                      {for $var=0 to 3}
                                      {if isset($lookFeature[$offset_tosee + $var])}

                                          <div class="col-md-12">
                                              <div class="cont-toggle  arrow_top pos{ $var + 1}" data-index-toggle="pos{$lookFeature[$offset_tosee + $var].id}">
                                                  <div class="clearfix">
                                                      <div class="row">
                                                          <div class="col-md-8">
                                                              <figure class="pictures">
                                                                  <img src="{$base_url_v3d_image}&view={$angleView}&color={$lookFeature[$offset_tosee + $var].id}" alt="Blanc perle">
                                                              </figure>
                                                          </div>
                                                          <div class="col-md-4">
                                                              <p class="sideTitle">{'CONFIGURATOR_DF41_CAR_BASE_COLOR'|t}</p>
                                                              <p class="sideLabel">{$lookFeature[$offset_tosee + $var].label}</p>
                                                              <div class="price_check text">
                                                                  <p>
                                                                      {'CONFIGURATOR_DF41_PRICE_INCLUDED'|t} <sup></sup>                              </p>
                                                              </div>
                                                          </div>
                                                      </div>

                                                      {if $biton == '1'}
                                                          <div class="row with-slide with-select">
                                                              <div class="col-md-8">
                                                                  <p class="slideTitle">{'CONFIGURATOR_DF41_ROOF_AVALAIBLE'|t} :</p>

                                                                  <div class="x-bloc_carroussel">
                                                                      <ul class="multiple-items slideOn">
                                                                          <li class="item-slide on teinte-0" data-for="teinte-0"><img data-slide-index="teinte-0" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view={$angleView}" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view={$angleView}" alt="img"></li>
                                                                          <li class="item-slide  teinte-1" data-for="teinte-1"><img data-slide-index="teinte-1" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=002" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=002" alt="img"></li>
                                                                          <li class="item-slide  teinte-2" data-for="teinte-2"><img data-slide-index="teinte-2" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view=003" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view=003" alt="img"></li>
                                                                      </ul>
                                                                  </div>


                                                              </div>
                                                              <div class="col-md-4 plus">
                                                                  <p class="sideTitle">{'CONFIGURATOR_DF41_ROOF_COLOR'|t}</p>
                                                                  <p class="sideLabel">{$lookFeature[$offset_tosee + $var].label}</p>
                                                                  <div class="price_check text">
                                                                      <p>
                                                                          {'CONFIGURATOR_DF41_PRICE_INCLUDED'|t} <sup></sup>                              </p>
                                                                  </div>
                                                                  <div class="cta cta-primary cta-sm selections">
                                                                      <span>{'CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN'|t}</span>
                                                                  </div>
                                                              </div>
                                                          </div>


                                                      {else}
                                                          <div class="row with-slide with-select">
                                                              <div class="col-md-8">
                                                                  <p class="slideTitle">{'CONFIGURATOR_DF41_ROOF_AVALAIBLE'|t} :</p>

                                                                  <div class="x-bloc_carroussel">
                                                                      <ul class="multiple-items slideOn">
                                                                          <li class="item-slide on teinte-0" data-for="teinte-0"><img data-slide-index="teinte-0" src="{$base_url_v3d_small_image}&color={$lookFeature[$offset_tosee + $var].id}&view={$angleView}" data-target-src="{$base_url_v3d_image}&color={$lookFeature[$offset_tosee + $var].id}&view={$angleView}" alt="img"></li>
                                                                      </ul>
                                                                  </div>


                                                              </div>
                                                              <div class="col-md-4 plus">
                                                                  <p class="sideTitle">{'CONFIGURATOR_DF41_ROOF_COLOR'|t}</p>
                                                                  <p class="sideLabel">{$lookFeature[$offset_tosee + $var].label}</p>
                                                                  <div class="price_check text">
                                                                      <p>
                                                                          {'CONFIGURATOR_DF41_PRICE_INCLUDED'|t} <sup></sup>                              </p>
                                                                  </div>
                                                                  <div class="cta cta-primary cta-sm selections">
                                                                      <span>{'CONFIGURATOR_DF41_MORE_DETAILS_COLOR_SELECT_BTN'|t}</span>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                      {/if}


                                                      <div class="row">
                                                          <div class="col-md-12">
                                                              <p class="desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vehicula iaculis vulputate. Sed nulla velit, maximus et diam tincidunt, ornare vestibulum dui. Integer imperdiet, massa ac dapibus mollis, ligula velit ornare eros, quis hendrerit
                                                                  tellus purus et est. Suspendisse potenti.</p>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      {/if}
                                      {/for}
                                      {assign var=offset_tosee value=$offset_tosee+4}
                                  </div>
                              </div>
                              {/while}
                              {/foreach}
                            </div>
                        </div>
                        <div class="notification ">
                            <span class="notification-txt t-incompatible">{'CONFIGURATOR_DF41_NOTIFICATION_INCOMPATIBLE_COLOR'|t}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="panier">

    </div>
    {literal}<div class="popin_overlay" data-jsobj="[ { 'obj':'iso-popin' } ]"></div>{/literal}
</div>

<script type="text/javascript" src="{$path_resources_ds}/bower_components/jquery/dist/jquery.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/bower_components/slick-carousel/slick/slick.min.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/bower_components/jquery-lazy/jquery.lazy.min.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/bower_components/js-polyfills/typedarray.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/bower_components/three.js/three.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/bower_components/jquery-selectBox/jquery.selectBox.min.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/bower_components/underscore/underscore-min.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/iso.accordeonDs.js"></script>

<script type="text/javascript" src="{$path_resources_ds}/js/pubsub.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/iso.expand.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/iso.expandLame.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/core.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/iso.sitecontainer.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/main.js"></script>

<script type="text/javascript" src="{$path_resources_ds}/js/common/iso.saveconfig.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/iso.form.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/iso.popin.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/scroll.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/iso.infobulle.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/video-js.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/common/comparatorTable.js"></script>


<script type="text/javascript" src="{$path_resources_ds}/js/tranches/f02/Projector.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/f02/CanvasRenderer.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/f02/f02.sim.namespace.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/f02/f02.sim.pointofinterest.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/f02/f02.sim.cube.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/f02/f02.sim.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/f02/f02.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/dc87.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/dc97.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/df56.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/df57.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/df58.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/df53.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/df43.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/df90.js"></script>
<script type="text/javascript" src="{$path_resources_ds}/js/tranches/df42.js"></script>


</body>
</html>
