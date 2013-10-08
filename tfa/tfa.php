<?php
/**
 * @package     Joomla Plugin for two way authetication
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @author      mManishTrivedi
 */

defined('_JEXEC') or die;

/**
 * @package     System.TFA
 * @since       2.5
 */
require_once 'helper.php';
include_once("lib/GoogleAuthenticator.php");

class plgSystemTFA extends JPlugin
{
	// authetication is activated or not for user
	private $_is_activated	= false;
	// is activated then verified or not
	private $_is_varified	= false;
	//
	private $_enable_for;
	
	public function __construct(&$subject, $config)
	{
		// call Parent construstor
		parent::__construct($subject, $config);
		$this->_enable_for = JFactory::getUser()->get('id');
		//if user logged-in den check activation nd verification
		if($this->_enable_for) {
			$this->_is_activated = $this->_isActivated();
			$this->_is_varified	 = $this->_isVerified();
		}
	}
	
	
	/**
	 * @param   JForm    $form    The form to be altered.
	 * @param   array    $data    The associated data for the form.
	 *
	 * @return  boolean
	 * @since   2.5
	 */
	public function onContentPrepareForm($form, $data)
	{
		// if user not logged-in
		if(!$this->_enable_for) {
			return true;
		}
	
		
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		
		// Check we are manipulating a valid form.
		$name = $form->getName();
		if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile')))
		{
			return true;
		}

		// Add the registration fields to the form.
		JForm::addFormPath(__DIR__ . '/forms');
		$form->loadFile('TFA', false);

		return true;
	}
	
	function onAfterRoute()
	{
		// if user not logged-in
		if(!$this->_enable_for) {
			return true;
		}
		
		$input = JFactory::getApplication()->input;
		
		// is it ajax req or not
		if('TFA' != $input->get('plugin', '')) {
			return true;
		}
		
		$method = $input->get('method');
		
		echo $this->$method();
		exit;
	}
	
	/**
	 * Generate 16 digit secret key
	 */
	private function getSecretKey() 
	{	
		$g = new GoogleAuthenticator();
	    return $g->generateSecret();
	}
	
	/**
	 * Create QR code URL
	 */
	private function getQRcode() 
	{
	    $input = JFactory::getApplication()->input;
	    
	    $desc = $input->get('tfa_username');
	    $key  = $input->get('tfa_secret');
	    
	    return GoogleAuthenticationHelper::getQRcode($desc, $key);
	}
	
	function onAfterRender()
	{
		// if user not logged-in || logged-in but not activated || vefied user for TFA
		if(!$this->_enable_for || !($this->_is_activated) || $this->_is_varified) {
			return true;
		}
		
		$buffer      = JResponse::getBody();
		ob_start();
			require_once 'tmpl/default.php';
		$tfa_html = ob_get_contents();
		ob_end_clean();
		
		//$buffer = preg_replace("%<body\s*\w*>([\w\W]*)</body>%i",$two_way_html, $buffer);
		//$buffer = preg_replace('%<body id="minwidth-body">([\w\W]*)</body>%i',$two_way_html, $buffer);
		$buffer = preg_replace('%<body.*>([\w\W]*)</body>%i', $tfa_html, $buffer);		
		JResponse::setBody($buffer);
	}

//	Check user is verified or not
	private function _isVerified()
	{
		$user = JFactory::getSession()->get('user') ;
		if(!($user instanceof JUser) ){
			return false;
		}
		
		if(isset($user->tfa) && !empty($user->tfa)) {
			return true;
		}
		// default
		return false;		
	}

	//	Check TFA is Activated or not for specific user
	private function _isActivated()
	{
		$tfa  = JFactory::getUser()->get('_params')->get('tfa');
		if($tfa) {
			return (bool)$tfa->activate;
		}
		// default value
		return false;		
	}
	
	/**
	 * Check the verification code entered by the user.
	 */
	private function verify() 
	{
		$app = JFactory::getApplication();
		// get Submit tfa_key
		$key = $app->input->get('tfa_key');
		// Get user tfa secret ket
		$tfa = JFactory::getUser()->get('_params')->get('tfa');	
		$secretkey = $tfa->authentication->secret;
		// Check Verification
		$g = new GoogleAuthenticator();
		$this->_is_varified = (boolean)$g->checkCode($secretkey, $key);
		
		// Set into session user verified or not
		$session = JFactory::getSession();
		$user = $session->get('user') ;
		$user->tfa = $this->_is_varified;
		$session->set('user',$user);
		$msg = '';
		if (!$this->_is_varified) {
			$msg = JText::_("PLG_TFA_AUTHENTICATION_FAILED");
		}
		$redirect_url = $app->input->get('redirect','index.php');
		$app->redirect($redirect_url, $msg);
	}
//	

//	

//	
//
//	/**
//	 * Send backup code to login user email id
//	 */
//	function recovery() {
//		$user = JFactory::getUser();
//		$email = $user->email;
//		$backupCode = $this->params->get('backup')->code;
//		$config = JFactory::getConfig();
//		
//		$from 		= $config->sitename;
//		$fromname	= $config->sitename;
//		$recipient	= $email;
//		$subject 	= "$config->sitename Backup Code";
//		$body		= "Hello {$user->name}, <br />You have requested for backup code. Your backup code is $backupCode. Now you can enter this code as verification code.";
//		
//		$msg = "System Email Fail To : $email";
//		$jversion = new JVersion;
//		$release = str_replace('.', '', $jversion->RELEASE);
//		if($release >= 30) {
//			$mail = new JMail();
//			if(true == $mail->sendMail($from, $fromName, $recipient, $subject, $body, true)){
//				$msg = "Backup code sent";
//			}
//		}else if(true == JUtility::sendMail($from, $fromname, $recipient, $subject, $body, true)) {
//			$msg = "Backup code sent";
//		}
//		JFactory::getApplication()->redirect('index.php', $msg);
//	}

}


