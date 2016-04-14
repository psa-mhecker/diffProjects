function isNumeric(strSaisie) {
	var re = new RegExp("^([\-]?[0-9]*)$");
	if (re.test(strSaisie)) {
		return true;
	}
	return false;
}
 
function isFloat(strSaisie) {
	var re = new RegExp("^([\-]?[0-9]*[\\.\\,]?[0-9]+)$");
	if (re.test(strSaisie)) {
		return true;
	}
	return false;
}
 
function isTel(strSaisie) {
	var re = new RegExp("^([0-9\\.\\-\\ ]*)$");
	if (!isBlank(strSaisie) && re.test(strSaisie)) {
		return true;
	}
	return false;
}

function isEAN13(gc) {
	var gencode = new String(gc);
	if (gencode.length == 13) {
		for (i = 0; i < 13; i++) {
			var strI = gencode.substring(i,(i+1));
			if (!isNumeric(strI)) {
				return false;
			}
		}
		var checksum = 0;
		for (i = 11; i >= 0; i = i - 2) {
			checksum = checksum + new Number(gencode.substring(i,(i+1)));
		}
		checksum = checksum * 3;
		for (i = 10; i >= 0; i = i - 2) {
			checksum = checksum + new Number(gencode.substring(i,(i+1)));
		}
		checksum = (((10 - checksum)%10)+10)%10;
		if (checksum != new Number(gencode.substring(12,13))) {
			return false;
		}
	} else {
		return false;
	}
	return true;
}
