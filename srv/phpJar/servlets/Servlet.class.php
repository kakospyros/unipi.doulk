<?php
/*****************************************************************************************************
 * Servlet Implementation																															*
 * Basic Class for servlet classes,  includes basic method for those classes									*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Servlet_Exceptions Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\servlets\Servlet Current class for which building this exception class					*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Servlet_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\servlets;
use phpJar\Exceptions as _exceptions;
use phpJar;
/**
 * Servlet Class --	 																																		*
 * @abstract																																					*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
abstract class Servlet	implements phpJar\Registry_I
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 *
	 * @var unknown_type
	 */
	const REG_INDEX = 'SERVLET_INDEX';
	/**
	 * A Flag, which declare if the using for class need authorization or not
	 * if it set to true then when call this class instance
	 * the SESSION checked if it set or not
	 * @static
	 * @access protected
	 * @see /phpJar/Authenticated#_sessionCheck()
	 * @var boolean $_needAuthorize
	 */
	protected static $_needAuthorize = true;
	/**
	 * Array with all neccessery value which servlet need to store for future use
	 * @see /phpJar/servlet/Servlet#$_currentKeys
	 * @var array $_storedKey
	 */
	public static $_storedKeys = array();
	/**
	 * Reflection object for current class
	 * @var unknown_type
	 */
	public $_oReflectionClass;
	/********************************
	 * Class Registry methods Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistryGet($key)
	 * @uses /phpJar/Register#_get($key = null)
	 * @return mixed requested index value
	 */
	public static function _callRegistryGet($key = null)
	{
		$oReg = array();
		phpJar\Registry::_set(static::REG_INDEX,$oReg,false);
		$oReg = phpJar\Registry::_get(static::REG_INDEX);
		if(!is_null($key))
			return $oReg[$key];
		return $oReg;
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistrySet($key,$value)
	 * @return null
	 */
	public static function _callRegistrySet($key, $value)
	{
		$oReg = array();
		phpJar\Registry::_set(static::REG_INDEX,$oReg,false);
		$oReg = phpJar\Registry::_get(static::REG_INDEX);
		$oReg[$key] = $value;
		phpJar\Registry::_set(static::REG_INDEX,$oReg);
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistryUnSet($key)
	 * @return null
	 */
	public static function _callRegistryUnSet($key)
	{
		$oReg = static::_callRegistryGet();
		unset($oReg[$key]);
		phpJar\Registry::_set(static::REG_INDEX,$oReg);
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * Scheme constructor,
	 * check the security level for using this class
	 * @access public
	 * @param mixed $attrs
	 * @uses /phpJar/Servlet/Servlet#$_needAuthorize
	 * @uses /phpJar/Authenticated#_sessionCheck()
	 * @return mixed a class instance
	 */
	public function __construct ($attrs = null)
	{
		if(static::$_needAuthorize)
			phpJar\Authenticated::_sessionCheck();
		$this->_oReflectionClass = new \ReflectionClass(get_called_class());
	}
	/**
	 *
	 * @param boolean $lower
	 */
	public function _getReflectionName($lower = null)
	{
		if($lower === true)
			return mb_strtolower($this->_oReflectionClass->getShortName(), 'UTF-8');
		if($lower === false)
			return mb_strtoupper($this->_oReflectionClass->getShortName(), 'UTF-8');
		return $this->_oReflectionClass->getShortName();
	}
	/**
	 *
	 * @param $class
	 */
	protected static function get_called_method($class)
	{
		$attrs = debug_backtrace();
		if(!empty($attrs))
		{
			foreach ($attrs as $debug)
				if($debug['class'] == $class)
					return $debug['function'];
		}
		_exceptions\Servlet_Exceptions::throwException('t-t-t');
	}
}
?>