<div class="content">
    <div class="addpadding">
        <div id="dynamic-content">
            <div class="slice-cf41">
                <div class="col-md-12 notifications">
                    <div class="info info--orange"></div>
                    <span class="not-text-1">{t('CF41_NOUS_VOUS_PRESENTONS_LA_FINITION')} <a href="#{$teintesCompatibles['0M'][$idPreSelect]['id']}-ancre" class="ancre">{$teintesCompatibles['0M'][$idPreSelect]['label']}</a></span><br/>
                    <span class="not-text-2">{t('CF41_VOUS_POUVEZ_SELECTIONNER_UNE_AUTRE_TEINTE')}.</span>
                </div>
                <p class="nb">
                    <span class="kit-color">{$nbTeintesCompatibles}</span>
                    <span class="txt-teinte">{if $nbTeintesCompatibles > 1}{t('CONFIGURATOR_CF41_TEINTES_DISPONIBLES')}{else}{t('CONFIGURATOR_CF41_TEINTE_DISPONIBLE')}{/if}</span>
                </p>

                <div class="expands">
                    <div class="vignettes-toggle">
                        {foreach $teintesCompatibles as $category=>$exteriorFeature}
                        <p class="title-type">{$category} (from GDV) :</p>
                        <div class="row">
                            {foreach $exteriorFeature as $id => $teinte}
                            <div class="vignette col-md-4 {$teinte.id}-ancre ">
                                <div class="vignette_bloc pos{$id} {if $teinte.id == $idPreSelect} selected {/if}">
                                  <div class="elemnt_input input-radio text-black">
                                      <input type="radio" {if $id == $idPreSelect} checked="checked" {/if} name="speciale" value="" id="radio{$teinte.id}" class="form-control">
                                      <label for="radio{$teinte.id}" class="selections">
                                      <span class="radio_btn"><i class="input--check"></i></span>
                                      <span class="label">{$teinte.label}</span>
                                      </label>
                                  </div>
                                  <div class="img-vignette">
                                    <img width="279" height="186" src="http://visuel3d.citroen.com/V3DCentral/{$idModelPreSelect}/{$idBodystylePreSelect}/ThumbnailsV2/Colors/th_{$teinte.id}.png" alt="{$teinte.label}">
                                  </div>
                                  <div class="bloc-marg"></div>

                                  <div class="price_check text">
                                      <p>
                                          <span>{if $teinte.Price.basePrice != '0.00'}{t('CONFIGURATOR_CF41_A_PARTIR_DE')}{/if}</span><br/>
                                          {if $teinte.Price.basePrice == '0.00'} {t('CONFIGURATOR_CF41_INCLUS')} {else}{$teinte.Price.basePrice}{$paramsGlocal.DEVISE_PAYS} {if $paramsGlocal.ACTIVATION_PRIX_MENSUALISE}/{t('CONFIGURATOR_CF41_MOIS')}{/if}{$paramsGlocal.TYPE_TAXE}<sup>*</sup>{/if}

                                      </p>
                                  </div>

                                  <!--<div class="color-list">
                                      <p>TODO Choisir un AirBump (from GDV)</p>
                                      <ul>
                                          <li class="on teinte-0" data-for="teinte-0">
                                              <img src="../../../img/teintes/001.jpg" alt="" />
                                          </li>
                                          <li class=" teinte-1" data-for="teinte-1">
                                              <img src="../../../img/teintes/002.jpg" alt="" />
                                          </li>
                                          <li class=" teinte-2" data-for="teinte-2">
                                              <img src="../../../img/teintes/003.jpg" alt="" />
                                          </li>
                                          <li class=" teinte-3" data-for="teinte-3">
                                              <img src="../../../img/teintes/003.jpg" alt="" />
                                          </li>
                                      </ul>
                                </div>-->

                                <div class="cta cta-expand-2 cta-details">
                                    <span class="span_detail">
                                        <em class="on">{t('CONFIGURATOR_CF41_PLUS')}<br/> {t('CONFIGURATOR_CF41_DE')} {t('CONFIGURATOR_CF41_DETAILS')}</em>
                                        <em class="off">{t('CONFIGURATOR_CF41_MASQUER')}<br/> {t('CONFIGURATOR_CF41_LES')} {t('CONFIGURATOR_CF41_DETAILS')}</em>
                                    </span>
                                    <span class="tit-expand"></span>
                                </div>
                            </div>
                        </div>
                        {/foreach}

                    </div>
                    {/foreach}
                    <div class="row list-vignette-toggle">
                        {foreach $teintesCompatibles as $category=>$exteriorFeature}
                            {foreach $exteriorFeature as $id=>$teinte}
                            <div class="col-md-12">
                                <div class="cont-toggle arrow_top pos{$teinte.id}" data-index-toggle="pos{$teinte.id}">
                                    <div class="clearfix">
                                        <div class="row with-slide with-select">
                                            <div class="col-md-8">
                                                <figure class="pictures">
                                                    <img src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=2&format=png&view={$angleView}&version={$idVersionPreSelect}&width=512&color={$teinte.id}" alt="{$teinte.label}">

                                                </figure>

                                                <!--<p class="slideTitle">TODO Sélectionnez votre AirBump (from GDV)</p>-->

                                                <div class="x-bloc_carroussel">
                                                    <ul class="multiple-items slideOn">

                                                        <li class="item-slide on teinte-1" data-for="teinte-1"><img data-slide-index="teinte-1" src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view={$angleView}&version={$idVersionPreSelect}&width=512&color={$teinte.id}" data-target-src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view={$angleView}&version={$idVersionPreSelect}&width=154&color={$teinte.id}" alt="img"></li>
                                                        <li class="item-slide teinte-2" data-for="teinte-2"><img data-slide-index="teinte-2" src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=002&version={$idVersionPreSelect}&width=512&color={$teinte.id}" data-target-src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=002&version={$idVersionPreSelect}&width=154&color={$teinte.id}" alt="img"></li>
                                                        <li class="item-slide teinte-3" data-for="teinte-3"><img data-slide-index="teinte-3" src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=003&version={$idVersionPreSelect}&width=512&color={$teinte.id}" data-target-src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=003&version={$idVersionPreSelect}&width=154&color={$teinte.id}" alt="img"></li>
                                                        <li class="item-slide teinte-4" data-for="teinte-4"><img data-slide-index="teinte-4" src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=001&version={$idVersionPreSelect}&width=512&color={$teinte.id}" data-target-src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=001&version={$idVersionPreSelect}&width=154&color={$teinte.id}" alt="img"></li>

                                                    </ul>
                                                </div>


                                                <p class="desc">TODO Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vehicula iaculis vulputate. Sed nulla velit, maximus et diam tincidunt, ornare vestibulum dui. Integer imperdiet, massa ac dapibus mollis, ligula velit ornare eros, quis hendrerit
                                                tellus purus et est. Suspendisse potenti.(from GDV)</p>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="side ">
                                                    <div class="right-top">
                                                        <p class="sideTitle">  {$teinte.label} </p>
                                                        <div class="price_check text">
                                                        <p>
                                                            <span class="sideLabel">{if $teinte.Price.basePrice != '0.00'}{t('CONFIGURATOR_CF41_A_PARTIR_DE')}{/if}</span>
                                                            {if $teinte.Price.basePrice == '0.00'} {t('CONFIGURATOR_CF41_INCLUS')} {else}{$teinte.Price.basePrice}{$paramsGlocal.DEVISE_PAYS} {if $paramsGlocal.ACTIVATION_PRIX_MENSUALISE}/{t('CONFIGURATOR_CF41_MOIS')}{/if}{$paramsGlocal.TYPE_TAXE}<sup>*</sup>{/if}

                                                        </p>
                                                        </div>
                                                        <span class="plus"></span>
                                                    </div>
                                                    <!--<div class="right-bottom">
                                                        <p class="sideTitle">TODO Airbump<br/> Chocolat (from GDV)</p>
                                                        <div class="price_check text">
                                                        <p>
                                                            <span class="sideLabel">A  partir de</span>                      199,68€ /mois<sup>*</sup>
                                                        </p>
                                                        </div>
                                                    </div>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {/foreach}
                        {/foreach}
                    </div>
                </div>
            </div>
            {if $teintesIncompatibles|count > 0}
            <div class="row nopadding">
                <div class="col-md-12 notifications">
                    <div class="info info--orange"></div>
                    <span class="not-text-1">{t('CONFIGURATOR_CF41_AUTRES_TEINTES')}.</span>
                </div>
            </div>
            {/if}
            {if $teintesIncompatibles|count > 0}
            <div class="more-choice">
                <div class="more-choice-head">
                    {t('CONFIGURATOR_CF41_SEE_AUTRES_TEINTES')}
                    <span class="tit-toggle"></span>
                </div>

                <div class="more-choice-content expands">
                    <p class="title-type">TODO Peinture nacrée (from GDV)</p>
                    <div class="vignettes-toggle">
                        <div class="row">
                              {foreach $teintesIncompatibles as $id=>$teinte}
                            <div class="vignette col-md-4 {$teinte.id}-ancre monoton">
                                <div class="vignette_bloc pos{$teinte.id} ">
                                    <div class="elemnt_input input-radio text-black">
                                        <input type="radio"  name="speciale" value="" id="radio{$teinte.id}" class="form-control">
                                        <label for="radio{$teinte.id}" class="selections">
                                            <span class="radio_btn"><i class="input--check"></i></span>
                                            <span class="label">{$teinte.label}</span>
                                        </label>
                                    </div>
                                    <div class="img-vignette">
                                        <img width="279" height="186" class="img-sprite-vign" src="http://visuel3d.citroen.com/V3DCentral/{$idModelPreSelect}/{$idBodystylePreSelect}/ThumbnailsV2/Colors/th_{$teinte.id}.png" alt="{$teinte.label}">

                                    </div>
                                    <div class="bloc-marg"></div>

                                    <div class="price_check text">
                                        <p>
                                          {if $teinte.Price.basePrice == '0.00'} {t('CONFIGURATOR_CF41_INCLUS')} {else}{$teinte.Price.basePrice}{$paramsGlocal.DEVISE_PAYS} {if $paramsGlocal.ACTIVATION_PRIX_MENSUALISE}/{t('CONFIGURATOR_CF41_MOIS')}{/if}{$paramsGlocal.TYPE_TAXE}<sup>*</sup>{/if}
                                        </p>
                                    </div>


                                    <div class="cta cta-expand-2 cta-details">
                                        <span class="span_detail">
                                            <em class="on">{t('CONFIGURATOR_CF41_PLUS')}<br/> {t('CONFIGURATOR_CF41_DE')} {t('CONFIGURATOR_CF41_DETAILS')}</em>
                                            <em class="off">{t('CONFIGURATOR_CF41_MASQUER')}<br/> {t('CONFIGURATOR_CF41_LES')} {t('CONFIGURATOR_CF41_DETAILS')}</em>
                                        </span>
                                        <span class="tit-expand"></span>
                                    </div>
                                </div>
                            </div>
                            {/foreach}

                          </div>
                      <div class="row list-vignette-toggle">
                            {foreach $teintesIncompatibles as $id=>$teinte}
                          <div class="col-md-12">
                              <div class="cont-toggle arrow_top pos{$teinte.id}" data-index-toggle="pos{$teinte.id}">
                                  <div class="clearfix">
                                      <div class="row with-slide with-select">
                                          <div class="col-md-8">
                                              <figure class="pictures">
                                                <img src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=2&format=png&view={$angleView}&version={$idVersionPreSelect}&width=512&color={$teinte.id}" alt="{$teinte.label}">
                                              </figure>

                                              <p class="slideTitle">TODO Velit ornare eros, quis hendr (from GDV)</p>


                                              <div class="x-bloc_carroussel">
                                                  <ul class="multiple-items slideOn">

                                                      <li class="item-slide on teinte-1" data-for="teinte-1"><img data-slide-index="teinte-1" src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view={$angleView}&version={$idVersionPreSelect}&width=512&color={$teinte.id}" data-target-src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view={$angleView}&version={$idVersionPreSelect}&width=154&color={$teinte.id}" alt="img"></li>
                                                      <li class="item-slide teinte-2" data-for="teinte-2"><img data-slide-index="teinte-2" src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=002&version={$idVersionPreSelect}&width=512&color={$teinte.id}" data-target-src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=002&version={$idVersionPreSelect}&width=154&color={$teinte.id}" alt="img"></li>
                                                      <li class="item-slide teinte-3" data-for="teinte-3"><img data-slide-index="teinte-3" src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=003&version={$idVersionPreSelect}&width=512&color={$teinte.id}" data-target-src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=003&version={$idVersionPreSelect}&width=154&color={$teinte.id}" alt="img"></li>
                                                      <li class="item-slide teinte-4" data-for="teinte-4"><img data-slide-index="teinte-4" src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=001&version={$idVersionPreSelect}&width=512&color={$teinte.id}" data-target-src="http://visuel3d.citroen.com/V3DImage.ashx?client=websimulator&ratio=1&format=png&view=001&version={$idVersionPreSelect}&width=154&color={$teinte.id}" alt="img"></li>

                                                  </ul>
                                              </div>

                                              <p class="desc">TODO Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam vehicula iaculis vulputate. Sed nulla velit, maximus et diam tincidunt, ornare vestibulum dui. Integer imperdiet, massa ac dapibus mollis, ligula velit ornare eros, quis hendrerit
                                              tellus purus et est. Suspendisse potenti. from (GDV)</p>
                                          </div>
                                          <div class="col-md-4">
                                              <div class="side simple">
                                                  <div class="right-top">
                                                      <p class="sideTitle">  {$teinte.label} </p>
                                                      <div class="price_check text">
                                                      <p>
                                                          <span class="sideLabel">{if $teinte.Price.basePrice != '0.00'}{t('CONFIGURATOR_CF41_A_PARTIR_DE')}{/if}</span>
                                                          {if $teinte.Price.basePrice == '0.00'} {t('CONFIGURATOR_CF41_INCLUS')} {else}{$teinte.Price.basePrice}{$paramsGlocal.DEVISE_PAYS} {if $paramsGlocal.ACTIVATION_PRIX_MENSUALISE}/{t('CONFIGURATOR_CF41_MOIS')}{/if}{$paramsGlocal.TYPE_TAXE}<sup>*</sup>{/if}

                                                      </p>
                                                      </div>
                                                  </div>
                                                  <!--<div class="right-bottom">
                                                      <p class="sideTitle">TODO Airbump<br/> Chocolat (from GDV)</p>
                                                      <div class="price_check text">
                                                      <p>
                                                          <span class="sideLabel">A  partir de</span>                      199,68€ /mois<sup>*</sup>
                                                      </p>
                                                      </div>
                                                  </div>-->
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          {/foreach}
                      </div>
                  </div>
              </div>
          </div>
          {/if}
          <div class="row nopadding">
            <div class="col-md-12 mentions-legale">
                <span class="mention-text"> {if $paramsGlocal.ACTIVATION_PRIX_MENSUALISE}{else}TODO WS Financement{/if}.</span>
            </div>
          </div>
      </div>

  </div>
</div>
</div>
