<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{$header}
</head>
<body id="body_child">
{$title}
{$languageTabAdmin}
{$begin}
{$body}
{$button}
<div id="dialog"></div>
{$footer}
{if $flash_message}

<script type="text/javascript">
    $(document).ready(function(){ldelim}
        {foreach from=$flash_message item=message}
            noty({ldelim}theme: 'relax', text: "{$message.message|htmlspecialchars}", type: "{$message.type}", timeout: 4000{rdelim});
        {/foreach}

        {rdelim});
</script>

{/if}
</body>
</html>
