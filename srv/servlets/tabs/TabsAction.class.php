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
namespace project\servlets\tabs;

use phpJar\servlets;

use phpJar;

class TabsAction	extends \phpJar\servlets\ServletScheme
									implements \phpJar\servlets\TabsActions
{

	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'TABS_ACTIONS';
	const REG_ACTION = 'action';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 * (non PHP-Doc)
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => 'action0',
													);
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/********************************
	 * Class templates method Area *
	 ********************************/
	/**
	 *
	 */
	public function _getFormTpl(\stdClass $args = null){return parent::_getFormTpl($args);}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @param unknown_type $attrs
	 */
	public function __construct($attrs = null)
	{
		parent::__construct($attrs);
		self::_setTemplateFolder('tabsactions');
	}
	/**
	 *
	 * @param \stdClass $data
	 */
	public function _runEvent(\stdClass $data)
	{
		try{
			static::_callRegistrySet(static::REG_ACTION,$data->action);
			return $this->_run_Event($data);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			$oError = \phpJar\Exceptions\PhpJar_Exception::return_Exception($e);
			return self::_getFormErrorTpl($oError->message);
		}
	}

	public function _run_Event(\stdClass $data)
	{
		$setting = new \stdClass();
		$index = $data->index;
		$action = $data->action;
		return self::_getFormTpl();
	}

}
?>