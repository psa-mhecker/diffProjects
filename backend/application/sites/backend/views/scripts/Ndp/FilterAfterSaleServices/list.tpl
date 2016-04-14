{$form}

{literal}
<script>
    $( document ).ready(function() {
        $('input[name="FILTER_AFTER_SALE_SERVICE"]').change(checkBoxValueChanged);
    });

    function checkBoxValueChanged() {
        var value = '0';
        if ($('input[name="FILTER_AFTER_SALE_SERVICE"]:checked').val() == '1') {
            value = '1';
        }
        callAjax({
            method: "POST",
            url: "Ndp_FilterAfterSaleServices/saveSite",
            data: { FILTER_AFTER_SALE_SERVICE: value},
        });
    }
</script>
{/literal}
