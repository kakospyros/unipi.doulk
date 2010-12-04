<?php
/*****************************************************************************************************
 * Authenticate Class implementation																										*
 * check the authorization level for current class/method/attribute													*
*****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Authenticated_Exceptions Class - Exception class for follow class									*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\Authenticated Current class for which building this exception class					*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Authenticated_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar;
use phpJar;
/**
 * Authenticated Class --	 																															*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Authenticated	implements Registry_I
{
	const REG_INDEX = 'AUTH';
	/********************************
	 * Class Registry methods Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistryGet($key)
	 * @uses /phpJar/Register#_get($key = null)
	 * @return mixed requested index value
	 */
	final public static function _callRegistryGet($key)
	{
		$oAuth = array();
		phpJar\Registry::_set(static::REG_INDEX,$oAuth,false);
		$oAuth = phpJar\Registry::_get(self::REG_INDEX);
		return $oAuth[$key];
	}
	/**
	 *
	 * @param string $calledClass
	 * @return
	 */
	final public static function _callRegistrySet($key, $value)
	{
		$oAuth = array();
		phpJar\Registry::_set(self::REG_INDEX,$oAuth,false);
		$oAuth = phpJar\Registry::_get(self::REG_INDEX);
		$oAuth[$key] = $value;
		phpJar\Registry::_set(self::REG_INDEX,$oAuth);
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistryUnSet($key)
	 * @return null
	 */
	public static function _callRegistryUnSet($key)
	{
		$oReg = self::_callRegistryGet();
		unset($oReg[$key]);
		phpJar\Registry::_set(self::REG_INDEX,$oReg);
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * Authenticated constructor,
	 * @access public
	 * @return mixed a class instance
	 */
	public function __construct(){}
	/**
	 * Authentication for using a class from the package
	 * using session
	 * @access public
	 * @static
	 * @param string $user
	 * @param boolean $exit
	 * @return true on success, or 401 header for unauthorized use
	 */
	public static function _sessionCheck($user = null, $exit = true)
	{
		$session = session_id();
		$username = trim(self::_callRegistryGet('username'));
		$rs = true;
		if ($username == '')
		{
			if($exit)
			{
				header("HTTP/1.0 401 Unauthorized");
				exit();
			}
			$rs = false;
		}
		return $rs;
	}

}
?>