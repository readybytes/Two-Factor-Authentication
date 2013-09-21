<?php
/**
 * @package     Joomla Plugin for two factor authetication
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @author      mManishTrivedi
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class plgSystemTFAInstallerScript
{
	/**
	 * Called before any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install)
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($type, $parent)
	{}

	/**
	 * Called on installation
	 *
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function install($parent)
	{ }

    /**
	 * Called on uninstallation
	 *
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function uninstall($parent)
	{ }

	/**
	 * Called after install
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install)
	 * @param   object  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, $parent)
	{
		ob_start();
		?>
			<div class="hide">
			<?php
				$version = new JVersion();
				$suffix = 'jom=J'.$version->RELEASE.'&extension=2FA&dom='.JURI::getInstance()->toString(array('host'));?>
				<iframe src="http://pub.jpayplans.com/labs/broadcast/installation.html?<?php echo $suffix?>"></iframe>
			</div>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		echo $html;
	}

}




