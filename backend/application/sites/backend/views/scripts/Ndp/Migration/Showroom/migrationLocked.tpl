<div class="form_title">{'NDP_MIG_LOCK_TITLE'|t}</div>

<div class="content">

    <p>{t('NDP_MIG_LOCK_BY_USER', '', ['Date' => $infos.date, 'Hour' => $infos.hour, 'User' => $infos.user])}</p>
    <p>{t('NDP_MIG_LOCK_MIG_AVERAGE_TIME', '', ['AvgTime' => $avgTime])}</p>
    <p>{'NDP_MIG_LOCK_UNLOCK_MSG'|t}</p>

    <form name="fForm" id="fForm" action="{$unlock.controller}" method="post" style="margin:0 0 0 0;" class="fwForm">
        <input type="hidden" name="stepAction" value="{$unlock.action}"/>
        <input type="submit" class="button" value="{'NDP_MIG_LOCK_UNLOCK_BTN'|t}" />
    </form>
</div>
