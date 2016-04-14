<div class="form_title">{'NDP_MIG_RESULT_TITLE'|t}</div>

<div>
    <h4>Input :</h4>
    <ul>
        <li>Type of showroom url: {$reporting->getTypeShowroom()}</li>
    </ul>
    <ul>
        {foreach from=$reporting->getUrls() key=language item=url}
            <li>
                URL for language '{$language}' :
                <a href="{$url}" target="_blank">{$url}</a>
            </li>
        {/foreach}
    </ul>

    {* XML *}
    <h4>XML Data:</h4>
    <ul>
        {foreach from=$reporting->getXmls() key=language item=url}
            <li>
                XML for language '{$language}' :
                <a href="{$url}" target="_blank">{$url}</a>
            </li>
        {/foreach}
    </ul>

    {if $reporting->getErrorMessages()|@count === 0}
        {* SRT *}
        <h4>SRT Found:</h4>
        <ul>
            {if $reporting->getSrtUrls()|@count === 0}
                No SRT found in the XML.
            {/if}
            {foreach from=$reporting->getSrtUrls() key=index item=url}
                <li>
                    {$index + 1} :
                    <a href="{$url}" target="_blank">{$url}</a>
                </li>
            {/foreach}
        </ul>
    {/if}

    {* Result *}
    <h4>RESULT: <span style="color:{$reporting->resultColor()}">{$reporting->resultMessage()}</span></h4>

    {* Log messages *}
    <ul>
        {foreach from=$reporting->getInfosMessages() item=msg}
            <li><span style="color:grey;">[Info]</span> {$msg}</li>
        {/foreach}
        {foreach from=$reporting->getWarningMessages() item=msg}
            <li><span style="color:orange;">[Warning]</span> {$msg}</li>
        {/foreach}
        {foreach from=$reporting->getErrorMessages() item=msg}
            <li><span style="color:red;">[Error]</span> {$msg}</li>
        {/foreach}
    </ul>
    <form name="fForm" id="fForm" action="{$migrateImage.controller}" method="post" style="margin:0 0 0 0;" class="fwForm">
        <input type="hidden" name="stepAction" value="{$migrateImage.action}"/>
        <input type="submit" class="button" value="{'NDP_MIG_MIGRATE_IMAGE'|t}" />
    </form>

</div>
