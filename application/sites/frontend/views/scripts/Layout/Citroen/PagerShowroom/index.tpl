{literal}
    <style>
        .slicePagerShowroomDesk ul li a.buttonShowRoomArrowRight,.slicePagerShowroomDesk ul li a.buttonShowRoomArrowLeft{
            border: 4px solid {/literal}{if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#007c92{/if}{literal}!important;
            background-color: #fff!important;
        }
        .slicePagerShowroomDesk ul li a.buttonShowRoomArrowRight:after,.slicePagerShowroomDesk ul li a.buttonShowRoomArrowLeft:after{
            color: {/literal}{if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#007c92{/if}{literal}!important;
        }
        .slicePagerShowroomDesk ul li a.buttonShowRoomArrowRight:hover,.slicePagerShowroomDesk ul li a.buttonShowRoomArrowLeft:hover{
            color: {/literal}{if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#007c92{/if}{literal}!important;
            border-width: 6px!important;
        }

    </style>
{/literal}

{if $next || $prev}
    <div id="{$aData.ID_HTML}" class="sliceNew slicePagerShowroomDesk">
        <ul class="row" >
            {if $prev}
                <li class="previous columns column_45"><a class="buttonShowRoomArrowLeft" href="{urlParser url=$prev.PAGE_CLEAR_URL}#sticky"
                                                          {gtm action='Showroom' data=$aData datasup=['eventLabel'=>{$prev.PAGE_TITLE}]}
                                                          >{$prev.PAGE_TITLE}</a></li>
                {/if}
                {if $next}
                <li class="next columns column_45 right"><a class="buttonShowRoomArrowRight" href="{urlParser url=$next.PAGE_CLEAR_URL}#sticky" {gtm name='clic_sur_le_pager' data=$aData datasup=['value'=>$next.PAGE_ID] labelvars=['%nom du modele%'=>$aVehicule.VEHICULE_LABEL,'%intitule de la rubrique%'=>$aData.PAGE_META_TITLE]}>{$next.PAGE_TITLE}</a></li>
                {/if}
        </ul>
    </div>	

{/if}
