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

use phpJar;
use phpJar\html as _html;
use project\servlets\forms as _pforms;
use project\scheme as _pscheme;

class UserVCard extends TabsAction
{

	const _ACTION_ADDRESS = 'address';
	const _ACTION_BASIC = 'basic';
	const _ACTION_CONTACT = 'contact';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'USERVCARD_TABS_ACTIONS';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	protected function _setReflectionScheme($name = null){parent::_setReflectionScheme('Users');}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @param \stdClass $data
	 */
	public function _run_Event(\stdClass $data)
	{
		$index = $data->index;
		$action = $data->action;
		if($action == self::_ACTION_ADDRESS)
		{
			$oServlet = new _pforms\User_Address();
			return $oServlet->_getFormTpl();
		}
		elseif($action == self::_ACTION_BASIC)
		{
			$oServlet = new _pforms\Users();
			return $oServlet->_runTabEvent();
		}
		elseif($action == self::_ACTION_CONTACT)
		{
			$oServlet = new _pforms\User_Contact();
			return $oServlet->_getFormTpl();
		}
		return null;
	}

}
?>