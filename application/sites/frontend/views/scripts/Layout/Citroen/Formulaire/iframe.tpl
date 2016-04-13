
{literal}
<!DOCTYPE html>
<html><head>
    <meta http-equiv="Content-Type" content="text/html, charset=UTF-8"/>
    <link href="/dcrv2/css/desktop_common.css" type="text/css" rel="stylesheet">
    <link href="/dcrv2/css/desktop_citroen{/literal}{$cssArial}{literal}.css" type="text/css" rel="stylesheet">
	{/literal}{if !$bShowroom}{literal}<link href="{/literal}{$formds_css['href']}{literal}" type="text/css" rel="stylesheet">{/literal}{else}{$sCss}{/if}{literal}
    <script type="text/javascript" src="/dcrv2/script/jquery-2.1.4.min.js" style=""></script>
	<script type="text/javascript" src="/dcrv2/script/inputmask.js"></script>
	<script type="text/javascript" src="/dcrv2/script/jquery.inputmask.js"></script> 
	<script	type="text/javascript"	src="//maps.google.com/maps/api/js?libraries=geometry&amp;sensor=true&amp;region={/literal}{$pays}{literal}"></script>
    <script type="text/javascript" src="/dcrv2/js/tracking/dcr-git.js"></script>
    <script type="text/javascript" src="/dcrv2/script/webFormsAppGlobal.min.js"></script>

    <script type="text/javascript">
        citroen.webforms.parameters.override({
            {/literal}
            culture: '{$culture}',
            lang: '{$lang}',
            country: '{$pays}',
            srcImg : '/version/vc',
            i18nService: '/dcr/srv/services/getflux?service=i18nService',
            brochureServiceParams : 'Country={$pays}&Lan={$culture}', 
            prefixGroupeCode : 'cpw_',
            i18nServiceParams : 'groupCode={literal}{groupCode}{/literal}&lang={$culture}&country={$pays}',
            vehicleServiceParams : 'Country={$pays}&Lan={$culture}',
            sessionService: '/dcr/srv/services/getflux?service=sessionService&country={$pays}',
            connexionService: '/dcr/srv/services/getflux?service=connexionService&country={$pays}',
            connectorLoginPopupService: '/dcr/connexion/login-popup?service=connexionService&culture={$culture}&context=pc&domain={$domain}',
            connectorGetDataService: '/dcr/connexion/get-user-data',
            getDealersListServiceParams: {literal}'Country={country}&Lan={lang}&language={lang}&latitude={latitude}&longitude={longitude}&region={region}&search={search}&search-name={searchname}'{/literal},
            getDealerService: '/dcr/srv/services/getflux?service=getDealerService',
            getDepartementsService: '/dcr/srv/services/getflux?service=getDepartementsService',
            getFavoriteDealersService: '/dcr/srv/services/getflux?service=getFavoriteDealersService&country={$pays}',
            getFavoriteDealersServiceParams: '',
            getAddDealerToFavoritesService: '/dcr/srv/services/getflux?service=getAddDealerToFavoritesService',
            getAddDealerToFavoritesServiceParams: 'Country={$pays}&Lan={$culture}&dealerid={literal}{dealerId}{/literal}',
            getDealersListService: '/dcr/srv/services/getflux?service=getDealersListService&country={$pays}',
            getDealerSuggest: '/dcr/srv/services/getflux?service=getDealerSuggestService&country={$pays}&culture={$culture}&input=$VALUE',
            {literal}
        });
    </script>
    <script type="text/javascript">
        function  {/literal}init{$aData.section}{literal}() {
            var IdSiteGeo = "";
           
            if($("#isGeocodeActive", window.parent.document))
            {
            IdSiteGeo = $("#isGeocodeActive", window.parent.document).val();
            }
            // launch app
            new citroen.webforms.WebFormsFacade({
                source: '/dcr/prm/getinstancebyid?instanceid={/literal}{$aData.idform}&culture={$culture}{$formulaire.FORM_PARAMS}&USR_EMAIL={$email}{if $aData.lcdv neq '' && $formulaire.FORM_CONTEXT_CODE eq 'CAR'}&codelcdv={$aData.lcdv}{/if}{literal}',
                returnURL: '',
                dealerLocatorFluxType: 'dealerdirectory2',
                iFrame: true,
                target: 'wf_form_content',
                siteGeo : IdSiteGeo,
                autoFill: {'GIT_TRACKING_ID': getGITID(), 'USR_EMAIL': '{/literal}{$email}{literal}'},
                carPickerPreselectedVehicles: [{/literal}{if $aData.lcdv neq '' && $formulaire.FORM_CONTEXT_CODE eq 'CAR'}'{$aData.lcdv}'{/if}{literal}],
                brochurePickerPreselectedVehiclesLcdv: [{/literal}{if $aData.lcdv neq '' && $formulaire.FORM_CONTEXT_CODE eq 'CAR'}'{$aData.lcdv}'{/if}{literal}],
                brochurePickerPreselectedVehicles: [],
                onPostAjaxSuccess: function(datas) {
                    window.parent.finalStepFunction(datas,{/literal}'{$aData.section}', '{$aData.idform}','{$formtype}'{literal});
                }
            });
        } {/literal}
        $(window).load(init{$aData.section});
         {literal}
        function sendGTM(category, action, label) 
		  {
		  dataLayer.push(
		  {'event': 'uaevent', 'eventCategory': category, 'eventAction': action, 'eventLabel': label }
						);
		  }
    </script>

    <script type="text/javascript">
    window.isDeployed = {/literal}{$isDeployed|json_encode}{literal};
    </script>
<body>

{/literal}{$gtmTag}{literal}

<div id="container" {/literal} class="{if $formClass}{$formClass}{/if} {if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if}"{literal}>
    <div id="wf_form_content" class="wf_form_content"></div>
</div>
<script type="text/javascript" src="/dcrv2/js/common/webFormUtils.js"></script>

</body></html>
{/literal}

{literal}
   <style>
	   div.wf_resume_img{
		width:210px;
	   }
	   .wf_form_content ul.options li img{
			min-width:0px;
	   }
	  .wf_form_content a img{
		min-width:0px;
	   }
	   .overview{
		   text-align:left;
		   margin-left:-41px;
	   }
	   .tooltip.wf_tooltip {
			height: auto;
			background-image: none;
			white-space: normal;
			text-indent: 0;
			overflow: visible;
		}

		.tooltip.wf_tooltip:hover, .tooltip.wf_tooltip:active {
			height: auto;
			width: 370px;
		}
		ul.wf_dealers_wrapper li{
			padding:0px!important;
			margin-left:50px!important;
			padding-top:2px!important; 
		}
		
		ul.options li{
			border:none!important;
			align:left!important;
		}
	
	  
   </style>
{/literal}

