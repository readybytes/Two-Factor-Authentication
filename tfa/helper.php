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
				
				var check_accountName 
							= function()
								{
									if(!$('#tfa_username').val()) {
						    			alert('<?php echo Jtext::_('PLG_TFA_ACCOUNT_NAME_REQUIRED');?>');
						    			$('#tfa_username').focus();
						    			return false;
						    		}
						    		return true;
								};
				
				var check_secretKey 
							= function()
								{
									if(!$('#tfa_secret').val()) {
						    			alert('<?php echo Jtext::_('PLG_TFA_SECRET_KEY_REQUIRED');?>');
						    			$('#tfa_secret').focus();
						    			return false;
						    		}
						    		
						    		return true;
								};
								
<!--	Get QR code							-->
				var get_qrImage 
							= function()
								{
									if(!$('#tfa_username').val()) {
										return false;
									}
									
									if(!$('#tfa_secret').val()) {
										return false;
									}
									
									$.get(
						        			loadUrl,
						        			{'method'	 	: 'getQRcode',
						        			 'tfa_username'	: $('#tfa_username').val(),
						        			 'tfa_secret' 	: $('#tfa_secret').val()},
						        			 function(response) {
						        				$("#tfa_qr_image").html(response);
						        			}
						        		);
								};
								
								
				<!--	 On documnet ready task			-->
				$(document).ready(function($) { 
					<!-- Task-1:: get secret key		-->
					$("#tfa_newsecret").click(function(event) {
						<!-- Check Account user name    		-->
			    		if(!check_accountName()) {
			    			return false;
			    		}
						<!-- get seret key and placed proper location  		-->
			    		$.get(
			        			loadUrl,
			        			{'method':'getsecretkey'},
			        			function(response) {
			        				$("#tfa_secret").attr('value', response);
			        				<!-- get qr image  		-->
									get_qrImage();
			        			}
			        		);
			        	
			       		event.preventDefault();
				    });
				    
				    <!-- Task-2:: get QR Code		-->
					$("#tfa_qr_get").click(function(event) {
					
						<!-- Check Account user name    		-->
			    		if(!check_accountName()) {
			    			return false;
			    		}
						
						<!-- Secret Key required   		-->
			    		if(!check_secretKey()) {
			    			return false;
			    		}
			    		
						<!-- get qr image  		-->
						get_qrImage();
			    		
			        	event.preventDefault();
				    });
				    
				    
				<!-- Task-3 : validation before Activate 2fa system				    -->
					$(".tfa_is_enable").click(function(event) {
						
						var value = $(".tfa_is_enable input:radio:checked").val();
						
						if (1 == value) {
							// validation required
							if(!check_accountName() || !check_secretKey() ) {
								//changed selected element
								$(".tfa_is_enable input:radio[value='0']").prop("checked","checked");
								return false;
							}
							
							var r=confirm("<?php echo JText::_('PLG_TFA_CONFIRM_ENABLE'); ?>");
							
							if (r==false) {
							  	//changed selected element
								$(".tfa_is_enable input:radio[value='0']").prop("checked","checked");
							 }
							
						}
					});
					
					
				<!-- Task-4 : When you will change account name then need to regenerate Q-R Image    -->
					$("#tfa_username").on("change paste", function() {
					   <!-- get qr image  		-->
						get_qrImage();
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
	static function XXupdatepluginParams($params)
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
