<?php
/*****************************************************************************************************
 * Template Html Tag implementation																										*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 *
 * @final HtmlTag_Exception Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see phpJar\servlets\ServletTemplate Current class for which building this exception class*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class HtmlTag_Exception extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\html;
use phpJar\Exceptions as _exceptions;
/**
 * HtmlTag Class --	 																																	*
 * @final																																						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class HtmlTag
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	public $id;
	public $events;
	public $value;
	public $name;
	public $title;
	public $class;
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/********************************
	 * Class templates method Area *
	 ********************************/
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @param array $attrs
	 */
	final public function __construct(array $attrs){static::_init($attrs);}
	/**
	 *
	 * @param array $attrs
	 */
	protected function _init(array $attrs)
	{
		if(!array_key_exists('value',$attrs))
			_exceptions\HtmlTag_Exception::throwException('element need value');
		if(!empty($attrs))
			foreach ($attrs as $attr => $value)
				$this->{$attr} = trim($value);
	}

}
?>