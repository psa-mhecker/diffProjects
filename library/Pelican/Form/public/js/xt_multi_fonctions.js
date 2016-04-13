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
        
        // Définition du champ ordre pour le nouvel élément multi (max +1)
        newElem = $(newElem);
        var multiWrap = $('#'+name+'_td');
        var orderFieldCollection = multiWrap.find("input[name*='PAGE_ZONE_MULTI_ORDER']");
        var maxOrder = 0;
        orderFieldCollection.each(function(index, el){
            try {
                var valOrder = parseInt($(this).val());
                if (valOrder > maxOrder) {
                    maxOrder = valOrder;
                }
            } catch (ex) {}
        });
        newElem.find("input[name*='PAGE_ZONE_MULTI_ORDER']").val(maxOrder+1);
        
        // Génération d'un identifiant unique pour le multi + mémorisation dans l'index + affichage
        try {
            var hash = generateMultiHash();
            var multiname = name.replace(/\d+$/, '');
            MultiMetadataManager.register("added_multi_index", multiname, hash);
            newElem.find("input[type='hidden'][name$='MULTI_HASH']").val(hash);
            newElem.find("a.multi-hash-display").attr("title", hash).text(hash.substr(0, 7));
        } catch (ex) {
            console.log("MULTI_HASH generation failed (new element) (" + ex + ")");
        }

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
    
    // Mémorisation de l'ID du multi supprimé
    try {
        // Sélection du champ contenant l'identifiant de l'élément multi supprimé
        // Ce champ varie d'une tranche à une autre (MULTI_HASH pour les push, PAGE_ZONE_MULTI_ID pour slideshow)
        var multiidFieldnames = ['MULTI_HASH', 'PAGE_ZONE_MULTI_ID'];
        var idInputEl = null;
        for (var i in multiidFieldnames) {
            idInputEl = $('#' + name + '_' + multiidFieldnames[i]);
            if (idInputEl.length != 0) {
                break;
            }
        }
        
        // Enregistrement de l'ID supprimé dans le formulaire
        var multiHash = idInputEl.val();
        var multiname = name.replace(/\d+$/, '');
        if (multiHash) {
            MultiMetadataManager.register("deleted_multi_index", multiname, multiHash);
        }
    } catch (ex) {
        if (window.console) {
            console.log(ex);
        }
    }

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

    // Nettoyage des champs cachés résiduels (en dehors de subForm) dans le formulaire principal (fForm) et dans le formulaire popin perso (fForm1)
    $('form#fForm').find("input[type='hidden'][name^='" + name + "']").remove();
    $('form#fForm1').find("input[type='hidden'][name^='" + name + "']").remove();
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

/**
 * Objet de gestion de métadonnées sur les multi
 * Les données sont mémorisées dans des champs hidden (appartement à l'élément formElement)
 * Chaque métadonnée est une collection de valeurs
 */
MultiMetadataManager = {
    /** Élément HTML auquel appartiennent les élément input hidden */
    formElement: null,
    
    /**
     * Défini formElement (à appeler une fois au chargement de la page, avant de commencer à utiliser l'objet)
     * @param formElement Élément html du formulaire (il s'agit d'un élément DOM natif, pas d'un objet jQuery)
     */
    init: function (formElement) {
        MultiMetadataManager.formElement = formElement;
    },
    
    /**
     * Insère la valeur value dans la liste correspondant au couple collection/multiname
     * Exemple : MultiMetadataManager("deleted_multi_index", "multi1_SLIDEOFFREADDFORM", "1");
     *
     * @param collection Nom de la collection de valeurs
     * @param multiname  Nom du multi, contient également le préfixe multi s'il y en a un (ex: multi1_)
     * @param value      Valeur à ajouter dans la collection
     */
    register: function (collection, multiname, value) {
        var selector = MultiMetadataManager.getSelector(collection, multiname, value);
        var inputEl = $(MultiMetadataManager.formElement).find(selector);
        
        // Création de l'élément input hidden si il n'existe pas
        if (inputEl.length == 0) {
            var inputName = MultiMetadataManager.getInputName(collection, multiname);
            inputEl = $('<input>').attr('type', 'hidden').attr('name', inputName);
            inputEl.appendTo(MultiMetadataManager.formElement);
        }
        
        // Enregistrement de la valeur
        inputEl.val(value);
    },
    
    /** Supprime l'input correspondant au couple collection/multiname et dont la valeur est value */
    unregister: function (collection, multiname, value) {
        var selector = MultiMetadataManager.getSelector(collection, multiname, value);
        $(MultiMetadataManager.formElement).find(selector).remove();
    },
    
    /**
     * Retourne le sélecteur jquery permettant de sélectionner l'élément hidden
     * correspondant à multiname (et dont la valeur est value si le paramètre est renseigné) 
     */
    getSelector: function (collection, multiname, value) {
        var inputName = MultiMetadataManager.getInputName(collection, multiname);
        var selector = "> input[type='hidden'][name='" + inputName + "']";
        if (value) {
            selector += "[value='" + value + "']"
        }
        return selector;
    },
    
    /**
     * Retourne le nom du champ hidden correspondant à multiname
     */
    getInputName: function (collection, multiname) {
        return collection + "[" + multiname + "][]";
    }
};

/**
 * Génération d'identifiant aléatoire
 * Source :
 *   http://phpjs.org/functions/uniqid/
 *   https://github.com/kvz/phpjs/blob/ffe1356af23a6f2512c84c954dd4e828e92579fa/functions/misc/uniqid.js
 */
function uniqid(prefix, more_entropy) {
    if (typeof prefix === 'undefined') {
        prefix = '';
    }

    var retId;
    var formatSeed = function(seed, reqWidth) {
        seed = parseInt(seed, 10)
            .toString(16); // to hex str
        if (reqWidth < seed.length) { // so long we split
            return seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) { // so short we pad
            return Array(1 + (reqWidth - seed.length))
                .join('0') + seed;
        }
        return seed;
    };

    // BEGIN REDUNDANT
    if (!this.php_js) {
        this.php_js = {};
    }
    // END REDUNDANT
    if (!this.php_js.uniqidSeed) { // init seed with big random int
        this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }
    this.php_js.uniqidSeed++;

    retId = prefix; // start with prefix, add current milliseconds hex string
    retId += formatSeed(parseInt(new Date()
        .getTime() / 1000, 10), 8);
    retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
    if (more_entropy) {
        // for more entropy we add a float lower to 10
        retId += (Math.random() * 10)
            .toFixed(8)
            .toString();
    }

    return retId;
}

/**
 * Génère un identifiant de multi (MULTI_HASH)
 */
function generateMultiHash(){
    var id = uniqid('', true);
    var hash = CryptoJS.SHA1(id);
    return hash.toString();
}