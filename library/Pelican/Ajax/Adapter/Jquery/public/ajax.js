Pelican = {};

(function(){

	var self;

	//constructeur
	Pelican.ajax = function(option){
		$.extend(this, option);
		self = this;
		this.call();
	};
	// variables et méthodes publiques propres à chaque instance
	Pelican.ajax.prototype = {
		type : "GET",
		data : {},
		url : "/library/Pelican/Ajax/Adapter/Jquery/public/?route=",
		debug : false,
		getInstance : function(){
			return this;
		},
		getData : function (data) {
			var datas = "";
			if(this.type.toUpperCase() == "GET"){
				if(data){
					//if(typeof option.data === "string") option.data = $.parseJSON(option.data);
					$.each(data, function(index, value) {
						datas += "&values["+index+"]="+value;
					});
				}
			} else if(this.type.toUpperCase() == "POST") {
				datas = {"values" : data};
			}
			return datas;
		},
		call : function (){
			$.ajax({
				data : this.getData(this.data)?this.getData(this.data):"&values[]=null",
				url : "/library/Pelican/Ajax/Adapter/Jquery/public/?route=" + this.url,
				type : this.type?this.type:"GET",
				dataType : this.dataType?this.dataType:'json',
				timeout : this.timeout?this.timeout:60000,
				error : function(xhr, ajaxOptions, thrownError) {
					if(this.debug == true){
						alert('Error processing request: '
								+ '/library/Pelican/Ajax/Adapter/Jquery/public/?route=' + func,
								10000);
						alert(xhr.responseText);
					}
					self.error(xhr, ajaxOptions, thrownError);
				},
				success : function(data, textStatus, jqXHR) {
					self.beforeAction(data, textStatus, jqXHR);
					if (data) {
						$.each(data, function() {

							switch (this.cmd) {
							case 'assign': {
								if (this.attr.toLowerCase() == 'innerhtml') {
									$('#' + this.id).html(this.value);
								} else {
									$('#' + this.id).attr(this.attr, this.value);
								}
								break;
							}
							case 'append': {
								$('#' + this.id).append(this.value);
								break;
							}
							case 'prepend': {
								$('#' + this.id).prepend(this.value);
								break;
							}
							case 'replace': {
								var ori = $('#' + this.id).attr(this.attr);
								$('#' + this.id).attr(this.attr,
										ori.replace(this.search, this.value));
								break;
							}
							case 'clear': {
								$('#' + this.id).removeAttr(this.attr);
								break;
							}
							case 'remove': {
								$('#' + this.id).remove();
								break;
							}
							case 'redirect': {
								document.location.href = this.url;
								/*
								 * this.'delay';
								 */
								break;
							}
							case 'reload': {
								document.location.reload();
								break;
							}
							case 'alert': {
								alert(this.value);
								break;
							}
							case 'script': {
								delegate(this.value);
								break;
							}
							}
						});
					}
					self.afterAction(data, textStatus, jqXHR);
					self.success(data, textStatus, jqXHR);
				}
			});
		},
		afterAction : function(data, textStatus, jqXHR){},
		error : function(xhr, ajaxOptions, thrownError){},
		beforeAction : function(data, textStatus, jqXHR){},
		success : function(data, textStatus, jqXHR){}
	};
})();


function doAjax(option) {

	if(typeof(option) === "object"){
		new Pelican.ajax(option);
	} else {
		var values = new Array();
		for ( var i = 1; i < arguments.length; i++) {
			values[i-1] = arguments[i];
		}
		new Pelican.ajax({
			url: arguments[0],
			data: values
		});
	}

};

function formGetData(formId) {
	var inputs = $('#'+formId+' :input');
	var datas = '';

	inputs.each(function() {
		datas += "&values["+this.name+"]="+$(this).val();
	});

	return datas;
}

function delegate(func) {
	eval(func);
}
function loadingAjax() {
};