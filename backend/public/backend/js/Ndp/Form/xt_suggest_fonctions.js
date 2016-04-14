/** dynamique */
var maxSuggest = 100;
//var comparaisonType = 'like';
var comparaisonType = 'begin';

/** fixe */
var ua = navigator.userAgent.toLowerCase();
var msIE = ((ua.indexOf('msie') != -1) && (ua.indexOf('opera') == -1) && (ua.indexOf('webtv') == -1));
var _tb;
var _lastPos = 0;
var _st = new Object() ;
var _ss = new Object() ;
var widthResults = new Object() ;
var hoveredSuggestion = new Object() ;
var hiddenTopAd = new Object() ;
var storedSearchString = new Object() ;
var searchArray = new Object() ;
var resultsArray = new Object() ;
var cQueries = new Object() ;
var cTimeStamps = new Object() ;
var cTemp;

function pos()
{
    if (window.innerHeight)
        return window.pageYOffset;
    if (document.documentElement && document.documentElement.scrollTop)
        return document.documentElement.scrollTop;
    if (document.body)
        return document.body.scrollTop;
    return 0;
}

function buildSearch(id, sA)
{
	widthResults[id] = 0;
	hoveredSuggestion[id] = -1;
	hiddenTopAd[id] = false;
	storedSearchString[id] = '';
	searchArray[id] = new Array();
	resultsArray[id] = new Array();
	cQueries[id] = new Array;
	cTimeStamps[id] = new Array;

    if (document.cookie.indexOf('searchqueries=') != -1) {
        cTemp = document.cookie.substring(document.cookie.indexOf('searchqueries='), document.cookie.length) + ';';
        eval('cQueries[' + id + '] = ' + unescape(cTemp.substring(14, cTemp.indexOf(','))) + ';');
    }
    for(var i = 0; i < cQueries[id].length; i++) {
        var sAIndex = sA.inArray(cQueries[id][i]);
        if (sAIndex != -1)
            sA.splice(sAIndex, 1);
    }
    searchArray[id] = cQueries[id].concat(sA);
    _st[id] = document.getElementById(id);
    _ss[id] = document.getElementById('search_suggest');
    if (_st[id]) {
    	_st[id].setAttribute('autocomplete', 'off');
	    _st[id].onkeydown = function(ev)
	    {
	        try {
			    initSearch(id);
	            searchKeyDown(id,event.keyCode);
	        }
	        catch(e) {
	            searchKeyDown(id,ev.keyCode);
	        }
	    } ;
	    _st[id].onkeyup = function(ev)
	    {
	        try {
			    initSearch(id);
	            searchKeyUp(id,event.keyCode);
	        }
	        catch(e) {
	            searchKeyUp(id,ev.keyCode);
	        }
	    } ;
	    widthResults[id] = _st[id].offsetWidth - 6;
    }
}

function initSearch(id)
{
    _ss[id].style.top = calcOffset(_st[id], 'offsetTop') + _st[id].offsetHeight - 0 + 'px';
    _ss[id].style.left = calcOffset(_st[id], 'offsetLeft') + 'px';
    _ss[id].style.width = _st[id].offsetWidth - 0 + 'px';
}

function hoverSuggestion(id, newHover, oldHover)
{
    if (oldHover != -1) {
        _ss[id].getElementsByTagName('a').item(oldHover).className = '';
    }
    if (newHover != -1) {
        _ss[id].getElementsByTagName('a').item(newHover).className = 'hovered';
        hoveredSuggestion[id] = newHover;
    }
}

function searchKeyDown(id,evKeyCode)
{
    if (_ss[id].style.display == 'block') {
        if (evKeyCode == 38 && hoveredSuggestion[id] != -1) {
            hoveredSuggestion[id]--;
            hoverSuggestion(id,hoveredSuggestion[id], hoveredSuggestion[id] + 1);
            _st[id].value = resultsArray[id][hoveredSuggestion[id]];
        }
        if (_st[id].value != '' && evKeyCode == 40 && hoveredSuggestion[id] < resultsArray[id].length -1) {
            hoveredSuggestion[id]++;
            hoverSuggestion(id,hoveredSuggestion[id], hoveredSuggestion[id]-1);
            _st[id].value = resultsArray[id][hoveredSuggestion[id]];
        }
        if (evKeyCode == 38 && hoveredSuggestion[id] == -1) {
            _st[id].value = storedSearchString[id];
		}
        if (evKeyCode != 38 && evKeyCode != 40) {
            hoveredSuggestion[id] = -1;
		}
        if (evKeyCode == 13) {
	        _ss[id].style.display = 'none';
        }
    }
}

function searchKeyUp(id,evKeyCode)
{
    if (evKeyCode != 38 && evKeyCode != 40)
        storedSearchString[id] = _st[id].value;
    if (_st[id].value != '' && evKeyCode != 13 && evKeyCode != 27 && evKeyCode != 38 && evKeyCode != 40) {
        showSearchResults(id);
    }
    if ((_st[id].value == '' || evKeyCode == 27) && _ss[id].style.display == 'block') {
        _ss[id].style.display = 'none';
    }
}

function showSearchResults(id, all)
{
    _ss[id].style.display = 'block';
    _ss[id].innerHTML = '';
    var i = 0;
    var j = 0;
    resultsArray[id] = new Array();
    while (resultsArray[id].length < maxSuggest && i < searchArray[id].length) {
		var bOK = (all || matchResult(id, i));
        if (bOK) {
            resultsArray[id][j] = searchArray[id][i];
            j++;
        }
        i++;
    }
    i = 0;
    while (resultsArray[id].length < maxSuggest && i < searchArray[id].length) {
        var doubleResult = false;
        var k = 0;
		var bOK = (all || matchResult(id, i));
        if (bOK) {
            while (!doubleResult && k < resultsArray[id].length) {
                if (searchArray[id][i] == resultsArray[id][k])
                    doubleResult = true;
                k++;
            }
            if (!doubleResult) {
                resultsArray[id][j] = searchArray[id][i];
                j++;
            }
        }
        i++;
    }
    for (var i = 0; i < resultsArray[id].length; i++) {
	    _ss[id].innerHTML += '<a onclick="selectSuggest(\'' + id+ '\',\'' + resultsArray[id][i]  + '\');" style="width:' + widthResults[id] + 'px;" onmouseover="hoverSuggestion(\'' + id + '\',' + i + ', hoveredSuggestion[\'' + id + '\'], 0)">' + resultsArray[id][i] + '</a>';
	}
}

function matchResult(id, i) {
	switch (comparaisonType) {
		case 'like': {
			return (searchArray[id][i].toLowerCase().indexOf(_st[id].value.toLowerCase()) != -1);
			break;
		}
		case 'begin' : {
			return (searchArray[id][i].toLowerCase().indexOf(_st[id].value.toLowerCase()) == 0);
			break;
		}
	}
}

function calcOffset(calcElement, offsetType)
{
    var calculatedOffset = 0;
    while (calcElement) {
        calculatedOffset += calcElement[offsetType];
        calcElement = calcElement.offsetParent;
    }
    return calculatedOffset;
}

Array.prototype.inArray = function(checkValue)
{
    for (var i = 0;i < this.length;i++)
    if (this[i] === checkValue)
        return i;
    return -1;
}

function selectSuggest(id,value) {
	if (value) {
		_st[id].value = value;
        _ss[id].style.display = 'none';
	}
}

function showSuggest(id) {
    if (_ss[id].style.display == 'block') {
		_ss[id].style.display = 'none';
	} else {
		initSearch(id);
		showSearchResults(id,true);
	}
}
