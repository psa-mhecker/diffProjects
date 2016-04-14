{$form}

{literal}
    <script>
        $( document ).ready(function() {
            $('input[name="POPIN_ACTIVE"]').change(radioValueChanged);
            if($('input[type="radio"][name="POPIN_ACTIVE"]:checked').val() == '1')
            {
                $('input[name="TARGET"]').attr("disabled", true);
            }
        });

        function radioValueChanged()
        {
            radioValue = $(this).val();

            if($(this).is(":checked") && radioValue == "'.self::DISABLE.'")
            {
                $('input[name="TARGET"]').attr("disabled", false);
            }
            else
            {
                $('input[name="TARGET"]').attr("disabled", true);
            }
        }
        function checkCtaUrl (msgStandard, msgClickToCall, msgOnlyNumber) {
            var standard = $("#ACTION_STANDARD");
            if(!standard.parents("tbody").hasClass("isNotRequired") &&  standard.val() == "http://") {
                alert(msgStandard);
                fwFocus(obj["ACTION_STANDARD"]);
                return false;
            }
            var clickToCall =  $("#ACTION_CLICK_TO_CALL");
            if(!clickToCall.parents("tbody").hasClass("isNotRequired") &&  clickToCall.val() == "") {
                alert(msgClickToCall);
                fwFocus(obj["ACTION_CLICK_TO_CALL"]);
                return false;
            }
            var pattern = new RegExp('^[0-9]+$');
            if (!clickToCall.parents("tbody").hasClass("isNotRequired") && !pattern.test(clickToCall.val())) {
                alert(msgOnlyNumber);
                fwFocus(obj["ACTION_CLICK_TO_CALL"]);
                return false;
            }

            return true;
        }

        function validateEditCta(msgValidate) {
            var msgCta = msgValidate + ": \n";
            $("#popin_cta ul li").each(function(idx, el){
                var pageName = $(el).text().replace(/(\r\n|\n|\r)/gm,' ');
                msgCta = msgCta + "- " +pageName+"\n";
            });

            return confirm(msgCta);
        }
    </script>
{/literal}
