<?php
echo HTML::div(array("class"=>"overlay", id=>"overlay"), HTML::div(array("class"=>"wrap")));
/*$this->setJqueryFunction('$("a[rel]").overlay(function() {
		// grab wrapper element inside content
		var wrap = this.getContent().find("div.wrap");
		// load only for the first time it is opened
		if (wrap.is(":empty")) {
			wrap.load(this.getTrigger().attr("href"));
		}
	});');*/
?>