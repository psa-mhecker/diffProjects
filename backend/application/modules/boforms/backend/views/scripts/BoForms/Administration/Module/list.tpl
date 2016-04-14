  <style>
  {literal}
	.form_standard { background-color: #c5d5e9; }
	.form_context { background-color: #e4ecf7; }
	.dialogContactSupport .ui-widget-header { background: none; background-color:#ff0000;}
	.dialogContactSupport .ui-dialog-title { color: #fff;}
  {/literal}
  </style>	

<script type="text/javascript">
{literal}
$( document ).ready(function() {

	$('#enableForms').click(function() {
		var listForms = [];
		$('#tbody input:checked').each(function() {
		  listForms.push($(this).val());
		});

		if(listForms.length >0)
		{
			{/literal}{if $aCulture|@count > 1}{literal}
				$("#dialog-confirm2").html("{/literal}{if $aCulture|@count > 1}<span style='vertical-align:top'>{t('BOFORMS_CHOOSE_CULTURES_TO_ENABLE')}</span><select name='choix_culture' id='choix_culture' multiple size='4'>{foreach from=$aCulture item=culture}<option value='{$culture.CULTURE_ID}'>{$culture.LANGUE_TRANSLATE}</option>{/foreach}</select>{/if}{literal}");
				$("#dialog-confirm2").dialog({
							        resizable: false,
							        modal: true,
							        title: "{/literal}{t('BOFORMS_CHANGESTATUS_TITLE_POPIN')}{literal}",
							        height: 170,
							        width: 300,
							        buttons: {
				        	    		"ok": function () {
						            	
							              var comment = "";	
							              var choosen_culture = '';
							              
							              {/literal}{if $aCulture|@count > 1}{literal}
											  $("select[id='choix_culture'] option:selected").each(function() {
							            		  if (choosen_culture == '') {
								            		  choosen_culture = $(this).val();
							            		  } else {
							            			  choosen_culture = choosen_culture  + ',' + $(this).val();
							            		  }
							                  }); 
							              {/literal}{/if}{literal}					              
								              
							              loaderAjax('dialog-confirm2');		            	
							              ChangeStatusForms("{/literal}{t('BOFORMS_CHANGESTATUS_CONFIRM_ENABLE')}{literal}",1,"", choosen_culture);
							            }							            
							        },
							        beforeClose: function( event, ui ) {
							        	
						        	}
					});
			{/literal}{else}{literal}
				ChangeStatusForms("{/literal}{t('BOFORMS_CHANGESTATUS_CONFIRM_ENABLE')}{literal}", 1, "", "{/literal}{$aCulture[0].CULTURE_ID}{literal}");
			{/literal}{/if}{literal}	
		}
	});
	
	$('#disableForms').click(function() {
		var listForms = [];
		$('#tbody input:checked').each(function() {
		  listForms.push($(this).val());
		});
		
		if(listForms.length >0)
		{
			$("#dialog-confirm").html("<div>{/literal}{t('BOFORMS_CHANGESTATUS_LABEL_FIELD')}{literal} :</div><textarea rows='3' cols='35' id='TextAreaComment'>{/literal}{t('BOFORMS_COMMENTARY_DEFAULT')}{literal}</textarea>{/literal}{if $aCulture|@count > 1}<span style='vertical-align:top'>{t('BOFORMS_CHOOSE_CULTURES_TO_DISABLE')}</span><select name='choix_culture' id='choix_culture' multiple size='4'>{foreach from=$aCulture item=culture}<option value='{$culture.CULTURE_ID}'>{$culture.LANGUE_TRANSLATE}</option>{/foreach}</select>{else}{foreach from=$aCulture item=culture}<input type='hidden' id='singleCulture2' name='singleCulture2' value='{$culture.CULTURE_ID}' />{/foreach}{/if}{literal}");
			
			$("#dialog-confirm").dialog({
						        resizable: false,
						        modal: true,
						        title: "{/literal}{t('BOFORMS_CHANGESTATUS_TITLE_POPIN')}{literal}",
						        height: 200,
						        width: 300,
						        buttons: {
			        	    		"ok": function () {
					            	
						              var comment = "";	
						              
						              if($('#TextAreaComment').val())
						              {
						              	comment = $('#TextAreaComment').val();
						              }

						              choosen_culture = '';
						              
						              {/literal}{if $aCulture|@count > 1}{literal}
						            	  $("select[id='choix_culture'] option:selected").each(function() {
						            		  if (choosen_culture == '') {
							            		  choosen_culture = $(this).val();
						            		  } else {
						            			  choosen_culture = choosen_culture  + ',' + $(this).val();
						            		  }
						                  }); 
							          {/literal}{else}{literal}choosen_culture = $('#singleCulture2').val();{/literal}{/if}{literal}
							              
						              loaderAjax('dialog-confirm');		            	
						              ChangeStatusForms("{/literal}{t('BOFORMS_CHANGESTATUS_CONFIRM_DISABLED')}{literal}",0,comment, choosen_culture);
						            }							            
						        },
						        beforeClose: function( event, ui ) {
						        
					        		
					        	}
						    });
		}
		
	});
	
	
	
	

	$('#checkAll').click(function() {
		$('#tbody :checkbox').attr('checked', true);        
	});
	
	$('#decheckAll').click(function() {		
		$('#tbody :checkbox').attr('checked', false);        
	});

	$( "#dialog" ).dialog({ 
			autoOpen: false, 
			width:700, height: 450,
			modal: false, dialogClass: "dialogContactSupport",
			open: function (event, ui) {
				$("#dialog iframe").attr("src", 
						"/_/module/boforms/BoForms_Administration_SupportRequest/supportDialogNewForm?groupe_id={/literal}{$smarty.get.groupe_id}{literal}&time=" + new Date().getTime());
				$('#dialog').css('overflow', 'hidden'); //this line does the actual hiding
			},
			close: function (event, ui) {
				// empty the iframe
				$("#dialog iframe").html("/_/module/boforms/BoForms_Administration_SupportRequest/emptyTask?time=" + new Date().getTime());
			},
			buttons: {
				"{/literal}{t('BOFORMS_CLOSE_POPUP')}{literal}": function() { 
				      $(this).dialog("close");
				}
			}
	});
	$( "#opener" ).click(function() {
	  	$( "#dialog" ).dialog( "open" );
	  	$('#ui-dialog-title-dialog').css('font-weight','bold');
	  	$('#ui-dialog-title-dialog').css('font-size','13px');
	}); 
});

function ChangeStatusForms(msg_confirm, editable, comment, choosen_culture){
		var listForms = [];
		$('#tbody input:checked').each(function() {
		  listForms.push($(this).val());
		});

		if(listForms.length >0)
		{
			$.ajax({
					type : "POST",
					url: '/_/module/boforms/BoForms_Administration_Module/ChangeStatusForms',
		            data:  {listForms : listForms, editable : editable, comment : comment, time: new Date().getTime(), cultures: choosen_culture},
					
					success: function( data ) {
					 	
						if ( editable == 1) {
							dialog_id = 'dialog-confirm2';
						} else {
							dialog_id = 'dialog-confirm';
						}
							
						$("#" + dialog_id).html(data);
						
					    $("#" + dialog_id).dialog({
					        resizable: false,
					        modal: true,
					        title: '{/literal}{t('BOFORMS_LABEL_SUCCESS')}{literal}',
					        height: 165,
					        width: 300,
					        buttons: {
					            "ok": function () {
					               window.location.reload()
					                
					            }
					        },
					        beforeClose: function( event, ui ) {
					        
				        		window.location.reload()
				        	}
					    });
					 	
					}
			});
		}
}

function substr_replace(str, replace, start, length) {
	  if (start < 0) { // start position in str
	    start = start + str.length;
	  }
	  length = length !== undefined ? length : str.length;
	  if (length < 0) {
	    length = length + str.length - start;
	  }
	  return str.slice(0, start) + replace.substr(0, length) + replace.slice(length) + str.slice(start + length);
}

//script fonction javascript "OpenForm" pour l'ouverture du Formbuilder 
function OpenForm(sCode,isEditable,sComment,culture,test)
{
	if(culture.length == 1 && culture<10)
	{
		culture='0'+culture;
	}
	
	sCode = substr_replace(sCode, culture, 10, 2);
	
	if(sCode.substr(5, 1)=="9")
	{
		sCode = substr_replace(sCode, "0", 5, 1);
	}
	if(isEditable==0)
	{
		$("#dialog-confirm").html(decodeURIComponent(sComment));
		$("#dialog-confirm").dialog({
	        resizable: false,
	        modal: true,
	        title: "code instance : "+ sCode,
	        height: 150,
	        width: 300,
	        buttons: {
	            "OK": function () {
	            	$(this).dialog("close");
	            }
	        }
	    });
	}else{
		url = "/_/module/boforms/BoForms_Administration_Module/editor?code_instance="+sCode + "&time=" + new Date().getTime();    			
		javascript:window.open(url);
	}
}
</script>

{/literal}

<div id="dialog-confirm"></div>
<div id="dialog-confirm2"></div>

<div id="dialog" title="{$popup_title}">
	<iframe height="100%" width="99%" frameborder=0 src="/_/module/boforms/BoForms_Administration_SupportRequest/emptyTask"></iframe>
</div>

<div style="width: 100%; margin-bottom:25px;margin-right:10px;">
	<div style="float:right;">
		<a href="#" id="opener" class="btn info" >{$popup_link}</a>
		
		{if $LP_link}
			<div style="margin-top:10px;"><a href="{$LP_href}" target="_blank" id="opener" class="btn info" >{$LP_link}</a></div>
		{/if}
	</div>
</div>
<div style="clear:both;"></div>

{$table_list}

{if $isAdmin}
<div>
	<span id='checkAll' style='color:#014ea2;cursor: pointer;text-decoration:underline;'>{t('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLCHECK')}</span> / <span style='color:#014ea2;cursor: pointer;text-decoration:underline;' id='decheckAll'>{t('BOFORMS_CHANGESTATUS_LABEL_ACTION_ALLDCHECK')}</span> <span>{t('BOFORMS_CHANGESTATUS_LABEL_SELECTION')} :</span> 
	<span id='enableForms' style='color:#014ea2;cursor: pointer;text-decoration:underline;'>{t('BOFORMS_CHANGESTATUS_LABEL_ACTION_ACTIVATE')}</span> / <span id='disableForms' style='color:#014ea2;cursor: pointer;text-decoration:underline;'>{t('BOFORMS_CHANGESTATUS_LABEL_ACTION_DEACTIVATE')}</span> {t('BOFORMS_CHANGESTATUS_LABEL_FORM')}
</div>
{/if}