<?php
/*****************************************************************************************************
 * Registry Class implementation																												*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Registry_Exceptions Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\Registry Current class for which building this exception class								*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Registry_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar;
use phpJar;
use phpJar\Exceptions as _exceptions;
/**
 * Registry Class --	 																																	*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Registry
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'PHPJAR_REGISTRY';
	/**
	 *
	 * @access protected
	 * @static
	 * @var unknown_type
	 */
	protected static $_store = array();
	/**
	 *
	 * @access protected
	 * @static
	 * @var unknown_type
	 */
	protected static $_instance;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 *
	 * @access public
	 * @static
	 * @param unknown_type $key
	 * @return
	 */
	public static function _get($key= null)
	{
		$instance = self::_getInstance();
		$instance->_checkOffset($key);
		return $instance->_returnOffset($key);
	}
	/**
	 *
	 * @final
	 * @access private
	 * @param unknown_type $key
	 */
	final private function _returnOffset($key){return self::$_store[$key];}
	/**
	 *
	 * @final
	 * @access private
	 * @return
	 */
	final private function _returnDump(){return self::$_store;}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 *
	 * @final
	 * @access public
	 * @param unknown_type $key
	 * @param unknown_type $value
	 * @param unknown_type $replace
	 * @return
	 */
	public static function _set($key,$value,$replace = true)
	{
		$instance = self::_getInstance();
		if($instance->_checkOffset($key,false))
		{
			if($replace)
				$instance->_setOffset($key,$value);
			return true;
		}
		$instance->_setOffset($key,$value);
		return true;
	}
	/**
	 *
	 * @param unknown_type $key
	 * @param unknown_type $value
	 */
	final private function _setOffset($key, $value)
	{
		self::$_store[$key] = $value;
		$_SESSION[self::REG_INDEX] = self::$_store;
		return true;
	}
	/**********************************
	 * Class implementation Area *
	 **********************************/
	/**
	 * @final
	 * @access private
	 * @return
	 */
	final private function __construct()
	{
		@session_start();
		self::$_store = (array)$_SESSION[self::REG_INDEX];
	}
	/**
	 * @final
	 * @access private
	 * @return
	 */
	final private function __clone(){}
	/**
	 *
	 * @final
	 * @access private
	 * @param unknown_type $key
	 * @param unknown_type $throw
	 * @uses /phpJar/utilities/Registry_Exceptions#throwException()
	 * @return
	 */
	final private function _checkOffset($key, $throw = true)
	{
		$instance = self::_getInstance();
		if(!isset(self::$_store[$key]))
		{
			if($throw)
				_exceptions\Registry_Exceptions::throwException(sprintf(phpJar\Language::_getSpecificLanguage()->error->registry->offSet,$key));
			return false;
		}
		return true;
	}
	/**
	 *
	 * @final
	 * @access private
	 * @param $key
	 * @return
	 */
	final private function _unsetOffset($key = null)
	{
		if(empty($key))
			self::$_store = array();
		else
		{
			$this->_checkOffset($key);
			unset(self::$_store[$key]);
		}
		$_SESSION[self::REG_INDEX] = self::$_store;
		return true;
	}
	/**
	 *
	 * @access public
	 * @static
	 * @return
	 */
	public static function _getInstance()
	{
		if(empty(self::$_instance))
			self::$_instance = new self();
		return self::$_instance;
	}
	/**
	 *
	 * @access public
	 * @static
	 * @param unknown_type $key
	 * @return
	 */
	public static function _unset($key = null)
	{
		$instance = self::_getInstance();
		return $instance->_unsetOffset($key);
	}
	/**
	 *
	 * @access public
	 * @static
	 * @return
	 */
	public static function _dump()
	{
		$instance = self::_getInstance();
		return $instance->_returnDump();
	}
}
?>