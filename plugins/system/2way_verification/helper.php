<?php


class GoogleAuthenticationHelper 
{
	/**
	 * Load Jquery
	 */
	static function loadJQuery() {
		static $required; 
		if($required) {	return ;	}
		$required = true;
		JFactory::getDocument()->addScript("//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");		
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
			        				$("#GA_QR_image").html(response_text);
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
	
	
	static public function getQRcode($desc, $key ) 
	{
			// Create URL for the Google charts QR code generator.
		$chl = urlencode( "otpauth://totp/{$desc}?secret={$key}" );
		$qrcodeurl = "https://chart.googleapis.com/chart?cht=qr&amp;chs=200x200&amp;chld=H|0&amp;chl={$chl}";
	    
		return  "<img width='200' height='200' id=\"GA_QRCODE\"  src=\"{$qrcodeurl}\" alt=\"QR Code\"/>";	
	}


}