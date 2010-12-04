<?php
/*****************************************************************************************************
 * Servlet Scheme Implementation																											*
 * Extend Servlet Template, adding database scheme support															*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Servlet_Exceptions Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\servlets\ServletScheme Current class for which building this exception class	*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class ServletScheme_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\servlets;
use phpJar;
use phpJar\utils as _utils;
/**
 * ServletScheme Class --	 																														*
 * @abstract																																					*
 * @see phpJar\servlet\ServletTemplate 																								*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
abstract class ServletScheme extends ServletTemplate
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 * class name of the linked scheme
	 * @access protected
	 * @var string $_oReflectionScheme
	 */
	protected $_oReflectionScheme;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 * get linked scheme class name
	 * @final
	 * @access public
	 * @uses /phpJar/Servlet/ServletScheme#$_oReflectionScheme
	 * @return null
	 */
	final public function _getReflectionScheme(){return $this->_oReflectionScheme;}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
/**
	 * attach scheme class to servlet class
	 * the scheme class must be included in namespace which is defined on config file
	 * @param string $name
	 * @access protected
	 * @uses /phpJar/Config#SCHEME_NAME_SPACE
	 * @uses /phpJar/Servlet/ServletScheme#$_oReflectionScheme
	 * @return null
	 */
	protected function _setReflectionScheme($name = null)
	{
		if(trim($name)== '')
			$name = $this->_getReflectionName();
		$schemeClass = sprintf('%s%s',phpJar\SCHEME_NAME_SPACE,$name);
		if(class_exists($schemeClass))
			$this->_oReflectionScheme = new \ReflectionClass($schemeClass);
		elseif( $name != $this->_getReflectionName())
		{
			$schemeClass = sprintf('%s%s',phpJar\SCHEME_NAME_SPACE,$name);
			$this->_oReflectionScheme = new \ReflectionClass($schemeClass);
		}
		else
			$this->_oReflectionScheme = null;
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * ServletScheme constructor,
	 * @access public
	 * @uses /phpJar/Servlet/Servlet#_setReflectionScheme()
	 * @uses /phpJar/Servlet#__construct()
	 * @return mixed a class instance
	 */
	public function __construct($attrs = null)
	{
		parent::__construct($attrs);
		$this->_setReflectionScheme();
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param string $method
	 * @param mixed $arguments
	 * @return method result
	 */
	final public function _callScheme($method)
	{
		$args = func_get_args();
		$count = func_num_args()-1;
		array_shift($args);
		if(!$this->_oReflectionScheme->hasMethod($method))
		{
			//throw message here
			return false;
		}
		$oReflectionMethod = $this->_oReflectionScheme->getMethod($method);
		$instance = ($oReflectionMethod->isStatic())?null:$this->_oReflectionScheme->newInstance();
		if($count > 1)
			return $oReflectionMethod->invokeArgs($instance,$args);
		elseif($count == 1)
			return $oReflectionMethod->invoke($instance,$args[0]);
		else
			return $oReflectionMethod->invoke($instance);
	}

}
?>