<link href="https://cdn.jsdelivr.net/jquery.footable/2.0.3/css/footable.standalone.css" rel="stylesheet" type="text/css" />
<script src="https://cdn.jsdelivr.net/jquery.footable/2.0.3/footable.all.min.js" type="text/javascript"></script>
<h3>Dev Tools</h3>
<hr />
<nav>
    <ul>
        {foreach $buttons as $button}
            <li>
                <form name="fForm" id="fForm" action="{$button.controller}" method="post" style="margin:0 0 0 0;" class="fwForm">
                    <input type="hidden" name="stepAction" value="{$button.action}"/>
                    <input type="submit" class="button" value="{$button.label}" />
                </form></li>
        {/foreach}
    </ul>
</nav>
{literal}
<script type="text/javascript">
    $(function () {
        $('.liste').footable();
    });
</script>
{/literal}