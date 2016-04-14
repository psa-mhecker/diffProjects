$(function() {
	MakeItDraggable();
	$('#EditZone').tabs();
	/*
	$('#EditZone').tabs({
   		select: function(event, ui) {
			
		}
	});
	*/
	//init screen tools
	SetScreenAction();
	//init first droppable tabs
	MakeItDroppable(0);
	//inti bullet tabs position
	moveTabs();
	$('#EditZone .inner .delete').hide();
	//cosmetic actions
	$('#EditZone ul').removeClass('ui-widget-header');
	$('#EditZone ul').removeClass('ui-corner-all');
	$('#EditZone ul li').removeClass('ui-state-default');
	$('#EditZone ul li').removeClass('ui-corner-top');
});

//check if tabs is full of apps.
function CheckTabsFull(MaxApps){
	var VerifArray = true;
	var CountTabs = 0;
	$('#EditZone .inner').each(function(){		
		var idTabInner = $(this).attr('id');
		if($('#'+idTabInner+' li').length==0){
			VerifArray = false;
		}
		if($('#'+idTabInner+' li').length<MaxApps){
			VerifArray = false;
			//$('#EditZone ul li:eq('+CountTabs+') a').css('color','red');			
		} else {
			if($('#'+idTabInner+' li').length==MaxApps){
				$('#'+idTabInner+' ul').droppable( "option", "disabled", true );
				//$('#EditZone ul li:eq('+CountTabs+') a').css('color','');
			}
		}
	CountTabs++;
	});
	return VerifArray;
}

//check and enable zones drop, messages, and tabs actions
function removePageLink(){
	var MaxApps = 9;
	var tab_counter = $('#EditZone .inner').length;
	//check if all zones are full and this zone is full
	if((($('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' li').length)==MaxApps) && CheckTabsFull(MaxApps)==true){
		//assign button functions
		$('#EditZone #Inner'+(tab_counter-1)+' .message a.button').bind('click', function(){
			$('#EditZone').tabs( "option", "selected", tab_counter );
			//$('#EditZone #Inner'+(tab_counter-1)+' .message').hide();
			$('#EditZone .inner .message').hide();
			$('#EditZone .inner ul').removeClass('ui-droppable-disabled');
		});
		//add tab
		$('#EditZone').tabs( "add", "#Inner" + tab_counter, "<img src='images/bullet.png' alt='Ecran " + tab_counter +"' />");
		$('#Inner' + tab_counter ).addClass('inner');
		//clone message in tab
		$('#Inner' + tab_counter ).append($('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' .message').clone());
		$('#Inner' + tab_counter ).append($('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' > .delete').clone());
		//$('#Inner' + tab_counter ).append($('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' .action').clone());
		$('#Inner' + tab_counter ).append('<ul></ul>');
		//enable drops on tab
		$('#EditZone #Inner' + tab_counter + ' ul').droppable( "option", "disabled", false );
		$('#EditZone #Inner'+(tab_counter-1)+' .message').show();
		MakeItDroppable(tab_counter);
		MakeItDraggable();
	} else {
		$('#EditZone .next').remove();
	}
	SetScreenAction();
	moveTabs();
}

function removeEmptyScreen(){
	//console.log('removeEmptyScreen');
	var CountTabs = 0;
	$('#EditZone .inner').each(function(){
		if($('#Inner'+CountTabs+ ' .appli').length==0 && CountTabs!=0){
			$('#EditZone').tabs( "remove" , CountTabs);
			SetScreenAction();
		}
		CountTabs++;
	});
}

//position of bullet tabs
function moveTabs(){
	$('#EditZone .ui-tabs-nav').css('left', (($('#EditZone').width()-$('#EditZone .ui-tabs-nav').width())/2));
}

//init droppable object
function MakeItDroppable(idTab){
	//enable drop
	$('#EditZone #Inner'+idTab+' ul').droppable( "option", "disabled", true );
	//init drop
	$("#EditZone #Inner"+idTab+" ul" ).droppable({
			activeClass: "ui-state-default",
			hoverClass: "ui-state-hover",
			accept: ":not(.ui-sortable-helper)",
			drop: function( event, ui ) {
				$( this ).find( ".placeholder" ).remove();
				$( "<li></li>" ).html( ui.draggable.html() ).appendTo( this );
				//set action on edit button
				$('#EditZone li .more a').bind('click',function(){
					$('#AppForm').show();
				});
				//set hover action on dropped app
				$(this).children('li:last').children().bind('hover',function(){
					$(this).children('.delete').toggle();
					$(this).children('.more').toggle();
					$(this).children('h4').toggle();
				});
				//set action on delete button
				$('#EditZone li .delete a').bind('click',function(){
					$(this).parents('li').remove();
					removeEmptyScreen();
					removePageLink();
					$('#EditZone ul').droppable( "option", "disabled", false );
				});
				$('#EditFormButton').bind('click',function(){
					$('#EditForm').show();
				});
				removePageLink();
			}
		}).sortable({
			items: "li:not(.placeholder)",
			sort: function() {
				// gets added unintentionally by droppable interacting with sortable
				// using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
				$( this ).removeClass( "ui-state-default" );
			}
		});
}
//get activ tabs
function getSelectedTabIndex(tabs) { 
    return $(tabs).tabs('option', 'selected');
}
//init tools on apps dropped
function MakeItUserFriendly(obj){ 
	obj.bind('hover',function(){
		$(this).children('.delete').toggle();
		$(this).children('.more').toggle();
	});
}

function SetScreenAction(){
	var CountTabs = 0;
//	$('#EditZone .delete').hide();
	$('#EditZone > .inner').each(function(){
		if($(this).children('.appli').length==0) {
			if(CountTabs!=0){
				$(this).children('.delete').bind('click', function(){
					$('#EditZone').tabs( "remove" , (CountTabs-1));
				});
				$(this).bind('mouseover', function(){
					$(this).children('.delete').show();
				});
				$(this).bind('mouseout', function(){
					$(this).children('.delete').hide();
				});
			}
		}
	CountTabs++;
	});
}

//inti draggable zone
function MakeItDraggable(){
	$( "#ListApps li" ).draggable({
		appendTo: "#EditZone",
//			appendTo: "#EditZone #Inner0 ul",
		helper: "clone",
		start: function(event, ui){
			if(($('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' ul').attr('aria-disabled')=="true") || ($('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' ul li').length==2)) {
				$('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' ul').addClass('ui-droppable-disabled');
				$('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' .message').show();
				
				//console.log($('#EditZone #Inner')+($('#EditZone .inner').length));
				
				if($('#EditZone #Inner')+($('#EditZone .inner').length)){
					//console.log($('#EditZone #Inner')+($('#EditZone .inner').length)-1);
//					console.log(($('#EditZone .inner').length));
//					alert('MakeItDraggable creat tab');
					removePageLink();				
				}
				//assign last tabs to go
				$('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' .message a.button').bind('click', function(){
					$('#EditZone').tabs( "option", "selected", ($('#EditZone .inner').length)-1 );
				});
				
			} else {
				//remove signs
				$('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' ul').removeClass('ui-droppable-disabled');
				$('#EditZone #Inner'+getSelectedTabIndex('#EditZone')+' .message').hide();
			}
		}
	});
}