<?php
include_once('config.php');

include(pelican_path('Media'));
mediaAction();
?>
<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
<script type="text/javascript">
var betd_mediaDialog = {
	insert : function(file, title,type) {

	var ed = tinyMCEPopup.editor, dom = ed.dom;
        if(type == "file") {
            tinyMCEPopup.execCommand("createlink", false, file);
        } else {
                tinyMCEPopup.execCommand('mceInsertContent', false, dom.createHTML('img', {
                    src : file,
                    alt : title,
                    title : title,
                    border : 0
                }));
         }
          tinyMCEPopup.close();
      }
};

betd_mediaDialog.insert('<?=Pelican::$config["MEDIA_HTTP"].Pelican_Db::$values["MEDIA_PATH"];?>','<?=Pelican_Db::$values["MEDIA_TITLE"];?>','<?=Pelican_Db::$values["view"];?>');

</script>