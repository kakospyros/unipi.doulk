<?php
/*****************************************************************************************************
 * Template Html Button implementation																										*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 *
 * @final HtmlButton_Exception Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see phpJar\servlets\ServletTemplate Current class for which building this exception class*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class HtmlButton_Exception extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\html;
use phpJar\utils\Html;

use phpJar\Exceptions as _exceptions;
/**
 * HtmlButton Class --	 																																	*
 * @final																																						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class HtmlButton extends HtmlTag
{
	const _ACTION_ADD = 1;
	const _ACTION_CLEAR = 2;
	const _ACTION_CLOSE = 3;
	const _ACTION_DELETE = 4;
	const _ACTION_DELETE_MULTI = 5;
	const _ACTION_DELETE_MULTI_2 = 6;
	const _ACTION_EDIT = 7;
	const _ACTION_NEW = 8;
	const _ACTION_NEXT = 9;
	const _ACTION_PREVIOUS = 10;
	const _ACTION_RESET = 11;
	const _ACTION_UPDATE = 12;
	public $type;
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
	final protected function _init(array $attrs)
	{
		if(!array_key_exists('value',$attrs))
			_exceptions\HtmlButton_Exception::throwException('button need value');
		if(!empty($attrs))
			foreach ($attrs as $attr => $value)
				$this->{$attr} = trim($value);
	}
	/**
	 *
	 * Create default buttons
	 * @param integer $action
	 * @param string $value
	 * @return HtmlButton instance
	 */
	public static function _createButton($action,$value = null,$class = null)
	{
		$attrs = array();
		$attrs['class'] = 'wpbutton';
		$attrs['type'] = 'button';
		if($action === self::_ACTION_ADD)
		{
			if(trim($value) == '')
				$value = 'add';
			$attrs['name'] = 'add-row';
		}
		elseif($action === self::_ACTION_CLEAR)
		{
			if(trim($value) == '')
				$value = 'clear';
			$attrs['name'] = 'clear';
			$attrs['type'] = 'reset';
		}
		elseif($action === self::_ACTION_CLOSE)
		{
			if(trim($value) == '')
				$value = 'close';
			$attrs['name'] = 'close';
			$attrs['type'] = null;
		}
		elseif($action === self::_ACTION_DELETE)
		{
			if(trim($value) == '')
				$value = 'delete';
			$attrs['name'] = 'delete-row';
		}
		elseif($action === self::_ACTION_DELETE_MULTI)
		{
			if(trim($value) == '')
				$value = 'delete';
			$attrs['name'] = 'delete-multi';
		}
		elseif($action === self::_ACTION_DELETE_MULTI_2)
		{
			if(trim($value) == '')
				$value = 'delete marked';
			$attrs['name'] = 'delete-multi';
		}
		elseif($action === self::_ACTION_EDIT)
		{
			if(trim($value) == '')
				$value = 'edit';
			$attrs['name'] = 'edit';
		}
		elseif($action === self::_ACTION_NEXT)
		{
			if(trim($value) == '')
				$value = '&rsaquo;';
			$attrs['name'] = 'next-row';
		}
		elseif($action === self::_ACTION_NEW)
		{
			if(trim($value) == '')
				$value = 'new';
			$attrs['name'] = 'new-form';
		}
		elseif($action === self::_ACTION_PREVIOUS)
		{
			if(trim($value) == '')
				$value = '&lsaquo;';
			$attrs['name'] = 'prev-row';
		}
		elseif($action === self::_ACTION_RESET)
		{
			if(trim($value) == '')
				$value = 'reset';
			$attrs['name'] = 'reset';
			$attrs['type'] = 'reset';
		}
		elseif($action === self::_ACTION_UPDATE)
		{
			if(trim($value) == '')
				$value = 'update';
			$attrs['name'] = 'update-row';
		}
		$attrs['value'] = $value;
		if(trim($class) != '')
			$attrs['class'] = $class;
		return new self($attrs);
	}

}
?>