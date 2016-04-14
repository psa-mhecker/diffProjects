<script type="text/javascript">
    {literal}function setDirectory(id, pid, type) {{/literal}
    if (!pid) pid='0';
    document.location.href = '{$url}' + "&id=" + id + "&pid=" + pid + "&type=" + type;
    {literal}}{/literal}
</script>
{$content}
<script type="text/javascript">
    {$js}
</script>
