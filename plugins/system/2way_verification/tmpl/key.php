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


//JHtml::_('behavior.noframes');
$config = new JConfig();
$logoutLink = JRoute::_('index.php?option=com_login&task=logout&'. JSession::getFormToken() .'=1');
?>
<body>
	<!--<div id="border-top" class="h_blue">
		<span class="title">
			<a href="index.php"><?php //echo $config->sitename; ?></a>
		</span>
	</div>	-->
	<fieldset style=" border: 1px solid ;width: 400px; margin:auto;text-align: center;">
		<legend><h1><?php echo 'Two-Way Verification(Beta Mode)'; ?></h1></legend>
		
		<div style="display: inline-block; width: 100%; text-align:right">
				<a href="<?php echo $logoutLink; ?>">Logout</a>
		</div>
		
		<div style="display: inline-block; width: 100%;">	
			<form method="post" action="index.php?plugin=2way_verification&method=verify"  style="width: 265px; margin: auto;">
			
				<label>Two-way code </label>&nbsp;<input name="2way" value="" style="margin-left:10px;"/>
				<input type="submit" value='submit' />
			
			<!--	<input type="checkbox" name="remember" id="remember"><label for="remember"> Remember verification for this computer for 1 day.</label> <br>-->
			</form>
		</div>
		
		<div style="display: inline-block; width: 100%; font-weight:bold">
			If you are unable to login then please disable two-way authentication plugin from your site database.
		</div>
		
	</fieldset>
	
</body>
	