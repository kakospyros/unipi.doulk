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
use project\scheme as _pscheme;
use project\servlets as _pservlets;
use phpJar\html as _html;
use project\servlets\forms as _pforms;

class Appointment	extends TabsAction
{

	const _ACTION_DAY = 'day';
	const _ACTION_MONTH = 'month';
	const _ACTION_WEEK = 'week';
	const _ACTION_YEAR = 'year';
	const _ACTION_ARCHIVED = 'archived';
	const _ACTION_DAY_DOCTOR = 'day_doctor';
	const _ACTION_MONTH_DOCTOR = 'month_doctor';
	const _ACTION_WEEK_DOCTOR = 'week_doctor';
	const _ACTION_YEAR_DOCTOR = 'year_doctor';
	const _ACTION_ARCHIVED_DOCTOR = 'archived_doctor';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'APPOINTMENT_TABS_ACTIONS';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => 'day',
																1 => 'week',
																2 => 'month',
																3 => 'year',
																4 => 'archived',
																5 => 'day_doctor',
																6 => 'week_doctor',
																7 => 'month_doctor',
																8 => 'archived_doctor',
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
	 * @param \stdClass $data
	 */
	public function _run_Event(\stdClass $data)
	{
		$this->buildRecords($data->action);
		return parent::_run_Event($data);
	}

	public function buildRecords($choice, $nested = true)
	{
		$oServlet = new _pforms\Users();
		$where = sprintf(' AND t.status = 1');
		$userCategory = _pservlets\Authenticated::_callRegistryGet('userCategory');
		$userID = _pservlets\Authenticated::_callRegistryGet('userID');
		if($userCategory == _pscheme\User_Category::DOCTOR)
			$where .= sprintf(' AND t.parent_id= %d',$userID);
		else
			$where .= sprintf(' AND t.parent2_id= %d',$userID);

		if($choice === self::_ACTION_DAY)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_DAY,$where);
		elseif($choice === self::_ACTION_DAY_DOCTOR)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_DAY,$where);
		elseif($choice === self::_ACTION_WEEK)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_WEEK,$where);
		elseif($choice === self::_ACTION_WEEK_DOCTOR)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_WEEK,$where);
		elseif($choice === self::_ACTION_MONTH)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_MONTH,$where);
		elseif($choice === self::_ACTION_MONTH_DOCTOR)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_MONTH,$where);
		elseif($choice === self::_ACTION_YEAR)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_YEAR,$where);
		elseif($choice === self::_ACTION_YEAR_DOCTOR)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_YEAR,$where);
		elseif($choice === self::_ACTION_ARCHIVED)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_ARCHIVED,$where);
		elseif($choice === self::_ACTION_ARCHIVED_DOCTOR)
			$oRecords = $this->_callScheme('_selectInnerFilterRecordsByTime',_pscheme\Appointment::_ACTION_YEAR,$where);
		else
			return false;
		$template_pos = array_search($choice,self::$_tplFile);
		$this->_setTemplate(self::$_tplFile[$template_pos]);
		$template = $this->_getRecordsFormTpl($oRecords);

		if($nested)
		{
			if($userCategory == _pscheme\User_Category::PATIENT)
			{
				$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_NEW);
				$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_DELETE_MULTI);
			}
			$this->_setNestedRecords($template);
		}
		else
			return $template;
	}

}
?>