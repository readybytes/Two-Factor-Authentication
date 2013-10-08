<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @author	mManishTrivedi
 */

defined('JPATH_BASE') or die;

class JFormFieldAuthentication extends JFormField
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
		<fieldset id="tfa_setup" class="tfa_setup">	
			<!-- Account Name	-->
			<label class=""><?php echo JText::_('PLG_TFA_USERNAME');?></label>
				<input 	type="text" size="25" id="tfa_username" name="<?php echo $name?>[username]"  
						value="<?php echo @$value['username'];?>"  title="<?php echo JText::_('PLG_TFA_USERNAME_DESC');?>" />
			<!-- Secret Key		-->
			<label class=""><?php echo JText::_('PLG_TFA_SECRET_KEY');?></label>
				<input type="text" size="25" id="tfa_secret" name="<?php echo $name?>[secret]"  value="<?php echo @$value['secret']; ?>" maxlength="16" readonly="true"/>
				<input type="button" class="button" value="Create new secret" id="tfa_newsecret" >
			<!-- Show QR image 	-->
			<label>
				<input type="button"  class="button" value="Show QR-Code" id="tfa_qr_get" 	/>
			</label>
			<!-- Render QR image	-->
			<div id=tfa_qr_image>
			<?php
				if(@$value['username'] && @$value['secret'] ) {
					echo GoogleAuthenticationHelper::getQRcode($value['username'], $value['secret']);
				}
			?>
			</div>
			
		</fieldset>
		
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
