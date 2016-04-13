function loadFormsResources() {
    var brand = formParams.brand.toLowerCase();
    if(brand === "ap"){
        var css_list = [
            "/version/vc/css/debug_"+formParams.context.toLowerCase()+".css",
            "/version/vc/css/"+formParams.context.toLowerCase()+"_common.css",
            "/version/vc/css/"+formParams.context.toLowerCase()+"_peugeot.css"
        ];
    }else if(brand === "ds"){
        var css_list = [
            "/version/vc/css/debug_"+formParams.context.toLowerCase()+".css",
            "/version/vc/css/"+formParams.context.toLowerCase()+"_common_isobar.css",
            "/version/vc/css/"+formParams.context.toLowerCase()+"_ds_isobar.css",
            "/version/vc/css/"+formParams.context.toLowerCase()+"_ds_infotel.css"
        ];
    }else{
        var css_list = [
            "/version/vc/css/debug_"+formParams.context.toLowerCase()+".css",
            "/version/vc/css/"+formParams.context.toLowerCase()+"_common.css",
            "/version/vc/css/"+formParams.context.toLowerCase()+"_citroen.css"
        ];
    }
        
    // Ajout de CSS supplementairs (specifiques au Consumer)
    if (formParams.otherCss.length) {
        for (var i=0; i<formParams.otherCss.length; i++) {
            css_list.push(formParams.otherCss[i]);
        }
    }


    for (i = 0; i < css_list.length; i++) {
        addcss(css_list[i]);
    }

    var folder = (formParams.context === 'mobile') ? 'scriptmobile' : 'script';
    var js_list = [
        "/version/vc/"+folder+"/inputmask.js",
        "/version/vc/"+folder+"/jquery.inputmask.js",
        "/version/vc/"+folder+"/webFormsAppGlobal.js"
    ];
    addjs(js_list, 0);
}

function addcss(url) {
    var lien_css = document.createElement('link');
    lien_css.href = url;
    lien_css.rel = "stylesheet";
    lien_css.type = "text/css";
    document.getElementsByTagName("head")[0].appendChild(lien_css);
}

function addjs(js_list, position) {
    $.getScript(js_list[position]).done(function (script, textStatus) {
        if (position < js_list.length - 1) {
            addjs(js_list, position + 1);
        }
        else {
            loadFormsParameters();
        }
    }).fail(function (jqxhr, settings, exception) {
        console.log('JS non charge');
    });
}
