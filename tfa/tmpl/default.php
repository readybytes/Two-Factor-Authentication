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

//Load language file
$lang = JFactory::getLanguage();
$lang->load('plg_system_tfa', JPATH_ADMINISTRATOR);

//Load plugin params
$plugin = &JPluginHelper::getPlugin('system', 'tfa');
$pluginParams = new JParameter($plugin->params);
$redirect = $pluginParams->get('redirect', '/');

$msg = $app->getMessageQueue();
$systemMessage = '';
if (is_array($msg))
{
    $systemMessage = @$msg[0]['message'];
}

$logoutLink = JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1');
if ($app->isAdmin())
{
    $logoutLink = JRoute::_('index.php?option=com_login&task=logout&' . JSession::getFormToken() . '=1');
}
?>
<style>
    body{
        padding:0px;
        margin:0px;
        background:#f5f5f5;
    }
    #tfa_key{
        width: auto;
        display: inline;
    }
    .tfa_label{
        display:inline;
        margin-right: 20px;
    }
    .recovery-email{
        text-align: right;
        margin-top: 20px;
        margin-bottom: 0;
        color: #686868;
    }
    .alert {
        background-color: #F2DEDE;
        color: #B94A48;
    }
</style>

<body>
    <div class="modal">
        <form method="post" action="<?php echo JRoute::_('index.php?plugin=TFA&method=verify') ?>">
        <div class="modal-header">
            <h2><?php echo JText::_("PLG_TWO_FACTOR_AUTHENTICATION") ?></h2>
        </div>
        <div class="modal-body">
            <?php if ($systemMessage): ?>
                <div class='alert' >
                    <?php echo $systemMessage; ?>
                </div>
            <?php endif; ?>

            <label for="tfa_key" class="tfa_label"><?php echo JText::_("PLG_TFA_VERIFICATION_CODE") ?> </label>
            <input name="tfa_key" id="tfa_key" type="text" value="" maxlength="6" autocomplete="off" /><br />

            <?php if (20 == $params->get('backup_mail', 20)): ?>
                <p class="recovery-email"><?php echo JText::sprintf("PLG_TFA_BACKUP_MAIL", JRoute::_('index.php?plugin=tfa&method=recovery')) ?></p>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <a href="<?php echo $logoutLink; ?>" class="btn btn-default"><?php echo JText::_("PLG_TFA_LOGOUT") ?></a>
            <input type="submit" value='<?php echo JText::_("JSUBMIT") ?> ' class="btn btn-primary"/>
        </div>
        <input type="hidden" name="redirect" value='<?php echo JRoute::_($redirect) ?>'/>
        </form>
    </div>
</body>
