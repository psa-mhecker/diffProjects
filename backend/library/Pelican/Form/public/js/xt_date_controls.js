function isDate(value) {
	 
	var re;
	 
	switch (dateLanguageFormat) {
		case "MM/DD/YYYY":
		{
			re = new RegExp(/(0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])[- \/.](16|17|18|19|20|21\d\d)/);
			reg1 = 2;
			reg2 = 1;
			reg3 = 3;
			break;
		}
		case "DD/MM/YYYY":
		{
			re = new RegExp(/(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.](16|17|18|19|20|21\d\d)/);
			reg1 = 1;
			reg2 = 2;
			reg3 = 3;
			break;
		}
		case "YYYY/MM/DD":
		{
			re = new RegExp(/(16|17|18|19|20|21\d\d)[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])/);
			reg1 = 3;
			reg2 = 2;
			reg3 = 1;
			break;
		}
	}
	 
	var ma = re.exec(value);
	if (ma == null) {
		return false;
	} else {
		j = eval(ma[reg1]);
		m = eval(ma[reg2]);
		y = eval(ma[reg3]);
	}
	 
	if (isNaN(j) || isNaN(m) || isNaN(y) ) {
		return false;
	}
	if ((j < 1) || (j > 31) ) {
		return false;
	}
	if ((m < 1) || (m > 12) ) {
		return false;
	}
	if (((m == 4) || (m == 6) || (m == 9) || (m == 11)) && (j == 31) ) {
		return false;
	}
	if ((m == 2) && (j == 29) && !((y%400 == 0) || ((y%4 == 0) && y%100 ) ) ) {
		return false;
	}
	if ((m == 2) && (j > 29)) {
		return false;
	}
	return true;
}
 
function isDate_edition(theDate) {
	// si c'est une date le test est bon
	if (isDate(theDate)) {
		return true;
	}
	else if (theDate.length == 7) {
		// si c'est mm/yyyy, le test est bon
		var m = theDate.substring(0, theDate.indexOf("/"));
		if (m.charAt(0) == "0" ) {
			m = m.charAt(1);
		}
		m = parseInt(m);
		var y = theDate.substring(theDate.lastIndexOf("/")+1, theDate.length);
		if (y.length < 4 || y.length > 4 ) {
			return false;
		}
		var y = parseInt(y);
		 
		for (var i = 0; i < theDate.length; i++) {
			if (isNaN(parseInt(theDate.charAt(i))) && (theDate.charAt(i) != '/') ) {
				return false;
			}
		}
		if (isNaN(m) || isNaN(y) ) {
			return false;
		}
		if ((m < 1) || (m > 12) ) {
			return false;
		}
	}
	else if (theDate.length == 4) {
		// si c'est yyyy, le test est bon
		var y = theDate;
		 
		for (var i = 0; i < theDate.length; i++) {
			if (isNaN(parseInt(theDate.charAt(i)))) {
				return false;
			}
		}
		if (isNaN(parseInt(y)) ) {
			return false;
		}
	} else {
		return false;
	}
	return true;
}
 
function isHour(strSaisie) {
	var h = strSaisie.substring(0, strSaisie.indexOf(":"));
	for (i = 0; i < strSaisie.length; i++) {
		if (isNaN(parseInt(strSaisie.charAt(i))) && (strSaisie.charAt(i) != ':') ) {
			return false;
		}
	}
	if (h.charAt(0) == "0" ) {
		h = h.charAt(1);
	}
	if (h.length > 2 ) {
		return false;
	}
	h = parseInt(h);
	var m = strSaisie.substring(strSaisie.lastIndexOf(":")+1, strSaisie.length);
	if (m.charAt(0) == "0" ) {
		m = m.charAt(1);
	}
	if (m.length > 2 ) {
		return false;
	}
	m = parseInt(m);
	if (h > 23 ) {
		return false;
	}
	if (m > 60 ) {
		return false;
	}
	return true;
}
 
