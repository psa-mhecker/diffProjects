{$doctype}
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    {$header}
</head>
<body>
    {$gtmTag}
    <div class="{if !isset($smarty.get.popin)}container{/if}">
        {$body}
        {$footer}
    </div>
</body>
</html>