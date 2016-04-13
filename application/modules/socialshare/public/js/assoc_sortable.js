function getSelected(obj){
	var selected_string='',
	selected_array = $('#sortable').sortable('toArray'),
	selected_size = selected_array.length;
	if(selected_size<1) return;
	for(var i=0;i<selected_size;i++){
		selected_string += selected_array[i];
		if(i<selected_size-1)
		selected_string += '#';
	}
	document.getElementById(obj).value = selected_string;
}
$(function() {
	// Begin sortable list
	$('#sortable').sortable({
		items: 'li',
		placeholder: 'ui-sortable-placeholder',
		opacity: .6,
		tolerance: 'pointer'
	});

	// Service click = move to sortable list
	var moveToSortableList = function(){
		if(('#sortable li').length>0){
			$('#sortable').prev('div').remove();
		}

		$(this).fadeTo('fast', '.2')
		.unbind('click', moveToSortableList)
		.bind('click', moveToSelectableList)
		.clone()
		.appendTo('#sortable');

		$(this).attr( 'id', 'old_'+$(this).attr('id') )
		.effect('transfer', {
			to: $('#sortable li:last')
		})

		$('#sortable li:last').fadeTo('fast', 1);
	};

	// Service click again = move back to selectable list
	var moveToSelectableList = function(){
		$(this).fadeTo('fast', '1')
		.unbind('click', moveToSelectableList)
		.bind('click', moveToSortableList);
		$( '#'+$(this).attr('id').substr(4).replace(/\./, '\\.') ).effect('transfer', {
			to: $(this)
		})
		.remove()

		$(this).attr('id', $(this).attr('id').substr(4));
	};

	// Service click = move to sortable list
	$('#selectable li').bind('click', moveToSortableList);

	$('#sortable_refresh').click(function(){
		$(this).sortable('refresh');
	});

	// Setup onReady if plugin_bookmark_values was already set
	if(plugin_bookmark_values){
		var services = $('#selectable li').map(function(){
			return $(this).attr('id');
		})
		.get();
		$.each(plugin_bookmark_values, function(i, val){
			var service_index = $.inArray(val, services);
			if( service_index!==-1 )
			$('#selectable li').eq(service_index).click();
		});
	}
});