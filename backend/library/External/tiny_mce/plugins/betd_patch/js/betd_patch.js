var parentWin = (!window.frameElement && window.dialogArguments) || opener || parent || top;
var tinyMCEPopup = parentWin.tinyMCE;
tinyMCEPopup.editor = tinyMCEPopup.activeEditor;
tinyMCEPopup.close = function(){
	this.activeEditor.windowManager.close();
}
