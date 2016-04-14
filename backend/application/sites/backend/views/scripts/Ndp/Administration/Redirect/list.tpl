<div id="importFo" style="float:left;width:50%;">
    <b> {'TRAD_IMPORT'|t}</b>

    <form name="fFormImport" id="fFormImport" action="{$import.controller}" method="post" onSubmit="return checkImport();" enctype="multipart/form-data">
        <input type="hidden" name="stepAction" value="{$import.action}"/>
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
        <input type="hidden" value="{$site_id}" id="site_id" name="site_id"/>
        <input type="hidden" value="{$tc}" id="tc" name="tc" />
        <input type="file" name="FILE_REDIRECT_IMPORT" id="FILE_REDIRECT_IMPORT" size="40" /><br/>
        <input name="submitUpload" type="submit" class="button" value="{'TRAD_IMPORT'|t}" />
    </form>
    {literal}
    <script type="text/javascript">
        function checkImport()
        {
            var sFichier = $('input[name="FILE_REDIRECT_IMPORT"]').val();
            if (sFichier == "") {
                alert("{/literal}{'NDP_REDIRECT_CHOOSE_FILE_WARNING'|t}{literal}.");
                return false;
            }
        }
    </script>
    {/literal}
</div>
{$table}


