$(document).ready(function(){
	$('#personnal-datas input[name=\'email\']').on('input', function() {
		if ($('#personnal-datas input[name=\'confirmEmail\']').attr('disabled')=='disabled') {
			$('#personnal-datas input').removeAttr('disabled');
			$('#personnal-datas select').removeAttr('disabled');
			$('#personnal-datas submit').removeClass('disabled');
			$('#personnal-datas > span.error').hide();
			$('#personnal-datas div.alredayaccount').hide();
			validationLock = false;
		}
    });
    $('#personnal-datas input[name=\'email\']').on('blur', function() {
		email = $('#personnal-datas input[name=\'email\']').val();
		callAjax({
			type: 'POST',
			url: "/_/User/emailValide",
			dataType: "json",
			data: {
				USR_EMAIL: email
			}
		});
	});
});
