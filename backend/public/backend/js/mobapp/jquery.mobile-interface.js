var oButton;

// check if tabs is full of apps.
function CheckTabsFull(MaxApps) {
	var VerifArray = true;
	var CountTabs = 0;
	$('#EditZone .inner').each(function() {
		var idTabInner = $(this).attr('id');
		if ($('#' + idTabInner + ' li').length == 0) {
			VerifArray = false;
		}
		if ($('#' + idTabInner + ' li').length < MaxApps) {
			VerifArray = false;
			// $('#EditZone ul li:eq('+CountTabs+') a').css('color','red');
		} else {
			if ($('#' + idTabInner + ' li').length == MaxApps) {
				// $('#'+idTabInner+' ul').droppable( "option", "disabled", true
				// );
				// $('#EditZone ul li:eq('+CountTabs+') a').css('color','');
			}
		}
		CountTabs++;
	});
	return VerifArray;
}

// check and enable zones drop, messages, and tabs actions
function removePageLink() {
	var MaxApps = 9;
	var tab_counter = $('#EditZone .inner').length;
	// check if all zones are full and this zone is full
	if ((($('#EditZone #Inner' + getSelectedTabIndex('#EditZone') + ' li').length) == MaxApps)
			&& CheckTabsFull(MaxApps) == true) {
		// assign button functions
		$('#EditZone #Inner' + (tab_counter - 1) + ' .message a.button').bind(
				'click', function() {
					$('#EditZone').tabs("option", "selected", tab_counter);
					// $('#EditZone #Inner'+(tab_counter-1)+' .message').hide();
					$('#EditZone .inner .message').hide();
					// $('#EditZone .inner
					// ul').removeClass('ui-droppable-disabled');
				});
		// add tab
		$('#EditZone').tabs(
				"add",
				"#Inner" + tab_counter,
				"<img src='/images/mobapp/bullet.png' alt='Ecran "
						+ tab_counter + "' />");
		$('#Inner' + tab_counter).addClass('inner');
		// clone message in tab
		$('#Inner' + tab_counter).append(
				$(
						'#EditZone #Inner' + getSelectedTabIndex('#EditZone')
								+ ' .message').clone());
		$('#Inner' + tab_counter).append(
				$(
						'#EditZone #Inner' + getSelectedTabIndex('#EditZone')
								+ ' > .delete').clone());
		// $('#Inner' + tab_counter ).append($('#EditZone
		// #Inner'+getSelectedTabIndex('#EditZone')+' .action').clone());
		$('#Inner' + tab_counter).append('<ul></ul>');
		// enable drops on tab
		// $('#EditZone #Inner' + tab_counter + ' ul').droppable( "option",
		// "disabled", false );
		$('#EditZone #Inner' + (tab_counter - 1) + ' .message').show();
		MakeItDroppable(tab_counter);
		MakeItDraggable();
	} else {
		$('#EditZone .next').remove();
	}
	SetScreenAction();
	moveTabs();
}
// init draggable zone
function MakeItDraggable() {
	var MaxApps = 9;
	$("#ListApps li")
			.draggable(
					{
						appendTo : "#EditZone",
						// appendTo: "#EditZone #Inner0 ul",
						helper : "clone",
						start : function(event, ui) {
							if (($(
									'#EditZone #Inner'
											+ getSelectedTabIndex('#EditZone')
											+ ' ul').attr('aria-disabled') == "true")
									|| ($('#EditZone #Inner'
											+ getSelectedTabIndex('#EditZone')
											+ ' ul li').length == MaxApps)) {
								// $('#EditZone
								// #Inner'+getSelectedTabIndex('#EditZone')+'
								// ul').addClass('ui-droppable-disabled');
								$(
										'#EditZone #Inner'
												+ getSelectedTabIndex('#EditZone')
												+ ' .message').show();

								// console.log($('#EditZone
								// #Inner')+($('#EditZone
								// .inner').length));

								if ($('#EditZone #Inner')
										+ ($('#EditZone .inner').length)) {
									// console.log($('#EditZone
									// #Inner')+($('#EditZone
									// .inner').length)-1);
									// console.log(($('#EditZone
									// .inner').length));
									// alert('MakeItDraggable creat tab');
									removePageLink();
								}
								// assign last tabs to go
								$(
										'#EditZone #Inner'
												+ getSelectedTabIndex('#EditZone')
												+ ' .message a.button')
										.bind(
												'click',
												function() {
													$('#EditZone')
															.tabs(
																	"option",
																	"selected",
																	($('#EditZone .inner').length) - 1);
												});

							} else {
								// remove signs
								// $('#EditZone
								// #Inner'+getSelectedTabIndex('#EditZone')+'
								// ul').removeClass('ui-droppable-disabled');
								$(
										'#EditZone #Inner'
												+ getSelectedTabIndex('#EditZone')
												+ ' .message').hide();
							}
						}
					});
}

