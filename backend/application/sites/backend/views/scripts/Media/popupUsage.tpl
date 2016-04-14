<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{$header}
</head>
<body id="body_popup" leftmargin="3" topmargin="3" onload="init()">
    <div id="div_content">
        {'POPUP_ADMIN_DATA'|t}<br/>
        {$aAdmin}
        <br/><br/>
        {'POPUP_RUBRIQUE_DATA'|t}
        {$aRubrique}
        <br/>
        {'POPUP_RUBRIQUE_DATA_AUTRE_SITE'|t}
        {$aRubriqueAutre}
        <br/>
        {'POPUP_CONTENT_DATA'|t}
        {$aContent}
    </div>

    <div id="div_popup_footer">
       <!-- <button onclick="closePopup();">{'POPUP_BUTTON_CLOSE'|t}</button>-->
    </div>
    {$default} {$footer}
</body>
</html>