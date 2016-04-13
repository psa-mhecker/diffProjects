<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{$header}
</head>
<body id="body_child">

{if $flash_message}
    <div class="flash_message">
    {foreach from=$flash_message key=k item=v}
        <div class="{if $v.type == 'error'}erreur{/if}">{$v.message|htmlspecialchars}</div>
    {/foreach}
    </div>
{/if}

{$title}
{$languageTabAdmin}
{$begin}
{$body}
{$button}
<div id="dialog"></div>
{$footer}
</body>
</html>