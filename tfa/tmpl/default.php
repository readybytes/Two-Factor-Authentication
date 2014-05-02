<?php
/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
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
//$config = new JConfig();
$logoutLink = JRoute::_('index.php?option=com_users&task=user.logout&'. JSession::getFormToken() .'=1');
if($app->isAdmin()){
	$logoutLink = JRoute::_('index.php?option=com_login&task=logout&'. JSession::getFormToken() .'=1');
}

?>
<style>
body{
	padding:0px;
	margin:0px;
}
.tfa-login-container {
	width: 500px;	
	position: absolute;
	top: 50%;
	left: 50%;
	margin-left: -250px;
	margin-top: 100px;
}
.tfa-login-screen {
	text-align: center;
	border: 1px solid #AAA;;
	border-radius: 3px;
	-webkit-border-radius: 3px;
	-mox-border-radius: 3px;
	-o-border-radius: 3px;
}
.alert {
    background-color: #F2DEDE;
    color: #B94A48;
}
.clearfix:after {
	visibility: hidden;
	display: block;
	font-size: 0;
	content: " ";
	clear: both;
	height: 0;
}
.middle-text{
	padding: 10px 20px 30px 20px;
}
.tfa-login-screen form input{
	margin-bottom: 15px;
	outline:none;
}
.tfa-login-screen form label{
	font-weight: 600;
	font-size: 15px;
	margin-bottom: 10px;
}
.tfa-login-container p {
	color:#666;
	font-size: 12px;
	line-height: 1.7em;
}
.tfa-login-screen .btn {
	display: inline-block;
	padding: 4px 14px;
	margin-bottom: 0;
	font-size: 14px;
	line-height: 24px;
	text-align: center;
	vertical-align: middle;
	cursor: pointer;
	color: #333333;
	text-shadow: 0 1px 1px rgba(255,255,255,0.75);
	background-color: #f5f5f5;
	background-image: -moz-linear-gradient(top,#ffffff,#e6e6e6);
	background-image: -webkit-gradient(linear,0 0,0 100%,from(#ffffff),to(#e6e6e6));
	background-image: -webkit-linear-gradient(top,#ffffff,#e6e6e6);
	background-image: -o-linear-gradient(top,#ffffff,#e6e6e6);
	background-image: linear-gradient(to bottom,#ffffff,#e6e6e6);
	background-repeat: repeat-x;
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff',endColorstr='#ffe6e6e6',GradientType=0);
	border-color: #e6e6e6 #e6e6e6 #bfbfbf;
	border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
	filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
	border: 1px solid #cccccc;
	border-bottom-color: #b3b3b3;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.2),0 1px 2px rgba(0,0,0,.05);
	-moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.2),0 1px 2px rgba(0,0,0,.05);
	box-shadow: inset 0 1px 0 rgba(255,255,255,.2),0 1px 2px rgba(0,0,0,.05);
}

.tfa-right-align {
	text-align: right;
	padding-right: 8px;
}
</style>
<?php 
	$root = JURI::root();
    // First we need to detect the URI scheme.
	if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) {
		$root = JString::str_ireplace("http:", "https:", $root);
	}
?>
<body>
	<div style="position:relative">
		<div class="tfa-login-container">
			<div class="tfa-login-screen">	
				<div style="background:#FEDB65;padding:20px 70px;" class="clearfix">
					<div style="float:left;margin-right: 30px;">
						<img src="<?php echo $root; ?>plugins/system/tfa/tmpl/tfa.png" title="" alt="Secure your joomla site with two factor authentication." width="60px">
					</div>
					<div style="float:left">
						<h2 style="font-weight: 500;font-size: 20px;"><?php echo JText::_("PLG_TWO_FACTOR_AUTHENTICATION") ?></h2>
					</div>
				</div>
				
				<div class="tfa-right-align" style="margin-top:10px;">
					<a href="<?php echo $logoutLink; ?>"><?php echo JText::_("PLG_TFA_LOGOUT") ?></a>
				</div>
						
				<div class="middle-text">
						<?php if($systemMessage):?>
						<div class='alert' >
							<?php echo $systemMessage; ?>
						</div>
						<?php endif;?>
						
						
						<div style="width: 100%;"><br>
						
							<form method="post" action="index.php?plugin=TFA&method=verify"  style="width: 265px; margin: auto;">
								<label><?php echo JText::_("PLG_TFA_VERIFICATION_CODE") ?> </label>
								<input name="tfa_key" type="text" value="" maxlength="6"  style="border: 1px solid #ccc;" autocomplete="off" /><br />
								<input type="submit" value='submit' class="btn"/>
								
					<!--				<input type="checkbox" name="remember" id="remember"><label for="remember"> Remember verification for this computer for 1 day.</label> <br>-->
								<input type="hidden" name="redirect" value='index.php'/>
							</form><br/>
						</div>
						<?php if(20==$params->get('backup_mail',20)):?>
							<p>
								<?php echo JText::sprintf("PLG_TFA_BACKUP_MAIL", JRoute::_('index.php?plugin=tfa&method=recovery')); ?>  
							</p>
						<?php endif;?>
					
				</div>
			</div>
		
			<p class="tfa-right-align">
				<span>Developed by <a href="http://www.readybytes.net" target="_blank">Team-Readybytes</a></span>
			</p>
			
		</div>
	</div>
	
</body>
	
