<?php


class GoogleAuthenticationHelper 
{
	/**
	 * Load Jquery
	 */
	static function loadJQuery() {
		static $required; 
		if($required) {
			return ;
		}
		self::loadCSS();
		$required = true;
		JFactory::getDocument()->addScript("//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
		JFactory::getDocument()->addScript("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js");
		
	}
	
	static public function loadCSS() {
		
		
		ob_start();
		?>
		
	.ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br {
    border-bottom-right-radius: 4px;
}
.ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl {
    border-bottom-left-radius: 4px;
}
.ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr {
    border-top-right-radius: 4px;
}
.ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl {
    border-top-left-radius: 4px;
}
.ui-widget-content {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-attachment: scroll;
    background-clip: border-box;
    background-color: #FFFFFF;
    background-image: url("images/ui-bg_flat_75_ffffff_40x100.png");
    background-origin: padding-box;
    background-position: 50% 50%;
    background-repeat: repeat-x;
    background-size: auto auto;
    border-bottom-color: #AAAAAA;
    border-bottom-style: solid;
    border-bottom-width: 1px;
    border-image-outset: 0 0 0 0;
    border-image-repeat: stretch stretch;
    border-image-slice: 100% 100% 100% 100%;
    border-image-source: none;
    border-image-width: 1 1 1 1;
    border-left-color-ltr-source: physical;
    border-left-color-rtl-source: physical;
    border-left-color-value: #AAAAAA;
    border-left-style-ltr-source: physical;
    border-left-style-rtl-source: physical;
    border-left-style-value: solid;
    border-left-width-ltr-source: physical;
    border-left-width-rtl-source: physical;
    border-left-width-value: 1px;
    border-right-color-ltr-source: physical;
    border-right-color-rtl-source: physical;
    border-right-color-value: #AAAAAA;
    border-right-style-ltr-source: physical;
    border-right-style-rtl-source: physical;
    border-right-style-value: solid;
    border-right-width-ltr-source: physical;
    border-right-width-rtl-source: physical;
    border-right-width-value: 1px;
    border-top-color: #AAAAAA;
    border-top-style: solid;
    border-top-width: 1px;
    color: #222222;
}
.ui-widget {
    font-family: Verdana,Arial,sans-serif;
    font-size: 1.1em;
}
.ui-dialog {
    outline-color: -moz-use-text-color;
    outline-style: none;
    outline-width: 0;
    padding-bottom: 0.2em;
    padding-left: 0.2em;
    padding-right: 0.2em;
    padding-top: 0.2em;
}
.ui-front {
    z-index: 100;
}
<?php 
		$style	=	ob_get_contents();
		ob_end_clean();
		JFactory::getDocument()->addStyleDeclaration($style);
	}
	
	/**
	 * Load havascript for ajax
	 */
	static function loadJS() {
		ob_start();
		?>
		
			function check_configuration()
			{
				var GA_desc = $('#GA_desc').val();
				var GA_secret = $('#GA_secret').val();
				    	
		    	if(!GA_desc) {
		    		alert('Description-field is empty ');
		    		$('#GA_desc').focus();
		    		return false;
		    	}
		    	
		    	if(!GA_secret ) {
					alert('Please generate Secret-key ');
		    		$('#GA_secret').focus();
		    		return false;
		    	}
			 	return true;
			}
			
			
			
			jQuery( document ).ready(function($) {
			
			
				var loadUrl = 'index.php?plugin=2way_verification';
				
			    $( '#GA_newsecret' ).click(function( event ) {
			        $.get(
			        			loadUrl,
			        			{'method':'getsecretkey'},
			        			function(response_text) {
			        				$("#GA_secret").attr('value', response_text);
			        			}
			        		);
			        event.preventDefault();
			    });
			    
			    $( '#GA_show_qr' ).click(function( event ) {
			    
			    	var GA_desc = $('#GA_desc').val();
			    	var GA_secret = $('#GA_secret').val();
			    	
			    	if(false == check_configuration()){
			    		return false;
			    	}
			    					    
			        $.get(
			        			loadUrl,
			        			{
			        				'method'	: 'getQRcode',
			        				'GA_desc'	: GA_desc,
			        				'GA_secret'	: GA_secret
			        			},
			        			function(response_text) {
			        				$("#GA_dialog_body").html(response_text);
			        				$("#GA_dialog").dialog();
			        			}
			        		);
			        event.preventDefault();
			    });
			    
			    
			    
			    $( '#jform_params_is_enable1' ).click(function( event ) {
			    
			    	if(false == check_configuration()){
			    		$("#jform_params_is_enable0").attr('checked', 'checked');
			    		return false;
			    	}
			    });
			    
			});
		
		<?php 
		$script	=	ob_get_contents();
		ob_end_clean();
		JFactory::getDocument()->addScriptDeclaration($script);
		
	}
	
	
	static function X_addField() {
		self::loadJQuery();
		ob_start();
		?>
				jQuery( document ).ready(function($) {
					alert('manish');			    
				});
		
		<?php 
		$script	=	ob_get_contents();
		ob_end_clean();
		JFactory::getDocument()->addScriptDeclaration($script);
		
	}
	
	static function XX_addField() {	
		?>
		
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
			</script>
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js" ></script>
			<script>
				jQuery( document ).ready(function($) {

					$('#lang').after('<label id="mod-login-2way-lbl" for="mod-login-2way">Two-Way</label><input name="2way" id="mod-login-2way" type="text" class="inputbox" size="15">');		    
				});
			</script>
		
		<?php 
	
	}

}