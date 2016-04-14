<div class="form_title">{'NDP_MIG_MIGRATE_IMAGE_TITLE'|t}</div>
{if count($sisters) >0 }
    <p>
    <b>{'NDP_MIG_MIGRATE_IMAGE_DESCRIPTION'|t}</b>
    </p>
<form name="fForm" data-error-10="{'NDP_MIG_ERROR_ALL_IMAGE_REQUIRED'|t}" id="fForm" action="{$migrateImage.controller}" method="post" style="margin:0 0 0 0;" class="fwForm">
    <input type="hidden" name="stepAction" value="{$migrateImage.action}"/>

    <div id="tableContainer" class="tableContainer">
        <table id="migrate-image"  border="0" cellpadding="0" cellspacing="0" width="100%" class="migrate-image-table scrollTable">
            <thead  class="fixedHeader">
            <tr>
                <th>{'NDP_MIG_IMAGE_SD'|t}</th>
                <th>{'NDP_MIG_IMAGE_HD'|t}</th>
                <th><input type="submit" class="button" value="{'NDP_MIG_IMAGE_REPLACE'|t}" /></th>
            </tr>
            </thead>
            <tbody class="scrollContent">
            {foreach from=$sisters key=id item=sister}
                {assign var="info1" value=$signatures[$id]}
                {assign var="info2" value=$signatures[$sister.id]}
                    <tr id="#image-{id}">
                        <td><img src="{$mediaRoot }/{$info1.path}" />
                            <br />({$info1.width }x {$info1.height})  <a href="javascript:void(0)" onClick="top.popupMediaUsage('{$id}');"> {'NB_USE'|t} : {$sister.count}</a></td>
                        <td><img src="{$mediaRoot}/{$info2.path }"/>
                            <br />({$info2.width}x {$info2.height}) <a href="javascript:void(0)" onClick="top.popupMediaUsage('{$sister.id}');"> {'NB_USE'|t} : {$mediaManager->countUsage($sister.id)}</a></td>
                        <td><label for="replace-image_{$id}_yes">{'NDP_YES'|t}</label>
                            <input id="replace-image_{$id}_yes" type="radio" name="replace-image[{$id}]" value="{$sister.id}">
                            <label for="replace-image_{$id}_no">{'NDP_NO'|t}</label>
                            <input id="replace-image_{$id}_no" type="radio" name="replace-image[{$id}]" value="0"></td>
                    </tr>
            {/foreach}
            </tbody>
        </table>
        <table  border="0" cellpadding="0" cellspacing="0"  id="header-fixed"></table>
    </div>
</form>
{else}
    {'NDP_MIG_NO_IMAGE_FOUND'|t}
    <form name="fForm" id="fForm" action="{$migrateBack.controller}" method="post" style="margin:0 0 0 0;" class="fwForm">
        <input type="hidden" name="stepAction" value="{$migrateBack.action}"/>
        <input type="submit" class="button" value="{'NDP_MIG_BACK_LIST'|t}" />
    </form>
{/if}
{literal}

<script type="text/javascript">
    $(document).ready(function() {
        var tableOffset = $("#migrate-image").offset().top;
        var $head = $("#migrate-image > thead");
        var $newHead =$head.clone();
        var $fixedHeader = $("#header-fixed").append($newHead);
        var $childs = $head.find('th');
        var $form = $('#fForm');

        $newHead.find('th').width(function(i) {
            return $childs.eq(i).width();
        });

        $(window).bind("scroll", function () {
            var offset = $(this).scrollTop();

            if (offset >= tableOffset && $fixedHeader.is(":hidden")) {
                $fixedHeader.show();
            }
            else if (offset < tableOffset) {
                $fixedHeader.hide();
            }
        });
        $form.find('.scrollContent input[type="radio"]').on('click',function(evt){

            var className= 'checked-off';
            if ($(this).val() != 0)
            {
                className =  'checked-on'
            }
            $(this).parents('tr').removeClass('error').removeClass('checked-off').removeClass('checked-on').addClass(className);
        });
        $form.on('submit',function(evt)
        {
            var $tr = $('.scrollContent tr');
            $tr.removeClass('error');
            var error = false;
            var firstErrorRow;
            $tr.each(function(idx,elm) {
                var $row = $(elm);
                var $radio = $row.find('input[type=radio]:checked');
                if ($radio.length == 0) {
                    if(!error) {
                        firstErrorRow = $row.get(0);
                    }
                    error = true;
                    $row.addClass('error');
                }
            });

            if(error) {
                firstErrorRow.scrollIntoView(true);
                noty({theme: 'relax', text: $form.data('error-10'), type: "error", timeout: 4000});
                evt.preventDefault();
            }

        });
    });


</script>
{/literal}