function dateDiff (strDDeb, strDFin, strHDeb, strHFin) {
	if (arguments.length > 2 ) {
		dDeb = new Date(strDDeb.substring(strDDeb.lastIndexOf("/")+1, strDDeb.length), strDDeb.substring(strDDeb.indexOf("/")+1, strDDeb.lastIndexOf("/")), strDDeb.substring(0, strDDeb.indexOf("/")), strHDeb.substring(0, strHDeb.indexOf(":")), strHDeb.substring(strHDeb.lastIndexOf(":")+1, strHDeb.length));
	} else {
		dDeb = new Date(strDDeb.substring(strDDeb.lastIndexOf("/")+1, strDDeb.length), strDDeb.substring(strDDeb.indexOf("/")+1, strDDeb.lastIndexOf("/")), strDDeb.substring(0, strDDeb.indexOf("/")));
	}
	if (arguments.length > 3 ) {
		dFin = new Date(strDFin.substring(strDFin.lastIndexOf("/")+1, strDFin.length), strDFin.substring(strDFin.indexOf("/")+1, strDFin.lastIndexOf("/")), strDFin.substring(0, strDFin.indexOf("/")), strHFin.substring(0, strHFin.indexOf(":")), strHFin.substring(strHFin.lastIndexOf(":")+1, strHFin.length));
	} else {
		dFin = new Date(strDFin.substring(strDFin.lastIndexOf("/")+1, strDFin.length), strDFin.substring(strDFin.indexOf("/")+1, strDFin.lastIndexOf("/")), strDFin.substring(0, strDFin.indexOf("/")));
	}
	return (dDeb - dFin);
}
 
function dateDiffInternational (strDDeb, strDFin, strHDeb, strHFin) {
	if (arguments.length > 2 ) {
		dDeb = new Date(strDDeb.substring(0, strDDeb.indexOf("/")), strDDeb.substring(strDDeb.indexOf("/")+1, strDDeb.lastIndexOf("/")),strDDeb.substring(strDDeb.lastIndexOf("/")+1, strDDeb.length), strHDeb.substring(0, strHDeb.indexOf(":")), strHDeb.substring(strHDeb.lastIndexOf(":")+1, strHDeb.length));
	} else {
		dDeb = new Date(strDDeb.substring(0, strDDeb.indexOf("/")), strDDeb.substring(strDDeb.indexOf("/")+1, strDDeb.lastIndexOf("/")),strDDeb.substring(strDDeb.lastIndexOf("/")+1, strDDeb.length));
	}
	if (arguments.length > 3 ) {
		dFin = new Date(strDFin.substring(0, strDFin.indexOf("/")), strDFin.substring(strDFin.indexOf("/")+1, strDFin.lastIndexOf("/"))
,strDFin.substring(strDFin.lastIndexOf("/")+1, strDFin.length), strHFin.substring(0, strHFin.indexOf(":")), strHFin.substring(strHFin.lastIndexOf(":")+1, strHFin.length));
	} else {
		dFin = new Date(strDFin.substring(0, strDFin.indexOf("/")), strDFin.substring(strDFin.indexOf("/")+1, strDFin.lastIndexOf("/"))
,strDFin.substring(strDFin.lastIndexOf("/")+1, strDFin.length));
	}
	return (dDeb - dFin);
}

function verifIntervalle (obj1, obj2) {
	if (!(obj1.value) && (obj2.value)) {
		alert("La date de fin doit être remplie.");
		obj2.focus();
		return false;
	}
	if (!(obj2.value) && (obj1.value)) {
		alert("La date de début doit être remplie.");
		obj1.focus();
		return false;
	}
	if (dateDiff(obj1.value, obj2.value, '0', '0') > 0) {
		alert("La date de fin doit être postérieure à la date de début.");
		obj2.focus();
		return false;
	}
	return true;
}