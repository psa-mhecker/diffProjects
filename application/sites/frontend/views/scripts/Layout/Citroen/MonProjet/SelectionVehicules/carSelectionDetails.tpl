<div id="selected_car_{$i}" class="col span4 selectedCar{if $iSelectionId == $i} active{/if}">
    <input type="hidden" class="vid" value="{$aSelectionDetails.$i.LCDV6}|{$aSelectionDetails.$i.GR_COMMERCIAL_NAME_CODE}|{$aSelectionDetails.$i.ENGINE_CODE}|{$i}" />
    <div class="content" data-sync="carCont{$aData.ORDER}">
        <div class="closer"></div>
        <span class="parttitle">
            <a href="javascript:setVehiculeEdit({$i})" title="{'CLICK_TO_EDIT'|t}" class="pictoModifier">&nbsp;</a>
            <span class="sortLabel">{'SELECTION'|t} 0{$i+1}</span>
        </span>
        <figure data-sync="cars{$aData.ORDER}">
            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aSelectionDetails.$i.IMAGE}" width="201" height="110" alt="{$aSelectionDetails.$i.LABEL}" />
            <noscript><img src="{$aSelectionDetails.$i.IMAGE}" width="201" height="110" alt="{$aSelectionDetails.$i.LABEL}" /></noscript>
        </figure>
        <span class="model parttitle">{$aSelectionDetails.$i.LABEL}</span>
        <div class="descCar">
            <p>{$aSelectionDetails.$i.FINITION_LABEL}</p>
            <p>{$aSelectionDetails.$i.ENGINE_LABEL}</p>
            <p class="price">{$aSelectionDetails.$i.PRICE_DISPLAY}</p>
        </div>
    </div>
</div>