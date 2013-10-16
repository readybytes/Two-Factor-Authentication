<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author      mManishTrivedi
 */

defined('JPATH_BASE') or die;

//require_once JPATH_PLUGINS.'/system/2way_verification/helper.php';

class JFormFieldBackup extends JFormField
{
	protected function getInput()
	{
		$name 	= $this->name;
		$value 	= $this->value;
		
		if(!isset($value['code']) || empty($value['code'])) {
			$value['code'] = GoogleAuthenticationHelper::backupCode();
		}
		
		if(!isset($value['count']) || empty($value['count'])) {
			$value['count'] = 0;
		}
		
		ob_start();
		?>
			<fieldset class="">				
		
				<label class=""><?php echo JText::_('PLG_TFA_CODE_BACKUP')?></label>

					<input 	type="text" size="6" 
							name="<?php echo $name?>[code]"  value="<?php echo $value['code'];?>"  
							readonly="true" title="<?php echo JText::_('PLG_TFA_CODE_BACKUP_DESC')?>" />
				
				<label class=""><?php echo JText::_('PLG_TFA_CODE_USED')?></label>

					<fieldset id="jform_params_is_enable" class="radio">
						<input 	type="radio" name="<?php echo $name?>[count]" value="1" <?php echo ($value['count']== 1) ? 'checked=checked': '' ?>
								title="<?php echo JText::_('PLG_TFA_CODE_ONE_TIME_DESC')?>"/>
						<label class=""><?php echo JText::_('PLG_TFA_CODE_ONE_TIME')?></label>
						<input 	type="radio" name="<?php echo $name?>[count]" value="0" <?php echo ($value['count']== 0) ? 'checked=checked': '' ?>
								title="<?php echo JText::_('PLG_TFA_CODE_UNLIMITED_DESC')?>"/>
						<label class=""><?php echo JText::_('PLG_TFA_CODE_UNLIMITED')?></label>
					</fieldset>
							
			</fieldset>
			
			<?php
		$html = ob_get_contents();		
		ob_end_clean();
		return $html;
	}
}
