var hidden_multi;

function pelican_form_addMulti(obj, name, file, prefixe, compteur, limit, numberField,complement) {
	hidden_multi = document.getElementById("count_state");
	
	//Check le maximum d'iteration
	if (limit && limit <= eval(hidden_multi.value) + 1) {
		alert("Maximum atteint !");
	} else {
		//Incr�ment pour la nouvelle it�ration
		new_hidden = eval(hidden_multi.value) + 1;
		//On fait un ajax qui appelle le php pour rajouter la partie de formulaire.
		$.ajax({
			type : 'GET',
			url : libDir + "/Pelican/Form/public/popup_multi_pelican_form.php",
			data: "hmvc="+escape(file)+"&prefixe="+prefixe+"&compteur="+hidden_multi.value+"&numberField="+numberField+"&fname="+obj.name + complement,
			success : function(data){
				if (new_hidden % 2) {
					var strCss2 = "bgcolor=\"#F9FDF3\"";
				} else {			
					var strCss2 = "bgcolor=\"#FAEADA\"";
				} 
				
				var rHtml = "<table cellspacing=\"0\" " + strCss2 + " cellpadding=\"0\" class=\"multi\" id=\""+ prefixe + new_hidden + "_multi_table" + "\">"+data+"</table>";
				$("#td_"+name).append(rHtml);
				document.getElementById("count_"+name).value = new_hidden;
			}
		});
	}
}

function delMulti_Pelican_Form(number) {
	table = document.getElementById(number + "multi_table");
	display = document.getElementById(number + "multi_display");
	table.style.display = "none";
	
	if (hidden_multi) {
		if (eval(hidden_multi.value) >= 1) {
			hidden_multi.value = eval(hidden_multi.value) - 1;
		}
	}
	
	display.value = "";
}
