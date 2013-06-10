<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

require_once JPATH_PLUGINS.'/system/2way_verification/helper.php';

class JFormFieldSecret_Key extends JFormFieldList
{
	protected function getInput()
	{
		// load jQuery
		GoogleAuthenticationHelper::loadJQuery();
		GoogleAuthenticationHelper::loadJS();
		
		$name 	= $this->name;
		$value 	= $this->value;
		ob_start();
		?>
			<fieldset id="google_authentication_setup" class="">				
				<label class="">Description</label>
					<input type="text" size="25" id="GA_desc" name="<?php echo $name?>[GA_desc]"  value="<?php echo @$value['GA_desc'];?>"  title="Description that you'll see in the Google Authenticator app on your phone." />
				<label class="">Secret-Key</label>
					<input type="text" size="25" id="GA_secret" name="<?php echo $name?>[GA_secret]"  value="<?php echo @$value['GA_secret']; ?>"  />
				
				<input type="button" class="button" value="Create new secret" id="GA_newsecret" >
				<label>
					<input type="button"  class="button" value="ShowQR code" id="GA_show_qr" 	>
				</label>
				<div id=GA_QR_image>
						<?php
							if(@$value['GA_desc'] && @$value['GA_secret'] )
								echo GoogleAuthenticationHelper::getQRcode($value['GA_desc'], $value['GA_secret']);
						?>
				</div>
				
<!--				<input type="hidden" size="25" value="" id="GA_params" name="<?php //echo $name?>[GA_params]['mode']"  title="Mode" />-->

<!--				<div id="GA_dialog" class="hide" title="QR Code" >-->
<!--					<span id="GA_dialog_header">Please scan this QR/bar Code from your <a href='http://support.google.com/accounts/bin/answer.py?hl=en&answer=1066447' target='_blank'>google-authentication app </a></span>-->
<!--					<span id="GA_dialog_body"></span>-->
<!--				</div>-->
			</fieldset>
		
		<?php
		$html = ob_get_contents();
		ob_clean();
		return $html;
	}
}
