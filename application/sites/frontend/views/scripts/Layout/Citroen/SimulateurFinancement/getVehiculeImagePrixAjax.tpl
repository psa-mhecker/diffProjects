<div class="result field col new">
    <figure>
        <img src="{$aData.IMAGE}" alt="{$aData.LABEL}" />
        <figcaption>{$aData.LABEL}</figcaption>
        {if $aData.PRICE_DISPLAY neq ''}
            <p class="price">{'A_PARTIR_DE'|t}<em><strong>{$aData.PRICE_DISPLAY}</strong> {$aData.PRICE_TYPE|t}</em></p>
        {/if}
        
    </figure>
</div>


