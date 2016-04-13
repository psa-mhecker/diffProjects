(function() {

    var NameSpace = function(name) {
        var current;
        try {
            current = eval(name);
        } catch (e) {}
        if (!current) {
            var tab = name.split('.');
            window[tab[0]] = window[tab[0]] || {};
            current = window[tab[0]];
            for (var i = 1; i < tab.length; i++) {
                current[tab[i]] = current[tab[i]] || {};
                current = current[tab[i]];
            }
        }
        return current;
    };
    window.NameSpace = NameSpace;
}());
