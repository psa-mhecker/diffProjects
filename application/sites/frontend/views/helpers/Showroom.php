<?php

class Frontoffice_Showroom_Helper
{
	/**
	 * @param int $iPageId           Id de la page
	 * @param int $iLangueId         Langue id de la page
	 * @param int $iTemplateShowroom Template page Showroom acceuil
	 * Return array
	 */
	public static function getShowroomColor($iPageId,$iLangueId,$iTemplateShowroom)

	{
		$aPageShowroomColor = Pelican_Cache::fetch("Frontend/Page/Showroom", array($iPageId,$iLangueId,'',$iTemplateShowroom));
		return $aPageShowroomColor;

	}


	/**
	 * @param string sPrimaryColor    couleur primaire
	 * @param string sSecondColor     couleur secondaire
	 * Return string
	 */
	public static function getCssWithDynamicColors($sPrimaryColor,$sSecondColor,$isIframe=null)

	{
		if($isIframe != 1){
			$sCss = '
		 <style type="text/css">
		.sliceDeployableFormDesk .subtitle {
			color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_page_open .wf_title_container .wf_page_title, .sliceDeployableFormDesk .wf_page_valid .wf_title_container .wf_page_title {
			color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_page_open .wf_numbering, .sliceDeployableFormDesk .wf_page_valid .wf_numbering {
			background-color: '.$sPrimaryColor.';
			color: #fff;
		}
		.sliceDeployableFormDesk .wf_carpicker ul.wf_cars_type li a {
			border-top: 2px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_carpicker ul.wf_cars_type li.wf_active a {
			background-color: '.$sPrimaryColor.';
			color: #fff;
		}
		.sliceDeployableFormDesk .wf_box_check:before {
			border: 2px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_button input, .sliceDeployableFormDesk .wf_button button {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormDesk .wf_form_content .wf_field_input input, .sliceDeployableFormDesk .wf_form_content .wf_field_input select, .sliceDeployableFormDesk .wf_form_content .wf_field_input textarea {
		  border: 2px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_geo_btn {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormDesk .wf_validsearch button {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormMobile .wf_radio_check:before, .sliceDeployableFormDesk .wf_radio_check:before {
		  border: 3px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_radio_check.wf_checked:after, .sliceDeployableFormDesk .wf_radio_check.wf_checked:after {
		  background-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_scrollbar .overview .wf_dealer_locator_item .distance {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .subtitle {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_page_open .wf_title_container .wf_page_title, .sliceDeployableFormDesk .wf_page_valid .wf_title_container .wf_page_title {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_page_open .wf_numbering, .sliceDeployableFormDesk .wf_page_valid .wf_numbering {
		  background-color: '.$sPrimaryColor.';
		   color: #fff;
		}
		.sliceDeployableFormDesk .wf_carpicker ul.wf_cars_type li a {
		  border-top: 2px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_carpicker ul.wf_cars_type li.wf_active a {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormDesk .wf_box_check:before {
			border: 2px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_button input, .sliceDeployableFormDesk .wf_button button {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormDesk .wf_form_content .wf_field_input input, .sliceDeployableFormDesk .wf_form_content .wf_field_input select, .sliceDeployableFormDesk .wf_form_content .wf_field_input textarea {
		  border: 2px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_geo_btn {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormDesk .wf_validsearch button {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormDesk .wf_line .wf_error .wf_field_input input, .sliceDeployableFormDesk .wf_linemultiple .wf_error .wf_field_input input {
			border-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .containerMax .wf_cars label:active, .sliceDeployableFormDesk .containerMax .wf_cars label:hover, .sliceDeployableFormDesk .containerMax .wf_cars.wf_highlight_car label {
		  border-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .sb-custom .sb-dropdown {
		  border-color: '.$sPrimaryColor.' !important;
		}
		.sliceDeployableFormDesk .wf_validsearch button:hover {
		  background-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_geo_btn:hover {
		  background-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_button:hover .wf_button_input, .sliceDeployableFormDesk .wf_button:hover button, .sliceDeployableFormDesk .wf_button:hover input {
		  background-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_scrollbar .overview .wf_dealer_locator_item:hover, .sliceDeployableFormDesk .wf_scrollbar .overview .wf_dealer_locator_item:active {
		  border-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_box_check.wf_checked:after {
			color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_button input:hover{
			background-color: '.$sPrimaryColor.'!important;
		}
		.sliceDeployableFormDesk .wf_forms_global .wf_dealerlocator button.wf_geo_btn:hover {
			border: 4px solid '.$sPrimaryColor.'!important; 
			background-color: #FFF!important;
			color: '.$sPrimaryColor.'!important; 
		}
		
		 .secret .closer{
			    color: #FFF!important;
				border: 2px solid #FFF!important;
		}
		
		
	  .sliceDeployableFormDesk .wf_button input:hover, .sliceDeployableFormDesk .wf_button input:hover:hover, .sliceDeployableFormDesk .wf_button button:hover, .sliceDeployableFormDesk .wf_button button:hover:hover, .sliceDeployableFormDesk .wf_button:hover input:hover, .sliceDeployableFormDesk .wf_button:hover input:hover:hover, .sliceDeployableFormDesk .wf_button:hover button:hover, .sliceDeployableFormDesk .wf_button:hover button:hover:hover {
			 color: '.$sPrimaryColor.'!important;
			 border: 4px solid '.$sPrimaryColor.'!important;
			 background-color: #fff!important; 
		}
		.sliceDeployableFormDesk .wf_synthesis .distance, .sliceDeployableFormDesk .wf_resume_content .distance{
			 color: '.$sPrimaryColor.'!important; 
		}
		
		
		.wf_forms_global .wf_dealer_locator_item .distance, .wf_forms_global .wf_synthesis .distance, .wf_forms_global .wf_synthesis .distance strong{
			color: '.$sPrimaryColor.'!important; 
		}
	
		.sliceDeployableFormDesk .wf_button input, .sliceDeployableFormDesk .wf_button button{
			 background-color: '.$sPrimaryColor.'!important; 
		}
		
		.sliceDeployableFormDesk .wf_forms_global .wf_dealers_more_button:hover{
			border: 4px solid '.$sPrimaryColor.'!important; 
			background-color: #FFF!important;
			color: '.$sPrimaryColor.'!important;
		}
		.wf_forms_global .wf_dealers_more_button:hover, .wf_forms_global .wf_validsearch button:hover, .wf_forms_global .wf_range li.wf_cars:hover, .wf_forms_global .wf_range li.wf_cars:active{
			color: #fff!important; 
		}
		.wf_forms_global .wf_dealers_more_button, .wf_geo_btn, .wf_forms_global .wf_validsearch button{
			background-color: '.$sPrimaryColor.'!important; 
		}
		.wf_carPicker_darkCars label {
			background-color: #FFF!important;  
		}
		
		.sliceDeployableFormDesk .showroom.form .actions li a:hover {

			border: 4px solid '.$sPrimaryColor.'!important; 
			background-color: #FFF!important;
		}
		
		.sliceDeployableFormDesk .showroom.form .actions li a:hover span{
			color: '.$sPrimaryColor.'!important; 
		}
		.sliceDeployableFormDesk .wf_forms_global .wf_validsearch button:hover{
			border: 4px solid '.$sPrimaryColor.'!important; 
			background-color: #FFF!important;
			color: '.$sPrimaryColor.'!important; 
		}
		
		.wf_forms_global .wf_scrollbar .overview li:hover, .wf_forms_global .wf_scrollbar .overview li.wf_selected{
			border: 4px solid '.$sPrimaryColor.'!important; 
		}
		</style>';

		}else{
		$sCss = '
		 <style type="text/css">
		.ds .wf_dealer_locator_message,
		.ds .wf_validsearch button:hover,
		.ds .wf_geo_error,
		.ds .wf_geo_btn:hover,
		.ds .wf_form_content .sb-custom .sb-dropdown a:hover,
		.ds .wf_form_content a.popuptypeFACEBOOK:hover,
		.ds .wf_synthesis strong,
		.ds .wf_resume_content strong,
		.ds .wf_geo_and,
		.ds label,
		.ds .wf_page_valid .wf_numbering,
		.ds .wf_page_open .wf_numbering,
		.ds .wf_car_model,
		.ds .wf_form_content a,
		.ds .wf_modify:before,
		.ds .wf_synthesis h3,
		.ds .wf_resume_content h3,
		.ds .wf_html,
		.ds .wf_box_check.wf_checked,
		.ds .wf_form_content .wf_label_field,
		.ds .wf_brochurePickerCar h3,
		.ds .wf_form_content fieldset,
		.ds .wf_form_content,
		.ds .wf_form_content label,
		.ds .wf_form_content p,
		.ds .wf_form_content ul,
		.ds .wf_brochurePickerBrochures tbody,
		.ds .wf_validsearch button,
		.ds .wf_brochurePickerBrochures .wf_highlight  {
			color: #cfc3b8; /* Couleur Champagne */
		}


		.ds .wf_resume_img img {
			min-width: 200px;
		}
		.wf_resume_img img {
			min-width: 200px;
		}

		.ds .wf_html p,
		.ds .wf_html div,
		.ds .wf_synthesis,
		.ds .wf_resume_content {
			color: #cfc3b8!important;
		}


		.ds .wf_synthesis_brochurePicker .left:after {
			display: none;
		}

		.wf_synthesis_brochurePicker .left:after {
			display: none;
		}

		.mobile.ds .wf_synthesis_brochurePicker .left {
			width: inherit!important;
		}

		.mobile .wf_synthesis_brochurePicker .left {
			width: inherit!important;
		}

		.ds .wf_geo_btn,
		.ds .wf_form_content .wf_field_input input,
		.ds .wf_form_content .wf_field_input select,
		.ds .wf_form_content .wf_field_input textarea {
			background:  #9d8c7a; /* Couleur Champagne */
			color: #CCCCCC;
			border-color: #4B4A4D;
		}

		.ds .sb-custom .sb-dropdown {
			background: #9d8c7a;
			border-color:#9d8c7a;
		}

		.ds .wf_range .wf_cars {
			border-color: #9d8c7a;
		}

		.ds .wf_validsearch button:hover,
		.ds .wf_geo_btn:hover,
		.ds .sb-dropdown a:hover,
		.ds .sb-dropdown .selected {
			background-color: #796654;
		}

		.ds .wf_form_content .sb-custom .sb-dropdown a {
			color: #595959; /* dark grey - original color */
		}

		.ds .wf_form_content .wf_field_input input::-webkit-input-placeholder,
		.ds .wf_form_content .wf_field_input select::-webkit-input-placeholder,
		.ds .wf_form_content .wf_field_input textarea::-webkit-input-placeholder{ /* WebKit browsers */
			color:    #4B4A4D;
		}
		.ds .wf_form_content .wf_field_input input:-moz-placeholder,
		.ds .wf_form_content .wf_field_input select:-moz-placeholder,
		.ds .wf_form_content .wf_field_input textarea:-moz-placeholder{ /* Mozilla Firefox 4 to 18 */
			color:    #4B4A4D;
		}
		.ds .wf_form_content .wf_field_input input::-moz-placeholder,
		.ds .wf_form_content .wf_field_input select::-moz-placeholder,
		.ds .wf_form_content .wf_field_input textarea::-moz-placeholder{ /* Mozilla Firefox 19+ */
			color:    #4B4A4D;
		}
		.ds .wf_form_content .wf_field_input input:-ms-input-placeholder,
		.ds .wf_form_content .wf_field_input select:-ms-input-placeholder ,
		.ds .wf_form_content .wf_field_input textarea:-ms-input-placeholder { /* Internet Explorer 10+ */
			color:    #4B4A4D;
		}

		.ds .wf_synthesis,
		.ds .wf_resume_content,{
			color: #9D8C7C!important;
		}

		.ds .wf_page_errorMessage,
		.ds .wf_carpicker .wf_cars_errorMessage,
		.ds .wf_brochurepicker .wf_errorMessage,
		.ds .wf_brochurePickerCar .wf_modify_in:hover,
		.ds .wf_brochurePickerCar .wf_modify_in:active,
		.ds .wf_brochurePickerCar .wf_modify_in:hover:before,
		.ds .wf_brochurePickerCar .wf_modify_in:active:before,
		.ds .wf_page_valid .wf_page_title,
		.ds .wf_page_open .wf_page_title,
		.ds .wf_form_content a:hover,
		.ds .wf_modify:hover:before,
		.ds .wf_box_check.wf_checked:after,
		.ds .wf_icon_box .wf_help_icon:hover {
			color: #ad0040; /* Couleur Carmin */
		}

		.ds .wf_page_errorMessage { font-size: 20px; }

		.ds .wf_line .wf_error .wf_field_input input {
			border: 4px solid #ad0040; /* Couleur Carmin */
		}

		.ds .wf_radio_check.wf_checked:after,
		.ds .wf_page_open .wf_numbering {
			background-color: #ad0040;
		}

		.ds .wf_error .sb-custom .sb-dropdown {
			border-color: -moz-use-text-color #ad0040 #ad0040;
		}

		.ds .wf_page_valid .wf_numbering {
			background-color: #A70242;
		}

		.ds .wf_title_container .wf_page_title {
			color: #9d8c7a;
		}

		.ds .wf_numbering {
			background-color: #292220;
			color: #9d8c7a;
		}

		.ds .wf_page, .ds .wf_cars_type_content {
			border-top: 1px solid #433c35;
		}

		.ds .wf_cars_type_content {
			border: 1px solid #433c35;
		}

		/* Onglets ON */
		.ds ul.wf_cars_type li.wf_active a {
			border: 1px solid #433c35;
			border-bottom: 1px solid #0d0c0c;
			background: #0d0c0c;
			color: #9d8c7a;
		}

		.ds ul.wf_cars_type li a {
			background: #292220;
			color: #9d8c7a;
			border-bottom: 1px solid #433c35;
		}

		.ds ul.wf_cars_type li a:hover {
			color: #9d8c7a;
		}

		.ds ul.wf_cars_type li a:hover, .ds ul.wf_cars_type li a:active {
			background: #15110f;
		}

		.ds .wf_carPicker_darkCars {
			background: none;
		}

		.ds .wf_cars_type_content:before {
			display: none;
		}

		.ds .wf_cars label:hover, .ds .wf_cars label:active{
			background: #1D1A18;
		}

		.ds .wf_button input, .ds .wf_button button, .ds .wf_button .wf_button_input {
			background: #ad0040;
		}

		.ds .wf_button:hover input, .ds .wf_button:hover button, .ds .wf_button:hover .wf_button_input {
			background: #850034;
		}

		.ds .wf_dealer_locator {
			background: #FFF;
		}

		.ds .wf_highlight_car label {
			background-color: #292220;
		}


		/**
		 * MOBILE
		 */
		.ds .wf_form_content label,
		.ds .wf_form_content .wf_field_select,
		.ds .wf_dealerlocator_form .wf_searchbox_container .wf_validsearch button,
		.ds .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn:before,
		.ds .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn {
			color: #cfc3b8;
		}

		.ds .wf_resume_details span {
			color: #cfc3b8;
		}

		.ds .wf_validsearch button,
		.ds .wf_field_select:hover,
		.ds .wf_dealerlocator_form .wf_searchbox_container .wf_validsearch button,
		.ds .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn {
			background-color: #9d8c7a;
		}

		.mobile.ds .wf_form_content .wf_field_select {
			background-color: #9d8c7a;
		}


		.ds .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn:hover {
			background-color: #796654;
		}

		.ds .wf_form_content .wf_field_select {
			border-color: #9d8c7a;
		}

		.ds .wf_content .wf_error .wf_field_select select,
		.ds .wf_content .wf_error .wf_field_select select option {
			color: #ad0040;
		}

		.ds .wf_form_content .wf_page_page {
			border-color: #433c35;
		}

		/*.ds .wf_dealer_locator_item .distance {
			color: #ad0040;
		}*/


		.showroom .wf_dealer_locator_item .distance{
			color:#000000;
		}





		/*--------------------------------
		----------------------------------
				FORMULAIRE DRIVE
		----------------------------------
		--------------------------------*/

		/*BORDER BLUE ENCADREMENT TOP/BOTTOM*/
		.secret{
			margin: 0 40px 60px!important;
			padding: 20px 0 0 !important;
			border-top: 4px solid '.$sPrimaryColor.' !important;
			border-bottom: 4px solid '.$sPrimaryColor.' !important;
		}
		/*ICONE CLOSE*/
		.showroom .closer{
			width: auto;
		}

		/*TITLE*/
		.showroom section .subtitle {
			font-size: 32px;
			color: '.$sPrimaryColor.';
			text-align: left;
			font-weight: normal;
		}

		.showroom.showroom-formhead section h2.subtitle,
		.showroom.showroom-formhead section div.subtitle{
		  font-family: citroenbold, Arial, sans-serif!important;
		  font-weight: normal!important;
		  font-size: 37px!important;
		  text-align: left!important;
		  margin-bottom:0!important;
		}

		/*CHAPO*/
		.showroom section .FormulaireDeploy1Chapo {
			padding: 0 20px;
		}

		/*------------------------
			GLOBAL STYLE
		------------------------*/

		/*MARGE BLOC*/
		div#container.showroom {
		  margin-left: 12px;
		}
		.showroom .wf_form_content .wf_page_open .wf_contentPage{
			padding: 0 0 0 60px;
		}
		.showroom .wf_form_content .wf_page_valid .wf_synthesis{
			padding-left: 60px;
		}

		/*ICONE NUMBER SUBTITLE*/
		.showroom .wf_form_content .wf_title_container span.wf_numbering{
			background-color: '.$sPrimaryColor.'!important;
			color: #fff;
			border-radius: 0 !important;
			width: 32px;
			height: 32px;
			line-height: 33px;
		}
		/*SUBTITLE*/
		.showroom .wf_form_content .wf_title_container .wf_page_title {
			color: '.$sPrimaryColor.';
		}
		/*ICONE NUMBER SUBTITLE OPEN*/
		.showroom .wf_form_content .wf_page_open .wf_title_container span.wf_numbering{
			background-color: '.$sPrimaryColor.';
			color: #fff;
			border-radius: 0 !important;
			width: 32px;
			height: 32px;
			line-height: 33px;
		}

		.showroom .wf_form_content .wf_page_valid .number_container .wf_numbering{
		background-color:#868689!important;
		}
		/*SUBTITLE OPEN*/
		.showroom .wf_form_content .wf_page_open .wf_title_container .wf_page_title {
			color: '.$sPrimaryColor.';
		}
		.showroom .wf_form_content .wf_page_valid .wf_page_title{
		color:#868689!important;
		}


		/*ICONE MODIFICATION*/
		.showroom .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01 a.wf_modify:hover span{
			color: '.$sPrimaryColor.' !important;
		}
		.showroom .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01 a.wf_modify:hover:before{
			color: '.$sPrimaryColor.' !important;
		}
		.showroom .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01 a.wf_modify:active:before{
			color: '.$sPrimaryColor.' !important;
		}
		/*SEPARATION*/
		.showroom .wf_form_content .wf_content .wf_page{
			border: none !important;
		}



		/*------------------------
		PARTIE 1 SELECTION MODELE
		------------------------*/


		/*------------------------
		PARTIE 2 SELECTION PDV
		------------------------*/

		/*INPUT CODE POSTAL, VILLE,..*/
		.showroom .wf_form_content .wf_field_input .wf_searchbox_container input{
			border: 2px solid '.$sPrimaryColor.';
			height: 42px;
		}

		.showroom .sb-custom .sb-dropdown {
			border: 2px solid '.$sPrimaryColor.';
		}

		/*BUTTON OK DE VALIDATION*/
		.showroom .wf_form_content .wf_field_input .wf_validsearch button{
			background-color: '.$sPrimaryColor.';
			color: #fff;
			border-radius: 0 !important;
			width: 42px;
			height: 42px;
			padding: 0;
		}
		/*BUTTON DE GEOLOCALISATION*/
		.showroom .wf_form_content .wf_geo_wrapper button{
			background: #ffffff;
			border: 4px solid '.$sPrimaryColor.';
			border-radius: 0;
			height: 42px;
			text-transform: uppercase;
			font-size: 14px;
		}
		.showroom .wf_form_content .wf_geo_wrapper button:before{
			/*background-image: url(../images/geo-blue.png);*/
			background-repeat: no-repeat;
			background-position: center 3px;
			background-size: 26px 26px;
			width: 26px;
			height: 26px;
			text-transform: none;
		}
		.showroom .wf_form_content .wf_geo_wrapper .wf_geo_btn:before{
			content: "\007A";
			font-family: webformsIcons;
			font-size: 37px;
			position: absolute;
			left: 10px;
			line-height: 1em;
			color: '.$sPrimaryColor.';
			top: 2px;
		}
		/* AU DESSUS MAP*/
		.showroom .wf_form_content .wf_dealer_locator_message{
			color: #333333;
			font-size: 14px;
			text-transform: uppercase;
			font-weight: bold;
		}
		/*MAP*/
		.showroom .wf_form_content .wf_dealer_locator_results .wf_line{
			border: 0 !important;
			border-radius: 0px !important;
		}
		.showroom .wf_form_content .wf_dealer_locator_results .wf_line .wf_dealer_locator .viewport{
			border: 4px solid '.$sPrimaryColor.';
			border-radius: 0 !important;
			height: 479px;
		}
		.showroom .wf_form_content .wf_dealer_locator_results .wf_line .wf_dealer_locator .viewport ul.wf_dealers_wrapper li{
			padding: 20px 0 20px 0;
			margin: 0;
			padding: 15px 20px;
			border-bottom: 4px solid '.$sPrimaryColor.';
		}
		.showroom .wf_form_content .wf_dealer_locator_results .wf_line .wf_dealer_locator .scrollbar{
			height: 487px;
			margin: 0 4px 0 7px;
		}
		.showroom .wf_form_content .wf_dealer_locator_results .wf_line .wf_dealer_locator .scrollbar .track{
			height: 487px;
			width: 16px;
		}
		.showroom .wf_form_content .wf_dealer_locator_results .wf_line .wf_dealer_locator .scrollbar .track .thumb{
			background: '.$sPrimaryColor.';
			border-radius: 0 !important;
			width: 16px;
		}
		.showroom .wf_form_content .wf_dealer_locator_results .wf_line .wf_dealer_locator_map .wf_location_map{
			border: 4px solid '.$sPrimaryColor.';
		}


		/*------------------------
		PARTIE 3 COORDONNEES
		------------------------*/

		/*TITLE INPUT*/
		.showroom .wf_form_content span.wf_label_field{
			font-weight: bold;
		}
		/*INPUT*/
		.showroom .wf_form_content .wf_page_open fieldset .wf_line_textbox span.wf_field_input input{
			border: 2px solid '.$sPrimaryColor.' !important;
		}
		/*SELECT*/
		.showroom .wf_form_content .wf_page_open fieldset .wf_line_dropdown span.wf_field_select input{
			border: 2px solid '.$sPrimaryColor.' !important;
		}
		.showroom .wf_form_content .wf_page_open fieldset .wf_line_dropdown span.wf_field_select .sb-select:after{
			color: '.$sPrimaryColor.';
		}
		/*TEXTAREA*/
		.showroom .wf_form_content .wf_page_open fieldset .wf_line_textarea span.wf_field_textarea textarea{
			border: 2px solid '.$sPrimaryColor.' !important;
		}


		/*ICONE INFORMATION*/
		.showroom .wf_form_content .wf_page_open .wf_icon_box span.wf_help_icon{
			background: #fff;
			color: #4B4A4D !important;
			border: 1px solid #4B4A4D;
		}
		.showroom .wf_form_content .wf_page_open .wf_line_textbox .wf_icon_box{
			bottom: 6px !important;
		}
		.showroom .wf_form_content .wf_page_open .wf_line_dropdown .wf_icon_box{
			bottom: 18px !important;
		}
		/*CHECKBOX*/
		.showroom .wf_form_content .wf_page_open fieldset .wf_checkbox span.wf_label_field .wf_box_check{
			padding-left: 50px !important;
		}
		.showroom .wf_form_content .wf_page_open fieldset .wf_checkbox span.wf_label_field .wf_box_check:before{
			border: 1px solid '.$sPrimaryColor.' !important;
			width: 30px !important;
			height: 30px !important;
		}
		.showroom .wf_form_content .wf_page_open fieldset .wf_checkbox span.wf_label_field .wf_box_check.wf_checked:after{
			top: 5px;
			left: 7px;
			color: '.$sPrimaryColor.';
		}
		.showroom .wf_box_check.wf_checked:after{
			color: '.$sPrimaryColor.';
		}
		/*BUTTON VALIDEZ*/
		.showroom .wf_form_content .wf_page_open fieldset .wf_line_button{
			margin: 0 0 18px 0 !important;
		}
		.showroom .wf_form_content .wf_page_open fieldset .wf_line_button span.wf_button input{
			background: '.$sPrimaryColor.';
			color: #4B4A4D;
			text-transform: uppercase;
			font-weight: bold;
			font-size: 14px;
			border: 0;
			height: 48px;
			padding: 8px 20px !important;
		}
		.showroom .wf_form_content .wf_page_open fieldset .wf_line_button span.wf_button_input:after{
			display: none;
		}
		/*CONDITION UTILISATION*/
		.showroom .wf_form_content .wf_html div{
			width: 120%;
			font-size: 14px;
			color: #4B4A4D;
		}
		/*BUTTON FERMER*/
		.showroom span.popClose span{
			height: 48px;
			text-transform: uppercase;
			background: #fff;
			border: 4px solid '.$sPrimaryColor.';
			border-radius: 0;
			color: #4B4A4D;
			font-size: 14px;
			font-weight: bold;
			padding: 10px 35px;
			margin: 0;
		}


		/*--------------------------------
		----------------------------------
				FORMULAIRE BROCHURES
		----------------------------------
		--------------------------------*/


		/*------------------------
		PARTICULIER OU PRO
		------------------------*/
		.showroom .form .parttitle{
			color: '.$sPrimaryColor.';
			margin: 25px 0 !important;
			font-weight: bold;
		}
		/*CHECKBOX*/
		.showroom .form .field label:before{
			border: 1px solid '.$sPrimaryColor.' !important;
		}
		.showroom .form .field label:after{
			background: '.$sPrimaryColor.' !important;
		}
		/*BUTTON NEXT*/
		.showroom .form .actions li a{
			background: '.$sPrimaryColor.' !important;
			text-transform: uppercase;
			color: #4B4A4D;
			height: 48px;
			border-radius: 0;
			padding: 14px 0 15px
		}
		.showroom .form .actions li a span:after{
			background-image: url(../images/arrow-right-black.png);
			background-repeat: no-repeat;
			background-position: center;
			background-size: 8px 15px;
			width: 8px;
			height: 15px;
		}


		/*------------------------
		PARTIE 1 SELECTION MODELE
		------------------------*/
		/*SEPARATION*/
		.showroom .wf_form_content .wf_brochurePicker_wrapper .wf_brochurePickerCar:after{
			display: none;
		}
		/*TEXTE RECAPITULATIF*/
		.showroom .wf_form_content .wf_brochurePicker_wrapper .wf_brochurePickerInfos{

		}
		/*CHOIX BROCHURE*/
		.showroom .wf_form_content .wf_brochurePicker_wrapper .wf_brochurePickerBrochures .wf_highlight{
			font-size: 14px;
		}
		.showroom .wf_form_content .wf_brochurePicker_wrapper .wf_brochurePickerBrochures .wf_desc_table{
			color: #4B4A4D;
		}
		.showroom .wf_form_content .wf_brochurePicker_wrapper .wf_brochurePickerBrochures .wf_box_check:before{
			border: 1px solid '.$sPrimaryColor.';
		}
		/*BUTTON VALIDATION BROCHURE*/
		.showroom .wf_form_content .wf_brochurePicker_wrapper .wf_button_wrapper span button{
			background: '.$sPrimaryColor.' !important;
			text-transform: uppercase;
			color: #4B4A4D !important;
			height: 48px;
			border-radius: 0 !important;
			padding: 8px 20px;
			font-weight: bold !important;
			font-size: 14px;
		}
		.showroom .wf_form_content .wf_brochurePicker_wrapper .wf_button_wrapper span button:after{
			display: none;
		}


		/*------------------------
		PARTIE 2 SELECTION PDV
		------------------------*/


		/*IDENTIQUE AU FORMULAIRE TEST DRIVE*/


		/*--------------------------------
		----------------------------------
				FORMULAIRE OFFRES
		----------------------------------
		--------------------------------*/


		/*IDENTIQUE AUX ELEMENTS DES DEUX AUTRES FORMULAIRE*/



		/*------------------------
			FORMULAIRE MOBILE
		------------------------*/

		/*MARGE BLOC*/
		div#container.showroom {
		  margin-left: 12px;
		}
		.showroom-mobile .wf_form_content .wf_page_open .wf_contentPage{
			padding: 0 0 0 60px;
		}
		.showroom-mobile .wf_form_content .wf_page_valid .wf_synthesis{
			padding-left: 60px;
		}

		.showroom-mobile .sb-custom .sb-dropdown {
			border: 2px solid '.$sPrimaryColor.';
		}



		/*ICONE NUMBER SUBTITLE*/
		.showroom-mobile .wf_form_content .wf_title_container span.wf_numbering{
			background-color: #868689;
			color: #fff;
			border-radius: 0 !important;
			width: 32px;
			height: 32px;
			line-height: 33px;
			font-size: 20px;
			padding: 0;
		}
		/*SUBTITLE*/
		.showroom-mobile .wf_form_content .wf_title_container .wf_page_title {
			color: #868689;
		}
		/*ICONE NUMBER SUBTITLE OPEN*/
		.showroom-mobile .wf_form_content .wf_page_open .wf_title_container span.wf_numbering{
			background-color: '.$sPrimaryColor.';
			color: #fff;
			border-radius: 0 !important;
			width: 32px;
			height: 32px;
			line-height: 33px;
		}
		/*ICONE MODIFICATION*/
		.showroom-mobile .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01 a.wf_modify:hover span{
			color: '.$sPrimaryColor.' !important;
		}
		.showroom-mobile .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01 a.wf_modify:hover:before{
			color: '.$sPrimaryColor.' !important;
		}
		.showroom-mobile .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01 a.wf_modify:active:before{
			color: '.$sPrimaryColor.'!important;
		}
		/*SUBTITLE OPEN*/
		.showroom-mobile .wf_form_content .wf_page_open .wf_title_container .wf_page_title {
			color: '.$sPrimaryColor.';
		}
		/*SEPARATION*/
		.showroom-mobile .wf_form_content .wf_content .wf_page{
			border: none !important;
		}

		/*SELECTION VEHICULE*/
		.showroom-mobile .wf_form_content .wf_page_open fieldset span.wf_field_input select{
			border: 2px solid '.$sPrimaryColor.';
			border-radius: 0;
			color: '.$sPrimaryColor.';
		}
		/*COLOR ARROW CHOIX VEHICULE*/
		.showroom-mobile .wf_form_content .wf_range .wf_cars label .wf_car_model:after{
			color: '.$sPrimaryColor.';
		}
		/*BORDER CHOIX VEHICULE*/
		.showroom-mobile .wf_form_content .wf_range li.wf_cars{
			  border-top: 2px solid #e7e7e7 !important;
		}
		.showroom-mobile .wf_form_content .wf_page_page{
			  border-bottom: 4px solid #e7e7e7 !important;
		}

		/*INPUT CODE POSTAL, VILLE,..*/
		.showroom-mobile .wf_form_content .wf_field_input .wf_searchbox_container input{
			border: 2px solid '.$sPrimaryColor.';
			height: 42px;
		}
		/*BUTTON OK DE VALIDATION*/
		.showroom-mobile .wf_form_content .wf_field_input .wf_validsearch button{
			background-color: '.$sPrimaryColor.';
			color: #fff;
			border-radius: 0 !important;
			width: 42px;
			height: 42px;
			padding: 0;
		}
		/*BUTTON DE GEOLOCALISATION*/
		.showroom-mobile .wf_form_content .wf_geo_wrapper button{
			background: #ffffff;
			border: 4px solid '.$sPrimaryColor.';
			border-radius: 0;
			height: 42px;
			text-transform: uppercase;
			font-size: 14px;
			color: #333333;
		}
		.showroom-mobile .wf_form_content .wf_geo_wrapper button:before{
			background-image: url(../../images/geo-blue.png);
			background-repeat: no-repeat;
			background-position: center 3px;
			background-size: 26px 26px;
			width: 26px;
			height: 26px;
			text-transform: none;
		}
		.showroom-mobile .wf_form_content .wf_geo_wrapper .wf_geo_btn:before{
			content: "\007A";
			font-family: webformsIcons;
			font-size: 37px;
			position: absolute;
			left: 10px;
			line-height: 1em;
			color: '.$sPrimaryColor.';
			top: 2px;
		}
		.showroom-mobile .wf_form_content .wf_geo_wrapper .wf_geo_btn{
			width: 100% !important;
		}

		/*CODE POSTAL, LIEU*/
		.showroom-mobile .wf_form_content span.wf_searchbox_container{
			background: #ffffff;
			border: 4px solid '.$sPrimaryColor.';
			border-radius: 0;
			height: 42px;
			text-transform: uppercase;
			font-size: 14px;
			width: 99% !important;
		}
		/* AU DESSUS MAP*/
		.showroom-mobile .wf_form_content .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner li.wf_dealer_locator_item{
			border-bottom: 2px solid #e7e7e7 !important;
		}
		.showroom-mobile .wf_form_content .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner li.wf_dealer_locator_item span.distance{
			font-style: normal;
			font-weight: bold;
			color: #333235;
		}
		.showroom-mobile .wf_form_content .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner li.wf_dealer_locator_item address{
			color: #333235;
		}
		.showroom-mobile .wf_form_content .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner li.wf_dealer_locator_item:after{
			color: '.$sPrimaryColor.';
		}

		/*BUTTON PLUS DE RESULTAT*/
		.showroom-mobile .wf_form_content .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealers_more .wf_dealers_more_button{
			background: #ffffff;
			border: 4px solid '.$sPrimaryColor.';
			border-radius: 0;
			height: 42px;
			text-transform: uppercase;
			font-size: 14px;
			color: #333333;
		}
		/*BUTTON PLUS DE RESULTAT >> ARROW */
		.showroom-mobile .wf_form_content .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealers_more .wf_dealers_more_button:after{
			content: "\0074";
			font-family: "webformsIcons";
			display: block;
			margin-left: 25%;
			z-index: 1;
			-moz-transform: rotate(-90deg);
			-webkit-transform: rotate(-90deg);
			-ms-transform: rotate(-90deg);
			transform: rotate(-90deg);
		}

		/*TEXTE SUBTITLE PARTIE VOS COORDONNEES*/
		.showroom-mobile .wf_form_content .wf_html{
			font-size: 14px !important;
			color: #333235 !important;
			font-weight: lighter !important;
		}
		/*BULLE INFO*/
		.showroom-mobile .wf_form_content .wf_page_open .wf_icon_box span.wf_help_icon{
			background: #fff;
			color: #4B4A4D !important;
			border: 1px solid #4B4A4D;
		}
		/*CHECKBOX*/
		.showroom-mobile .wf_form_content .wf_input_group .wf_radio_check{
			padding-left: 50px !important;
		}
		.showroom-mobile .wf_form_content .wf_input_group .wf_radio_check:before{
			border: 1px solid '.$sPrimaryColor.' !important;
			width: 30px !important;
			height: 30px !important;
		}
		.showroom-mobile .wf_form_content .wf_input_group .wf_radio_check:after{
			top: 8px;
			left: 9px;
			background-color: '.$sPrimaryColor.';
		}
		.showroom-mobile .wf_form_content .wf_input_group .wf_radio .wf_label_field{
			margin-right: 30px !important;
		}
		
		
		/*INPUT COORDONNEES*/
		.showroom-mobile .wf_form_content .wf_field_input input{
			border: 2px solid '.$sPrimaryColor.';
			margin-top: 5px;
		}
		/*SELECT COORDONNEES*/
		.showroom-mobile .wf_form_content span.wf_field_select{
			border: 2px solid '.$sPrimaryColor.';
			margin-top: 5px;
		}
		.showroom-mobile .wf_form_content span.wf_field_select .wf_custom_select_text:after{
			color: '.$sPrimaryColor.';
		}
		/*CHECKBOX CARRE*/
		.showroom-mobile .wf_form_content .wf_page_open fieldset .wf_checkbox span.wf_label_field label.wf_box_check {
			line-height: 20px;
			padding-left: 43px !important;
		}
		.showroom-mobile .wf_form_content .wf_page_open fieldset .wf_checkbox span.wf_label_field .wf_box_check:before {
			border: 2px solid '.$sPrimaryColor.' ;
			width: 30px !important;
			height: 30px !important;
		}
		/*BUTTON VALIDEZ*/
		.showroom-mobile .wf_form_content .wf_page_open fieldset .wf_line_button span.wf_button input{
			background: '.$sPrimaryColor.';
			color: #4B4A4D;
			text-transform: uppercase;
			font-weight: bold;
			font-size: 14px;
			border: 0;
			height: 48px;
			padding: 8px 65px !important;
		}
		.showroom-mobile .wf_form_content .wf_page_open fieldset .wf_line_button span.wf_button_input:after{
			display: none;
		}
		/*MODIFICATION*/
		.showroom-mobile .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01{
			color: #595959;
			border: 2px solid #868689;
			width: 30px;
			height: 30px;
			padding: 0 !important;
		}
		.showroom-mobile .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01 .wf-modify{
			padding: 0 !important;
			margin: 0 3px 0 0 !important;
		}
		.showroom-mobile .wf_form_content .wf_page_valid .wf_title_container span.wf_link_01 .wf-modify:before{
			font-size: 25px;
			top: 2px;
			right: 3px;
			color: '.$sPrimaryColor.';
		}
		.ds.cls2colonnes .actions a {
			padding: 10px 5px 13px 15px !important;
		}
		/*NEW STYLE FORM DS*/
		.ds input[type="text"], .ds input[type="email"], .ds input[type="password"], .ds input[type="submit"], .ds textarea, .ds button {
			width: 100%;
			padding: 0 15px;
			height: 40px;
			line-height: 1em;
			background: transparent !important;
			border: 1px solid #D0D0D3;
			border-radius: 0 !important;
		}
		
		
		</style>
		';
		}

		return $sCss;

	}



	public static function getCssWithDynamicColorsMobile($sPrimaryColor,$sSecondColor,$isIframe=null)

	{

		if($isIframe != 1){
			$sCss = '
		 <style type="text/css">

		.sliceDeployableFormMobile .wf_radio_check:before, .sliceDeployableFormDesk .wf_radio_check:before {
		  border: 3px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_radio_check.wf_checked:after, .sliceDeployableFormDesk .wf_radio_check.wf_checked:after {
		  background-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_scrollbar .overview .wf_dealer_locator_item .distance {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormDesk .wf_scrollbar .overview .wf_dealer_locator_item:hover, .sliceDeployableFormDesk .wf_scrollbar .overview .wf_dealer_locator_item:active {
		  border-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_page_open .wf_title_container .wf_page_title, .sliceDeployableFormMobile .wf_page_valid .wf_title_container .wf_page_title {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_form_content .wf_field_input input, .sliceDeployableFormMobile .wf_form_content .wf_field_input select, .sliceDeployableFormMobile .wf_form_content .wf_field_input textarea {
		  border: 2px solid '.$sPrimaryColor.'!important;
		}
		.sliceDeployableFormMobile .wf_field_select {
			   border: 2px solid '.$sPrimaryColor.'!important;	
		}
		
		.sliceDeployableFormMobile .wf_forms_global .wf_brochurepicker .wf_range .wf_highlight_car label{
			color: '.$sPrimaryColor.'!important;
		}
		.sliceDeployableFormMobile .wf_forms_global .wf_range li.wf_cars:hover, .wf_forms_global .wf_range li.wf_cars.wf_highlight_car{
			border: 4px solid '.$sPrimaryColor.' !important;
		}
		.sliceDeployableFormMobile .wf_forms_global .wf_content .wf_button{
			background-color:#fff!important;
		}
		.sliceDeployableFormMobile input[type=radio] + label:before{
			border:3px solid '.$sPrimaryColor.' !important;
		}
		.sliceDeployableFormMobile input[type=radio]:checked + label:after{
			background-color:'.$sPrimaryColor.' !important;
		}
		.sliceDeployableFormMobile .wf_page_open .wf_numbering, .sliceDeployableFormMobile .wf_page_valid .wf_numbering {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormMobile .wf_box_check:before {
		  border: 2px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_box_check.wf_checked:after {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn {
		  background-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item .distance {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_button input, .sliceDeployableFormMobile .wf_button button{
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormMobile .wf_page_open .wf_title_container .wf_page_title, .sliceDeployableFormMobile .wf_page_valid .wf_title_container .wf_page_title {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_form_content .wf_field_input input, .sliceDeployableFormMobile .wf_form_content .wf_field_input select, .sliceDeployableFormMobile .wf_form_content .wf_field_input textarea {
		  border: 2px solid '.$sPrimaryColor.'!important;
		}
		.sliceDeployableFormMobile .wf_page_open .wf_numbering, .sliceDeployableFormMobile .wf_page_valid .wf_numbering {
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.sliceDeployableFormMobile .wf_box_check:before {
		  border: 2px solid '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_box_check.wf_checked:after {
		  color: '.$sPrimaryColor.';
		}
		
		.sliceDeployableFormMobile .wf_forms_global .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item.wf_selected{
			 border: 4px solid '.$sPrimaryColor.'!important;
		}
		.sliceDeployableFormMobile .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn {
		  background-color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item .distance {
		  color: '.$sPrimaryColor.';
		}
		.sliceDeployableFormMobile .wf_button input, .sliceDeployableFormMobile .wf_button button{
		  background-color: '.$sPrimaryColor.';
		  color: #fff;
		}
		.wf_button:hover input, .wf_button:hover button, .wf_button:hover .wf_button_input {
		  background-color: #B40027;
		}
		.wf_forms_global .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:hover, .wf_forms_global .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:hover, .wf_forms_global .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:focus, .wf_forms_global .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:focus, .wf_forms_global .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:active, .wf_forms_global .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:active{
			border: 4px solid '.$sPrimaryColor.' !important;
		}
		.sliceDeployableFormMobile .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn:hover, .sliceDeployableFormMobile .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn:active{
			 background-color: '.$sPrimaryColor.'!important;
		}
	
		.sliceDeployableFormMobile .wf_button input:hover, .sliceDeployableFormMobile .wf_button input:hover:hover, .sliceDeployableFormMobile .wf_button button:hover, .sliceDeployableFormMobile .wf_button button:hover:hover, .sliceDeployableFormMobile .wf_button:hover input:hover, .sliceDeployableFormMobile .wf_button:hover input:hover:hover, .sliceDeployableFormMobile .wf_button:hover button:hover, .sliceDeployableFormMobile .wf_button:hover button:hover:hover, .sliceDeployableFormDesk .wf_button input:hover, .sliceDeployableFormDesk .wf_button input:hover:hover, .sliceDeployableFormDesk .wf_button button:hover, .sliceDeployableFormDesk .wf_button button:hover:hover, .sliceDeployableFormDesk .wf_button:hover input:hover, .sliceDeployableFormDesk .wf_button:hover input:hover:hover, .sliceDeployableFormDesk .wf_button:hover button:hover, .sliceDeployableFormDesk .wf_button:hover button:hover:hover{
			border: 4px solid '.$sPrimaryColor.'!important; 
			background-color: #FFF!important;
			color: '.$sPrimaryColor.'!important; 
			padding:18px 15px 16px!important; 
			min-width:150px!important;
		}
		.sliceDeployableFormMobile .wf_button:hover input, .sliceDeployableFormMobile .wf_button:hover button, .sliceDeployableFormMobile .wf_button:hover .wf_button_input{
			background:'.$sPrimaryColor.'!important;
		}
		.sliceDeployableFormMobile .wf_synthesis .distance{
			color: '.$sPrimaryColor.'!important;
		}
		.wf_forms_global .wf_dealer_locator_item .distance, .wf_forms_global .wf_synthesis .distance, .wf_forms_global .wf_synthesis .distance strong{
			color: '.$sPrimaryColor.'!important;
		}
		 </style>';

		}else{
		$sCss = '
		 <style type="text/css">

		.clearfix,
.wf_form_content .wf_linecontent,
.wf_form_content .wf_page_button_next .wf_linecontent,
.wf_line,
.wf_linemultiple,
.wf_cars_selection,
.wf_pagination ul,
.wf_dealer_locator_icons,
.wf_multiplecomponent {
    zoom: 1
}

.clearfix:before,
.wf_form_content .wf_linecontent:before,
.wf_form_content .wf_page_button_next .wf_linecontent:before,
.wf_line:before,
.wf_linemultiple:before,
.wf_cars_selection:before,
.wf_pagination ul:before,
.wf_dealer_locator_icons:before,
.wf_multiplecomponent:before,
.clearfix:after,
.wf_form_content .wf_linecontent:after,
.wf_form_content .wf_page_button_next .wf_linecontent:after,
.wf_line:after,
.wf_linemultiple:after,
.wf_cars_selection:after,
.wf_pagination ul:after,
.wf_dealer_locator_icons:after,
.wf_multiplecomponent:after {
    display: table;
    content: " "
}

.clearfix:after,
.wf_form_content .wf_linecontent:after,
.wf_form_content .wf_page_button_next .wf_linecontent:after,
.wf_line:after,
.wf_linemultiple:after,
.wf_cars_selection:after,
.wf_pagination ul:after,
.wf_dealer_locator_icons:after,
.wf_multiplecomponent:after {
    clear: both
}

.wf_box_check,
.wf_radio .wf_custom_radio .wf_label_field .wf_radio_check {
    position: relative;
    padding-left: 34px !important;
    padding-top: 1px !important
}

.wf_box_check input,
.wf_radio .wf_custom_radio .wf_label_field .wf_radio_check input {
    position: absolute;
    left: -9999px
}

.clearfix,
.wf_form_content .wf_linecontent,
.wf_form_content .wf_page_button_next .wf_linecontent,
.wf_line,
.wf_linemultiple,
.wf_cars_selection,
.wf_pagination ul,
.wf_dealer_locator_icons,
.wf_multiplecomponent {
    zoom: 1
}

.clearfix:before,
.wf_form_content .wf_linecontent:before,
.wf_form_content .wf_page_button_next .wf_linecontent:before,
.wf_line:before,
.wf_linemultiple:before,
.wf_cars_selection:before,
.wf_pagination ul:before,
.wf_dealer_locator_icons:before,
.wf_multiplecomponent:before,
.clearfix:after,
.wf_form_content .wf_linecontent:after,
.wf_form_content .wf_page_button_next .wf_linecontent:after,
.wf_line:after,
.wf_linemultiple:after,
.wf_cars_selection:after,
.wf_pagination ul:after,
.wf_dealer_locator_icons:after,
.wf_multiplecomponent:after {
    display: table;
    content: " "
}

.clearfix:after,
.wf_form_content .wf_linecontent:after,
.wf_form_content .wf_page_button_next .wf_linecontent:after,
.wf_line:after,
.wf_linemultiple:after,
.wf_cars_selection:after,
.wf_pagination ul:after,
.wf_dealer_locator_icons:after,
.wf_multiplecomponent:after {
    clear: both
}

.wf_box_check,
.wf_radio .wf_custom_radio .wf_label_field .wf_radio_check {
    position: relative;
    padding-left: 34px !important;
    padding-top: 1px !important
}

.wf_box_check input,
.wf_radio .wf_custom_radio .wf_label_field .wf_radio_check input {
    position: absolute;
    left: -9999px
}

.wf_input .wf_field_input input {
    height: 38px;
    padding-left: 15px;
    padding-right: 15px;
    -webkit-appearance: none
}

.wf_field_status_icon {
    display: block;
    float: left;
    height: 38px;
    padding-left: 120px;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 2
}

.wf_form_content textarea {
    min-width: 370px;
    min-height: 158px;
    height: 158px;
    max-height: 316px;
    padding: 7px 15px
}

.wf_form_content .wf_line_2_cols textarea {
    min-width: 280px
}

.textareaResizing {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
}

.wf_textarea_content {
    display: inline-block;
    width: 100%
}

.wf_textarea_content.resized {
    width: auto
}

.wf_line_button .wf_linecontent.wf_content {
    display: block !important
}

.wf_button {
    position: relative;
    overflow: visible
}

.wf_button input,
.wf_button button {
    overflow: visible;
    padding: 8px 20px;
    line-height: 1em;
    height: 40px
}

.datepicker {
    z-index: 99;
    display: none
}

.datepicker .calendarHeader {
    overflow: hidden;
    zoom: 1;
    height: 21px;
    line-height: 21px
}

.datepicker .calendarTable {
    padding: 0 11px 9px 5px
}

.datepicker .wf_timePicker {
    min-height: 28px;
    overflow: auto
}

.datepicker .closeBtnContainer {
    margin-bottom: 2px
}

.datepicker .dayTitle {
    display: block;
    overflow: hidden;
    zoom: 1
}

.datepicker .previousBtn {
    float: left;
    margin: 6px 3px 5px 3px
}

.datepicker .nextBtn {
    float: right;
    margin: 6px 5px 3px 3px
}

.datepicker .calendarTable thead th {
    background: #ebebeb
}

.datepicker .calendarTable table {
    width: 100%;
    border-collapse: collapse
}

.datepicker .day {
    width: 18px;
    height: 18px;
    background: #dadada
}

.datepicker .wf_field_select {
    float: left;
    width: 25%;
    margin-right: 5px
}

.datepicker .wf_year {
    width: 45%
}

.datepicker .wf_field_select_last {
    width: auto;
    float: none;
    margin-right: 0;
    display: block;
    overflow: hidden
}

.datepicker .wf_input_group {
    margin-top: 0
}

.datepicker .wf_timepicker_label {
    margin: 8px 5px 0 8px;
    float: left
}

.datepicker select {
    padding: 0;
    height: 19px;
    float: left;
    margin-top: 5px
}

.datepicker .wf_calendarButton {
    float: left;
    margin: 5px 0 0 5px
}

.datepicker .wf_calendarButton input {
    padding: 0 5px
}

.datepicker .previousMonthBtn,
.datepicker .nextMonthBtn {
    outline: none;
    text-indent: -9999px;
    display: block;
    width: 8px;
    height: 9px
}

.wf_connexion_content {
    position: relative
}

.wf_connexion_content p {
    margin: 0 0 15px
}

.wf_connexion_content .wf_connexion_text {
    margin-top: 30px
}

.wf_connexion_content form {
    margin: 0 0 26px
}

.wf_popin_login .wf_linecontent {
    float: left;
    width: 70%
}

.wf_popin .wf_login,
.wf_popin .wf_password {
    width: 200px;
    padding-left: 4px
}

.wf_popin .wf_close {
    position: absolute;
    right: 24px;
    top: 15px
}

.wf_shadow_box_inner {
    padding: 0 2px 0 10px
}

.wf_shadow_box_inside {
    zoom: 1;
    padding: 20px 16px 21px 20px
}

.wf_shadow_box_inside:after {
    position: relative;
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden
}

.wf_shadow_top:after {
    content: ".";
    display: block;
    height: 0;
    clear: both;
    visibility: hidden
}

.wf_shadow_tl,
.wf_shadow_bl {
    float: left;
    width: 17px;
    height: 8px
}

.wf_shadow_tr,
.wf_shadow_br {
    display: table;
    *display: block;
    zoom: 1;
    float: none;
    width: auto;
    height: 8px
}

.wf_shadow_tr:after,
.wf_shadow_br:after {
    content: " . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . ";
    visibility: hidden;
    clear: both;
    height: 0;
    display: block;
    line-height: 0
}

.wf_btn_connexion {
    float: right;
    height: 23px;
    padding-left: 23px;
    margin-top: 10px
}

.wf_btn_connexion input {
    padding: 0;
    margin-top: 3px
}

.wf_btn_connexion span {
    display: block;
    height: 23px;
    line-height: 23px;
    padding-right: 7px
}

.wf_popin_login .wf_connexion_bottom,
.wf_popin_login .wf_page_errorMessage {
    margin-right: 166px
}

.wf_popin_component {
    padding: 18px;
    position: fixed !important
}

.wf_popin_component .wf_pop_content {
    padding: 12px 20px 17px 20px
}

.wf_popin_component .wf_close {
    padding: 0 20px 0 0;
    right: 40px;
    top: 33px
}

.wf_popin_component .wf_popin_component_title {
    margin: 0 0 10px 0;
    min-height: 28px;
    line-height: 28px;
    display: block
}

.wf_popin_component .wf_popin_component_title .alert {
    display: block;
    min-height: 28px;
    line-height: 28px;
    padding: 0 0 0 37px
}

.wf_form_loading {
    position: relative
}

.wf_loading_mask {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    display: none;
    zoom: 1;
    z-index: 998
}

.wf_loading_layer {
    position: absolute;
    right: 0px;
    bottom: 27px;
    display: none;
    zoom: 1;
    z-index: 999;
    vertical-align: middle
}

.wf_loading_layer img {
    vertical-align: middle;
    margin-left: 10px
}

.wf_box_check {
    display: inline-block;
    min-height: 20px
}

.wf_checkbox .wf_input_group .wf_label_field {
    width: auto
}

.wf_horizontal.wf_checkbox .wf_input_group .wf_label_field {
    margin-right: 30px
}

.wf_horizontal.wf_checkbox .wf_input_group .wf_label_field:after {
    content: ""
}

.wf_vertical.wf_radio .wf_input_group .wf_label_field {
    width: 100%
}

.wf_horizontal.wf_radio .wf_input_group .wf_label_field {
    margin-right: 30px
}

.wf_radio .wf_custom_radio .wf_label_field {
    display: inline;
    width: auto
}

.wf_radio .wf_custom_radio .wf_label_field:after {
    content: ""
}

.wf_radio .wf_custom_radio .wf_label_field .wf_radio_check {
    display: block;
    margin: -4px 0 4px 0
}

.wf_multiplecomponent_content .wf_linemultiple {
    max-width: 630px;
    width: 100%
}

.wf_line_2_cols .wf_multiplecomponent_content .wf_linemultiple {
    width: 48%
}

.wf_multiplecomponent_content .wf_linemultiple .wf_linecontent {
    width: 100%
}

.wf_multiplecomponent_content .wf_linemultiple .wf_linecontent>.wf_field {
    padding-right: 120px
}

.wf_multiplecomponent_content .wf_linemultiple .buttons_addremove {
    right: 45px;
    top: 3px
}

.wf_error.wf_input .wf_field_file .wf_fakeinput {
    border-color: #DE113C
}

.wf_input .wf_field_file {
    position: relative;
    display: block;
    width: auto
}

.wf_input .wf_field_file .fake {
    position: relative;
    z-index: 1
}

.wf_input .wf_field_file .fake span {
    display: block;
    overflow: hidden
}

.wf_input .wf_field_file .fake span input {
    width: 100%
}

.wf_input .wf_field_file .fake .wf_fake_button {
    float: right;
    margin-left: 15px;
    width: auto
}

.wf_input .wf_field_file .wf_progressblock {
    display: none;
    vertical-align: middle;
    padding: 9px 0 8px 15px
}

.wf_input .wf_field_file .wf_progressblock.wf_show {
    display: block
}

.wf_input .wf_field_file .wf_progressblock .wf_inline {
    display: block
}

.wf_input .wf_field_file .wf_progressblock .wf_inline:after {
    content: "";
    width: 100%;
    display: inline-block;
    height: 0;
    vertical-align: top
}

.wf_input .wf_field_file .wf_progressblock .wf_progress_replaced .wf_inline {
    display: none
}

.wf_input .wf_field_file .wf_filename {
    overflow: hidden;
    display: inline-block;
    max-width: 110px;
    white-space: nowrap
}

.wf_input .wf_field_file .wf_progressbar {
    width: 130px;
    display: block;
    position: absolute;
    top: 15px;
    right: 35px
}

.wf_input .wf_field_file .wf_progressbar .wf_progressbar_content {
    visibility: hidden;
    display: block;
    border: 1px solid #B7B3B3;
    -moz-border-top-left-radius: 4px;
    -moz-border-top-right-radius: 4px;
    -moz-border-bottom-left-radius: 4px;
    -moz-border-bottom-right-radius: 4px;
    -webkit-border-top-left-radius: 4px;
    -webkit-border-top-right-radius: 4px;
    -webkit-border-bottom-left-radius: 4px;
    -webkit-border-bottom-right-radius: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px
}

.wf_input .wf_field_file .wf_progressbar .wf_progressbar_content span:first-child {
    position: relative;
    display: block;
    width: 1px;
    background: #da0000;
    height: 10px
}

.wf_input .wf_field_file input[type=file] {
    position: absolute;
    left: 0;
    top: 0;
    width: 0;
    height: 0;
    opacity: 0;
    filter: alpha(opacity=0);
    z-index: 1;
    padding: 0;
    cursor: pointer
}

.wf_input .wf_field_file .wf_field_filefakelabel {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    z-index: 2;
    overflow: hidden;
    font-size: 1000%;
    background: #FFF;
    opacity: 0;
    filter: alpha(opacity=0)
}

.wf_delete_file {
    cursor: pointer;
    display: none;
    font-size: 20px;
    text-align: center;
    width: 25px;
    position: absolute;
    right: 5px;
    top: 5px
}

.wf_waitprogress {
    width: 43px;
    position: absolute;
    left: auto;
    right: 0;
    top: -9px;
    margin-left: -22px;
    background-position: 0 0 !important;
    height: 35px
}

.wf_multicomponent_full .wf_line_last.wf_line_first .buttons_addremove .add_line {
    display: none
}

.wf_fileUploaded .wf_field_file .wf_filename {
    max-width: 240px;
    width: 100%
}

.wf_fileUploaded .wf_field_file .wf_progressbar {
    width: 0px
}

.wf_dealer_locator {
    float: left;
    width: 31%;
    padding-right: 40px;
    margin-right: -40px;
    min-height: 260px
}

.wf_dealer_locator .resultBoxField .wf_label_field {
    float: none;
    display: block;
    width: auto;
    margin-right: 0;
    *line-height: 25px
}

.wf_dealer_locator .wf_fiche_complete {
    float: left;
    margin-top: 5px
}

.wf_dealer_locator .wf_searchresult {
    padding-left: 14px
}

.wf_dealer_locator .wf_icon_box {
    *margin-top: 3px
}

.wf_dealer_locator .wf_dealer_select_links {
    overflow: hidden;
    margin-top: 10px
}

.wf_dealer_locator .wf_searchresult {
    margin-bottom: 5px
}

.wf_dealerlocator_form {
    width: 75%
}

.wf_dealerlocator_form .wf_searchbox_container {
    max-width: 590px;
    display: table-cell;
    width: 100%;
    vertical-align: middle
}

.wf_dealerlocator_form .wf_searchbox_container input {
    width: 100%
}

.wf_dealerlocator_form .wf_field_input {
    display: table;
    width: 75%
}

.wf_dealerlocator_form .wf_field_input:after {
    display: none
}

.wf_dealers_more_button,
.wf_validsearch button {
    height: 38px;
    padding: 0px 20px 3px 20px
}

.wf_dealers_more_button {
    margin: 20px auto;
    display: block
}

.wf_geo_btn {
    padding: 0px 19px 3px 45px;
    margin: 0;
    height: 40px
}

.wf_validsearch {
    display: table-cell;
    width: 1px
}

.wf_validsearch button {
    margin: 0 20px 0 11px;
    padding: 0px 15px 3px 15px
}

.wf_geo_wrapper {
    display: table-cell;
    width: 310px;
    white-space: nowrap
}

.wf_geo_and {
    margin-right: 20px
}

.wf_dealer_locator_phone_label {
    display: inline-block
}

.wf_dealer_locator_phone {
    margin: 0 0 15px
}

.wf_dealer_locator_bottom .wf_dealer_locator_results {
    margin: 17px 0 20px 0
}

.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line,
.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_linemultiple {
    margin-bottom: 0;
    overflow: hidden
}

.wf_dealer_locator_map {
    overflow: hidden;
    padding-left: 10px;
    position: relative
}

.wf_location_map {
    height: 479px
}

.wf_location_map_legend {
    position: absolute;
    display: table;
    bottom: 22px;
    right: 22px;
    background-color: #e3e2e7;
    z-index: 1;
    opacity: 0.9;
    -moz-opacity: 0.9;
    filter: alpha(opacity=90);
    padding: 15px 0 15px 15px;
    white-space: nowrap
}

.wf_legend_title {
    display: table-cell;
    text-transform: uppercase;
    vertical-align: middle;
    padding-right: 15px
}

.wf_legend_title span {
    display: table-cell;
    vertical-align: middle
}

.wf_legend_title img {
    margin-left: 10px
}

.wf_dealer_locator_inner {
    min-height: 237px;
    margin-right: 4px;
    padding-right: 3px;
    position: relative;
    zoom: 1
}

.wf_dealer_locator_inner .wf_dealer_locator_item {
    overflow: hidden
}

.wf_dealer_locator_item {
    padding: 11px 12px 10px;
    position: relative;
    zoom: 1;
    margin-bottom: 6px;
    padding-bottom: 0
}

.wf_dealer_locator_item .wf_button {
    float: none
}

.wf_dealer_locator_item .wf_dealer_select {
    float: right;
    padding: 3px 5px
}

.wf_dealer_locator_item h3,
.wf_dealer_locator_item address {
    display: block
}

.wf_dealer_locator_item h3 {
    margin: 0
}

.wf_dealer_locator_item .inner {
    padding-bottom: 7px;
    position: relative
}

.wf_dealer_locator_item .distanceFromPoint {
    margin-bottom: 2px
}

.wf_selected_dealer {
    width: 80%;
    float: left
}

.wf_pagination_previous {
    visibility: hidden
}

.wf_comp_loading {
    min-height: 100px
}

.wf_comp_loading span {
    zoom: 1
}

.wf_comp_loading .wf_dealer_locator_results,
.wf_comp_loading .wf_dealer_locator_noresults {
    zoom: 1
}

.wf_dealer_locator_layer {
    width: 100%;
    min-height: 100%;
    position: absolute;
    left: 0;
    top: 0;
    z-index: 12
}

.wf_dealer_locator_layer h3 {
    margin-top: 0
}

.wf_dealer_locator_layer .wf_link_04 {
    margin-right: 28px
}

.wf_dealer_locator_layer_content {
    padding: 12px 16px 15px 20px
}

.wf_scrollbar {
    width: 100%
}

.wf_scrollbar .overview {
    position: absolute;
    left: 0;
    top: 0;
    padding: 0;
    right: 0
}

.wf_scrollbar .overview li {
    margin: 0 15px 0 21px;
    padding: 20px 0 20px 20px
}

.wf_scrollbar .disable {
    display: none
}

.wf_scrollbar .scrollbar {
    margin-right: 5px;
    padding: 0 0 1px 0;
    position: relative;
    float: right;
    width: 10px
}

.wf_scrollbar .viewport {
    height: 478px;
    overflow: hidden;
    position: relative
}

.wf_scrollbar .up,
.wf_scrollbar .down {
    display: none
}

.wf_scrollbar .track {
    width: 15px;
    height: 100%;
    position: relative
}

.wf_scrollbar .thumb {
    height: 20px;
    width: 15px;
    overflow: hidden;
    position: absolute;
    top: 0;
    left: 0
}

.wf_dealer_locator_bottom .wf_dealer_locator_message {
    margin: 22px 0 29px 0
}

.wf_info_box .wf_dealer_locator_item {
    padding: 7px;
    position: relative;
    zoom: 1;
    margin-bottom: 0
}

.wf_info_box .wf_dealer_locator_item img {
    z-index: 4
}

.wf_info_box .wf_dealer_locator_item .wf_info_box_arrow {
    height: 9px;
    width: 7px;
    position: absolute;
    left: -7px;
    top: 27px
}

.wf_synthesis_dealerlocator h3 {
    margin: 0
}

.wf_dealerlocator_form_light {
    width: 100%;
    margin-bottom: 0
}

.wf_dealerlocator_form_light .wf_intro_form {
    margin: 0 0 10px;
    overflow: hidden
}

.wf_dealerlocator_form_light .wf_line,
.wf_dealerlocator_form_light .wf_linemultiple {
    position: relative
}

.wf_dealerlocator_form_light .wf_loupeButton {
    -moz-border-top-left-radius: 0;
    -moz-border-top-right-radius: 4px;
    -moz-border-bottom-left-radius: 4px;
    -moz-border-bottom-right-radius: 0;
    -webkit-border-top-left-radius: 0;
    -webkit-border-top-right-radius: 4px;
    -webkit-border-bottom-left-radius: 4px;
    -webkit-border-bottom-right-radius: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 4px;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 0;
    bottom: 10px;
    margin: 0 0 0 -80px;
    position: absolute;
    top: 0;
    width: 40px
}

.wf_dealerlocator_form_light .wf_loupeButton input {
    text-indent: -9999px
}

.wf_dealerlocator_form_light .wf_field_input input {
    -webkit-appearance: none
}

.wf_dealerlocator_form_light .picker_dropdown {
    *padding-right: 0
}

.wf_dealerlocator_form_light .wf_field_input:after {
    display: block
}

.wf_carpicker {
    margin-bottom: 10px;
    min-width: 142px;
    font-size: 17px
}

.wf_carpicker ul.wf_cars_type {
    display: table;
    margin: 0 auto -1px auto;
    margin-bottom: -1px;
    max-width: 100%;
    table-layout: fixed;
    position: relative;
    z-index: 1
}

.wf_carpicker ul.wf_cars_type li {
    display: table-cell;
    cursor: pointer;
    border-spacing: 0;
    border-left: 5px solid transparent;
    vertical-align: bottom
}

.wf_carpicker ul.wf_cars_type li a {
    display: table-cell;
    padding: 0 30px;
    margin: 0;
    height: 54px;
    width: 100%;
    vertical-align: middle;
    overflow: hidden;
    font-size: 18px
}

.wf_cars_type_content {
    padding: 13px 0 0 0;
    position: relative
}

.wf_cars_type_content:before {
    content: "";
    height: 20px;
    left: 0;
    position: absolute;
    right: 0;
    top: 1px;
    z-index: -1
}

.wf_cars_selection {
    padding: 0 11px
}

.wf_carpicker .wf_range {
    margin-right: -2px;
    padding-top: 1px
}

.wf_cars {
    position: relative;
    margin: -1px -1px 0 0;
    width: 142px;
    display: inline-block;
    vertical-align: top
}

.wf_cars label {
    display: block;
    position: relative;
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px
}

.wf_cars label span {
    display: block
}

.containerMax .wf_cars {
    width: 16%;
    height: auto;
    padding: 3px
}

.wf_car_model {
    z-index: 5;
    display: block;
    width: 90%;
    padding-bottom: 10px;
    margin: 0 auto
}

.wf_car_image {
    display: inline-block;
    padding: 20px 7% 15px 7%;
    height: auto;
    pointer-events: none;
    width: 86%
}

.wf_car_loader {
    background: url("../../images/forms/skin/icons/ajax_loader.gif") no-repeat center center;
    background-size: 28px
}

.wf_car_box {
    position: absolute;
    right: 12px;
    bottom: 8px;
    display: block
}

.wf_car_box input {
    margin: 0
}

.wf_cars_type_content_result {
    margin: 20px 0 30px 0;
    display: table
}

.wf_cars_type_content_result span {
    display: table-cell;
    vertical-align: middle
}

.wf_carpicker .wf_cars_errorMessage,
.wf_brochurepicker .wf_errorMessage {
    padding-left: 20px
}

.wf_cars_errorMessage {
    padding-top: 4px
}

.wf_cars_messagescontainer {
    display: block;
    overflow: hidden;
    zoom: 1;
    padding-right: 10px
}

.wf_recap_cars {
    margin: 12px 0 19px;
    padding-bottom: 3px
}

.wf_recap_cars .wf_recap_cars table tr td {
    padding: 0
}

.wf_recap_cars .wf_recap_cars table th {
    padding: 17px 0
}

.wf_recap_cars .wf_second_block_recap table {
    padding-left: 20px
}

.wf_send_mail {
    padding-left: 29px
}

.wf_send_letter {
    padding-left: 25px
}

.wf_brochure_flipbook_icon,
.wf_brochure_pdf_icon {
    height: 12;
    width: 14px;
    display: block;
    overflow: hidden;
    text-indent: -5000px;
    margin: 0 auto
}

.wf_brochure_pdf_icon {
    height: 16px;
    width: 16px
}

.wf_brochurePickerCar.wf_carPicker_darkCars {
    margin-right: 70px;
    position: relative
}

.wf_brochurePickerCar.wf_carPicker_darkCars .wf_modify_in {
    right: -84px
}

.wf_cars_selection .wf_carPicker_darkCars {
    padding: 10px 30px
}

.wf_brochurepicker .wf_button_wrapper {
    margin: 20px 0 25px 0;
    display: table
}

.wf_brochurepicker .wf_button_wrapper .wf_brochurepicker_validate_button {
    display: table-cell
}

.wf_brochurepicker .wf_button_wrapper .wf_brochures_messagescontainer {
    display: table-cell
}

.wf_brochurepicker .wf_button_wrapper .wf_brochures_messagescontainer .wf_errorMessage {
    display: none
}

.wf_brochurePickerCar {
    padding-left: 20px
}

.wf_brochurePickerCar .left {
    display: inline-block
}

.wf_brochurePickerCar img {
    width: 142px;
    height: auto
}

.wf_brochurePickerCar:after {
    content: "";
    display: block;
    height: 1px;
    margin-bottom: 20px
}

.wf_brochurePickerCar .wf_modify_in {
    float: right;
    margin-top: 30px;
    padding-left: 25px
}

.wf_brochurePickerCar .wf_modify_in {
    float: right;
    margin-top: 7px;
    padding-left: 25px
}

.wf_brochurePickerBrochures {
    max-width: 66%;
    margin: 18px 0 40 0px
}

.wf_brochurePickerBrochures .wf_highlight {
    white-space: nowrap;
    padding: 10px
}

.wf_brochurePickerBrochures .wf_brochure_pdf a {
    padding: 5px 10px;
    width: 102px;
    display: block;
    float: right
}

.wf_desc_table {
    min-width: 200px;
    padding-right: 30px
}

.wf_brochure_email,
.wf_brochure_postal {
    width: 170px
}

.wf_resume_details {
    margin-top: 10px
}

.wf_resume_details span {
    font-size: 17px
}

.wf_synthesis_brochurePicker {
    display: table
}

.wf_synthesis_brochurePicker .left {
    padding: 15px 20px 14px 20px
}

.wf_synthesis_brochurePicker .left img {
    width: 142px;
    height: auto
}

.wf_synthesis_brochurePicker .right {
    display: table-cell;
    vertical-align: middle;
    padding: 0 20px 0 30px
}

.wf_brochurePickerCar {
    padding-top: 15px
}

.wf_link_resume {
    position: absolute
}

.wf_link_resume .wf_modify {
    float: none
}

img.img_model_medium {
    width: 50%
}

div.wf_select_carpicker {
    width: 100%;
    display: inline-block
}

div.wf_selects {
    float: left;
    width: 50%;
    margin-top: 4%;
    margin-bottom: 4%
}

div.wf_d_select_gamme {
    width: 60%;
    float: left
}

div.wf_select_model {
    width: 100%
}

div.img_model {
    float: left;
    width: 50%
}

div.label_select_gamme {
    width: 35%;
    float: left;
    text-align: right;
    margin-right: 15px
}

div.label_select_model {
    width: 35%;
    float: left;
    text-align: right;
    margin-right: 15px
}

select.wf_select_gamme {
    width: 100%;
    height: 27px
}

select.wf_select_model {
    width: 96%;
    height: 27px
}

div.wf_cars_selection.selectcarpicker {
    padding: 0px
}

.wf_cars_select {
    display: block;
    width: 100%
}

.wf_page_page {
    padding: 10px 0 0
}

.wf_clear {
    clear: both
}

.floatL {
    float: left
}

.hidden {
    display: none
}

.visHidden {
    visibility: hidden
}

.wf_line,
.wf_linemultiple,
.wf_cars_type,
.wf_cars_selection,
.wf_pagination ul,
.wf_dealer_locator_icons {
    zoom: 1;
    margin: 0 0 10px
}

.wf_last,
.wf_question {
    display: block;
    zoom: 1
}

.wf_container_explanation {
    margin-bottom: 10px
}

.wf_f_left {
    float: left
}

.wf_f_right {
    float: right
}

.wf_title_01 {
    padding-left: 11px;
    min-height: 20px;
    line-height: 20px
}

.wf_link_02 {
    padding-left: 9px
}

.wf_link_03,
.wf_link_04 {
    padding-left: 22px;
    height: 18px;
    display: block;
    line-height: 18px
}

.wf_form_content fieldset,
.wf_form_content,
.wf_form_content label,
.wf_form_content p,
.wf_form_content ul {
    border: 0;
    margin: 0;
    padding: 0;
    vertical-align: baseline
}

.wf_field {
    zoom: 1
}

.wf_legend {
    padding: 0;
    margin-top: 0
}

.wf_button_selection_dealer {
    padding: 2px 12px
}

.wf_grey {
    padding: 0
}

.wf_grey span {
    display: block;
    padding: 8px 15px;
    margin: 0
}

.wf_button_image {
    float: none;
    display: block;
    width: 100%
}

.wf_button_image input {
    position: absolute;
    top: -5000px;
    left: -5000px
}

.wf_button_image label {
    display: block;
    width: 100%;
    position: relative;
    padding: 0
}

.wf_button_image .wf_button_image_block,
.wf_button_image .wf_button_image_block img {
    display: block;
    width: 100%;
    position: relative
}

.wf_button_text_block {
    display: block;
    width: 100%;
    position: absolute;
    bottom: 0;
    left: 0
}

.wf_button_text_block span {
    display: inline-block;
    padding: 30px 30px 30px 0
}

.wf_field_input:after,
.wf_input_group:after,
.wf_input_group .wf_label_field:after,
.wf_form_content .wf_message_box:after,
.wf_popin_login .wf_message_box:after {
    content: " . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . ";
    visibility: hidden;
    clear: both;
    height: 0;
    display: block;
    overflow-x: hidden
}

.no-fontface .wf_field_input:after,
.no-fontface .wf_input_group:after,
.no-fontface .wf_input_group .wf_label_field:after,
.no-fontface .wf_form_content .wf_message_box:after,
.no-fontface .wf_popin_login .wf_message_box:after {
    content: " "
}

.wf_form_content .wf_linecontent {
    display: block;
    margin-bottom: 12px
}

.wf_form_content p {
    margin-bottom: 0
}

.wf_form_content .wf_line,
.wf_form_content .wf_linemultiple {
    margin-bottom: 0
}

.wf_form_content .wf_linecontent {
    float: left;
    width: 100%;
    max-width: 630px
}

.wf_form_content .wf_linecontent>.wf_field {
    float: left;
    padding-right: 40px;
    position: relative
}

.wf_form_content .wf_linecontent .wf_page fieldset {
    padding: 0
}

.wf_form_content .wf_line_button {
    margin: 20px 0 18px 0
}

.wf_form_content .wf_line_button .wf_linecontent {
    margin: 0 20px 12px 0;
    width: auto
}

.wf_form_content .wf_line_2_cols .wf_line_button {
    margin: 20px 0 18px 0
}

.wf_form_content .wf_line_2_cols .wf_line_button .wf_linecontent {
    margin: 0 20px 12px 0;
    width: auto
}

.wf_form_content .wf_line_2_cols .wf_linecontent {
    width: 48%;
    float: right
}

.wf_form_content .wf_line_2_cols .wf_linecontent:first-child {
    float: left
}

.wf_form_content .wf_page_button_next .wf_linecontent {
    width: auto;
    float: none !important
}

.wf_form_content .wf_page_button_next .wf_page_errorMessage {
    overflow: hidden;
    zoom: 1
}

.wf_form_content .wf_page_button_next .wf_validate_field {
    margin: 0 20px 12px 0;
    float: left
}

.wf_label_field {
    min-height: 1px;
    width: 150px;
    float: left;
    margin-right: 7px;
    margin-top: 7px;
    display: inline
}

.wf_label_field:after {
    content: "";
    clear: none
}

.wf_label_top {
    float: none;
    display: block;
    margin-bottom: 4px;
    width: auto
}

.wf_label_top.wf_field_input {
    margin: 0
}

.wf_input_group .wf_label_field {
    vertical-align: middle
}

.wf_group {
    display: inline-block;
    margin-bottom: 2px
}

.wf_vertical .wf_input_group .wf_group {
    display: block
}

.wf_header {
    height: 150px;
    margin: 0 0 10px
}

.wf_logo_icon {
    display: block;
    width: 101px;
    height: 24px
}

.wf_page {
    position: relative
}

.wf_page .wf_connexion {
    padding: 0 4px 5px 35px;
    margin-bottom: 10px
}

.wf_page .wf_connexion .wf_html p img {
    position: relative;
    top: -1px;
    vertical-align: middle
}

.wf_page .wf_title_container {
    zoom: 1;
    padding-bottom: 13px;
    display: table;
    width: 100%
}

.wf_page .wf_validate,
.wf_page .wf_contentPage {
    zoom: 1;
    position: relative
}

.wf_title_container .wf_page_title {
    padding: 0 0 0 12px;
    line-height: 1.8em;
    min-height: 27px;
    display: table-cell
}

.number_container {
    display: table-cell;
    width: 45px
}

.wf_numbering {
    width: 40px;
    height: 40px;
    display: block;
    line-height: 37px
}

.wf_page_valid .wf_title_container {
    padding-bottom: 13px
}

.wf_page_open .wf_title_container {
    padding-bottom: 13px
}

.wf_page_footer .wf_linecontent {
    width: 100%;
    clear: both
}

.wf_page_footer .wf_title_container {
    display: none
}

.wf_page_footer .wf_checkbox,
.wf_page_footer .wf_radio {
    margin: 0 0 10px
}

.wf_page_footer .wf_input_checkbox,
.wf_page_footer .wf_input_radio {
    width: 20px;
    float: left
}

.wf_page_footer .wf_label_field {
    min-height: 0
}

.wf_page_footer .wf_message_box {
    display: none
}

.wf_page_footer .wf_input_group .wf_label_field {
    min-height: 0;
    margin-bottom: 10px
}

.wf_page_footer .wf_message_box {
    display: none
}

.wf_page_header .wf_linecontent {
    width: 91%;
    clear: both
}

.wf_page_header .wf_title_container {
    display: none
}

.wf_page_header .wf_checkbox,
.wf_page_header .wf_radio {
    margin: 0px
}

.wf_page_header .wf_checkbox .wf_label_field,
.wf_page_header .wf_radio .wf_label_field {
    min-height: 0;
    margin-bottom: 0px
}

.wf_page_header .wf_input_checkbox,
.wf_page_header .wf_input_radio {
    width: 20px;
    float: left
}

.wf_page_header .wf_radio .wf_input_group {
    padding-top: 1px
}

.wf_page_header .wf_contentPage {
    margin-top: 0
}

.wf_page_header .wf_label_field {
    min-height: 0;
    margin-top: 0
}

.wf_page_header .wf_question {
    padding: 5px 20px
}

.wf_page_header .wf_container_explanation {
    display: none
}

.wf_dropdown {
    position: absolute;
    overflow: auto;
    width: 200px;
    margin-top: -1px;
    height: 270px
}

.wf_dropdown .dpcontent {
    width: 100%;
    padding: 0
}

.wf_dropdown .dpcontent .imageCell {
    padding-bottom: 10px
}

.wf_dropdown .dpcontent td {
    vertical-align: middle;
    padding: 4px 6px
}

.wf_dropdown .dpcontent li {
    margin: 0;
    padding: 0;
    margin-bottom: 3px
}

.wf_dropdown ul {
    display: block;
    margin: 0;
    padding: 0;
    zoom: 1
}

.wf_dropdown ul a {
    display: block;
    vertical-align: top;
    margin: 0;
    padding: 4px 14px;
    clear: both;
    overflow: auto;
    *zoom: 1
}

.wf_dropdown li {
    display: block;
    margin: 0;
    padding: 0;
    zoom: 1
}

.wf_dropdown li .itemText {
    display: block;
    overflow: hidden
}

.wf_dropdown li .img {
    overflow: hidden;
    float: left;
    margin-right: 6px
}

.wf_dropdown li .img img {
    display: block
}

.wf_dropdown .inline,
.wf_dropdown .inline a {
    display: inline-block;
    *display: inline;
    zoom: 1
}

.wf_question {
    min-height: 1px
}

.wf_question .wf_content>.wf_line,
.wf_question .wf_content>.wf_linemultiple {
    display: block
}

.wf_question_nochild {
    margin-bottom: -1px
}

.wf_question_nochild .wf_question_resume,
.wf_question_nochild .wf_content,
.wf_question_nochild .wf_container_explanation {
    display: none
}

.wf_connexion .wf_linecontent,
.wf_htmlTemplate .wf_linecontent {
    width: 100%
}

.wf_connexion .wf_icon_box,
.wf_htmlTemplate .wf_icon_box {
    display: none
}

.wf_icon_box {
    display: block;
    float: left;
    position: absolute;
    right: 0
}

.wf_label_top+.wf_icon_box {
    top: inherit;
    bottom: 8px
}

.wf_icon_box .wf_help_icon {
    margin-top: 8px;
    margin-left: 9px;
    height: 22px;
    width: 22px;
    display: block
}

.wf_validate {
    display: inline-block;
    height: 22px;
    width: 26px;
    float: left
}

.wf_line_button .wf_icon_box,
.wf_line_button_image .wf_icon_box {
    display: none
}

.wf_pagination {
    margin: 7px 8px 0 0;
    overflow: hidden;
    position: relative;
    z-index: 1
}

.wf_pagination ul {
    display: inline-block
}

.wf_pagination ul li {
    display: inline;
    zoom: 1
}

.wf_pagination ul li a {
    padding: 0 5px
}

.wf_pagination .wf_previous a,
.wf_pagination .wf_next a {
    line-height: 10px;
    text-indent: -9999px;
    padding: 0;
    display: inline-block;
    *display: block;
    height: 9px;
    width: 7px
}

.wf_globalError {
    padding: 10px;
    width: 300px;
    margin-left: -150px;
    left: 50%;
    position: absolute;
    top: 20%
}

.wf_50pc {
    width: 50%
}

.wf_adviceMessage {
    padding-left: 30px
}

.wf_loader {
    width: 1px;
    height: 1px;
    visibility: hidden;
    z-index: 1000
}

.wf_question_resume {
    position: relative
}

.wf_question_resume .wf_resume_block .wf_resume_details {
    padding-top: 10px
}

.wf_question_resume .wf_resume_block .wf_resume_img {
    display: inline-block;
    vertical-align: middle;
    margin-right: 10px
}

.wf_question_resume .wf_resume_dealer .wf_resume_details {
    padding: 0 0 0 70px;
    font-size: 10px;
    width: 50%;
    min-height: 50px
}

.wf_question_resume .wf_resume_dealer .wf_resume_details h3 {
    margin: 0
}

.wf_question_resume .wf_content {
    padding: 10px 0 0 0;
    position: relative;
    z-index: 1
}

.wf_question_resume .wf_resume_content {
    margin-bottom: 0px
}

.wf_question_resume .wf_form_content .wf_linecontent {
    margin-bottom: 4px
}

.wf_question_resume .wf_link_01 {
    padding: 2px 0 3px 14px;
    right: 0;
    z-index: 2;
    top: -5px
}

.wf_question_resume .wf_link_01 .wf_modify {
    margin-top: 0
}

.wf_title_container .wf_link_01 {
    display: table-cell
}

.wf_connector .wf_icon_wrapper {
    display: inline-block;
    *display: inline;
    *zoom: 1;
    margin: 3px 0 0 11px
}

.wf_connector .wf_icon_box {
    float: none;
    width: 22px;
    margin: 0;
    position: static
}

.wf_connector .wf_icon_box .wf_help_icon {
    width: 22px;
    margin: 0;
    font-size: 15px
}

.wf_connector .wf_tooltip_mobile {
    margin: 1.500em 0 !important;
    padding: 12px 10px 15px 10px !important;
    border: 1px solid #a5a5a5;
    position: relative;
    width: auto !important;
    display: none
}

.wf_connector .wf_tooltip_mobile_titre {
    background: url("../../images/forms/skinMobile/icons/icon_question_field_s.png") no-repeat scroll 0 -29px transparent;
    display: block;
    font-size: 0.833em;
    height: 25px;
    line-height: 25px;
    margin: 0 0 12px;
    padding: 0 0 0 23px;
    text-transform: uppercase
}

.wf_connector .wf_mobile_close {
    position: absolute;
    top: 9px;
    right: 9px;
    width: 20px;
    height: 20px;
    color: #fff;
    text-indent: -5000px;
    background: url("../../images/forms/skinMobile/icons/close_s.png") no-repeat scroll center center #666
}

.wf_noMessage .wf_message {
    display: none
}

.wf_message_behavior .wf_message {
    margin-left: 5px
}

.wf_noMessage .wf_message_behavior .wf_message {
    display: block
}

.wf_connector {
    float: left;
    margin: 0 15px 10px 0
}

.wf_connector a {
    display: inline-block;
    *display: inline;
    *zoom: 1;
    height: 28px;
    *height: 25px;
    padding: 0 0 0 13px;
    *padding: 3px 0 0 13px;
    vertical-align: top;
    margin: 0 0 0 30px
}

.wf_connector a .wf_icon_wrapper {
    float: left
}

.wf_connector a img {
    max-width: 115px;
    display: inline-block;
    padding: 0 13px 0 0;
    vertical-align: middle;
    height: 15px;
    border: 0px
}

.wf_connector a:first-child {
    margin: 0
}

.wf_connector a:after {
    content: "";
    display: inline-block;
    height: 100%;
    vertical-align: middle;
    width: 0
}

.wf_connector a .textWrapper {
    padding: 0 13px 0 0;
    vertical-align: middle
}

.wf_tooltip .wf_connector a {
    margin: 5px 0 0 0
}

.wf_tooltipQAS {
    margin-left: 45px
}

.wf_tooltipQAS .arrow {
    position: absolute;
    left: -5px;
    height: 7px;
    width: 5px;
    top: 18px
}

.wf_tooltipQAS .wf_QAS_inner {
    padding: 10px
}

.wf_tooltipQAS .wf_select .wf_label_field,
.wf_tooltipQAS .wf_message_box {
    display: none
}

.wf_tooltipQAS .wf_field_select {
    display: block;
    width: 100%
}

.wf_tooltipQAS .wf_field_select select {
    width: 100%
}

.wf_tooltipQAS .wf_question .wf_select {
    margin-bottom: 15px
}

.wf_tooltip {
    zoom: 1;
    width: 370px;
    z-index: 999;
    padding: 15px;
    position: absolute;
    margin-top: -41px;
    margin-left: 11px
}

.wf_tooltip .close {
    display: none
}

.wf_tooltip:before {
    content: "";
    width: 10px;
    height: 21px;
    display: block;
    position: absolute;
    top: 20px;
    left: -10px
}

.wf_tooltip_left {
    margin-left: -20px
}

.wf_tooltip_left:before {
    left: auto;
    right: -10px
}

.datepicker {
    position: absolute;
    width: 170px;
    margin-left: 10px;
    margin-top: -84px
}

.wf_vertical {
    display: inline-block
}

.wf_form_content .wf_page_footer .wf_radio .wf_input_group .wf_label_field {
    display: inline-block;
    float: none;
    margin: 0 15px 0 0px;
    white-space: normal;
    width: auto
}

.wf_form_checkBoxMyAdress {
    margin-left: 160px
}

.wf_form_checkBoxMyAdress .wf_label_field {
    width: auto;
    float: none
}

.wf_form_checkBoxMyAdress .wf_input_group {
    margin-top: 10px
}

.wf_input_group {
    margin-top: 5px
}

.wf_form_content .wf_field_input,
.wf_form_content .wf_input_group,
.wf_form_content .wf_page_footer .wf_checkbox .wf_input_group .wf_label_field,
.wf_form_content .wf_message_box,
.wf_form_content .wf_popin_login .wf_message_box {
    display: table;
    *display: block;
    zoom: 1;
    float: none;
    width: auto
}

.wf_form_content .wf_field_input .wf_field_input,
.wf_form_content .wf_input_group .wf_field_input,
.wf_form_content .wf_page_footer .wf_checkbox .wf_input_group .wf_label_field .wf_field_input,
.wf_form_content .wf_message_box .wf_field_input,
.wf_form_content .wf_popin_login .wf_message_box .wf_field_input {
    *margin-right: 4px;
    margin-top: 3px
}

.wf_form_content .wf_field_input .wf_ajax_loading *,
.wf_form_content .wf_input_group .wf_ajax_loading *,
.wf_form_content .wf_page_footer .wf_checkbox .wf_input_group .wf_label_field .wf_ajax_loading *,
.wf_form_content .wf_message_box .wf_ajax_loading *,
.wf_form_content .wf_popin_login .wf_message_box .wf_ajax_loading * {
    visibility: hidden
}

.wf_form_content .wf_comp_loading {
    min-height: 32px
}

.wf_form_content .wf_field_input input,
.wf_form_content .wf_field_input select,
.wf_form_content .wf_field_input textarea {
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -ms-box-sizing: border-box;
    box-sizing: border-box;
    *margin-right: -4px;
    position: relative
}

.wf_form_content .wf_input_select {
    *margin-right: 0
}

.wf_form_content .wf_captcha {
    margin: 0 0 10px
}

.wf_form_content .wf_captcha_box {
    height: 59px;
    position: relative;
    display: block;
    margin: 0 0 10px
}

.wf_form_content .wf_captcha_box .wf_captcha_reload {
    position: absolute;
    bottom: 3px;
    right: 3px
}

.wf_form_content .wf_captcha_image {
    width: 100%;
    height: 59px
}

.wf_form_content .wf_field_select {
    *margin-right: 0;
    *margin-top: 5px;
    *overflow: hidden;
    position: relative;
    *height: auto
}

.wf_form_content .wf_field_select select {
    *margin: -1px -6px -1px -1px;
    *height: auto;
    *width: 101%
}

.wf_popin .wf_QAS_inner input {
    width: 100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -ms-box-sizing: border-box;
    box-sizing: border-box;
    *margin-right: -4px;
    position: relative
}

.no-fontface .wf_form_content .wf_field_input,
.no-fontface .wf_form_content .wf_input_group,
.no-fontface .wf_form_content .wf_page_footer .wf_checkbox .wf_input_group .wf_label_field,
.no-fontface .wf_form_content .wf_message_box,
.no-fontface .wf_form_content .wf_popin_login .wf_message_box {
    width: 100%
}

.wf_form_content .wf_page_footer .wf_checkbox .wf_input_group .wf_input_checkbox {
    margin: 0 3px 0 0
}

.wf_message_box_content {
    position: relative;
    display: block
}

.wf_popin_login .wf_message {
    margin-left: 3px
}

.wf_message {
    position: absolute;
    width: 100%
}

.wf_page .wf_title_container:after {
    display: block;
    content: '.';
    height: 0;
    visibility: hidden;
    clear: both
}

.wf_modify {
    float: right;
    margin-top: 7px;
    padding-left: 25px;
    visibility: hidden
}

.wf_page_valid .wf_title_container .wf_modify,
.wf_page_valid .wf_title_container .wf_page .wf_link_01,
.wf_page_open .wf_contentPage .wf_question_resume .wf_modify {
    visibility: visible
}

.wf_page_valid.wf_page_open .wf_title_container .wf_modify {
    visibility: hidden
}

.wf_page_valid .wf_title_container .wf_link_01 {
    padding: 5px 0 6px 14px
}

.wf_page_errorMessage {
    margin: 10px 0;
    display: none
}

.wf_multiplecomponent_content .wf_linemultiple {
    display: table !important;
    margin-bottom: 20px;
    position: relative
}

.wf_multiplecomponent_content .wf_linemultiple .wf_linecontent {
    display: table-cell;
    float: none;
    vertical-align: top;
    width: 100%
}

.wf_multiplecomponent_content .wf_linemultiple .wf_linecontent+.wf_linecontent>.wf_field {
    padding-right: 120px
}

.wf_multiplecomponent_content .wf_linemultiple .wf_linecontent:first-child {
    float: none
}

.wf_multiplecomponent_content .wf_linemultiple .buttons_addremove {
    display: table-cell;
    float: none;
    min-width: 54px;
    position: absolute;
    right: -65px;
    top: 6px;
    vertical-align: middle
}

.wf_multiplecomponent_content .wf_linemultiple .wf_field_input {
    position: relative
}

.wf_linemultiple .wf_label_field {
    visibility: hidden;
    height: 20px
}

.wf_linemultiple .wf_label_field.wf_label_top {
    display: none
}

.wf_linemultiple .buttons_addremove {
    float: left
}

.wf_linemultiple .add_line,
.wf_linemultiple .remove_line {
    height: 22px;
    width: 22px;
    display: block;
    float: left;
    top: 3px;
    position: absolute;
    text-indent: -5000px
}

.wf_linemultiple .add_line {
    left: 2px;
    display: none
}

.wf_linemultiple .remove_line {
    left: 35px;
    line-height: 30px
}

.wf_line_first .wf_label_field {
    visibility: visible;
    height: auto
}

.wf_line_first .wf_label_field.wf_label_top {
    display: block
}

.wf_line_first.wf_line_last .remove_line {
    display: none
}

.wf_linemultiple.wf_line_last .add_line {
    display: block
}

.wf_multicomponent_full .wf_line .add_line,
.wf_multicomponent_full .wf_linemultiple .add_line {
    display: none
}

.wf_title_01 {
    margin: 10px 0
}

.wf_field_input.wf_slider {
    position: relative;
    top: 8px;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -o-user-select: none;
    user-select: none
}

.wf_slider_container {
    width: 100%;
    height: 30px;
    margin-top: -20px;
    position: relative
}

.wf_slider_left,
.wf_slider_right {
    position: absolute;
    width: 9px;
    height: 27px
}

.wf_slider_left {
    left: 0
}

.wf_slider_right {
    right: 0
}

.wf_slider_picker {
    margin-right: -2px;
    width: 50px;
    height: 2px;
    position: relative;
    z-index: 2;
    top: -4px
}

.wf_slider_last_picker {
    display: block;
    margin: 5px;
    width: 0;
    height: 0;
    position: absolute;
    top: -11px;
    right: -10px
}

.wf_slider_first_picker {
    display: block;
    content: " ";
    width: 0;
    height: 0;
    position: absolute;
    bottom: -6px;
    left: -5px
}

.wf_slider_value {
    float: left;
    height: 15px;
    padding: 2px 5px
}

.wf_slider_value_last {
    float: right
}

.wf_field_rte iframe {
    height: 121px
}

.wf_rte_zone {
    margin: 0;
    padding: 0;
    clear: both
}

.wf_rte_zone textarea {
    padding: 0;
    margin: 0;
    border: 0;
    position: relative;
    left: 0;
    clear: both
}

.wf_rte_resizer {
    width: 100%;
    height: 20px;
    margin: 0;
    padding: 0;
    display: block
}

.wf_rte_toolbar {
    width: 100%;
    margin: 0;
    padding: 0;
    display: block
}

.wf_rte_toolbar p {
    margin: 0;
    padding: 0;
    clear: both
}

.wf_rte_toolbar select {
    height: 16px;
    padding: 0;
    margin: 0
}

.wf_rte_toolbar .wf_colorPicker {
    display: block;
    float: left;
    width: 10px;
    height: 10px;
    margin-right: 5px;
    margin-top: 4px
}

.wf_rte_panel {
    position: absolute;
    left: 0;
    top: 0;
    display: block;
    clear: both;
    margin: 0px;
    padding: 5px 5px 0 5px
}

.wf_rte_panel .wf_rte_panel-title {
    margin: -5px -5px 5px -5px;
    padding: 5px;
    height: 16px;
    line-height: 16px;
    display: block;
    clear: both
}

.wf_rte_panel .wf_rte_panel-title .close {
    position: absolute;
    top: 0;
    right: 0;
    display: block;
    float: right
}

.wf_rte_panel label {
    display: block;
    float: left;
    width: 50px;
    margin: 0 5px 0 2px;
    line-height: 20px
}

.wf_rte_panel input,
.wf_rte_panel select {
    margin: 0 5px 0 2px;
    padding: 0;
    height: 20px;
    float: left;
    vertical-align: middle;
    line-height: 20px
}

.wf_rte_panel .symbols {
    margin: 0;
    padding: 0;
    clear: both
}

.wf_rte_panel .symbols a {
    line-height: 14px;
    vertical-align: middle;
    width: 18px;
    height: 18px;
    float: left
}

.wf_rte_panel .colorpicker2 .palette .item {
    width: 10px;
    height: 10px;
    margin: 0;
    padding: 0;
    float: left
}

.wf_rte_panel img {
    padding: 0;
    margin: 0;
    border: 0
}

.wf_rte_toolbar .clear {
    display: block;
    clear: both;
    border: 0;
    padding: 2px 0 0 0;
    margin: 0
}

.wf_rte_toolbar ul {
    display: block;
    margin: 0px;
    padding: 0;
    width: 100%
}

.wf_rte_toolbar ul .separator {
    height: 16px;
    margin: 5px
}

.wf_rte_toolbar li {
    float: left;
    padding: 0;
    margin: 5px 2px;
    height: 16px
}

.wf_rte_toolbar li a {
    display: block;
    width: 16px;
    height: 16px;
    margin: 0;
    padding: 0
}

.wf_form_content .wf_page_header .wf_group .wf_custom_radio {
    top: -5px;
    margin-bottom: -10px;
    display: block
}

.wf_form_content .wf_input_group .wf_custom_radio {
    top: -2px
}

.wf_error_message {
    color: #da0000;
    display: block
}

.wf_line .wf_content ~ .wf_linecontent,
.wf_linemultiple .wf_content ~ .wf_linecontent {
    margin: 0
}

.wf_line .wf_content ~ .wf_linecontent:last-of-type,
.wf_linemultiple .wf_content ~ .wf_linecontent:last-of-type {
    margin-bottom: 10px
}

.wf_multiplecomponent_content .wf_linemultiple .wf_linecontent {
    display: block;
    float: none;
    vertical-align: top;
    width: 100%
}

.wf_multiplecomponent_content .wf_linemultiple .wf_linecontent>.wf_field {
    float: left;
    padding-right: 0
}

.wf_multiplecomponent_content .wf_linemultiple .wf_linecontent:first-child {
    float: none
}

.wf_multiplecomponent_content .wf_linemultiple .buttons_addremove {
    vertical-align: bottom
}

.wf_multiplecomponent_content .wf_linemultiple .buttons_addremove a {
    margin-bottom: 18px
}

.wf_multiplecomponent_content .wf_linemultiple .wf_field_input {
    position: relative
}

.wf_multiplecomponent_content .wf_linemultiple .buttons_addremove {
    top: 5px;
    right: -65px
}

.wf_multiplecomponent_content .wf_linemultiple .wf_label_field {
    display: none
}

.wf_multiplecomponent_content .wf_linemultiple.wf_line_first .wf_label_field {
    display: block
}

.wf_multiplecomponent_content .wf_linemultiple {
    margin-bottom: 20px
}

.wf_multiplecomponent_content .wf_linemultiple.wf_line_last {
    margin-bottom: 0
}

#wf_form_content {
    padding: 0 9px
}

.wf_form_content {
    box-sizing: border-box
}

.wf_form_content .wf_page_page {
    padding-bottom: 10px;
    padding-top: 11px
}

.wf_title_container {
    display: table;
    width: 100%
}

.wf_title_container .wf_page_title {
    line-height: 1.3em;
    display: table-cell;
    float: none;
    padding-right: 0;
    vertical-align: top;
    padding-top: 6px
}

.wf_page_valid .wf_title_container,
.wf_page .wf_title_container {
    padding-bottom: 0
}

.wf_page_valid .wf_title_container .wf_link_01 {
    padding: 0
}

.number_container {
    display: table-cell;
    width: 1px;
    vertical-align: top
}

.number_container .wf_numbering {
    width: 30px;
    height: 22px;
    line-height: 1em;
    margin: 0 10px 0 0;
    float: none;
    padding-top: 4px;
	background-color:'.$sPrimaryColor.';
}

.wf_question_resume .wf_link_01 {
    display: table-cell;
    float: none;
    vertical-align: top;
    top: 20px !important
}

.wf_question_resume .wf_link_01 .wf_modify {
    margin-top: -20px
}

.no-fontface .wf_modify:before {
    display: none
}

.wf_form_content .wf_linecontent {
    max-width: none;
    margin-bottom: 0 !important
}

.wf_form_content .wf_linecontent>.wf_field {
    max-width: 100%;
    width: 100%;
    padding-right: 0
}

.wf_form_content .wf_linecontent>.wf_field .wf_field_input {
    margin-top: 7px;
    display: table-cell
}

.wf_form_content .wf_linecontent>.wf_field .wf_field_input input {
    min-height: 30px;
    max-width: 100%
}

.wf_form_content .wf_linecontent>.wf_field .wf_input_group {
    display: table-cell
}

.wf_form_content .wf_linecontent>.wf_field .wf_input .wf_field {
    margin-bottom: 12px
}

.wf_form_content .wf_label_field {
    width: auto;
    display: block;
    float: none;
    margin-top: 10px
}

.wf_form_content .wf_label_field input {
    width: 30%
}

.wf_group {
    white-space: normal
}

.wf_question .wf_line .wf_linecontent.wf_content,
.wf_question .wf_linemultiple .wf_linecontent.wf_content {
    display: table;
    width: 100%
}

.wf_question .wf_line .wf_linecontent.wf_content .wf_input,
.wf_question .wf_linemultiple .wf_linecontent.wf_content .wf_input {
    display: table-cell;
    float: none
}

.wf_question .wf_line .wf_linecontent.wf_content .wf_icon_box,
.wf_question .wf_linemultiple .wf_linecontent.wf_content .wf_icon_box {
    display: table-cell;
    float: none;
    vertical-align: top;
    padding: 2px 0 0 0;
    position: static
}

.wf_question .wf_line .wf_linecontent.wf_content .wf_icon_box .wf_help_icon,
.wf_question .wf_linemultiple .wf_linecontent.wf_content .wf_icon_box .wf_help_icon {
    height: 25px;
    width: 30px;
    margin: 0 0 2px 10px;
    padding-top: 5px
}

.wf_question .wf_line .wf_linecontent.wf_content.wf_linecontent_hidden,
.wf_question .wf_linemultiple .wf_linecontent.wf_content.wf_linecontent_hidden {
    margin: 0
}

.wf_tooltip {
    width: 100%;
    left: 0;
    margin: 0 0 0 30px
}

.wf_tooltip .tooltipcontent {
    margin-right: 15px
}

.wf_tooltip .close {
    display: block;
    position: absolute;
    right: 4px;
    top: 4px;
    width: 25px
}

.wf_tooltip:before {
    content: "";
    width: 10px;
    height: 21px;
    display: block;
    position: absolute;
    top: -15px;
    right: 15px;
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg)
}

.wf_synthesis {
    margin-top: 15px
}

.no-csstransforms .wf_tooltip:before {
    width: 21px;
    height: 10px;
    position: absolute;
    top: -10px;
    background-position: -1px -24px !important
}

.wf_form_content .wf_page_button_next .wf_validate_field {
    float: none;
    margin-right: 0
}

.select-custom {
    top: -2px
}

.wf_form_content .wf_linecontent {
    float: none !important
}

.wf_question .wf_line .wf_linecontent.wf_content .wf_connector .wf_icon_box .wf_help_icon,
.wf_question .wf_linemultiple .wf_linecontent.wf_content .wf_connector .wf_icon_box .wf_help_icon {
    margin: 0;
    height: 20px;
    width: 25px
}

.wf_connector .wf_icon_wrapper {
    margin: 0 0 0 11px
}

.wf_tooltip_mobile_wrapper {
    clear: both;
    overflow: auto
}

.wf_tooltip_mobile_wrapper .wf_tooltip_mobile {
    border: 1px solid #A5A5A5;
    display: none;
    margin-top: 10px;
    padding-bottom: 15px;
    padding-left: 10px !important;
    padding-right: 10px !important;
    padding-top: 12px;
    position: relative;
    width: auto !important;
    overflow: auto
}

.wf_tooltip_mobile_wrapper .wf_mobile_close {
    background: url("../../images/forms/skinMobile/icons/close_s.png") no-repeat scroll center center #666;
    color: #FFFFFF;
    height: 20px;
    position: absolute;
    right: 9px;
    text-indent: -5000px;
    top: 9px;
    width: 20px
}

.wf_dealer_locator {
    min-height: inherit
}

.wf_dealerlocator_form {
    width: auto
}

.wf_dealerlocator_form .wf_geo_wrapper {
    display: block;
    width: auto;
    white-space: normal
}

.wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn {
    display: block;
    width: 80%;
    margin: 0 auto;
    height: auto;
    padding: 15px 10px 15px 25px
}

.wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn:before {
    top: 50%;
    margin-top: -12px;
    left: 0;
    margin-left: 10px
}

.wf_dealerlocator_form .wf_geo_wrapper .wf_geo_and {
    display: block;
    width: 100%;
    text-align: center;
    margin: 5px 0 7px 0
}

.wf_dealerlocator_form .wf_searchbox_container {
    display: block;
    position: relative;
    max-width: none;
    width: 80%;
    margin: 0 auto
}

.wf_dealerlocator_form .wf_searchbox_container .searchBox {
    height: 40px;
    max-width: 100%;
    width: 100%;
    padding-right: 47px
}

.wf_dealerlocator_form .wf_searchbox_container .wf_validsearch {
    display: block;
    position: absolute;
    top: 1px;
    bottom: 1px;
    right: 2px;
    width: auto
}

.wf_dealerlocator_form .wf_searchbox_container .wf_validsearch button {
    margin-top: 4px;
    margin-left: -14px
}

.wf_geo_error {
    margin: 5px 0 0 10px
}

.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line,
.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_linemultiple {
    border: none
}

.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator,
.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator {
    width: auto;
    float: none;
    margin: 0;
    padding: 0
}

.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner,
.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner {
    margin: 0;
    padding: 0;
    min-height: inherit
}

.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item,
.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item {
    border-bottom: 1px solid #c7c7c9;
    padding: 17px 30px 15px 10px;
    margin: 0
}

.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:last-child,
.wf_dealer_locator_bottom .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:last-child {
    border: none
}

.wf_dealers_more {
    text-align: center
}

.wf_dealers_more .wf_dealers_more_button {
    height: auto;
    padding: 10px 30px 10px 30px;
    min-height: 30px;
    display: inline-block;
	border : 4px solid '.$sPrimaryColor.';
	width:15%;
	content: "\0074";
	font-family: "webformsIcons";
	display: block;
	background-color: #fff;
}

.wf_linecontent wf_content wf_select wf_field wf_custom_select input_select span.wf_custom_select_wrapper  wf_field_input wf_field_select select.selectSkin wf_cars_type wf_cars_type_4{
	border:0px;
}

.choose {
    padding: 10px 30px 10px 30px;
    border: none;
    margin-top: 30px
}

.wf_dealer_info {
    text-align: center
}

.wf_dealer_info .back {
    border: none;
    display: block;
    height: 30px;
    position: relative;
    padding: 0 15px 0 35px
}

.wf_dealer_info .address {
    padding: 0 10px;
    text-align: left
}

.wf_dealer_info .address h3 {
    margin-bottom: 0
}

.wf_dealer_info .address address {
    margin-bottom: 20px
}

.wf_dealer_info .map {
    height: 195px;
    border: 1px solid #c7c7c9
}

.label_custom_select {
    margin-bottom: 10px
}

.wf_brochurepicker .wf_brochurePickerBrochures {
    max-width: 100%;
    padding-top: 10px
}

.wf_carpicker .wf_cars_type_content .wf_cars_selection .selectSkin option {
    height: 75px
}

.wf_carpicker .wf_select .wf_field_select {
    height: 40px;
    line-height: 1.7em
}

.wf_carpicker .wf_select .wf_field_select select {
    height: 40px
}

.wf_cars_model {
    margin-top: 20px
}

.wf_range .wf_cars {
    float: none;
    display: block;
    width: auto
}

.wf_range .wf_cars label {
    display: table;
    width: 100%;
    min-height: 10px;
    padding: 15px 0;
    cursor: pointer
}

.wf_range .wf_cars label .wf_car_model {
    position: static;
    display: table-cell;
    vertical-align: middle;
    padding: 0 30px 0 10px
}

.wf_range .wf_cars label .wf_car_image {
    display: table-cell;
    vertical-align: middle;
    width: 30%;
    padding: 0
}

.wf_range .wf_cars label .wf_car_image img {
    padding-left: 3px;
    width: 100%
}

.wf_cars_type_content_result {
    margin: 10px auto
}

.wf_resume_block {
    padding-top: 10px
}

.wf_resume_block .wf_linecontent {
    display: table;
    width: 100%
}

.wf_resume_block .wf_linecontent .wf_resume_img {
    width: 30%;
    display: table-cell;
    vertical-align: middle
}

.wf_resume_block .wf_linecontent .wf_resume_img img {
    width: 100%
}

.wf_resume_block .wf_linecontent .wf_resume_details {
    display: table-cell;
    vertical-align: middle
}

.wf_brochurePickerBrochures .wf_brochurepicker_validate_button {
    margin-bottom: 30px
}

.wf_brochurePickerBrochures li>div {
    margin: 10px 0 20px 0;
    width: 100%;
    padding: 3px 0;
    overflow: hidden
}

.wf_brochurePickerBrochures li>div>div {
    float: left;
    width: 50%
}

.wf_brochurePickerBrochures li>div>div .wf_box_check {
    padding: 0 0 0 50px !important
}

.wf_brochurePickerBrochures li .wf_brochure_pdf {
    position: relative;
    padding-left: 14px;
    margin-bottom: 30px;
    display: block
}

.wf_synthesis_brochurePicker {
    display: block
}

.wf_synthesis_brochurePicker .left {
    width: 100%;
    display: table;
    font-size: 13px
}

.wf_synthesis_brochurePicker .left img {
    width: 100%;
    display: table-cell;
    border: 1px solid white
}

.wf_synthesis_brochurePicker .left h3 {
    width: 70%;
    display: table-cell;
    vertical-align: middle
}

.wf_synthesis_brochurePicker .right {
    padding: 15px 20px 0 20px
}

.wf_question_resume .wf_link_01 {
    top: 40px
}

.wf_cars_selection .wf_carPicker_darkCars {
    padding: 1px 0 0 0
}

.wf_form_content textarea {
    min-width: 100%;
    height: 170px;
    resize: none
}

.wf_form_content .wf_line_button,
.wf_cars_type_content_result {
    visibility: visible;
    text-align: center
}

.wf_button {
    visibility: visible;
    display: inline-block;
    float: none
}

.wf_content .wf_button {
    -moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    min-height: 30px;
    height: auto
}

.wf_content .wf_field .datepicker {
    width: 100%;
    border: 1px solid #a4a4a4;
    position: relative;
    margin: 0
}

.wf_content .wf_field .datepicker .calendarHeader {
    background: #747474;
    height: 32px;
    color: #fff;
    font-weight: bold;
    border-bottom: 1px solid #a4a4a4
}

.wf_content .wf_field .datepicker .dayTitle {
    font-size: 16px;
    padding-top: 5px
}

.wf_content .wf_field .calendarTable {
    background: #fff
}

.wf_content .wf_field .calendarTable thead th {
    background: #fff;
    font-size: 14px;
    color: #000;
    padding-bottom: 10px;
    padding-top: 10px
}

.wf_content .wf_field .day {
    background: #fff !important
}

.datepicker .previousBtn .previousMonthBtn,
.datepicker .nextBtn .nextMonthBtn {
    outline: none;
    text-indent: -9999px;
    display: block
}

.wf_bada .datepicker .day a {
    width: 21px;
    height: 21px;
    line-height: 21px
}

.datepicker .day a {
    width: 26.5px;
    height: 23px;
    display: inline-block;
    color: #424242;
    font-weight: bold;
    font-size: 15px;
    padding-top: 3px
}

.datepicker .forbiddenDay a {
    color: #dedede !important
}

.datepicker .day.nextMonth,
.datepicker .day.previousMonth {
    background: #fff
}

.datepicker .previousMonth a,
.datepicker .nextMonth a {
    color: #a1a1a1
}

.datepicker .calendarTable {
    background: #fff
}

.wf_form_content .datepicker .dayHover a {
    color: #000000
}

.wf_form_content .datepicker .dayHover.selectedDay a {
    color: #fff
}

.datepicker .calendarTable {
    padding: 0 5px 9px
}

.datepicker .calendarHeader .previousBtn .previousMonthBtn,
.datepicker .calendarHeader .nextBtn .nextMonthBtn {
    background: url("../../images/forms/skinMobile/icons/calendar_arrow.png") no-repeat 0 0;
    height: 20px;
    width: 15px
}

.datepicker .calendarHeader .nextBtn .nextMonthBtn {
    background-position: top right
}

.datepicker .calendarHeader .previousBtn {
    margin: 6px 3px 5px 14px
}

.datepicker .calendarHeader .nextBtn {
    margin: 6px 14px 3px 3px
}

.datepicker .wf_timePicker {
    padding: 5px 11px;
    text-transform: uppercase;
    padding-top: 0;
    padding-bottom: 0
}

.datepicker .wf_timePicker .wf_field_select {
    border: none;
    position: relative
}

.wf_form_content .datepicker .wf_timePicker .wf_field_select {
    display: block
}

.datepicker .wf_timePicker .wf_field_select .wf_custom_select_inner {
    color: #A4A4A4;
    font-weight: bold;
    font-size: 15px;
    text-transform: uppercase;
    border: 1px solid #a4a4a4;
    display: block;
    position: relative;
    white-space: normal;
    overflow: hidden;
    height: 20px;
    background: #fff url("../../images/forms/skinMobile/backgrounds/bg_custom_select.png") no-repeat right top
}

.datepicker .wf_timePicker .wf_field_select .wf_custom_select_inner .wf_custom_select_text:after {
    content: ""
}

.datepicker .wf_timePicker .wf_timepicker_select {
    font-size: 10px;
    cursor: pointer;
    height: 100%;
    border: 0;
    left: 0;
    opacity: 0;
    filter: alpha(opacity=0);
    top: 0;
    width: 100%;
    position: absolute;
    z-index: 2;
    padding: 0
}

.datepicker .wf_timepicker_label {
    font-size: 20px;
    color: #fff;
    margin: 12px 5px 10px 2px;
    font-weight: bold
}

.datepicker .wf_custom_select {
    float: right;
    width: 50%
}

.wf_inputHidden {
    position: absolute;
    left: -10000px;
    top: -100000px;
    height: 0
}

.wf_timePicker .wf_custom_select_text:after {
    visibility: hidden !important
}

.wf_box_check {
    padding-left: 40px !important;
    padding-top: 0px !important;
    display: inline-block;
    min-height: 30px
}

.wf_checkbox .wf_input_group .wf_group .wf_label_field:after {
    content: ""
}

.wf_box_check,
.wf_label_field .wf_radio_check {
    padding-left: 40px !important;
    padding-top: 0px !important;
    display: inline-block
}

.wf_page_footer .wf_radio .wf_input_group {
    display: table
}

.wf_page_footer .wf_radio .wf_input_group .wf_group {
    display: table-cell;
    vertical-align: top
}

.wf_page_footer .wf_radio .wf_input_group .wf_group .wf_radio_check {
    white-space: nowrap
}

.wf_radio .wf_custom_radio .wf_label_field {
    display: block
}

.no-fontface .wf_custom_radio .wf_label_field {
    margin-bottom: 15px
}

.wf_field_select {
    overflow: hidden;
    position: relative;
    max-width: 590px;
    height: 36px
}

.wf_field_select .wf_custom_select_wrapper {
	border: 1px solid '.$sPrimaryColor.';
    position: relative;
    display: block;
    height: 36px
}

.wf_field_select .wf_custom_select_inner {
    display: block;
    padding: 9px 0 10px 15px;
    position: absolute;
    left: 0;
    right: 4px;
    top: 0;
    bottom: 0;
    height: 17px
}

.wf_field_select .wf_custom_select_inner select {
    position: absolute;
    height: 36px;
    top: 0px;
    left: 0;
    width: 100%;
    z-index: 1
}

.wf_select .wf_field_select {
    max-width: 100%
}

.wf_select .wf_field_select select {
    min-height: 30px;
    max-width: 100%;
    height: auto
}

.no-fontface .wf_custom_select_inner .wf_custom_select_text:first-child,
.no-fontface .select-custom:first-child {
    visibility: hidden
}

.no-fontface .wf_custom_select_inner .wf_custom_select_text:first-child:after,
.no-fontface .select-custom:first-child:after {
    visibility: visible
}

.no-fontface .selectSkin,
.no-fontface .wf_field_select>select {
    top: 10px;
    left: 10px
}

.wf_question .wf_linemultiple .wf_linecontent.wf_content .wf_icon_box {
    padding-left: 75px
}

.wf_input .wf_field_file .wf_filename {
    position: absolute;
    top: 0;
    width: 70%;
    max-width: none
}

.wf_input .wf_field_file .wf_progressblock .wf_inline {
    min-height: 25px
}

.wf_fileUploaded .wf_field_file .wf_filename {
    max-width: none;
    top: 9px
}

.wf_input .wf_field_file .wf_progressbar {
    right: auto;
    left: 15px;
    top: 23px;
    width: 70%
}

.wf_multiplecomponent_content .wf_linemultiple {
    max-width: none
}

.wf_question .wf_linemultiple .wf_label_top+.wf_icon_box {
    top: 1px
}

.wf_question .wf_linemultiple.wf_line_first .wf_label_top+.wf_icon_box {
    bottom: 6px;
    top: initial
}

.clearfix,
.wf_form_content .wf_linecontent,
.wf_form_content .wf_page_button_next .wf_linecontent,
.wf_line,
.wf_linemultiple,
.wf_cars_selection,
.wf_pagination ul,
.wf_dealer_locator_icons,
.wf_multiplecomponent {
    zoom: 1
}

.clearfix:before,
.wf_form_content .wf_linecontent:before,
.wf_form_content .wf_page_button_next .wf_linecontent:before,
.wf_line:before,
.wf_linemultiple:before,
.wf_cars_selection:before,
.wf_pagination ul:before,
.wf_dealer_locator_icons:before,
.wf_multiplecomponent:before,
.clearfix:after,
.wf_form_content .wf_linecontent:after,
.wf_form_content .wf_page_button_next .wf_linecontent:after,
.wf_line:after,
.wf_linemultiple:after,
.wf_cars_selection:after,
.wf_pagination ul:after,
.wf_dealer_locator_icons:after,
.wf_multiplecomponent:after {
    display: table;
    content: " "
}

.clearfix:after,
.wf_form_content .wf_linecontent:after,
.wf_form_content .wf_page_button_next .wf_linecontent:after,
.wf_line:after,
.wf_linemultiple:after,
.wf_cars_selection:after,
.wf_pagination ul:after,
.wf_dealer_locator_icons:after,
.wf_multiplecomponent:after {
    clear: both
}

.wf_box_check,
.wf_radio .wf_custom_radio .wf_label_field .wf_radio_check {
    position: relative;
    padding-left: 34px !important;
    padding-top: 1px !important
}

.wf_box_check input,
.wf_radio .wf_custom_radio .wf_label_field .wf_radio_check input {
    position: absolute;
    left: -9999px
}

@font-face {
    font-family: "roboto";
    src: url("roboto_light-webfont.eot");
    src: url("roboto_light-webfont.eot?#iefix") format("embedded-opentype"), url("roboto_light-webfont.woff") format("woff"), url("roboto_light-webfont.ttf") format("truetype"), url("roboto_light-webfont.svg") format("svg");
    font-weight: 100;
    font-style: normal;
    -moz-font-feature-settings: "liga=0";
    -moz-font-feature-settings: "liga" 0
}

@font-face {
    font-family: "roboto";
    src: url("roboto_medium-webfont.eot");
    src: url("roboto_medium-webfont.eot?#iefix") format("embedded-opentype"), url("roboto_medium-webfont.woff") format("woff"), url("roboto_medium-webfont.ttf") format("truetype"), url("roboto_medium-webfont.svg") format("svg");
    font-weight: 400;
    font-style: normal;
    -moz-font-feature-settings: "liga=0";
    -moz-font-feature-settings: "liga" 0
}

@font-face {
    font-family: "roboto";
    src: url("roboto_bold-webfont.eot");
    src: url("roboto_bold-webfont.eot?#iefix") format("embedded-opentype"), url("roboto_bold-webfont.woff") format("woff"), url("roboto_bold-webfont.ttf") format("truetype"), url("roboto_bold-webfont.svg") format("svg");
    font-weight: 700;
    font-style: normal;
    -moz-font-feature-settings: "liga=0";
    -moz-font-feature-settings: "liga" 0
}

@font-face {
    font-family: "webformsIcons";
    src: url("../css/fonts/webformsIcons.eot");
    src: url("../css/fonts/webformsIcons.eot?#iefix") format("embedded-opentype"), url("../css/fonts/webformsIcons.woff") format("woff"), url("../css/fonts/webformsIcons.ttf") format("truetype"), url("../css/fonts/webformsIcons.svg") format("svg");
    font-weight: normal;
    font-style: normal;
    -moz-font-feature-settings: "liga=0";
    -moz-font-feature-settings: "liga" 0
}

.wf_dealer_locator .wf_fiche_complete:hover,
.wf_dealer_locator .wf_fiche_complete:active {
    color: #116973
}

.wf_dealer_locator .wf_searchresult {
    font-size: 10px
}

.wf_dealer_locator .wf_searchresult strong {
    color: '.$sPrimaryColor.'
}

.wf_dealer_locator .wf_hover {
    border-color: #EDEDED;
    background-color: #f3f3f3
}

.wf_dealer_locator .wf_selected {
    background-color: #f3f3f6;
    cursor: default
}

.wf_dealer_locator .wf_selected .wf_dealer_select {
    visibility: hidden
}

.wf_dealerlocator_form {
    margin: 15px 0
}

.wf_dealerlocator_form .wf_html.betweenLineWord {
    margin: 0 0 5px
}

.wf_dealerlocator_form label {
    color: #939393;
    font-weight: normal
}

.wf_form_content .wf_dealerlocator_form .wf_error input {
    border-color: #de113c
}

.wf_dealer_locator_bottom {
    margin-bottom: 34px
}

.wf_dealers_more_button,
.wf_geo_btn,
 {

    font-size: 17px;
    /*-moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;*/
    cursor: pointer;
    background-color: #fff;
	border: 4px solid '.$sPrimaryColor.';
	filter: brightness(100%);
	width:15%;
}
.wf_validsearch button{

	 border: 0;
}

.wf_dealers_more_button:hover,
.wf_geo_btn:hover,
.wf_validsearch button:hover {
    /*background-color: #BEBEBE*/
	filter: brightness(120%);
}

.wf_dealers_more_button {
    font-family: "roboto";
    font-weight: 100
}

.wf_geo_btn {
    font-family: "roboto";
    position: relative
}

.wf_geo_btn:before {
    content: "\007A";
    font-family: webformsIcons;
    font-size: 37px;
    position: absolute;
    left: 10px;
    top: 5px;
    color: '.$sPrimaryColor.';
    line-height: 1em
}

.wf_validsearch button {
    font-size: 17px;
    font-family: "roboto"
}

.wf_geo_and {
    color: #4b4a4d;
    font-size: 17px;
    font-family: "roboto"
}

.wf_dealer_select_links .wf_button {
    background: #116973
}

.wf_dropdown {
    font-family: "roboto";
    font-size: 17px
}

.wf_location_map {
    border-left: 1px solid #cececf
}

.wf_dealer_locator_item {
    font-family: arial;
    font-size: 12px;
    cursor: pointer
}

.wf_dealer_locator_item .distance {
   /*color: #DA0000;*/
    font-style: italic;
    font-weight: bold
}

.wf_dealer_locator_item address {
    font-style: normal;
    color: #868689
}

.wf_dealer_locator_item a {
    color: #595959
}

.wf_dealer_locator_item h3 {
    text-transform: uppercase;
    color: #333235;
    font-size: 1em;
    font-weight: bold;
    text-align: left
}

.wf_dealer_locator_item .inner {
    border: 1px solid #EDEDED
}

.wf_dealer_locator_item .distanceFromPoint {
    color: #116973;
    text-transform: uppercase;
    font-size: 10px
}

.wf_dealer_locator_item .wf_button {
    font-size: 10px
}

.wf_current_location {
    background: #eaeaea
}

.wf_selected_dealer {
    cursor: pointer
}

.wf_comp_loading span {
    opacity: 0;
    filter: alpha(opacity=0)
}

.wf_comp_loading .wf_dealer_locator_results,
.wf_comp_loading .wf_dealer_locator_noresults {
    opacity: 0.3;
    filter: alpha(opacity=30)
}

.wf_dealer_locator_layer {
    background: #f7f7f7
}

.wf_dealer_locator_layer h3 {
    color: #333235;
    text-transform: uppercase;
    font-size: 1em
}

.wf_dealer_locator_layer a {
    color: #333235
}

.wf_dealer_locator_layer address {
    font-style: normal
}

.wf_scrollbar .overview li {
    list-style: none;
    font-size: 12px;
    border-bottom: 1px solid #cececf
}

.wf_scrollbar .overview li:hover,
.wf_scrollbar .overview li:active {
    background-color: #f3f3f6
}

.wf_scrollbar .overview li h3 {
    text-transform: uppercase;
    font-weight: normal
}

.wf_scrollbar .viewport {
    height: 478px;
    overflow: hidden;
    position: relative
}

.wf_scrollbar .up,
.wf_scrollbar .down {
    display: none
}

.wf_scrollbar .track {
    background-color: #f3f3f6;
    width: 15px;
    height: 100%;
    position: relative
}

.wf_scrollbar .thumb {
    background: #cececf;
    cursor: pointer;
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px
}

.noSelect {
    user-select: none;
    -o-user-select: none;
    -moz-user-select: none;
    -khtml-user-select: none;
    -webkit-user-select: none;
    -ms-user-select: none
}

.wf_dealer_locator_message {
    font-size: 17px;
    font-family: "roboto";
    color: #868689
}

.wf_dealer_locator_results .wf_line,
.wf_dealer_locator_results .wf_linemultiple {
    border: 1px solid #e0e0e3;
    -moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    overflow: hidden
}

.wf_info_box .wf_dealer_locator_item {
    box-shadow: 1px 1px 6px #555;
    -moz-box-shadow: 1px 1px 6px #555;
    -webkit-box-shadow: 1px 1px 6px #555
}

.wf_info_box .wf_dealer_locator_item h3,
.wf_info_box .wf_dealer_locator_item address,
.wf_info_box .wf_dealer_locator_item a {
    font-size: 9px
}

.wf_info_box .wf_dealer_locator_item span a {
    color: #595959
}

.wf_info_box .wf_dealer_locator_item .wf_info_box_arrow {
    background: transparent url("../../images/forms/skin/icons/icon_info_box_arrow.png") no-repeat 0 0
}

.wf_location_map .gmapCluster {
    text-align: center;
    margin: -34px 0 0 3px
}

.wf_location_map .gmapCluster:before {
    content: "x"
}

.wf_synthesis,
.wf_resume_content {
    font-size: 12px;
    font-family: arial;
    color: #868689;
    margin-bottom: 17px;
    margin-top: -5px;
    padding-top: 7px
}

.wf_synthesis h3,
.wf_resume_content h3 {
    color: #333235;
    font-weight: normal;
    font-size: 12px
}

.wf_synthesis strong,
.wf_resume_content strong {
    color: #4b4a4d
}

.wf_synthesis address,
.wf_resume_content address {
    font-style: normal
}

.wf_dealerlocator_form_light .wf_errorMessage {
    display: none
}

.wf_dealerlocator_form_light .wf_loupeButton {
    cursor: pointer
}

.wf_dealerlocator_form_light .wf_loupeButton input {
    background: none;
    border: 0
}

.wf_dealerlocator_form_light input:focus {
    -webkit-user-modify: read-write-plaintext-only
}

.wf_dealerlocator_form_light label {
    color: #30333d
}

.wf_dealerlocator_form_light .wf_error label {
    color: #DC002E
}

.wf_form_content_medium fieldset {
    border: 0;
    margin: 0;
    padding: 0;
    vertical-align: baseline
}

.wf_question_medium {
    min-height: 1px;
    display: block;
    zoom: 1
}

.wf_container_explanation {
    margin-bottom: 10px
}

.wf_form_content_medium {
    font-weight: normal;
    text-rendering: optimizeSpeed;
    -webkit-appearance: none;
    border: 0;
    margin: 0;
    padding: 0;
    vertical-align: baseline
}

.wf_form_content_medium .wf_line_medium {
    margin-bottom: 0
}

.wf_question_medium .wf_line_medium .wf_linecontent.wf_content {
    display: table;
    width: 100%
}

.wf_form_content_medium .wf_linecontent {
    float: none !important;
    max-width: none;
    margin-bottom: 0 !important
}

.wf_dealerlocator_form .wf_geo_wrapper_medium {
    display: block;
    width: auto;
    white-space: normal
}

.wf_geo_btn_medium {
    background-color: #0f6873;
    color: #fff;
    line-height: 1em;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px
}

.wf_dealerlocator_form .wf_geo_wrapper_medium .wf_geo_btn_medium {
    display: block;
    width: 100%;
    margin: 0 auto;
    height: auto;
    padding: 15px 10px 15px 25px
}

.wf_geo_btn_medium {
    position: relative;
    border: 0;
    cursor: pointer
}

button.wf_geo_btn_medium {
    font-size: 17px
}

.wf_geo_btn_medium:before {
    content: "\007A";
    font-family: webformsIcons;
    font-size: 37px;
    position: absolute;
    left: 10px;
    top: 8px;
    display: table-cell;
    vertical-align: middle;
    color: #fff
}

.wf_dealerlocator_form .wf_searchbox_container_medium {
    display: block;
    position: relative;
    max-width: none;
    width: 100%;
    margin: 0 auto
}

.wf_dealerlocator_form .wf_searchbox_container_medium {
    vertical-align: middle
}

.wf_form_content_medium .wf_field_input input {
    border: 1px solid #b7b3b3;
    color: #595959
}

.wf_input_medium .wf_field_input input {
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px
}

.wf_form_content_medium .wf_field_input input {
    width: 100%;
    box-sizing: border-box;
    position: relative
}

.wf_input_medium .wf_field_input input {
    height: 38px;
    padding-left: 15px;
    padding-right: 15px;
    -webkit-appearance: none;
    font-weight: 100
}

.wf_dealerlocator_form .wf_searchbox_container_medium .wf_validsearch_medium {
    display: block;
    position: absolute;
    top: 1px;
    bottom: 1px;
    right: 2px;
    width: auto
}

.wf_dealerlocator_form .wf_searchbox_container_medium .wf_validsearch_medium button {
    font-family: webformsIcons;
    font-size: 25px;
    color: #86858a;
    line-height: 1em;
    background-color: #fff;
    margin: 0;
    padding: 0 5px;
    height: 36px
}

.wf_validsearch_medium button {
    border: 0;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
    cursor: pointer
}

.wf_dealerlocator_form .wf_geo_wrapper_medium .wf_geo_and_medium {
    color: #4b4b4e;
    font-weight: 100;
    font-size: 17px
}

.wf_dealerlocator_form .wf_geo_wrapper_medium .wf_geo_and_medium {
    display: block;
    width: 100%;
    text-align: center;
    margin: 5px 0 7px 0
}

.wf_dealerlocator_form .wf_geo_wrapper_medium {
    display: block;
    width: auto;
    white-space: normal
}

.wf_dealer_info_medium .map {
    height: 195px;
    border: 1px solid #c7c7c9
}

.choose_medium {
    font-weight: normal;
    background-color: '.$sPrimaryColor.';
    min-height: 30px;
    height: auto;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    padding: 10px 30px 10px 30px;
    border: none;
    margin-top: 30px
}

.wf_button.choose_list_medium:hover,
.wf_button.choose_list_medium.selected {
    background-color: '.$sSecondColor.'.
}

.wf_button.choose_list_medium {
    font-weight: lighter;
    font-size: 14px;
    background-color: '.$sPrimaryColor.';
    height: auto;
    min-width: 20%;
    border-radius: 5px;
    padding: 10px 30px 10px 30px;
    border: none;
    position: absolute;
    right: 21px;
    top: 26%
}

.wf_dealer_info_medium .back {
    background-color: #86858a;
    color: #fff;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    border: none;
    display: block;
    height: 30px;
    position: relative;
    padding: 0 15px 0 35px;
    font-size: 13px !important
}

.wf_dealer_info_medium .back:before {
    content: "\0074";
    -moz-transform: rotate(90deg);
    -webkit-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
    font-family: "webformsIcons";
    font-size: 18px;
    font-weight: bold;
    position: absolute;
    left: 11px;
    top: 45%;
    margin-top: -9px
}

.wf_dealer_info_medium .address .distance {
    font-weight: bold;
    color: #DA0000;
    font-style: italic
}

wf_dealer_locator_medium {
    float: left;
    width: 100%;
    padding-right: 40px;
    margin-right: -40px;
    min-height: 260px
}

.wf_form_content .wf_linecontent_medium {
    max-width: none
}

.wf_dealer_locator_medium .wf_dealer_locator_inner .wf_dealer_locator_item_medium {
    border-bottom: 1px solid #c7c7c9;
    padding: 17px 30px 15px 10px;
    margin: 0;
    font-size: 12px;
    line-height: 1.1em;
    overflow: hidden
}

.wf_dealer_locator_item_medium {
    border-bottom: 1px solid #c7c7c9;
    padding: 17px 30px 15px 10px;
    margin: 0
}

.wf_dealer_locator_medium {
    width: auto;
    float: none;
    margin: 0;
    padding: 0
}

.wf_dealer_locator_item_medium h3 {
    font-weight: bold;
    text-transform: uppercase;
    color: #333235;
    font-size: 1em;
    text-align: left;
    margin: 0;
    display: block
}

.wf_dealer_locator_item_medium .distance {
    font-weight: bold;
    color: #DA0000;
    font-style: italic
}

.wf_dealer_locator_item_medium adresse {
    color: #868689;
    font-style: normal;
    display: block
}

.wf_dealer_locator_item_medium:after {
    font-family: "webformsIcons";
    position: absolute;
    right: 20px;
    top: 50%;
    margin-top: -8px;
    z-index: 1;
    font-size: 16px;
    font-weight: bold;
    -moz-transform: rotate(-90deg);
    -webkit-transform: rotate(-90deg);
    -ms-transform: rotate(-90deg);
    transform: rotate(-90deg);
	color:'.$sPrimaryColor.';
}

.wf_dealer_locator_item_medium:hover {
    background-color: #f3f3f6
}

div.wf_line.wf_line_medium,
div.wf_line_medium.wf_linemultiple {
    border: none
}

div.wf_dealer_locator_results_medium.border-n {
    border: none
}

div.wf_dealer_locator_results_medium.border-y {
    border: 1px solid #b7b3b3;
    border-radius: 10px
}

div.wf_line.wf_line_medium.wf_dealer_locator_results_medium,
div.wf_line_medium.wf_dealer_locator_results_medium.wf_linemultiple {
    max-height: 585px;
    overflow: auto
}

.wf_dealerlocator_form.wf_dealerlocator_form_medium {
    width: 100%
}

div.addressWrapper h3 {
    margin-bottom: 0px;
    margin-top: 30px
}

div.wf_dealer_info_medium button.choose_medium {
    display: block;
    margin: auto;
    margin-top: 2%
}

.wf_button.choose_medium:hover,
.wf_button.choose_medium.selected {
    background-color: '.$sSecondColor.'.
}

.wf_dealerlocator .wf_dealerlocator_form_light .wf_loupeButton {
    text-indent: -9999px;
    width: 31px;
    background-position: center center;
    background-color: #000;
    margin-left: -31px;
    z-index: 10
}

.wf_dealerlocator_form_light .wf_field_input input {
    -webkit-appearance: none
}

.isIE .wf_dealerlocator_form_light .wf_loupeButton {
    height: 19px !important
}

.isIE .wf_dealerlocator_form_light input.picker_dropdown {
    *padding-right: 0 !important
}

.wf_dealerlocator .wf_dealerlocator_form_light input:focus {
    -webkit-user-modify: read-write-plaintext-only;
    background: none
}

.wf_dealerlocator .wf_dealerlocator_form_light {
    margin-bottom: 0
}

.wf_dealerlocator .wf_dealerlocator_form_light label {
    color: #30333d
}

.wf_dealerlocator .wf_dealerlocator_form_light .wf_error label {
    color: #DC002E
}

.wf_dropdown .itemText {
    color: #000
}

.wf_dealerlocator .resultBoxField .wf_label_field {
    float: none;
    display: block;
    width: auto;
    margin-right: 0
}

.wf_linemultiple .wf_content .add_line,
.wf_content .remove_line {
    *display: inline;
    float: right;
    background: #116973;
    margin-left: 5px;
    margin-right: 0
}

.wf_linemultiple .wf_float_field {
    float: left;
    width: 52%;
    margin-right: 5px;
    height: 21px
}

.wf_linemultiple .wf_field_input.text input {
    margin-top: 0px;
    *margin-top: -1px;
    height: 21px;
    *height: 13px
}

.wf_field_input.text input.picker_dropdown {
    padding-right: 18px;
    cursor: pointer;
    background: url("../../images/forms/skin/icons/icon_dropdown.png") no-repeat right 15px transparent
}

.wf_field_input.text span.picker_dropdown {
    display: none
}

.wf_title_01 {
    margin: 10px 0
}

ul.wf_cars_type {
    font-family: "roboto";
    font-size: 17px;
    text-align: center
}

ul.wf_cars_type li a {
    text-decoration: none;
    color: #fff;
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 0px;
    -moz-border-bottom-right-radius: 0px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 0px;
    -webkit-border-bottom-right-radius: 0px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 3px;
    background: #848484 url("../../images/forms/skin/backgrounds/bg_capicker_tab_dark.png") 0 bottom repeat-x
}

ul.wf_cars_type li a:hover {
    background: #4B4A4D
}

ul.wf_cars_type li.wf_active a {
    border: 1px solid #e0e0e3;
    border-bottom: 1px solid #fff;
    background: #fff;
    color: #333235
}

.wf_highlight_car label {
    background-color: #f3f3f6
}

.wf_car_model {
    /*font-family: "roboto";*/
    color: #4B4A4D;
    font-size: 17px;
    line-height: 1em;
    text-align: center
}

.wf_car_image {
    text-align: center
}

.wf_car_box {
    opacity: 0;
    -moz-opacity: 0;
    filter: alpha(opacity=0)
}

.wf_brochurepicker .wf_messagescontainer {
    line-height: 2em
}

.wf_carpicker {
    font-family: "roboto"
}

.wf_carpicker .wf_cars_errorMessage,
.wf_brochurepicker .wf_errorMessage {
    color: #dc002e;
    font-family: "roboto";
    font-size: 14px;
    font-style: italic
}

.wf_recap_cars {
    border-bottom: 1px solid #e8e8e8
}

.wf_recap_cars .wf_brochure_link {
    text-decoration: none
}

.wf_recap_cars .wf_brochure_link:hover,
.wf_recap_cars .wf_brochure_link:active {
    text-decoration: underline
}

.wf_recap_cars p {
    font-size: 1.091em;
    font-weight: bold
}

.wf_recap_cars .wf_recap_cars table th {
    text-align: left;
    font-weight: normal
}

.wf_recap_cars .wf_first_block_recap {
    border-right: 1px solid #e8e8e8
}

.wf_recap_cars .wf_trash {
    text-align: center
}

.wf_send_mail {
    background: url("../../images/forms/skin/icons/icon_envelope.png") no-repeat 0 1px
}

.wf_send_letter {
    background: url("../../images/forms/skin/icons/icon_envelope.png") no-repeat -7px -30px
}

.wf_brochure_flipbook_icon,
.wf_brochure_pdf_icon {
    background: url("../../images/forms/skin/icons/icon_flipbook.png") no-repeat left top
}

.wf_brochure_pdf_icon {
    background-image: url("../../images/forms/skin/icons/icon_pdf.png")
}

.wf_carPicker_darkCars {
    background-color: #0d0c0c
}

.wf_carPicker_darkCars .wf_cars label:hover,
.wf_carPicker_darkCars .wf_cars label:active,
.wf_carPicker_darkCars .wf_highlight_car label {
    background-color: #181313
}

.wf_carPicker_darkCars .wf_cars .wf_car_model {
    color: #CFC3B8
}

.wf_carPicker_darkCars .left h3 {
    color: #CFC3B8
}

.wf_brochurepicker_wrapper {
    font-family: "roboto";
    font-size: 17px;
    color: #4B4A4D
}

.wf_brochurePickerCar:after {
    background: -moz-linear-gradient(left, rgba(219, 219, 220, 0) 0%, #dbdbdc 50%, rgba(219, 219, 220, 0) 100%);
    background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(219, 219, 220, 0)), color-stop(50%, #dbdbdc), color-stop(100%, rgba(219, 219, 220, 0)));
    background: -webkit-linear-gradient(left, rgba(219, 219, 220, 0) 0%, #dbdbdc 50%, rgba(219, 219, 220, 0) 100%);
    background: -o-linear-gradient(left, rgba(219, 219, 220, 0) 0%, #dbdbdc 50%, rgba(219, 219, 220, 0) 100%);
    background: -ms-linear-gradient(left, rgba(219, 219, 220, 0) 0%, #dbdbdc 50%, rgba(219, 219, 220, 0) 100%);
    background: linear-gradient(to right, rgba(219, 219, 220, 0) 0%, #dbdbdc 50%, rgba(219, 219, 220, 0) 100%);
    filter: progid: DXImageTransform.Microsoft.gradient(startColorstr="#00dbdbdc", endColorstr="#00dbdbdc", GradientType=1)
}

.wf_brochurePickerCar .wf_modify_in {
    color: #333235;
    font-family: "roboto";
    font-size: 17px;
    position: relative;
    text-decoration: none
}

.wf_brochurePickerCar .wf_modify_in:hover,
.wf_brochurePickerCar .wf_modify_in:active {
    color: '.$sPrimaryColor.'
}

.wf_brochurePickerCar .wf_modify_in:hover:before,
.wf_brochurePickerCar .wf_modify_in:active:before {
    color: '.$sPrimaryColor.'
}

.wf_brochurePickerCar .wf_modify_in:before {
    color: #595959;
    content: "r";
    font-family: webformsIcons;
    font-size: 27px;
    left: 0;
    position: absolute;
    top: -4px
}

.wf_brochurePickerInfos {
    color: #4B4A4D
}

.wf_brochurePickerBrochures {
    text-align: center
}

.wf_brochurePickerBrochures tbody {
    color: #868689
}

.wf_brochurePickerBrochures tbody .wf_desc_table {
    text-align: left
}

.wf_brochurePickerBrochures .wf_brochure_pdf a {
    background-color: #868689;
    color: #ffffff;
    font-size: 15px;
    text-decoration: none;
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
    float: right
}

.wf_brochurePickerBrochures .wf_highlight {
    color: #4b4a4d;
    font-style: normal;
    font-weight: normal
}

.wf_brochurePickerBrochures .wf_box_check {
    padding-left: 22px !important
}

.wf_resume_details {
    margin-top: 10px
}

.wf_resume_details span {
    font-family: "roboto";
    font-size: 17px
}

.wf_synthesis_brochurePicker {
    font-family: "roboto";
    font-size: 17px;
    color: #4b4a4d
}

.wf_synthesis_brochurePicker .left {
    text-align: left;
    position: relative
}

.wf_synthesis_brochurePicker .left img {
    display: block
}

.wf_synthesis_brochurePicker .left h3 {
    font-size: 17px;
    font-family: "roboto";
    text-align: center;
    margin-bottom: 0;
    margin-top: 7px;
    color: #4B4A4D
}

.wf_synthesis_brochurePicker .left:after {
    content: "";
    display: inline-block;
    width: 20px;
    position: absolute;
    -moz-border-top-left-radius: 100px;
    -moz-border-top-right-radius: 100px;
    -moz-border-bottom-left-radius: 100px;
    -moz-border-bottom-right-radius: 100px;
    -webkit-border-top-left-radius: 100px;
    -webkit-border-top-right-radius: 100px;
    -webkit-border-bottom-left-radius: 100px;
    -webkit-border-bottom-right-radius: 100px;
    border-top-left-radius: 100px;
    border-top-right-radius: 100px;
    border-bottom-left-radius: 100px;
    border-bottom-right-radius: 100px;
    top: 0;
    bottom: 0;
    right: 0;
    box-shadow: 10px 0 10px #ccc;
    clip: rect(0px, 44px, 1000px, 29px)
}

.wf_brochurePickerCar h3 {
    font-size: 17px;
    font-weight: normal;
    text-align: center;
    color: #4B4A4D;
    margin-top: 7px
}

.mySelectcarpicker {
    margin-top: 33px;
    width: 35%;
    float: left
}

.wf_form_content img.selectcarpicker_image {
    border: 0;
    width: 100%
}

span.wf_button_input.selectcarpicker_button {
    width: 200px
}

div.wf_cars_selection.selectcarpicker_img {
    width: 35%;
    float: left
}

.wf_input .wf_field_input input {
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px
}

.wf_input .wf_field_input input:focus {
    outline: 0
}

.wf_form_content .wf_field_textarea .textareaResizer_handler {
    display: block;
    width: 20px;
    height: 20px;
    margin: -22px 0 0 auto;
    position: relative;
    z-index: 2;
    cursor: nwse-resize;
    background: url("../../images/forms/skin/backgrounds/bg_textarea.png") no-repeat bottom right;
    border: 1px solid transparent;
    -moz-border-top-left-radius: 0;
    -moz-border-top-right-radius: 0;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 0;
    -webkit-border-top-left-radius: 0;
    -webkit-border-top-right-radius: 0;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 0;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 0
}

.wf_form_content textarea {
    margin: 0;
    display: block;
    -webkit-appearance: none;
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
    resize: none
}

.wf_form_content textarea:focus {
    outline: 0
}

.wf_content .wf_error .wf_textarea_content textarea {
    color: #dc002e;
    border: 1px solid #dc002e
}

.wf_field_input input[type="password"] {
    font-size: 2.5em
}

.wf_error .wf_field_input input[type="password"] {
    color: #dc002e;
    border: 1px solid #dc002e
}

.wf_button {
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    font-family: Arial, Helvetica, sans-serif;
    color: #fff;
    display: inline-block
}

.wf_button input,
.wf_button button,
.wf_button .wf_button_input {
    border: 0;
    background: none;
    color: #fff;
    cursor: pointer;
    font-family: "roboto";
    font-size: 17px;
    line-height: 1.2em;
    background: '.$sPrimaryColor.';
    position: relative;
    padding-right: 49px;
    font-weight: normal;
    -moz-border-top-left-radius: 2px;
    -moz-border-top-right-radius: 2px;
    -moz-border-bottom-left-radius: 2px;
    -moz-border-bottom-right-radius: 2px;
    -webkit-border-top-left-radius: 2px;
    -webkit-border-top-right-radius: 2px;
    -webkit-border-bottom-left-radius: 2px;
    -webkit-border-bottom-right-radius: 2px;
    border-top-left-radius: 2px;
    border-top-right-radius: 2px;
    border-bottom-left-radius: 2px;
    border-bottom-right-radius: 2px;
    margin: 0;
    -webkit-appearance: none
}

.wf_button:hover input,
.wf_button:hover button,
.wf_button:hover .wf_button_input {
    background-color: '.$sSecondColor.'.
}

.wf_button .wf_button_input {
    padding-right: 0;
    display: inline-block
}

.wf_button .wf_button_input:after,
.wf_button button:after {
    content: "\0074";
    display: block;
    font-family: "webformsIcons";
    font-size: 12px;
    position: absolute;
    right: 20px;
    text-transform: none;
    font-weight: normal;
    top: 9px;
    -ms-transform: rotate(-90deg);
    -webkit-transform: rotate(-90deg);
    transform: rotate(-90deg);
	/*color:'.$sPrimaryColor.';*/
}

.wf_button.wf_disabled input,
.wf_button.wf_disabled button,
.wf_button.wf_disabled .wf_button_input,
.wf_button_disabled input,
.wf_button_disabled button,
.wf_button_disabled .wf_button_input {
    background-color: #848484
}

.wf_button.wf_disabled input:hover,
.wf_button.wf_disabled button:hover,
.wf_button.wf_disabled .wf_button_input:hover,
.wf_button_disabled input:hover,
.wf_button_disabled button:hover,
.wf_button_disabled .wf_button_input:hover {
    background-color: #848484
}

.wf_button_grey {
    background: #747474
}

.wf_button_grey input:disabled {
    color: #fff
}

.datepicker {
    background: #fff;
    color: #595959;
    font-size: 10px;
    font-family: Arial, Helvetica, sans-serif
}

.datepicker .closeBtn {
    color: #5EB0D0;
    text-decoration: underline
}

.datepicker .calendarHeader {
    background: #dadada;
    color: #595959
}

.datepicker .calendarTable {
    background: #ebebeb
}

.datepicker .wf_timePicker {
    background: #747474
}

.datepicker .closeBtnContainer {
    text-align: right
}

.datepicker .dayTitle {
    font-size: 13px;
    text-align: center
}

.datepicker .calendarTable thead th {
    background: #ebebeb
}

.datepicker .day {
    text-align: center;
    cursor: pointer;
    font-size: 11px;
    background: #dadada
}

.datepicker .day a {
    text-decoration: none;
    color: #000
}

.datepicker .day.nextMonth,
.datepicker .day.previousMonth {
    background: #ebebeb
}

.datepicker .day.nextMonth a,
.datepicker .day.previousMonth a {
    color: #999
}

.datepicker .currentDay {
    font-weight: bold;
    background: #dd002c
}

.datepicker .currentDay a {
    background: #dd002c;
    color: #fff
}

.datepicker .today {
    font-weight: bold
}

.datepicker .dayHover {
    background: #dd002c
}

.datepicker .dayHover a {
    color: white
}

.datepicker .selectedDay {
    background: #dd264a
}

.datepicker .selectedDay a {
    background: #dd264a;
    color: white
}

.datepicker .forbiddenDay {
    cursor: default;
    background: #dedede
}

.datepicker .forbiddenDay a {
    color: #999999;
    cursor: default
}

.datepicker select {
    border: 1px solid #B7B3B3;
    color: #595959;
    font-size: 11px
}

.datepicker .wf_calendarButton input {
    background: '.$sPrimaryColor.';
    color: #fff;
    cursor: pointer;
    border: 0
}

.datepicker .previousMonthBtn,
.datepicker .nextMonthBtn {
    background: transparent url("../../images/forms/skin/icons/icon_arrow_calendar.jpg") no-repeat 0 0
}

.datepicker .nextMonthBtn {
    background-position: right top
}

.wf_connexion_content a {
    color: #595959
}

.wf_connexion_content form {
    text-align: right
}

.wf_popin .wf_login,
.wf_popin .wf_password {
    border: 1px solid #b7b3b3;
    font-size: 1em;
    color: #595959
}

.wf_connexion_footer {
    text-align: right
}

.wf_popin .wf_close {
    cursor: pointer;
    font-size: 1em;
    color: #595959
}

.wf_shadow_box_inner {
    background: transparent url("../../images/forms/skin/backgrounds/bg_shadow_left.png") repeat-y 3px 0
}

.wf_shadow_box_inside {
    background: #fff url("../../images/forms/skin/backgrounds/bg_shadow_right.png") repeat-y right 0
}

.wf_shadow_tl,
.wf_shadow_tr {
    background: transparent url("../../images/forms/skin/backgrounds/bg_shadow_top.png") no-repeat 0 0
}

.wf_shadow_bl,
.wf_shadow_br {
    background: transparent url("../../images/forms/skin/backgrounds/bg_shadow_bottom.png") no-repeat 0 0
}

.wf_shadow_tr,
.wf_shadow_br {
    background-position: top right
}

.wf_btn_connexion {
    background: url("../../images/forms/skin/buttons/btn_01.png") no-repeat scroll left top transparent
}

.wf_btn_connexion input {
    border: 0;
    background: none;
    color: #595959;
    text-transform: uppercase;
    cursor: pointer
}

.wf_btn_connexion span {
    background: url("../../images/forms/skin/buttons/btn_01.png") no-repeat scroll right top transparent;
    text-align: center
}

.wf_popin_component {
    font-family: Arial, Helvetica, sans-serif;
    background: #fff
}

.wf_popin_component .wf_pop_content {
    background: #eee
}

.wf_popin_component .wf_close {
    background: url("../../images/forms/skin/icons/icon_close_b.png") no-repeat scroll right 5px transparent;
    font-size: 18px;
    text-transform: uppercase
}

.wf_popin_component .wf_popin_component_title {
    text-transform: uppercase;
    font-size: 18px
}

.wf_popin_component .wf_popin_component_title .alert {
    background: url("../../images/forms/skin/icons/alert.png") no-repeat scroll left top transparent
}

.wf_popin_component_text {
    font-size: 15px
}

.wf_box_check {
    cursor: pointer;
    color: #4B4A4D
}

.wf_box_check:before {
    border: 1px solid '.$sPrimaryColor.';
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
    content: "";
    position: absolute;
    left: 0;
    top: -1px;
    width: 20px;
    height: 20px
}

.wf_box_check.wf_checked {
    color: #4B4A4D
}

.wf_box_check.wf_checked:after {
    content: "\0065";
    font-family: "webformsIcons";
    position: absolute;
    top: 0px;
    left: 3px;
    width: 20px;
    z-index: 1;
    font-size: 1.3em;
    height: 20px;
    color: '.$sPrimaryColor.';
    text-align: left;
    line-height: 1.1em
}

.wf_error .wf_box_check:before {
    border: 1px solid #dc002e
}

.wf_radio_check {
    cursor: pointer;
    color: #4B4A4D
}

.wf_radio_check:before {
    border: 1px solid #ccc;
    -moz-border-top-left-radius: 100px;
    -moz-border-top-right-radius: 100px;
    -moz-border-bottom-left-radius: 100px;
    -moz-border-bottom-right-radius: 100px;
    -webkit-border-top-left-radius: 100px;
    -webkit-border-top-right-radius: 100px;
    -webkit-border-bottom-left-radius: 100px;
    -webkit-border-bottom-right-radius: 100px;
    border-top-left-radius: 100px;
    border-top-right-radius: 100px;
    border-bottom-left-radius: 100px;
    border-bottom-right-radius: 100px;
    content: "";
    position: absolute;
    left: 0;
    top: 1px;
    width: 20px;
    height: 20px
}

.ie8 .wf_radio_check {
    background: url("../../images/forms/skin/icons/radio_icon.png") no-repeat left 1px;
    height: 24px
}

.ie8 .wf_radio_check:before {
    content: none
}

.wf_radio_check.wf_checked {
    color: #333235
}

.wf_radio_check.wf_checked:after {
    content: "";
    position: absolute;
    -moz-border-top-left-radius: 102px;
    -moz-border-top-right-radius: 102px;
    -moz-border-bottom-left-radius: 102px;
    -moz-border-bottom-right-radius: 102px;
    -webkit-border-top-left-radius: 102px;
    -webkit-border-top-right-radius: 102px;
    -webkit-border-bottom-left-radius: 102px;
    -webkit-border-bottom-right-radius: 102px;
    border-top-left-radius: 102px;
    border-top-right-radius: 102px;
    border-bottom-left-radius: 102px;
    border-bottom-right-radius: 102px;
    top: 8px;
    left: 7px;
    width: 8px;
    height: 8px;
    z-index: 1;
    background-color: '.$sPrimaryColor.'
}

.ie8 .wf_radio_check.wf_checked {
    background-position: left -876px
}

.ie8 .wf_radio_check.wf_checked:after {
    content: none
}

.wf_error .wf_radio_check:before {
    border: 1px solid #dc002e
}

.wf_html {
    font-family: "roboto";
    font-size: 17px;
    color: #4b4a4d;
    margin-top: 5px
}

.sb-custom select {
    display: none
}

.sb-custom {
    cursor: pointer;
    display: block;
    position: relative;
    width: 100%
}

.sb-custom .sb-dropdown {
    background: white;
    display: none;
    font-size: 17px;
    left: 0;
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    top: 36px;
    width: 100%;
    z-index: 3;
    border: 1px solid '.$sPrimaryColor.';
    border-top: none;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box
}

.ie9 .sb-custom:after {
    margin-top: -12px
}

.wf_error .sb-custom .sb-dropdown {
    border: 1px solid #de113c;
    border-top: none
}

.open_dropdown.sb-custom:after {
    -moz-transform: rotate(180deg);
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg)
}

.sparkbox-custom {
    display: none
}

.no-js .sparkbox-custom {
    display: block
}

.sb-select {
    color: #4b4a4d;
    position: relative;
    text-decoration: none;
    z-index: 1;
    cursor: pointer;
    display: block
}

.sb-select input {
    cursor: pointer;
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
    padding: 7px 30px 7px 20px
}

.sb-select:hover input {
    background-color: #F0F0F0
}

.sb-select:after {
    content: "\0074";
    font-family: "webformsIcons";
    position: absolute;
    right: 35px;
    top: 50%;
    margin-top: -7px;
    z-index: 1
}

.sb-dropdown a {
    color: #4b4a4d;
    display: block;
    padding: 10px 20px;
    text-decoration: none
}

.sb-dropdown a:hover,
.sb-dropdown .selected {
    background-color: #F0F0F0
}

.wf_form_content input[type="file"] {
    border: 1px solid #B7B3B3
}

.wf_input .wf_field_file .wf_field_filefakelabel {
    cursor: pointer
}

.wf_fileUploaded .wf_field_file .wf_progressblock {
    background: #f3f3f3;
    border-radius: 4px
}

.wf_toggle_title {
    font-family: "roboto";
    font-size: 17px;
    color: #4b4a4d;
    margin-top: 5px;
    cursor: pointer
}

.wf_toggle_content {
    font-family: "roboto";
    font-size: 17px;
    color: #4b4a4d;
    margin-top: 5px
}

.wf_form_content {
    font-size: 11px;
    font-family: "roboto", Arial, Helvetica, sans-serif;
    text-rendering: optimizeSpeed;
    -webkit-appearance: none
}

.wf_form_content a {
    color: #595959
}

.wf_form_content a img {
    border: none
}

.wf_form_content ul li {
    list-style: none
}

.wf_form_content img {
    border: 0
}

.wf_form_content .wf_linecontent .wf_page fieldset {
    border-bottom: 1px solid #E4E2E2
}

.wf_form_content fieldset,
.wf_form_content,
.wf_form_content label,
.wf_form_content p,
.wf_form_content ul {
    font-size: 100%
}

input:focus,
input:active,
button:focus,
button:active {
    outline: none
}

.wf_dropdown .dpcontent td {
    cursor: pointer
}

.wf_dropdown .dpcontent td:hover,
.wf_dropdown .dpcontent td:active {
    background: #ccc
}

.wf_line .wf_error .wf_field_input input,
.wf_linemultiple .wf_error .wf_field_input input {
    border-color: #de113c
}

.wf_line .wf_error .wf_label_field,
.wf_linemultiple .wf_error .wf_label_field {
    color: #de113c
}

.wf_form_content .wf_linecontent>.wf_field input {
    -webkit-appearance: none
}

.wf_form_content .wf_linecontent>.wf_field .wf_disabled {
    background-color: #ebebe4
}

.wf_title_01 {
    font-size: 18px;
    color: #000;
    text-transform: uppercase
}

.wf_title_02 {
    color: '.$sPrimaryColor.';
    font-size: 15px
}

.wf_link_01 {
    color: #595959
}

.wf_link_02 {
    background: transparent url("../../images/forms/skin/icons/icon_arrow_red.png") no-repeat 0 center;
    color: #dc002e;
    text-decoration: underline
}

.wf_link_02 a {
    color: #dc002e
}

.wf_link_03,
.wf_link_04 {
    background: transparent url("../../images/forms/skin/icons/icon_layer_dealer.png") no-repeat 0 -26px;
    color: #444
}

.wf_link_04 {
    background-position: 0 1px
}

.wf_legend {
    font-weight: bold;
    font-size: 1em;
    font-family: "roboto"
}

.wf_input_group .wf_label_field {
    font-weight: normal
}

.wf_button_selection_dealer {
    font-weight: normal
}

.wf_grey {
    text-align: center;
    background: #868689
}

.wf_grey span {
    background: none
}

span.wf_button_disabled {
    cursor: default
}

.wf_button_image {
    background: none
}

.wf_button_image input {
    position: absolute;
    top: -5000px;
    left: -5000px
}

.wf_button_image label {
    background: none;
    border: 0;
    color: #fff;
    cursor: pointer
}

.wf_button_image .wf_button_image_block,
.wf_button_image .wf_button_image_block img {
    background: none;
    border: 0
}

.wf_button_text_block {
    border: 0;
    color: #fff;
    text-align: center;
    background: transparent url("../../images/forms/skin/backgrounds/bg_black_transparent.png") repeat left top
}

.wf_button_text_block span {
    font-size: 25px;
    font-weight: bold;
    text-transform: uppercase;
    background: transparent url("../../images/forms/skin/icons/icon_arrow_button.png") no-repeat right center
}

.wf_field_input:after,
.wf_input_group:after,
.wf_input_group .wf_label_field:after,
.wf_form_content .wf_message_box:after,
.wf_popin_login .wf_message_box:after {
    line-height: 0;
    font-size: xx-large
}

.wf_logo_icon {
    text-indent: -5000px;
    background: url("../../images/forms/skin/img/mycitroen_logo.png") no-repeat left top
}

.wf_page_header {
    border: 0
}

.wf_page_header .wf_label_field {
    font-weight: normal
}

.wf_page_header .wf_question {
    background: #f6f6f6
}

.wf_page {
    border-top: 1px solid #e4e2e2
}

.wf_page:first-of-type {
    border: none
}

.wf_page .wf_connexion {
    background: #fff url("../../images/forms/skin/backgrounds/bg_head_step.jpg") repeat-x left bottom
}

.wf_title_container .wf_page_title {
    font-family: "roboto";
    font-size: 20px;
    text-transform: uppercase;
    color: '.$sPrimaryColor.';
	font-weight:bold;
}

.wf_numbering {
    /*-moz-border-top-left-radius: 100px;
    -moz-border-top-right-radius: 100px;
    -moz-border-bottom-left-radius: 100px;
    -moz-border-bottom-right-radius: 100px;
    -webkit-border-top-left-radius: 100px;
    -webkit-border-top-right-radius: 100px;
    -webkit-border-bottom-left-radius: 100px;
    -webkit-border-bottom-right-radius: 100px;
    nos modifs border-top-left-radius: 100px;
    border-top-right-radius: 100px;
    border-bottom-left-radius: 100px;
    border-bottom-right-radius: 100px;*/
    text-align: center;
    background-color: #848484;
    color: #fff;
    font-family: "roboto";
    font-size: 20px;

}

.wf_page_valid {
    border-bottom: 0
}

.wf_page_valid .wf_numbering {
    background-color: '.$sPrimaryColor.';
    color: #ffffff
}

.wf_page_valid .wf_page_title {
    /*color: '.$sPrimaryColor.'*/
}

.wf_page_open {
    border-bottom: 0
}

.wf_page_open .wf_numbering {
    background-color: '.$sPrimaryColor.';
    color: #fff
}

.wf_page_open .wf_page_title {
    color: '.$sPrimaryColor.';
	/*nos modifs*/
	font-weight:bold;
	/*nos modifs*/
}

.ie8 .wf_numbering {
    background-color: transparent;
    background-image: url("../../images/forms/skin/icons/icon_step.png");
    background-position: 0 -46px
}

.ie8 .wf_page_valid .wf_numbering {
    background-position: 0 -46px
}

.wf_dropdown {
    background: #fff;
    border: 1px solid #B7B3B3
}

.wf_dropdown ul strong {
    font-weight: bold
}

.wf_dropdown ul a {
    color: #595959
}

.wf_dropdown ul a:hover,
.wf_dropdown ul a:focus {
    background: #E8E8E8
}

.wf_dropdown ul .selected a {
    background: #E8E8E8
}

.wf_icon_box {
    text-align: center;
    top: 0px
}

.wf_icon_box .wf_help_icon {
    background-color: #333235;
    line-height: 1.2em;
    -moz-border-top-left-radius: 100px;
    -moz-border-top-right-radius: 100px;
    -moz-border-bottom-left-radius: 100px;
    -moz-border-bottom-right-radius: 100px;
    -webkit-border-top-left-radius: 100px;
    -webkit-border-top-right-radius: 100px;
    -webkit-border-bottom-left-radius: 100px;
    -webkit-border-bottom-right-radius: 100px;
    border-top-left-radius: 100px;
    border-top-right-radius: 100px;
    border-bottom-left-radius: 100px;
    border-bottom-right-radius: 100px;
    color: #fff;
    font-family: "roboto";
    font-weight: 700;
    font-size: 17px
}

.wf_icon_box .wf_help_icon:hover,
.wf_icon_box .wf_help_icon:active {
    cursor: help
}

.wf_label_top+.text+.wf_icon_box {
    top: inherit;
    bottom: 11px
}

.ie8 .wf_icon_box .wf_help_icon {
    background-color: transparent;
    background-image: url("../../images/forms/skin/icons/icon_help.png");
    background-position: 0 1px
}

.wf_pagination {
    text-align: center
}

.wf_pagination ul li {
    border-left: 1px solid #ccc
}

.wf_pagination ul li a {
    text-decoration: none
}

.wf_pagination ul li a:hover,
.wf_pagination ul li a:active {
    color: #116973
}

.wf_pagination .wf_current_page a {
    font-weight: bold;
    font-size: 13px
}

.wf_pagination .wf_previous {
    border: 0
}

.wf_pagination .wf_next {
    border: 0
}

.wf_pagination .wf_next a {
    background-position: right bottom
}

.wf_pagination .wf_next a:hover,
.wf_pagination .wf_next a:active {
    background-position: right 1px
}

.wf_pagination .wf_previous a,
.wf_pagination .wf_next a {
    background: url("../../images/forms/skin/icons/icon_pagination.png") no-repeat left bottom
}

.wf_pagination .wf_item_noborder {
    border: 0
}

.wf_tooltipQAS {
    border: 1px solid #b7b3b3;
    background: #fff
}

.wf_tooltipQAS .arrow {
    background: url("../../images/forms/skin/deco/arrow_popin.png") no-repeat 0 0
}

.wf_tooltipQAS .wf_QAS_inner .errorMessage {
    color: #DC002E
}

.wf_tooltipQAS .wf_QAS_inner .infoMessage {
    color: #595959
}

.wf_tooltip {
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    border: 1px solid #d0d0d3;
    background-color: #ffffff;
    -moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    -moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    -webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3)
}

.wf_tooltip:before {
    background: url("../../images/forms/skin/backgrounds/icon_arrow_tooltip.png") no-repeat left top
}

.wf_tooltip_left:before {
    background-position: right top
}

.datepicker {
    border: 1px solid #B7B3B4
}

.wf_form_content .wf_label_field {
    font-family: "roboto";
    font-size: 17px;
    color: #4b4a4d
}

.wf_form_content .wf_field_input .wf_ajax_loading,
.wf_form_content .wf_input_group .wf_ajax_loading,
.wf_form_content .wf_page_footer .wf_checkbox .wf_input_group .wf_label_field .wf_ajax_loading,
.wf_form_content .wf_message_box .wf_ajax_loading,
.wf_form_content .wf_popin_login .wf_message_box .wf_ajax_loading {
    background: url("../../images/forms/skin/icons/ajax_loader.gif") no-repeat center center;
    background-size: 28px
}

.wf_form_content .wf_comp_loading {
    background: url("../../images/forms/skin/icons/ajax_loader.gif") no-repeat center center;
    background-size: 28px
}

.wf_form_content .wf_searchbox_container .wf_ajax_loading {
    background: url("../../images/forms/skin/icons/ajax_loader.gif") no-repeat right center;
    background-size: 28px
}

.wf_form_content .wf_field_input input,
.wf_form_content .wf_field_input select,
.wf_form_content .wf_field_input textarea {
    border: 1px solid '.$sPrimaryColor.';
    color: #595959;
    font-size: 17px;
    font-family: "roboto"
}

.wf_form_content .wf_field_input option {
    color: #595959
}

.wf_form_content .title {
    color: #000;
    font-weight: bold
}

.wf_popin .wf_QAS_inner input {
    border: 1px solid #b7b3b3;
    color: #595959;
    font-size: 17px;
    font-family: "roboto"
}

.wf_captcha_box {
    border: 1px solid #B7B3B3;
    text-align: center
}

.wf_field_select {
    *border: 1px solid #b7b3b3
}

.wf_form_content .wf_page_footer .wf_checkbox .wf_input_group .wf_label_field,
.wf_form_content .wf_page_footer .wf_html {
    color: #999
}

.wf_form_content .wf_page_open .wf_checkbox .wf_input_group .wf_label_field {
    font-family: "roboto", Arial, sans-serif;
    font-size: 17px
}

.wf_message {
    font-size: 16px
}

.wf_message_error {
    position: relative
}

.wf_modify {
    color: #333235;
    font-family: "roboto";
    font-size: 17px;
    text-decoration: none;
    position: relative
}

.wf_modify:hover,
.wf_modify:active {
    color: '.$sPrimaryColor.';
    background-position: 0px -28px
}

.wf_modify:hover:before,
.wf_modify:active:before {
    color: '.$sPrimaryColor.'
}

.wf_modify:before {
    content: "\0072";
    font-family: webformsIcons;
    font-size: 27px;
    position: absolute;
    left: 0px;
    top: -4px;
    color: #2e3131
}

.wf_page_valid .wf_title_container .wf_link_01 {
    color: #595959
}

.wf_page_errorMessage {
    font-size: 15px;
    line-height: 1em;
    color: #dc002e;
    font-family: "roboto";
    font-style: italic
}

.wf_dropdown .itemText {
    color: #000
}

.wf_field_input.wf_slider {
    border-top: 4px solid #cacaca
}

.wf_slider_left,
.wf_slider_right {
    background: url("../../images/forms/skin/backgrounds/sliderShadow.png") no-repeat
}

.wf_slider_left {
    left: 0;
    background-position: left top
}

.wf_slider_right {
    background-position: right top
}

.wf_slider_picker {
    background: '.$sPrimaryColor.';
    border: 1px solid '.$sPrimaryColor.'
}

.wf_slider_disabled .wf_slider_picker {
    background: #B7B3B3
}

.wf_slider_last_picker {
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-top: 6px solid #909291
}

.wf_slider_first_picker {
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-bottom: 6px solid #909291
}

.wf_slider_last_picker:hover,
.wf_slider_first_picker:hover,
.wf_slider_last_picker:hover,
.wf_slider_first_picker:active {
    cursor: pointer
}

.wf_slider_value {
    border: 1px solid #B7B3B3;
    text-align: center
}

.wf_field_rte iframe {
    height: 121px
}

.wf_rte_zone {
    border: 1px #999 solid;
    font: 10px Tahoma, Verdana, Arial, Helvetica, sans-serif
}

.wf_rte_resizer {
    border-top: 1px solid #999;
    background-color: #fdfdfd
}

.wf_rte_toolbar {
    border-bottom: 1px dashed #999;
    background-color: #fdfdfd;
    font: 10px Tahoma, Verdana, Arial, Helvetica, sans-serif
}

.wf_rte_toolbar select {
    font: 10px Tahoma, Verdana, Arial, Helvetica, sans-serif
}

.wf_rte_toolbar .wf_colorPicker {
    cursor: pointer
}

.wf_rte_toolbar .black {
    background: #000000
}

.wf_rte_toolbar .blue {
    background: #0000ff
}

.wf_rte_toolbar .red {
    background: #ff0000
}

.wf_rte_panel {
    border: 1px solid #999;
    background: #f0f0f0;
    font: 10px Tahoma, Verdana, Arial, Helvetica, sans-serif
}

.wf_rte_panel .wf_rte_panel-title {
    font-weight: bold;
    line-height: 16px;
    background: #e0e0e0;
    border-bottom: 1px solid #ccc;
    cursor: move
}

.wf_rte_panel .wf_rte_panel-title .close {
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    color: #f00
}

.wf_rte_panel label {
    font-weight: bold;
    font-size: 10px;
    text-align: right;
    line-height: 20px;
    font-size: 100%
}

.wf_rte_panel input,
.wf_rte_panel select {
    font-size: 10px;
    border: 1px solid #ccc;
    line-height: 20px
}

.wf_rte_panel .symbols a {
    font-size: 14px;
    text-align: center;
    color: #000;
    text-decoration: none
}

.wf_rte_panel .symbols a:hover,
.wf_rte_panel .symbols a:active {
    background: #ccc
}

.wf_rte_panel .colorpicker2 .palette .item {
    cursor: crosshair;
    border: 0
}

.wf_rte_toolbar .clear {
    border: 0
}

.wf_rte_toolbar .clear ul .separator {
    border-left: 1px solid #ccc
}

.wf_rte_toolbar .clear li {
    list-style-type: none
}

.wf_rte_toolbar .clear li a {
    border: 1px solid #fdfdfd;
    background: url("../../images/forms/skin/icons/rte_icons.gif") no-repeat 0 0;
    cursor: pointer;
    opacity: 0.5;
    -moz-opacity: 0.5;
    filter: alpha(opacity=50)
}

.wf_rte_toolbar .clear li a:hover,
.wf_rte_toolbar .clear li a.active {
    opacity: 1.0;
    -moz-opacity: 1.0;
    filter: alpha(opacity=100)
}

.wf_rte_toolbar .clear li .active {
    background-color: #f9f9f9;
    border: 1px solid #ccc
}

.wf_rte_toolbar .clear li .wf_bold {
    background-position: 0 -112px
}

.wf_rte_toolbar .clear li .wf_italic {
    background-position: 0 -128px
}

.wf_rte_toolbar .clear li .wf_underline {
    background-position: 0 -160px
}

.wf_globalError {
    border: 2px solid #da0000;
    color: #DA0000;
    font-size: 16px
}

.wf_adviceMessage {
    background: url("../../images/forms/skin/icons/icon_info.png") no-repeat 0 5px;
    font-weight: bold
}

.wf_linemultiple .buttons_addremove .add_line,
.wf_linemultiple .buttons_addremove .remove_line {
    background-image: url("../../images/forms/skin/buttons/buttons_more_less.gif");
    text-decoration: none
}

.wf_linemultiple .buttons_addremove .add_line {
    background-position: 0 22px
}

.wf_linemultiple .buttons_addremove .add_line:hover {
    background-position: 22px 22px
}

.wf_linemultiple .buttons_addremove .remove_line {
    background-position: 0 0
}

.wf_linemultiple .buttons_addremove .remove_line:hover {
    background-position: 22px 0
}

.wf_linemultiple .buttons_addremove .picker_dropdown {
    cursor: pointer;
    background: url("../../images/forms/skin/icons/icon_dropdown.png") no-repeat right -1px transparent
}

.wf_loader,
.wf_waitprogress {
    background: url("../../images/forms/skin/icons/ajax_loader.gif") no-repeat 0 -9999px;
    background-size: 28px
}

.wf_question_resume .wf_resume_dealer .wf_resume_details {
    font-size: 10px;
    font-style: normal;
    text-transform: none;
    background: transparent url("../../images/forms/skin/icons/icon_citroen_dealer.png") no-repeat left center
}

.wf_question_resume .wf_resume_dealer .wf_resume_details h3 {
    font-size: 11px
}

.wf_question_resume .wf_link_01 {
    color: #595959
}

.wf_loading_mask {
    background: #FFF;
    filter: alpha(opacity=60);
    opacity: 0.6
}

.wf_ajax_loading {
    background: url("../../images/forms/skin/icons/ajax_loader.gif") no-repeat center center;
    background-size: 28px
}

.wf_form_content .wf_ajax_loading * {
    visibility: hidden !important
}

#linkid {
    font-size: 17px
}

.wf_connector a {
    color: #fff;
    text-decoration: none;
    background: #DC002D;
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px
}

.wf_connector .wf_tooltip_mobile {
    font-family: "roboto";
    font-weight: 100
}

.wf_validate_field .wf_field_input input {
    background: url("../../images/forms/skin/icons/icon_validate.png") no-repeat right center transparent
}

.wf_error .wf_field_input input {
    background: url("../../images/forms/skin/icons/icon_wrong_field.png") no-repeat right center transparent
}

.wf_validate_field .wf_custom_select_wrapper {
    background: url("../../images/forms/skin/icons/icon_validate.png") no-repeat right center transparent
}

.wf_error .wf_custom_select_wrapper {
    background: url("../../images/forms/skin/icons/icon_wrong_field.png") no-repeat right center transparent
}

.wf_radio.wf_validate_field .wf_input_group {
    background: url("../../images/forms/skin/icons/icon_validate.png") no-repeat right center transparent
}

.wf_radio.wf_error .wf_input_group {
    background: url("../../images/forms/skin/icons/icon_wrong_field.png") no-repeat right center transparent
}

.wf_validate_field .wf_field_input .wf_searchbox_container input {
    background: none
}

.wf_error .wf_field_input .wf_searchbox_container input {
    background: none
}

.wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn {
    background-color: #ffffff;
    font-family: "roboto";
    color: #4b4b4e;
    font-size: 14px;
    line-height: 1em;
    /*-moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;*/
	border: 2px solid;
	border-color:'.$sPrimaryColor.';
}



.wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn:before {
    color: '.$sPrimaryColor.';
    font-size: 30px
}

.wf_dealerlocator_form .wf_geo_wrapper .wf_geo_and {
    color: #4b4b4e;
    font-family: "roboto";
    font-weight: 100;
    font-size: 14px
}

.wf_dealerlocator_form .wf_searchbox_container .searchBox {
    font-size: 14px;
    color: #4b4b4e;
    font-family: "roboto"
}

.wf_dealerlocator_form .wf_searchbox_container .wf_validsearch button {
    font-family: webformsIcons !important;
    font-size: 25px !important;
    color: #86858a;
    line-height: 1em;
    background-color: #fff;
    margin: 0;
    padding: 0 5px;
    height: 36px
}

.no-borderradius .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn:before,
.no-fontface .wf_dealerlocator_form .wf_geo_wrapper .wf_geo_btn:before {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -75px -67px;
    background-repeat: no-repeat;
    width: 21px;
    height: 21px;
    margin-top: -10px
}

.no-borderradius .wf_dealerlocator_form .wf_searchbox_container .wf_validsearch button,
.no-fontface .wf_dealerlocator_form .wf_searchbox_container .wf_validsearch button {
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -107px -62px;
    background-repeat: no-repeat;
    text-indent: -5000px;
    width: 45px
}

.wf_geo_error {
    font-family: "roboto";
    font-style: italic;
    color: #dc002e;
    font-size: 14px
}

.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner {
    font-family: "roboto"
}

.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item {
    font-size: 12px;
    line-height: 1.1em
}

.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:hover,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:hover,
.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:focus,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:focus,
.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:active,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:active {
    background-color: #f3f3f6
}

.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:after,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:after {
    content: "\0074";
    font-family: "webformsIcons";
    position: absolute;
    right: 20px;
    top: 50%;
    margin-top: -8px;
    z-index: 1;
    font-size: 16px;
    font-weight: bold;
    -moz-transform: rotate(-90deg);
    -webkit-transform: rotate(-90deg);
    -ms-transform: rotate(-90deg);
    transform: rotate(-90deg);
	color:'.$sPrimaryColor.';
}

.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item h3,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item h3 {
    font-weight: normal
}

.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item .distance,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item .distance {
    font-weight: normal
}

.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item address,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item address {
    color: #868689
}

.wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item a,
.wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item a {
    color: #868689;
    text-decoration: underline !important
}

.no-borderradius .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:after,
.no-borderradius .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:after,
.no-fontface .wf_dealer_locator_results .wf_line .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:after,
.no-fontface .wf_dealer_locator_results .wf_linemultiple .wf_dealer_locator .wf_dealer_locator_inner .wf_dealer_locator_item:after {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -21px -101px;
    background-repeat: no-repeat;
    width: 16px;
    height: 17px
}

.wf_dealers_more_button {
    font-size: 14px;
    font-family: "roboto";
    /*-moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px*/
	font-family:NSpictos;content:"\e606";
	display:block;
	text-align:center

}

.choose {
    font-size: 14px;
    font-family: "roboto";
    -moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    font-weight: normal;
    background-color: '.$sPrimaryColor.'
}

.wf_dealer_info {
    font-family: "roboto"
}

.wf_dealer_info .back {
    background-color: #86858a;
    color: #fff;
    -moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    font-size: 14px
}

.wf_dealer_info .back:before {
    content: "\0074";
    -moz-transform: rotate(90deg);
    -webkit-transform: rotate(90deg);
    -ms-transform: rotate(90deg);
    transform: rotate(90deg);
    font-family: "webformsIcons";
    font-size: 18px;
    font-weight: bold;
    position: absolute;
    left: 11px;
    top: 45%;
    margin-top: -9px
}

.wf_dealer_info .address {
    font-size: 12px;
    line-height: 1.1em
}

.wf_dealer_info .address h3 {
    font-weight: normal;
    font-size: 1em
}

.wf_dealer_info .address .distance {
    font-weight: normal;
    color: #DA0000;
    font-style: italic
}

.wf_dealer_info .address address {
    color: #868689;
    font-style: normal;
    min-height: 40px
}

.wf_dealer_info .map {
    -moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px
}

.no-fontface .wf_dealer_info .back:before {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -35px -112px;
    background-repeat: no-repeat;
    width: 16px;
    height: 17px;
    top: 13px
}

.wf_dropdown {
    font-size: 14px !important
}

.wf_synthesis,
.wf_resume_content {
    margin-top: 0px
}

.label_custom_select {
    font-size: 14px
}

.wf_custom_select_text {
    font-family: "roboto";
    font-size: 14px
}

.wf_custom_select_text:after {
    content: "\0074";
    font-family: "webformsIcons";
    position: absolute;
    top: 5px;
    right: 30px;
    line-height: 2em;
    font-weight: bold;
    font-size: 16px
}

.no-csstransforms .wf_custom_select_text:after {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png") !important;
    background-position: -21px -101px;
    background-repeat: no-repeat;
    width: 16px;
    height: 17px;
    top: 13px
}

.wf_cars_selection {
    padding: 0
}

.wf_cars_type {
    margin: 0
}

.wf_carpicker .wf_field_select {
    line-height: 4.5em
}

.wf_car_model {
    font-size: 16px;
    text-align: left;
   /* font-family: "roboto" !important*/
}

.wf_brochurepicker .wf_brochurePickerCar {
    text-align: left
}

.wf_synthesis_brochurePicker .left {
    font-family: "roboto"
}

.wf_synthesis_brochurePicker .left:after {
    content: none
}

.wf_range .wf_cars {
    border-top: 1px solid #bcbcbe
}

.wf_range .wf_cars:last-child {
    border-bottom: 1px solid #bcbcbe
}

.wf_range .wf_cars label.wf_car_selected {
    background-color: #ceced0
}

.wf_range .wf_cars label .wf_car_model:after {
    content: "\0074";
    /*font-family: "webformsIcons";/*
    position: absolute;
    right: 20px;
    top: 50%;
    margin-top: -7px;
    font-weight: bold;
    z-index: 1;
    -moz-transform: rotate(-90deg);
    -webkit-transform: rotate(-90deg);
    -ms-transform: rotate(-90deg);
    transform: rotate(-90deg);
	color:'.$sPrimaryColor.';
}

.wf_range .wf_highlight_car label {
    background-color: #f3f3f6
}

.wf_range .wf_highlight_car_canceler label {
    background-color: #fff
}

.no-borderradius .wf_range .wf_cars .wf_car_model:after,
.no-fontface .wf_range .wf_cars .wf_car_model:after {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -53px -99px;
    background-repeat: no-repeat;
    width: 16px;
    height: 17px
}

.no-borderradius .wf_brochurepicker .wf_range .wf_cars .wf_car_model:after,
.no-fontface .wf_brochurepicker .wf_range .wf_cars .wf_car_model:after {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -16px -99px;
    background-repeat: no-repeat;
    width: 16px;
    height: 17px
}

.wf_brochurepicker .wf_cars label:hover {
    background-color: transparent
}

.wf_brochurepicker .wf_cars_selection .wf_cars label .wf_car_model:after,
.wf_brochurepicker .wf_cars_selection .wf_range .wf_highlight_car_canceler label .wf_car_model:after {
    -moz-transform: rotate(0deg);
    -webkit-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    transform: rotate(0deg)
}

.wf_brochurepicker .wf_range .wf_highlight_car label {
    background-color: transparent
}

.wf_brochurepicker .wf_range .wf_highlight_car label:hover {
    background-color: transparent
}

.wf_brochurepicker .wf_range .wf_highlight_car label .wf_car_model:after {
    -moz-transform: rotate(180deg);
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    transform: rotate(180deg)
}

.no-borderradius .wf_brochurepicker .wf_range .wf_highlight_car label .wf_car_model:after,
.no-fontface .wf_brochurepicker .wf_range .wf_highlight_car label .wf_car_model:after {
    background-position: 1px -101px
}

.wf_brochurePickerBrochures li {
    text-align: left;
    font-size: 14px
}

.wf_brochurePickerBrochures li>div>div .wf_box_check {
    font-family: "roboto";
    font-weight: 100;
    font-size: 15px
}

.wf_brochurePickerBrochures li .wf_brochure_pdf {
    text-decoration: none;
    color: #848484
}

.wf_brochurePickerBrochures li .wf_brochure_pdf:before {
    content: "\0074";
    font-family: "webformsIcons";
    -moz-transform: rotate(-90deg);
    -webkit-transform: rotate(-90deg);
    -ms-transform: rotate(-90deg);
    transform: rotate(-90deg);
    position: absolute;
    left: 0;
    top: 2px;
    font-size: 16px;
    color:'.$sPrimaryColor.';
}

.no-fontface li .wf_brochure_pdf:before {
    background-position: -49px -101px;
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-repeat: no-repeat;
    width: 16px;
    height: 17px
}

.wf_synthesis_brochurePicker .left h3 {
    font-size: 16px;
    text-align: left
}

.no-fontface .wf_car_box {
    display: none
}

.wf_carpicker .wf_button>button:after {
    text-transform: none;
    top: 13px
}

.no-fontface .wf_carpicker .wf_button>button:after {
    text-transform: none;
    top: 13px;
    right: 20px;
    background-position: -50px -118px;
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-repeat: no-repeat;
    width: 16px;
    height: 17px
}

.wf_resume_details span {
    font-family: "roboto";
    font-size: 15px;
    color: #4b4a4b
}

.wf_carPicker_darkCars {
    background-color: transparent
}

.wf_carPicker_darkCars label {
    background-color: #0d0c0c
}

.wf_carPicker_darkCars .wf_highlight_car label {
    background-color: #181313
}

.wf_brochurepicker .wf_carPicker_darkCars {
    background-color: #fff
}

.wf_brochurepicker .wf_carPicker_darkCars .wf_cars>label {
    background-color: #0d0c0c !important
}

.wf_line .wf_error .wf_field_input textarea,
.wf_linemultiple .wf_error .wf_field_input textarea {
    border-color: #de113c;
    font-size: 14px !important
}

button {
    font-family: "roboto" !important;
    font-size: 13px !important
}

.wf_content .wf_button {
    -moz-border-top-left-radius: 5px;
    -moz-border-top-right-radius: 5px;
    -moz-border-bottom-left-radius: 5px;
    -moz-border-bottom-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-left-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px
}

.wf_content .wf_button input {
    font-size: 13px !important;
    font-family: "roboto" !important;
    text-transform: none
}

.wf_button_grey {
    background: #747474
}

.wf_content .wf_field .datepicker {
    font-family: "roboto";
    font-weight: 100
}

.wf_checkbox .wf_label_field {
    margin-right: 35px;
    display: inline-block
}

.wf_box_check {
    font-size: 14px !important;
    line-height: 2em;
    margin-right: 15px
}

.wf_box_check:before {
    width: 30px;
    height: 30px
}

.wf_box_check.wf_checked:after {
    top: 4px;
    left: 5px;
    width: 30px;
    height: 30px;
    font-size: 30px;
    line-height: 0.9em
}

.no-fontface .wf_box_check.wf_checked:after {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -74px -91px;
    background-repeat: no-repeat
}

.wf_page_footer .wf_label_field {
    margin-right: 0
}

.wf_page_footer .wf_label_field .wf_box_check {
    line-height: 1.5em
}

.wf_radio .wf_label_field {
    margin-right: 35px;
    display: inline-block
}

.wf_radio_check {
    line-height: 2.4em
}

.wf_radio_check:before {
    width: 30px;
    height: 30px;
    top: -1px
}

.no-fontface .wf_radio_check:before {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -80px 0px;
    background-repeat: no-repeat;
    border: none;
    width: 32px;
    height: 32px
}

.wf_radio_check.wf_checked:after {
    top: 10px;
    left: 9px;
    width: 14px;
    height: 14px;
    top: 8px
}

.no-fontface .wf_radio_check.wf_checked:before {
    background-image: none
}

.no-fontface .wf_radio_check.wf_checked:after {
    content: "";
    background-color: transparent;
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -80px -36px;
    background-repeat: no-repeat;
    border: none;
    width: 31px;
    height: 31px;
    top: 0;
    left: 0
}

.wf_html {
    font-family: "roboto";
    font-size: 14px !important;
    color: #868689;
    margin-top: 0px
}

.wf_field_select {
    background: none;
    -moz-border-top-left-radius: 3px;
    -moz-border-top-right-radius: 3px;
    -moz-border-bottom-left-radius: 3px;
    -moz-border-bottom-right-radius: 3px;
    -webkit-border-top-left-radius: 3px;
    -webkit-border-top-right-radius: 3px;
    -webkit-border-bottom-left-radius: 3px;
    -webkit-border-bottom-right-radius: 3px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px
}

.wf_field_select:hover {
    background: #F0F0F0
}

.wf_field_select select {
    filter: alpha(opacity=0);
    opacity: 0
}

.wf_field_select select:focus {
    outline: 0
}

.wf_content .wf_error .wf_field_select {
    border: 1px solid #dc002e
}

.wf_content .wf_error .wf_field_select select,
.wf_content .wf_error .wf_field_select select option {
    color: #dc002e
}

.select-custom:after {
    content: "\0074";
    font-family: "webformsIcons";
    position: absolute;
    right: 20px;
    top: 50%;
    margin-top: -15px;
    z-index: 1
}

.wf_custom_select_inner {
    text-align: left
}

.wf_line .wf_error .wf_field_select,
.wf_linemultiple .wf_error .wf_field_select {
    border-color: #de113c
}

.no-fontface .select-custom:after {
    content: "";
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png") !important;
    background-position: -21px -101px;
    background-repeat: no-repeat;
    width: 16px;
    height: 17px;
    top: 23px
}

.wf_form_content input[type="file"] {
    border: 1px solid #B7B3B3
}

.wf_input .wf_field_file .wf_field_filefakelabel {
    cursor: pointer
}

.wf_fileUploaded .wf_field_file .wf_progressblock {
    background: #f3f3f3;
    border-radius: 4px
}

.wf_toggle_title {
    font-family: "roboto";
    font-size: 14px !important;
    color: #868689;
    margin-top: 0px
}

.wf_toggle_content {
    font-family: "roboto";
    font-size: 14px !important;
    color: #868689;
    margin-top: 0px
}

.wf_form_content {
    font-family: "roboto" !important;
    font-weight: normal
}

.wf_form_content .wf_page_page {
    border-bottom: 1px solid #c7c7c9
}

.wf_title_container .wf_page_title {
    font-family: "roboto";
    font-weight: 100;
    font-size: 15px;
    color: '.$sPrimaryColor.';
	font-weight:bold;
}

.wf_numbering {
    font-family: "roboto";
    font-size: 15px;
    background-color: #848484;
    color: #fff
}

.wf_page {
    border-top: none
}

.wf_page_valid .wf_numbering {
    background-color: '.$sPrimaryColor.';
    color: #ffffff
}

.wf_page_valid .wf_page_title {
    /*color: '.$sPrimaryColor.';*/
	/*nos modifs*/
	font-weight:bold;
	/*nos modifs*/
}

.wf_page_open .wf_numbering {
    background-color: '.$sPrimaryColor.';
    color: #fff
}

.wf_page_open .wf_page_title {
    /*color: '.$sPrimaryColor.';*/
	/*nos modifs*/
	font-weight:bold;
	/*nos modifs*/
}

.no-borderradius .wf_numbering,
.no-fontface .wf_numbering {
    background-color: transparent;
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: 0 -34px;
    width: 31px;
    height: 23px
}

.no-borderradius .wf_page_open .wf_numbering,
.no-fontface .wf_page_open .wf_numbering {
    background-position: 0 0px
}

.no-borderradius .wf_page_valid .wf_numbering,
.no-fontface .wf_page_valid .wf_numbering {
    background-position: 0 -68px
}

.wf_modify {
    margin: 0 10px 0 0;
    color: #2e3131 !important;
    width: 20px;
    height: 20px;
    padding-left: 15px
}

.wf_modify:hover,
.wf_modify:active {
    color: '.$sPrimaryColor.' !important
}

.wf_modify:before {
    font-size: 35px;
    right: 0;
    left: auto
}

.wf_modify span {
    display: none
}

.no-borderradius .wf_modify,
.no-fontface .wf_modify {
    background-color: transparent;
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: 27px 1px;
    padding-left: 0;
    text-indent: 5000px;
    width: 27px;
    height: 30px
}

.wf_form_content .wf_linecontent>.wf_field {
    font-family: "roboto";
    font-size: 14px
}

.wf_form_content .wf_linecontent>.wf_field .wf_field_input {
    color: #4b4a4d
}

.wf_form_content .wf_linecontent>.wf_field .wf_field_input input {
    font-family: "roboto";
    font-weight: 100;
    font-size: 14px
}

.wf_form_content .wf_label_field {
    color: #4b4a4d;
    font-size: 14px;
    font-family: "roboto" !important;
    font-weight: 100
}

.wf_icon_box .wf_help_icon {
    line-height: 1em;
    font-family: "roboto";
    font-weight: 700;
    font-size: 20px
}

.no-borderradius .wf_icon_box .wf_help_icon,
.no-fontface .wf_icon_box .wf_help_icon {
    background-color: transparent;
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -39px -34px;
    background-repeat: no-repeat;
    text-indent: -5000px;
    border: none;
    height: 27px !important;
    width: 32px !important
}

.wf_tooltip {
    box-shadow: -5px 8px 10px #ccc
}

.wf_tooltip .close {
    background: none repeat scroll 0 0 #848484;
    -moz-border-top-left-radius: 100px;
    -moz-border-top-right-radius: 100px;
    -moz-border-bottom-left-radius: 100px;
    -moz-border-bottom-right-radius: 100px;
    -webkit-border-top-left-radius: 100px;
    -webkit-border-top-right-radius: 100px;
    -webkit-border-bottom-left-radius: 100px;
    -webkit-border-bottom-right-radius: 100px;
    border-top-left-radius: 100px;
    border-top-right-radius: 100px;
    border-bottom-left-radius: 100px;
    border-bottom-right-radius: 100px;
    height: 25px;
    line-height: 25px;
    text-align: center;
    color: #fff;
    font-size: 16px;
    cursor: pointer
}

.wf_tooltip:before {
    background-position: 0px 0px !important
}

.wf_popup_login .wf_icon_box .wf_help_icon {
    line-height: 1.5em
}

.no-borderradius .wf_tooltip .close,
.no-fontface .wf_tooltip .close {
    background-color: transparent;
    background-image: url("../../images/forms/skinMobile/icons/sprites_mobile.png");
    background-position: -42px -70px;
    background-repeat: no-repeat;
    height: 26px;
    width: 26px;
    line-height: 25px;
    border: none;
    text-indent: -5000px
}

.wf_contentPage .wf_page_errorMessage {
    background: none;
    padding-left: 0;
    font-family: "roboto";
    font-weight: 100;
    font-size: 15px;
    color: #dc002e;
    font-style: italic
}

#linkid {
    font-size: 14px
}

.wf_field_input input,
.wf_field_input select,
.wf_field_input textarea {
    font-size: 14px !important;
    font-family: "roboto" !important;
    font-weight: 100
}

.wf_tooltip_mobile_content p {
    width: 90%
}

.wf_message {
    font-size: 13px
}

.request-form .title .num {
	color: #fff;
    font: 15px/16px citroenlight,Arial,sans-serif;
    height: 30px;
    margin-right: 10px;
    padding: 7px 0 7px 10px;
    width: 30px;
}

		 </style>
		';}

		return $sCss;
	}
	
	
	

}
?>