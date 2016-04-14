var hidden_multi;
var name_multi;
var limit_multi;
var prefixe_multi;
var sequence_multi;

function getCount(name)
{
    var parentElt = $('#' + name + '_subForm').parent();
    if (!parentElt.children('table.multi').length) {
        return;
    }
    var tableOfMulti = parentElt.children('table.multi:not(:first)');
    var count = tableOfMulti.length;
    
    return count;
}

function delClone(name, compteur, ShowCompteurLabel, compteurLabel) {
    $('#' + name + '_multi_display').val(0);
    $('#' + name + '_subForm').fadeOut('slow');

    var reg = new RegExp((compteur - 1) + '$', "");
    var multi = name.replace(reg, '');
    var parentElt = $('#' + name + '_subForm').parent();

    if (!parentElt.children('table.multi').length) {
        return;
    }

    var bgColor = $('#' + name + '_subForm').css("background-color");
    $('#' + name + '_subForm').remove();

    var tableOfMulti = parentElt.children('table.multi:not(:first)');

    var count = tableOfMulti.length;

    $('#count_' + multi).val(count);

    var index = 1;
    var lastBgColor;
      var label = "n&deg;";
        if (compteurLabel) {
            label = compteurLabel;
        }

    tableOfMulti.each(function () {
        // décallage de la couleur de fond
        if (compteur < index) {
            lastBgColor = bgColor;
            bgColor = $(this).css("background-color");
            $(this).css("background-color", lastBgColor);
        }

        if (ShowCompteurLabel != false) {
            var firstTd = $(this).find('tbody tr td').first();
            if (firstTd) {
                firstTd.html(label+" "+ index);

            }
        }
        index++;
    });


    if ($('#' + name + '_MEDIA_ID'))
    {
        $('#' + name + '_MEDIA_ID').remove();
    }


}

function addMulti(obj, name, file, prefixe, compteur, limit, numberField,
        complement) {
    hidden_multi = obj["count_" + name];
    setSequence();
    name_multi = name;
    limit_multi = limit;
    prefixe_multi = prefixe;
    iframeM = document.getElementById("iframe_" + name_multi);
    var args = new Object();

    if (limit_multi && limit_multi <= eval(hidden_multi.value) + 1) {
        alert("Maximum atteint !");
    } else {
        iframeM.src = libDir + "/Pelican/Form/public/popup_multi.php?file="
                + escape(file) + "&prefixe=" + prefixe + "&compteur="
                + sequence_multi + "&numberField=" + numberField + "&fname="
                + obj.name + complement;
    }
}

function getMulti(arr) {
    if (arr) {
        // code HTML
        setSequence();
        var new_hidden = ++sequence_multi;
        var objTd = document.getElementById("td_" + name_multi);
        var strCss = '';
        // unescape(limit_multi)
        if (new_hidden % 2) {
            var strCss = "background-color=#F9FDF3;";
        } else {
            var strCss = "background-color=#FAEADA;";
        }
        objTd.insertAdjacentHTML("beforeEnd",
                "<table cellspacing=\"0\" style=\"" + strCss2 + "\" cellpadding=\"0\" class=\"multi\" id=\"" + prefixe_multi + new_hidden + "_multi_table" + "\">" + unescape(arr["html"]) + "</table>");
        // code JS
        var fonctionCheck = CheckForm_multi.toString();
        fonctionCheck = fonctionCheck.replace("function anonymous(obj) {", "");
        fonctionCheck = fonctionCheck.substring(0, fonctionCheck.length - 2);
        fonctionCheck = "if (document.getElementById('" + prefixe_multi
                + new_hidden
                + "_multi_display')) {\n if (document.getElementById('"
                + prefixe_multi + new_hidden + "_multi_display').value) {\n"
                + unescape(arr["js"]) + "\n}\n}\n" + fonctionCheck;
        CheckForm_multi = new Function("obj", fonctionCheck);
        hidden_multi.value++;
        if (arr["eval"]) {
            eval(unescape(arr["eval"]));
        }
    }
}

function delMulti(number) {
    table = document.getElementById(number + "multi_table");
    display = document.getElementById(number + "multi_display");
    table.style.display = "none";
    if (hidden_multi) {
        setSequence();
        if (eval(hidden_multi.value) >= 1) {
            hidden_multi.value = eval(hidden_multi.value) - 1;
        }
    }
    display.value = "";
}

function setSequence() {
    if (!sequence_multi) {
        if (hidden_multi) {
            sequence_multi = (hidden_multi.value || 0);
        } else {
            sequence_multi = 1;
        }
    }
}


function addClone(name, limit, level, ShowCompteurLabel, compteurLabel) {

    if (!$('#' + name + '_subForm').parent().children('table.multi').length) {
        return;
    }
    var tableOfMulti = $('.' + name + '_subForm').parent().children('table.multi:not(:first)');

    var count = tableOfMulti.length + 1;
    var number = parseInt($('#nombre_' + name).val()) || 0;

    if (limit != 0 && (count > limit)) {
        alert("Maximum atteint !");
    } else {

        // couleur alternative
        var strCss2 = "background-color=#FAEADA;";
        var color = "#FAEADA";
        if (level) {
            strCss2 = "background-color=#FAFAFA;width:85%;margin:0 auto;padding: 15px;";
            color = "#FAFAFA";
        }
        if (count % 2 != 1) {
            strCss2 = "background-color=#F9FDF3;";
            color = "#F9FDF3";
            if (level) {
                strCss2 = "background-color=#E6E6E6; width:85%;margin:0 auto;padding: 15px;";
                color = "#E6E6E6";
            }
        }

        //  création du dom a ajouter et ajout des attributs
        var newElem = $('#' + name + '_subForm')
                .clone()
                .attr('id', name + number + '_subForm')
                .attr('class', name + '_subForm multi')
                .attr('bgcolor', color)
                .attr('style', strCss2);

        // remplacement des valeurs génériques

        var cpt_regexp = new RegExp("__CPT" + level + "__", "g")
        var newElem = newElem.wrap('<div>').parent().html()
                .replace(cpt_regexp, number)
                .replace(new RegExp("__CPT1" + level + "__", "g"), count);
        // ajout des contrôles JS
        var fonctionCheck = CheckForm_multi.toString();
        oldFonctionCheck = fonctionCheck.substring(fonctionCheck.indexOf('{') + 1, fonctionCheck.length - 2);

        fonctionCheck = "if (document.getElementById('" + name + number + "_multi_display')) {\n";
        fonctionCheck += "    if (document.getElementById('" + name + number + "_multi_display').value == 1) {\n";
        fonctionCheck += document.getElementById(name + '_subFormJS').value.replace(cpt_regexp, number);
        fonctionCheck += "    }\n";
        fonctionCheck += "}\n" + oldFonctionCheck;
        CheckForm_multi = new Function("obj", fonctionCheck);

        // affichage du nouveau dom

        $('#' + name + '_td').append(newElem);

        // incrémentation du compteur du nombre d'élément du multi
        $('#count_' + name).val(count);
        $('#nombre_' + name).val(number + 1);

        var index = 1;
        var label = "n&deg;";
        if (compteurLabel) {
            label = compteurLabel;
        }

        tableOfMulti.each(function () {
            if (ShowCompteurLabel != false) {
                var firstTd = $(this).find('tbody tr td:first');
                if (firstTd) {
                    firstTd.html(label + " " + index);
                    index++;
                }
            }
        });
    }
}
