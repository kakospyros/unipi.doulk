<?php
/*****************************************************************************************************
 * Security implementation																															*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Security_Exceptions Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\utils\System Current class for which building this exception class						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Security_Exceptions extends PhpJar_Exception {}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\utils;
/**
 * Security Class with mathematical functions																						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage utils																																*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Security
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const _SALT_LENGTH = 5;
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *  Security constructor,
	 *  @access public
	 * @return System object
	 */
	public function __construct(){}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $string
	 * @param string $salt
	 * @param integer $length
	 * @return string
	 */
	public static function _getSaltedHash($string, $salt = null, $length = self::_SALT_LENGTH)
	{
		if(is_null($salt))
			$salt = substr(md5(time()), 0, $length);
		else
			$salt = substr($salt, 0, $length);
		return $salt.sha1($salt.$string);
	}
	/**
	 *
	 * Enter description here ...
	 */
	public static function _createFormToken(){ return sha1(uniqid(mt_rand(), true)); }
}
?>