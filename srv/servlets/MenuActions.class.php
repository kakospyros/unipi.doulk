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

use phpJar;
use phpJar\html as _html;
use project\scheme as _pscheme;

class MenuActions extends \phpJar\servlets\ServletTemplate
{

	CONST ACTION_ACCOUNT = 'account';
	CONST ACTION_ADMIN = 'admin';
	CONST ACTION_APPOINTMENT = 'appointment';
	CONST ACTION_APPOINTMENT_DOCTOR = 'appointment_doctor';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'MENU_ACTIONS';
	/**
	 * (non PHP-Doc)
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => self::ACTION_ACCOUNT,
																1 => self::ACTION_ADMIN,
																2 => self::ACTION_APPOINTMENT,
																3 => self::ACTION_APPOINTMENT_DOCTOR,
													);
	/**
	 *
	 * @var unknown_type
	 */
	protected static $_needAuthorize = true;
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
	 * @param \stdClass $data
	 */
	public function _runEvent(\stdClass $data)
	{
		switch ($data->action)
		{
			case self::ACTION_ACCOUNT:
			case self::ACTION_ADMIN:
			case self::ACTION_APPOINTMENT:
			case self::ACTION_APPOINTMENT_DOCTOR:
				break;
			default:
				return false;
		};
		$this->_setTemplateAsParent($data->action);
		$template_pos = (int)array_search($data->action,self::$_tplFile);
		$this->_setTemplate(self::$_tplFile[$template_pos]);
		return self::_getFormTpl();
	}

}
?>