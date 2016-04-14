<div>
    <span class='checkAll' style='color:#014ea2;cursor: pointer;text-decoration:underline;'>{t('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLCHECK')}</span> / <span style='color:#014ea2;cursor: pointer;text-decoration:underline;' class='decheckAll'>{t('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLDCHECK')}</span> <span>{t('BOFORMS_CHANGESTATUS_LABEL_SELECTION')} :</span>
    <button class="duplicateAction">{t('BOFORMS_DUPLICATE_ACTION')}</button>
</div>
{literal}
    <script type="text/javascript">

        $( document ).ready(function() {;
            $(".duplicateAction").click(function() {

                var listForms = "";
                var i = 0;
                $('.checkbox:checked').each(function() {
                    if(i==0){
                        listForms = $(this).val();
                    }else{
                        listForms = listForms + "_" + $(this).val();
                    }
                    i++;
                });

                if(listForms != "")
                {
                    doDuplicateMultiJS(listForms);
                }
            });

            $('.checkAll').click(function() {
                $('.checkbox').attr('checked', true);
            });

            $('.decheckAll').click(function() {
                $('.checkbox').attr('checked', false);
            });

        });
    </script>
{/literal}