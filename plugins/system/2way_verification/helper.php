<?php
/**
 * @package     Joomla Plugin for two way authetication
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @author      mManishTrivedi
 */

defined('_JEXEC') or die;

class GoogleAuthenticationHelper 
{
	/**
	 * Load Jquery
	 */
	static function loadJQuery() {
		$jversion = new JVersion;
		$release = str_replace('.', '', $jversion->RELEASE);
		if($release >= 30) {
			return true;
		}
		
		static $required; 
		if($required) {	return ;	}
		$required = true;
		JFactory::getDocument()->addScript("//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");		
	}
	
	static function isPluginEnable() {
		return JPluginHelper::isEnabled('system','2way_verification');
	}
	
	/**
	 * Load havascript for ajax
	 */
	static function loadJS() {
		$isEnable = self::isPluginEnable();
		ob_start();
		?>
		
			function check_configuration()
			{
				var GA_desc 	= jQuery('#GA_desc').val();
				var GA_secret 	= jQuery('#GA_secret').val();
				
		    	if(!GA_desc) {
		    		alert('Account name-field is empty ');
		    		jQuery('#GA_desc').focus();
		    		return false;
		    	}
		    	
		    	if(!GA_secret ) {
					alert('Please generate key ');
		    		jQuery('#GA_secret').focus();
		    		return false;
		    	}
			 	return true;
			}
			
			function isPluginEnable() 
			{ 
				var isEnable 	= <?php echo (int)$isEnable;?>;
				if(!isEnable) {
					alert('Enable this plugin first and save it. See Available steps in Description or Doc.');
					return false;
				}
				return true;
			}
			
			jQuery( document ).ready(function($) {
			
				var loadUrl = 'index.php?plugin=2way_verification';
			    jQuery( '#GA_newsecret' ).click(function( event ) {
			    
			    if(false == isPluginEnable() ){
			    		return false;
			    }
				
				   jQuery.get(
			        			loadUrl,
			        			{'method':'getsecretkey'},
			        			function(response_text) {
			        				jQuery("#GA_secret").attr('value', response_text);
			        			}
			        		);
			        event.preventDefault();
			    });
			    
			    jQuery( '#GA_show_qr' ).click(function( event ) {
			    
			    	var GA_desc = jQuery('#GA_desc').val();
			    	var GA_secret = jQuery('#GA_secret').val();
			    	
			    	if(false == check_configuration() || false == isPluginEnable() ){
			    		return false;
			    	}
			    					    
			        jQuery.get(
			        			loadUrl,
			        			{
			        				'method'	: 'getQRcode',
			        				'GA_desc'	: GA_desc,
			        				'GA_secret'	: GA_secret
			        			},
			        			function(response_text) {
			        				jQuery("#GA_QR_image").html(response_text);
			        			}
			        		);
			        event.preventDefault();
			    });
			    
			    
			    
			    jQuery( '#jform_params_is_enable1' ).click(function( event ) {
			    
			    	if(false == check_configuration()){
			    		jQuery("#jform_params_is_enable0").attr('checked', 'checked');
			    		return false;
			    	}
			    	
			    	var confirmation =confirm("Make Sure, You have scanned bar code or feed account name and key into your google authetication app!");
			    	if (confirmation == false) {
						//jQuery("#jform_params_is_enable0").attr('checked', 'checked');
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
	
	// @return 6 digit backup key	
	static public function backupCode() {
		return mt_rand(100000, 999999);
	}
	
	/**
	 * 
	 * Update plugin params
	 * @param should b JRegistry object
	 */
	static function updatepluginParams($params)
	{ 
		// convert JSON strin
		$params = $params->toString();
		$query = " 
					UPDATE #__extensions SET `params` = '$params'
					WHERE `element` = '2way_verification' AND `type` = 'plugin' AND `folder` ='system' 
				  ";
		
		return (bool)JFactory::getDbo()->setQuery($query)->query();
	}


}
