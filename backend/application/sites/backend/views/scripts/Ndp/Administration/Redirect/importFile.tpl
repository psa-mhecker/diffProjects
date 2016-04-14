{if $success}
<br />
<br />
<br />
<br />
<div id="accordion" data-theme="none">
    {if count($reporter->getInvalid()) > 0}
        <h3 class="zonetype1">{'NDP_INVALID_URLS'|t} : ({count($reporter->getInvalid())})</h3>
        <div class="accordion-content" style="display: none;">
        <table  border="0" cellspacing="0" cellpadding="0" class="liste" id="tableClassForm">
            <thead>
            <t >
                <th class="tblheader">Source</th>
                <th class="tblheader">Destination</th>
            </t>
            </thead>
            {foreach $reporter->getInvalid() as $url}
                <tr>
                    <td>{$url.2}</td>
                    <td>{$url.3}</td>
                </tr>
            {/foreach}
        </table>
        </div>
    {/if}

    {if count($reporter->getExisting()) > 0}
         <h3 class="zonetype1">{'NDP_EXISTING_URLS'|t} : ({count($reporter->getExisting())})</h3>
        <div class="accordion-content" style="display: none;">
        <table  border="0" cellspacing="0" cellpadding="0" class="liste" id="tableClassForm">
            <thead>
            <tr >
                <th class="tblheader">Source</th>
                <th class="tblheader">Destination</th>
            </tr>
            </thead>
            {foreach $reporter->getExisting() as $url}
                <tr>
                    <td>{$url.2}</td>
                    <td>{$url.3}</td>
                </tr>
            {/foreach}
        </table>
        </div>
    {/if}

    {if count($reporter->getIgnored()) > 0}
       <h3 class="zonetype1"> {'NDP_REDIRECT_NOT_FOUND'|t}: ({count($reporter->getIgnored())})</h3>
        <div class="accordion-content" style="display: none;">
        <table  border="0" cellspacing="0" cellpadding="0" class="liste" id="tableClassForm">
            <thead>
            <tr>
                <th class="tblheader">Source</th>
                <th class="tblheader">Destination</th>
            </tr>
            </thead>
            {foreach $reporter->getIgnored() as $url}
                <tr>
                    <td>{$url.REWRITE_URL}</td>
                    <td>{$url.DEST_URL}</td>
                </tr>
            {/foreach}
        </table>
        </div>
    {/if}
</div>
    {literal}
    <script>
        $(document).ready(function($) {
            $('#accordion').find('.zonetype1').click(function(){

                //Expand or collapse this panel
                $(this).next().slideToggle('fast');

                //Hide the other panels
                $(".accordion-content").not($(this).next()).slideUp('fast');

            });
        });
    </script>
    {/literal}
{/if}

