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
			if (typeof(tfa)=='undefined'){
				var tfa = {
					jQuery: window.jQuery,
					extend: function(obj){
						this.jQuery.extend(this, obj);
					}
				}
			}
			
			(function($){  
				// Required var
				var loadUrl 	 =	'index.php?plugin=TFA';
				<!--	 On documnet ready task			-->
				$(document).ready(function($) { 
					<!-- Task-1:: get secret key		-->
					$("#tfa_newsecret").click(function(event) {
						<!-- Check Account user name    		-->
			    		if(!$('#tfa_username').val()) {
			    			alert('Account name field should be required');
			    			$('#tfa_username').focus();
			    			return false;
			    		}
						<!-- get seret key and placed proper location  		-->
			    		$.get(
			        			loadUrl,
			        			{'method':'getsecretkey'},
			        			function(response) {
			        				$("#tfa_secret").attr('value', response);
			        			}
			        		);
			       		event.preventDefault();
				    });
				    
				    <!-- Task-2:: get QR Code		-->
					$("#tfa_qr_get").click(function(event) {
						<!-- username & Secret Key required   		-->
			    		if(!$('#tfa_secret').val()) {
			    			alert('Account name & Secre Key must be required for QR code');
			    			$('#tfa_secret').focus();
			    			return false;
			    		}
						<!-- get seret key and placed proper location  		-->
			    		$.get(
			        			loadUrl,
			        			{'method'	 	: 'getQRcode',
			        			 'tfa_username'	: $('#tfa_username').val(),
			        			 'tfa_secret' 	: $('#tfa_secret').val()},
			        			 function(response) {
			        				$("#tfa_qr_image").html(response);
			        			}
			        		);
			        	event.preventDefault();
				    });
				});
				
			})(tfa.jQuery);
			
			
				
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
