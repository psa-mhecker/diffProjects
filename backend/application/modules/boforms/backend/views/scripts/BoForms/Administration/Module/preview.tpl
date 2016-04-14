{literal}
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	{/literal}
 	{$header}
 	{literal}
 	
    <script type="text/javascript" src="/version/vc/script/jquery-2.1.4.min.js"></script>
    
    {/literal}
    <script type="text/javascript"  src="//maps.google.com/maps/api/js?libraries=geometry&amp;sensor=true&amp;region={$pays}"></script>
    {literal}
    
    <script type="text/javascript" src="/version/vc/script/webforms_loader.js"></script>
    
    <!-- Configuration Moteur de Rendu FORMS -->
        <script type="text/javascript">
             
            formParams = {
                brand:        '{/literal}{$BRAND_ID}{literal}',               // Marque [ap, ac, ds] en minuscule
                lang:         '{/literal}{$lang}{literal}',               // Code ISO de la Langue (en)
                country:      '{/literal}{$pays}{literal}',               // Code ISO du Pays (GB) 
                culture:      '{/literal}{$culture}{literal}',            // Culture (en-GB, nl-BE pour le Neerlandais en Belgique)
                instance:     '{/literal}{$sCode}{literal}', // Numero d'nstance du formulaire (16 caracteres)
                context :     '{/literal}{$context}{literal}',          // desktop ou mobile
                brandidConnector: '{/literal}{$connector}{literal}',       // pc ou mobile ou driveds
                otherCss:     [],                 // Liste de CSS additionnels
                GammeSource: '{/literal}{$gammeSource}{literal}',                // Source de la Gamme des Vehicules et Brochures (CPP ou GDG)
                environment: 'PROD' // Environnement (DEV, RECETTE, PREPROD, PROD)
            };

            function loadFormsParameters() {
                
                new citroen.webforms.WebFormsFacade({
                    source: "{/literal}{$sSource1}{literal}",
                    returnURL: '',
                    dealerLocatorFluxType: 'dealerdirectory2',
                    target: 'wf_form_content',
                    siteGeo : '',
                    autoFill: {
                        'GIT_TRACKING_ID': getGITID(),
                        '_ga':'GA1.2.1630037288.1409318439','HTTP_USER_AGENT':'OTHERS','GRC_ADS_SOURCE':'','GRC_ADS_OPERATION':'','GRC_ADS_CHANNEL':''                    },
                    carPickerPreselectedVehicles: [],
                    brochurePickerPreselectedVehiclesLcdv: [],
                    brochurePickerPreselectedVehicles: [],
                    onPostAjaxSuccess: function(datas) {
                        alert('[SUCCESS] : Formulaire correctement envoye');
                    },
                    onPostAjaxFailure: function() {
                        alert("[FAILED] : Erreur technique lors de l'enregistrement du formulaire");
                    },
                    onPostAjaxError: function(datas) {
                        alert("[ERROR] : Certaines donness du formulaire sont invalides (Controle de format cote serveur)");
                    }                   
                });
                // Contextualisation des parametres du moteur
                citroen.webforms.parameters.contextualize(formParams);
            }
            // Chargement du moteur
            $(window).load(loadFormsResources(formParams.context));
        </script>

</head>
<body >
        <!-- Google Tag Manager NBA_GTM_v1--> 
        <noscript>
            <iframe src="//www.googletagmanager.com/ns.html?id=GTM-MVRT82" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager NBA_GTM_v1 -->
        <div id="container">
            <div id="wf_form_content" class="wf_form_content"></div>
        </div>
    </body>
</html>
{/literal}