function removeEmptyScreen() {
	// console.log('removeEmptyScreen');
	var CountTabs = 0;
	$('#EditZone .inner').each(function() {
		if ($('#Inner' + CountTabs + ' .appli').length == 0 && CountTabs != 0) {
			$('#EditZone').tabs("remove", CountTabs);
			SetScreenAction();
		}
		CountTabs++;
	});
}

// position of bullet tabs
function moveTabs() {
	$('#EditZone .ui-tabs-nav')
			.css(
					'left',
					(($('#EditZone').width() - $('#EditZone .ui-tabs-nav')
							.width()) / 2));
}

function getButtonValues(obj) {
	obj.bind('click', function() {
		oButton = $(this).parents('.more').parents('div').children('input');
		aData = oButton.get(0).value.split('#');
		//alert(aData);
		$('#MOBAPP_SITE_HOME_ID').get(0).value = aData[0];
		$('#MOBAPP_CONTENT_TYPE_CODE').get(0).value = aData[1];
		$('#MOBAPP_SITE_HOME_LABEL').get(0).value = aData[4];
		$('#ICON').get(0).src = aData[2];
		//$('#MEDIA_ID').get(0).value = aData[2];
		$('#MEDIA_ID2').get(0).value = aData[3];
		$('#divMEDIA_ID2').get(0).width = 60;
		$('#divMEDIA_ID2').html(
				'<img id="media_" src="' + aData[3]
						+ '" alt="" width="72" height="72" border="0">');

	});
}

function setButtonValues() {
	alert($('#divMEDIA_ID2').children('img').html());
	var value = $('#MOBAPP_SITE_HOME_ID').get(0).value + '#'
			+ $('#MOBAPP_CONTENT_TYPE_CODE').get(0).value + '#'
			+ $('#ICON').get(0).src + '#' + $('#divMEDIA_ID2').children('img').attr('src')
			+ '#' + $('#MOBAPP_SITE_HOME_LABEL').get(0).value + '#';
	oButton.get(0).value = value;

	alert(oButton.get(0).value);
	
	return false;
}

function deleteButton(obj) {
	obj.bind('click', function() {
		$(this).parents('li').remove();
		removeEmptyScreen();
		removePageLink();
		$('#EditZone ul').droppable("option", "disabled", false);
	});

}

function editButton(obj) {
	obj.colorbox({
		inline : true,
		href : "#AppForm",
		innerWidth : 425,
		innerHeight : 300,
		scrolling : false
	});
}

function activeButton(obj) {
	obj.bind('hover', function() {
		$(this).children('.delete').toggle();
		$(this).children('.more').toggle();
		deleteButton($(this).children('.delete').children('a'));
		editButton($(this).children('.more').children('a'));
		getButtonValues($(this).children('.more').children('a'));
	});
}

// init droppable object
function MakeItDroppable(idTab) {
	// enable drop
	$('#EditZone #Inner' + idTab + ' ul').droppable("option", "disabled", true);
	// init drop

	// traitement de l'existant
	$("#EditZone #Inner" + idTab + " ul").children('li').each(function() {
		activeButton($(this).children());
	});

	$("#EditZone #Inner" + idTab + " ul").droppable({
		activeClass : "ui-state-default",
		hoverClass : "ui-state-hover",
		accept : ":not(.ui-sortable-helper)",
		drop : function(event, ui) {
			$(this).find(".placeholder").remove();
			$("<li></li>").html(ui.draggable.html()).appendTo(this);

			activeButton($(this).children('li:last').children());

			removePageLink();
		}
	}).sortable({
		items : "li:not(.placeholder)",
		sort : function() {
			// gets added unintentionally by droppable interacting with
			// sortable
			// using connectWithSortable fixes this, but doesn't allow you
			// to customize active/hoverClass options
			$(this).removeClass("ui-state-default");
		}
	});
}
// get activ tabs
function getSelectedTabIndex(tabs) {
	return $(tabs).tabs('option', 'selected');
}
// init tools on apps dropped
function MakeItUserFriendly(obj) {
	obj.bind('hover', function() {
		$(this).children('.delete').toggle();
		$(this).children('.more').toggle();
	});
}

function SetScreenAction() {
	var CountTabs = 0;
	// $('#EditZone .delete').hide();
	$('#EditZone > .inner').each(function() {
		if ($(this).children('.appli').length == 0) {
			if (CountTabs != 0) {
				$(this).children('.delete').bind('click', function() {
					$('#EditZone').tabs("remove", (CountTabs - 1));
				});
				$(this).bind('mouseover', function() {
					$(this).children('.delete').show();
				});
				$(this).bind('mouseout', function() {
					$(this).children('.delete').hide();
				});
			}
		}
		CountTabs++;
	});
}