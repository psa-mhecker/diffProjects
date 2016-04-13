/**
 * $Id$
 *
 * Korean phonetic IME
 *
 * This software is protected by patent No.2009611147 issued on 20.02.2009 by Russian Federal Service for Intellectual Property Patents and Trademarks.
 *
 * @author Konstantin Wiolowan
 * @copyright 2007-2009 Konstantin Wiolowan <wiolowan@mail.ru>
 * @version $Rev$
 * @lastchange $Author$ $Date$
 */
function(i,I,l){var o={'-':'-','а':'ㅏ','А':'ㅏ','б':'ㅂ','Б':'ㅃ','в':'ㅗ','В':'ㅗ','г':'ㄱ','Г':'ㄲ','д':'ㄷ','Д':'ㄸ','е':'ㅔ','Е':'ㅔ','ё':'ㅛ','Ё':'ㅕ','ж':'ㅈ','Ж':'ㅈ','з':'ㅈ','З':'ㅈ','и':'ㅣ','И':'ㅣ','й':'ㅣ','Й':'ㅣ','к':'ㄱ','К':'ㄲ','л':'ㄹ','Л':'ㄹ','м':'ㅁ','М':'ㅁ','н':'ㄴ','Н':'ㅇ','о':'ㅗ','О':'ㅓ','п':'ㅂ','П':'ㅃ','р':'ㄹ','Р':'ㄹ','с':'ㅅ','С':'ㅆ','т':'ㄷ','Т':'ㄸ','у':'ㅜ','У':'ㅜ','ф':'ㅍ','Ф':'ㅍ','х':'ㅎ','Ч':'ㅎ','ц':'ㅉ','Ц':'ㅉ','ч':'ㅈ','Ч':'ㅉ','ш':'ㅅ','Ш':'ㅅ','щ':'ㅅ','Щ':'ㅅ','ъ':'ъ','ы':'ㅡ','Ы':'ㅡ','ь':'ㅓ','Ь':'ㅓ','э':'ㅐ','Э':'ㅐ','ю':'ㅠ','Ю':'ㅠ','я':'ㅑ','Я':'ㅑ'},O="ьЬаАеЕёЁиИйЙОоуУыЫэЭюЮяЯ",Q="ㅕㅕㅑㅑㅖㅖㅕㅛㅣㅣㅣㅣㅕㅛㅠㅠㅡㅡㅒㅒㅠㅠㅑㅑ",_=VirtualKeyboard.Langs.KR,c=_.parseHangul(I);if(c==null){var C,e;if((C=o[i])&&(e=_.Jamo[C])){var v='\u0448\u0428\u0439\u0419\u0432\u0412'.indexOf(i);if(v>=0)_.flags|=parseInt('112244'.charAt(v),16);if(e[0]&1){return[String.fromCharCode(50500+e[1]),1]}}}else{switch(i){case'-':_.flags=0;return[I,0];case'\u044a':if(c&&c[2]&&c[2]==4)return[String.fromCharCode(c[0]+c[1]+21),1];else return[I,I&&1||0];break;case'\u0445':var V='\u3142\u3137\u3148\u3131'.indexOf(I);if(V!=-1)return['\u314d\u314c\u314a\u314b'.charAt(V),1];else if(c[2])switch(c[2]){case 1:return[String.fromCharCode(c[0]+c[1]+24),1];case 7:return[String.fromCharCode(c[0]+c[1]+25),1];case 17:return[String.fromCharCode(c[0]+c[1]+26),1];case 22:return[String.fromCharCode(c[0]+c[1]+23),1];case 11:return[String.fromCharCode(c[0]+c[1]+14),1]}break;case'\u0436':if(I=='\u3148'||I=='\u3137')return['\u3148',1];else if(c[2]){if(c[2]==22)return[I,1];else if(c[2]==7)return[String.fromCharCode(c[0]+c[1]+22),1]}break;case'\u0448':case'\u0428':_.flags=1;return[I+'\u3145',1];break;case'\u0439':case'\u0419':if(c[1]==-1||c[2])_.flags=2;break;case'\u0432':case'\u0412':_.flags=4;break;default:if(c&&(_.flags&1&&c[1]==-1||_.flags&2&&c[2]==0)){var x;if((x=O.indexOf(i))!=-1){_.flags&=~3;return _.charProcessor(Q.charAt(x),_.CV2C[(c[0]-44032)/588],[c[0],-1,0])}}}}return _.charProcessor(o[i]||i,I,l,c,1);}
