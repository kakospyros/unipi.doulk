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
namespace phpJar\Exceptions;
/**
 *
 *
 */
use phpJar;

class PhpJar_Exception extends \Exception
{
	/**
	 *
	 * @var unknown_type
	 */
	const exception = 'error';
	/**
	 *
	 * @var unknown_type
	 */
	const warning = 'warning';

	protected $extra_info;
	/**
	 *
	 * @param unknown_type $msg
	 */
	public function  __construct($msg, $type = self::exception)
	{
		parent::__construct($msg);
		phpJar\Logger::_create($msg,$type);
	}
	/**
	 *
	 *
	 */
	public function _getExtraInfo(){return $this->extra_info;}

	public function _createException( \phpJar\servlets\Servlet $oServlet, \stdClass $ArgList = null)
	{
		$info = $this->_getExtraInfo();
		if(isset($info->code) )
		{
			$field = 'default';
			$msg = null;
			$lang =  phpJar\Language::_getSpecificErrorLanguage($oServlet->_getReflectionName());
			if(is_object($lang) )
			{
				//db exceptions
				preg_match("/key '(\w+\S)'?\Z/i",$info->msg,$matches,PREG_OFFSET_CAPTURE);
				if(! ($matches[0] == '') )
				{
					$field = str_replace('key ',null,$matches[0][0]);
					$field = trim($field,'\'');
					$msg = sprintf($lang->{$field}[$info->code],$ArgList->{$field});
				}
			}
			else
			{
				$lang = phpJar\Language::_getSpecificErrorLanguage();
				$msg = sprintf($lang->generic);
			}

			if($msg == '')
				$msg = sprintf($lang->generic);

			$e = new self($msg);
		}
		else
			$e = $this;
		return $e;
	}

	public function attachAttr(\stdClass $attr)
	{
		if(!is_object($this->extra_info) )
			$this->extra_info = new \stdClass();
		if(!empty($attr))
		{
			foreach ($attr as $prop => $val)
				$this->extra_info->{$prop} = $val;
		}
	}
	/**
	 *
	 * @param PhpJar_Exception $e
	 * @param string $message
	 * @param array $function
	 */
	public static function return_Exception( PhpJar_Exception $e , $message = null, $function = null)
	{
		if(is_null($message))
			$message = $e->getMessage();
		$oError = new \stdClass();
		$oError->exception = true;
		$oError->message =  $message;
		return $oError;
	}

	public static function returnJSON_Exception( PhpJar_Exception $e , $message = null)
	{
		$oError = $e->return_Exception($e,$message);
		if(is_object($e->extra_info) && !empty($e->extra_info))
		{
			foreach ($e->extra_info as $prop => $val)
				$oError->{$prop} = $val;
		}
		return $oError;
	}

	public static function throwException($message = null, \stdClass $attrs = null)
	{
		$e = new static($message,self::exception);
		if(!empty($attrs))
			$e->attachAttr($attrs);
		throw $e;
	}

	public static function throwWarning($message = null)
	{
		phpJar\Logger::_create($message,self::warning);
	}

}