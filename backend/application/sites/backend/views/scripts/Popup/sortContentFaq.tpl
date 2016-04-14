{$doctype}
<html>
    <head>
        {$header}
    </head>
    <body id="body_popup" leftmargin="3" topmargin="3">
        <script type="text/javascript">
            {literal}
                function submitMe() {
                    document.fForm.submit();
                }
                function closeMe() {
                    window.close();
                }
            {/literal}
        </script>
        <fieldset><legend><b>{"Ordre d'affichage"|t}</b></legend>
            <div id="scroll-container" class="area">
                <ul id="thelist2" style="padding: 2px;">
                    {$sLi}
                </ul>
            </div>
            <form name="fForm" style="margin: 0px;" method="post">
                <input type="hidden" name="LANGUE_ID" value="{$iLangueId}" /> 
                <input type="hidden" name="CONTENT_TYPE_ID" value="{$iContentTypeId}" /> 
                <input type="hidden" name="FAQ_RUBRIQUE_ID" value="{$iFaqRubriqueId}" /> 
                <input type="hidden" name="SITE_ID" value="{$iSiteId}" /> 
                <input type="hidden" id="FAQ_RUBRIQUE_CONTENT_ORDER" name="FAQ_RUBRIQUE_CONTENT_ORDER" /></form>
            <br />
        </fieldset>
        <script type="text/javascript" language="javascript">
            // <![CDATA[
            {literal}
                Position.includeScrollOffsets = true;
                Sortable.create('thelist2', {scroll: 'scroll-container',
                    onChange: function(element) {
                        $('FAQ_RUBRIQUE_CONTENT_ORDER').value = Sortable.join(element.parentNode, ',')
                    }
                });
            {/literal}
                // ]]>
        </script>
        <p class="bottom">
            <button onclick="submitMe();">{'OK'|t}</button>
            <button onclick="closeMe();">{'POPUP_BUTTON_CANCEL'|t}</button>
        </p>
        {$footer}
    </body>
</html>