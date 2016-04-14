var hidden_multi;
var name_multi;
var limit_multi;
var prefixe_multi;
var sequence_multi;

function addClone(name, limit) {

    var number = $('.' + name + '_subForm').length - 1;


    var iteration = 0;


    for (iteration = 0; iteration < number; iteration++)
    {
        if ($('#' + name + '' + iteration + '_multi_display').length == 0)
        {
            break;
        }
    }

    number = iteration;
    var count = iteration;


    var test = name;




    $('#count_' + name).val(count);
    if (limit != 0 && (number) >= limit) {
        alert("Maximum atteint !");
    } else {
        //var number = $('.' + name + '_subForm').length - 1;

        // couleur alternative
        if (number % 2) {
            var strCss2 = "background-color=#F9FDF3;";
            var color = "#F9FDF3";
        } else {
            var strCss2 = "background-color=#FAEADA;";
            var color = "#FAEADA";
        }

        //  création du dom a ajouter et ajout des attributs
        var newElem = $('#' + name + '_subForm')
                .clone()
                .attr('id', name + count + '_subForm')
                .attr('class', name + '_subForm multi')
                .attr('bgcolor', color)
                .attr('style', strCss2);

        // remplacement des valeurs génériques


        var newElem = newElem.wrap('<div>').parent().html()
                .replace(/__CPT__/g, count)
                .replace(/__CPT1__/g, (eval(number) + 1));
        // ajout des contrôles JS
        var fonctionCheck = CheckForm_multi.toString();
        oldFonctionCheck = fonctionCheck.substring(fonctionCheck.indexOf('{') + 1, fonctionCheck.length - 2);

        fonctionCheck = "if (document.getElementById('" + name + count + "_multi_display')) {\n";
        fonctionCheck += "    if (document.getElementById('" + name + count + "_multi_display').value == 1) {\n";
        fonctionCheck += document.getElementById(name + '_subFormJS').value.replace(/__CPT__/g, count);
        fonctionCheck += "    }\n";
        fonctionCheck += "}\n" + oldFonctionCheck;
        CheckForm_multi = new Function("obj", fonctionCheck);

        // affichage du nouveau dom
        if (number == 0) {
            $('#' + name + '_subForm').after(newElem);
        } else {

            $('#' + name + '' + (number - 1) + '_subForm').after(newElem);
        }

        // incrémentation du compteur du nombre d'élément du multi
        $('#count_' + name).val(count + 1);

        iteration = 0;
        var index = 1;
        var aTable = document.getElementsByTagName("table");


        for (iteration = 0; iteration < aTable.length; iteration++)
        {
            for (var i = 0; i < 12; i++)
            {
                var uneTable = document.getElementById('' + test + '' + i + '_subForm');

                if (aTable[iteration] == uneTable)
                {

                    var td = $('#' + test + '' + i + '_subForm tbody tr td:first');
                    td.html("n° " + index);
                    index++;
                    break;
                }

            }
        }
    }
}

function delClone(name, compteur) {
    $('#' + name + '_multi_display').val(0);
    $('#' + name + '_subForm').fadeOut('slow');
    var reg = new RegExp((compteur - 1) + '$', "");
    var multi = name.replace(reg, '');
    var new_count = parseInt($('#count_' + multi).val()) - 1;
    $('#count_' + multi).val(new_count);


    $('#' + name + '_subForm').remove();

    var iteration = 0;
    var index = 1;
    var aTable = document.getElementsByTagName("table");


    for (iteration = 0; iteration < aTable.length; iteration++)
    {
        for (var i = 0; i < 12; i++)
        {
            var uneTable = document.getElementById('' + multi + '' + i + '_subForm');

            if (aTable[iteration] == uneTable)
            {

                var td = $('#' + multi + '' + i + '_subForm tbody tr td:first');
                td.html("n° " + index);
                index++;
            }

        }
    }

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
        // unescape(limit_multi)
        if (new_hidden % 2) {
            var strCss2 = "background-color=#F9FDF3;";
        } else {
            var strCss2 = "background-color=#FAEADA;";
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

// drag and drop au cas ou 
/*function moveMulti(multi) {
    $('#' + multi + '_td').sortable({
        placeholder: "ui-state-highlight",
        update: function() {  // callback quand l'ordre de la liste est changé
            // met a jours les compteurs multi
            var iteration = 0;
            var index = 1;
            var aTable = document.getElementsByTagName("table");

            for (iteration = 0; iteration < aTable.length; iteration++)
            {
                for (var i = 0; i < 12; i++)
                {
                    var uneTable = document.getElementById('' + multi + '' + i + '_subForm');

                    if (aTable[iteration] == uneTable)
                    {                        
                        var td = $('#' + multi + '' + i + '_subForm tbody tr td:first');
                        td.html("n° " + index);
                        $('#' + multi + '' + i + '_order').val(index);
                        index++;
                    }
                }
            }
        }
    });
    $('#' + multi).disableSelection();
}*/