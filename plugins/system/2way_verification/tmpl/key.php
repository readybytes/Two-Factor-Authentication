<?php
/**
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Manish Trivedi
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

$app = JFactory::getApplication();
$doc = JFactory::getDocument();

$msg = $app->getMessageQueue();
$systemMessage = '';
if(is_array($msg)) {
	$systemMessage = @$msg[0]['message'];
}
//JHtml::_('behavior.noframes');
$config = new JConfig();
$logoutLink = JRoute::_('index.php?option=com_login&task=logout&'. JSession::getFormToken() .'=1');
?>
<style>

.alert {
    background-color: #F2DEDE;
    color: #B94A48;
}
</style>
<body>
	<fieldset style=" border: 1px solid ;width: 400px; margin:auto;text-align: center;">
		<legend><h1><?php echo 'Two-Factor Authentication(Beta Mode)'; ?></h1></legend>
		<?php if($systemMessage):?>
		<div class='alert' >
			<?php echo $systemMessage; ?>
		</div>
		<?php endif;?>
		<div style="display: inline-block; width: 100%; text-align:right">
				<a href="<?php echo $logoutLink; ?>">Logout</a>
		</div>
		
		<div style="display: inline-block; width: 100%;">	
			<form method="post" action="index.php?plugin=2way_verification&method=verify"  style="width: 265px; margin: auto;">
			
				<label>Verification code </label>&nbsp;<input name="2way" value="" style="margin-left:10px;"/>
				<input type="submit" value='submit' />
			
			<!--	<input type="checkbox" name="remember" id="remember"><label for="remember"> Remember verification for this computer for 1 day.</label> <br>-->
			<!--	<input type="hidden" name="redirect" value='index.php'/>-->
			</form>
		</div>
		
		<div style="display: inline-block; width: 100%; font-weight:bold">
			If you are unable to login then please disable two-way authentication plugin from your site database. 
			OR Get <a href='index.php?plugin=2way_verification&method=recovery'>Backup-Code</a> at your email id 
		</div>
		
	</fieldset>
	
</body>
	
