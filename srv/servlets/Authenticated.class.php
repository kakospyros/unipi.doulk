<?php
/*****************************************************************************************************
 * ---------------------------------------------------------------------------------------------------------------------------*/
/**
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage database																														*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\servlets;

use phpJar\servlets;

use phpJar\database;

use phpJar;
use project\scheme as _pscheme;
use project\Exceptions as _pexceptions;

class Authenticated extends \phpJar\servlets\ServletTemplate
{
	const VIEW_DESKTOP = 0;
	const VIEW_LOGIN = 1;
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'AUTH';
	/**
	 * (non PHP-Doc)
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => 'login',
																1 => 'desktop-admin',
																2 => 'desktop-doctor',
																3 => 'desktop-patient'
													);
	/**
	 *
	 * @var unknown_type
	 */
	protected static $_needAuthorize = false;
	/********************************
	 * Class templates method Area *
	 ********************************/
	/**
	 *
	 */
	public function _getFormTpl(\stdClass $args = null)
	{
		$check = self::_sessionCheck();
		if($check)
		{
			$Uid = self::_callRegistryGet('userID');
			if($Uid == _pscheme\Users::_DEFAULT_ID)
				$this->_setTemplate(self::$_tplFile[1]);
			else
			{
				if(self::_callRegistryGet('userCategory') == _pscheme\User_Category::DOCTOR)
					$this->_setTemplate(self::$_tplFile[2]);
				else
					$this->_setTemplate(self::$_tplFile[3]);
			}
			$this->_setTemplateLanguage('desktop');
		}
		return parent::_getFormTpl($args);
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param unknown_type $info
	 * @return on success return an array with requested fields, else on fail return false
	 */
	public static function _getLoginInfo($info = '*')
	{
		if(!is_string($info) || !($check = self::_sessionCheck()))
			return false;
		if($info === '*')
		{
			$rs = array(
				'amka' => trim(self::_callRegistryGet('username')),
				'given_name' => trim(self::_callRegistryGet('given_name')),
				'surname' => trim(self::_callRegistryGet('surname')),
			);
		}
		else
			$rs = array($info = trim(self::_callRegistryGet($info)));
		return $rs;
	}
	/**
	 *
	 * @param \stdClass $data
	 * @param boolean $returnForm
	 * @return true on success, false on failure
	 */
	public function _login(\stdClass $data, $returnForm = true)
	{
		$oScheme = new _pscheme\Users();
		$oUser = $oScheme->_selectFilterRecordsingle(sprintf('AND t.amka = \'%s\'',$data->amka));
		if(empty($oUser))
			_pexceptions\Users_Exceptions::throwException(phpJar\Language::_getSpecificErrorLanguage('login')->loginAuth);
		if(!$oUser->_passwdValidation($data->passwd))
			_pexceptions\Users_Exceptions::throwException(phpJar\Language::_getSpecificErrorLanguage('login')->passwdAuth);
		$session = self::_sessionSet($oUser,$remember);
		if($returnForm)
		{
			if($oUser->id == _pscheme\Users::_DEFAULT_ID)
				$this->_setTemplate(self::$_tplFile[1]);
			else
			{
				$oScheme = new _pscheme\User_Category();
				$where = sprintf(' AND t.parent_id = %d AND t.child_id = %d',$oUser->id,_pscheme\User_Category::DOCTOR);
				$oRecord = $oScheme->_selectFilterRecordsingle($where);
				if(empty($oRecord))
					$this->_setTemplate(self::$_tplFile[3]);
				else
					$this->_setTemplate(self::$_tplFile[2]);
			}
			$this->_setTemplateLanguage('desktop');
			return parent::_getFormTpl();
		}
		return $session;
	}
	/**
	 *
	 *@param boolean $returnForm
	 *@return
	 */
	public function _logout($returnForm = true)
	{
		$cookieName = 'UUID';
		setcookie($cookieName,'',time()-3600*24*30);
		session_start();
		session_destroy();
		if($returnForm)
		{
			$this->_setTemplate(self::$_tplFile[0]);
			return parent::_getFormTpl();
		}
		return true;
	}
	/**
	 *
	 */
	public static function _sessionCheck($user = null)
	{
		$auth = phpJar\Authenticated::_sessionCheck($user,false);
		if($auth == false)
			return $auth;
		if(!is_null($user))
		{
			$username = trim(self::_callRegistryGet('username'));
			return ($username == $user);
		}
		return true;
	}
	/**
	 *
	 * @param $oUser
	 */
	protected static function _sessionSet(\project\scheme\Users $oUser, $remember = false)
	{
		@session_start();
		self::_callRegistrySet('username',$oUser->amka);
		self::_callRegistrySet('given_name',$oUser->given_name);
		self::_callRegistrySet('surname',$oUser->surname);
		self::_callRegistrySet('userID',$oUser->id);
		if($oUser->id == _pscheme\Users::_DEFAULT_ID)
			self::_callRegistrySet('userCategory',null);
		else
		{
			$oScheme = new _pscheme\User_Category();
			$where = sprintf(' AND t.parent_id = %d AND t.child_id = %d',$oUser->id,_pscheme\User_Category::DOCTOR);
			$oRecord = $oScheme->_selectFilterRecordsingle($where);
			if(!empty($oRecord))
				self::_callRegistrySet('userCategory',_pscheme\User_Category::DOCTOR);
			else
				self::_callRegistrySet('userCategory',_pscheme\User_Category::PATIENT);
		}
		//check remember me
		if($remember)
		{
			$cookieName = 'UUID';
			$cookieTime = time()+3600*24*30;//+30 days
			setcookie($cookieName,$oUser->username,$cookieTime);
		}
		return true;
	}


}
?>