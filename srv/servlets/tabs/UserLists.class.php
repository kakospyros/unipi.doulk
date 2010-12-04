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

class UserLists extends TabsAction
{

	const _ACTION_DOCTOR = 'doctors';
	const _ACTION_FULL = 'users';
	const _ACTION_PATIENT = 'patients';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'USERS_TABS_ACTIONS';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => 'doctors',
																1 => 'patients',
																2 => 'users',
													);
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	protected function _setReflectionScheme($name = null){parent::_setReflectionScheme('Users');}
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
	public function _run_Event(\stdClass $data)
	{
		$this->buildRecords($data->action);
		return parent::_run_Event($data);
	}

	public function buildRecords($choice, $nested = true)
	{
		if($choice === self::_ACTION_DOCTOR)
		{
			$template_pos = 0;
			$oRecords = $this->_callScheme('_selectRecordByCategory',_pscheme\User_Category::DOCTOR);
		}
		elseif($choice === self::_ACTION_PATIENT)
		{
			$template_pos = 1;
			$oRecords = $this->_callScheme('_selectRecordByCategory',_pscheme\User_Category::PATIENT);
		}
		elseif($choice === self::_ACTION_FULL)
		{
			$template_pos = 2;
			$oRecords = $this->_callScheme('_selectFilterRecords');
		}
		else
			return false;
		$this->_setTemplate(self::$_tplFile[$template_pos]);
		$template = $this->_getRecordsFormTpl($oRecords);

		if($nested)
		{
			$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_NEW);
			$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_DELETE_MULTI);
			$this->_setNestedRecords($template);
		}
		else
			return $template;
	}

}
?>