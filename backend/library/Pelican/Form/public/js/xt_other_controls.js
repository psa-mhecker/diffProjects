function isCreditCard(st) {
	// Encoding only works on cards with less than 19 digits with Luhn mod-10
	if (st.length > 19) return (false);
		sum = 0;
	mul = 1;
	l = st.length;
	for(i = 0; i < l; i++) {
		digit = st.substring(l-i-1, l-i);
		tproduct = parseInt(digit , 10) * mul;
		if (tproduct >= 10) {
			sum += (tproduct % 10) + 1;
		} else {
			sum += tproduct;
		}
		if (mul == 1) {
			mul++;
		} else {
			mul--;
		}
	}
	if ((sum % 10) == 0) {
		return (true);
	} else {
		return (false);
	}
}
 
function isVisa(cc) {
	if (((cc.length == 16) || (cc.length == 13)) && (cc.substring(0, 1) == 4)) return isCreditCard(cc);
		return false;
}
 
function isMasterCard(cc) {
	firstdig = cc.substring(0, 1);
	seconddig = cc.substring(1, 2);
	if ((cc.length == 16) && (firstdig == 5) && ((seconddig >= 1) && (seconddig <= 5))) return isCreditCard(cc);
		return false;
}
 
function isAmericanExpress(cc) {
	firstdig = cc.substring(0, 1);
	seconddig = cc.substring(1, 2);
	if ((cc.length == 15) && (firstdig == 3) && ((seconddig == 4) || (seconddig == 7))) return isCreditCard(cc);
		return false;
}
 
function isAnyCard(cc) {
	if (!isCreditCard(cc)) return false;
	 
	if (isMasterCard(cc)) alert('Master Card');
		if (isVisa(cc)) alert('Visa Card');
		if (isAmericanExpress(cc)) alert('American Express');
		 
	if (!isMasterCard(cc) && !isVisa(cc) && !isAmericanExpress(cc)) return false;
	return true;
}
 
function isSocialSecurity(ss) {
	//([1278][0-9][0-9](0[1-9]|1[0-2])(0[1-9]|[13456789][0-9]|2[023456789AB])[0-9][0-9][0-9][0-9][0-9][0-9])([0-9][0-9])
	/*  var e=a%97;
	if (e==0) { e=97; }
	if (e<10) { e="0"+e; }*/
	return true;
}