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
namespace project\servlets\forms;

use phpJar\servlets;

use phpJar;
use phpJar\utils as _utils;
use project\servlets\tabs as _ptabs;
use project\scheme as _pscheme;
use project\servlets as _pservlets;
use project\Exceptions as _pexceptions;

class Appointment	extends FormActionsExpand
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'APPOINTMENT_FORM_ACTIONS';

	const _CHILD_ID = 'appointment';

	protected static $_tplFile = array(
															0 => 'action0',
															1 => 'action1',
												);

	/********************************
	 * Class implementation Area *
	 ********************************/
/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::_buildDataRecords($response,$selected_fields)
	 */
	public function _buildDataRecords($response, array $selected_fields = array('t.id','t.reference','concat(d.surname,\' \',d.given_name) AS doctor','t.schedule_time','t.modify_time'))
	{
		$action = _ptabs\Appointment::_callRegistryGet(_ptabs\Appointment::REG_ACTION);
		$time = '*';
		$where = sprintf(' AND t.status = 1');

		if($action == _ptabs\Appointment::_ACTION_DAY)
			$time = _pscheme\Appointment::_ACTION_DAY;
		elseif($action == _ptabs\Appointment::_ACTION_WEEK)
			$time = _pscheme\Appointment::_ACTION_WEEK;
		elseif($action == _ptabs\Appointment::_ACTION_MONTH)
			$time = _pscheme\Appointment::_ACTION_MONTH;
		elseif($action == _ptabs\Appointment::_ACTION_YEAR)
			$time = _pscheme\Appointment::_ACTION_YEAR;

		$oRecords = $this->_callScheme('_selectInnerFilterArrayByTime',$time,$where,$selected_fields);
		if($oRecords)
		{
			foreach ($oRecords as &$record)
			{
				$time = array_pop($record);
				$schedule_time = array_pop($record);
				array_push($record,_utils\DT::_getLocalString($schedule_time));
				array_push($record,_utils\DT::_getLocalString($time));
			}
			$response->updateDataTable->records= $oRecords;
		}
		else
			$response->updateDataTable->records= array();
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_ADD()
	 */
	protected function event_ADD(\stdClass $data, array $selected_fields = array('t.id','t.reference','concat(d.surname,\' \',d.given_name) AS doctor','t.schedule_time','t.modify_time'),$buildData = true)
	{
		$data->data->parent2_id = _pservlets\Authenticated::_callRegistryGet('userID');
		$dt = new _utils\DT();
		$dt->_convertTzToGmt();
		$isoDate = $dt->_convertDateToIso($data->data->schedule_time)->date;
		$data->data->schedule_time = $dt->_convertDateToIso()->date;
		$data->data->reference = _pservlets\Reference_Counters::build(_pscheme\Reference_Counters::_TYPE_APPOINTMENT,$this);
		list($response,$oRecord) = parent::event_ADD($data,$selected_fields,false);
		$form_data = (array)$data->data;
		$response->updateDataTable->notification->message = 'You have successfully add an appointment !';

		$this->_buildDataRecords($response,$selected_fields);
		$response->populate = true;

		$oScheme = new _pscheme\Specialty();
		$oSpecialty = $oScheme->_selectFilterArray(null,array('t.id','t.name'));
		$response->_htmlTag['_selectOptions']['specialty'] = $oSpecialty;
		$oScheme = new _pscheme\Visiting_Hours();
		$oHours = $oScheme->_selectFilterArray(null,array('t.id','t.name'),array(),true);
		$response->_htmlTag['_selectOptions']['hours'] = $oHours;

		$doctorAttrs = new \stdClass();
		$doctorAttrs->specialty = key($oSpecialty);
		$doctorAttrs->hours = key($oHours);
		$doctorList = _pservlets\Utils::fetchDoctor($doctorAttrs);
		$response->_htmlTag['_selectOptions']['parent_id'] = $doctorList->options->fetchDoctor;
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_DELETE()
	 */
	protected function event_DELETE(\stdClass $data, array $selected_fields = array('t.id','t.reference','concat(d.surname,\' \',d.given_name) AS doctor','t.schedule_time','t.modify_time'))
	{
		list($response,$oRecords) = parent::event_DELETE($data,$selected_fields);
		if(!($response->close === true) )
		{
			$oScheme = new _pscheme\Specialty();
			$oSpecialty = $oScheme->_selectFilterArray(null,array('t.id','t.name'));
			$response->_htmlTag['_selectOptions']['specialty'] = $oSpecialty;
			$oScheme = new _pscheme\Visiting_Hours();
			$oHours = $oScheme->_selectFilterArray(null,array('t.id','t.name'),array(),true);
			$response->_htmlTag['_selectOptions']['hours'] = $oHours;

			$doctorAttrs = new \stdClass();
			$doctorAttrs->specialty = $response->_htmlTag['_tagValue']['specialty'];
			$doctorAttrs->hours = $response->_htmlTag['_tagValue']['hours'];
			$doctorList = _pservlets\Utils::fetchDoctor($doctorAttrs);
			$response->_htmlTag['_selectOptions']['parent_id'] = $doctorList->options->fetchDoctor;
			$response->_htmlTag['_tagValue']['schedule_time'] = _utils\DT::_getLocalString($response->_htmlTag['_tagValue']['schedule_time']);
		}
		$response->updateDataTable->notification->message = 'You have successfully delete an appointment !';
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_DELETE_MULTI()
	 */
	protected function event_DELETE_MULTI(\stdClass $data, array $selected_fields = array('t.id','t.reference','concat(d.surname,\' \',d.given_name) AS doctor','t.schedule_time','t.modify_time'))
	{
		list($response,$oRecords) = parent::event_DELETE_MULTI($data,$selected_fields);
		$response->updateDataTable->notification->message = sprintf('You have successfully delete %d appointment(s) !',count($data->data));
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_NEW_FORM()
	 */
	protected function event_NEW_FORM(\stdClass $data)
	{
		parent::event_NEW_FORM($data);
		$data->reference_type = _pscheme\Reference_Counters::_TYPE_APPOINTMENT;
		$reference = _pservlets\Reference_Counters::build($data->reference_type,$this);
		$this->_setTemplateTagValues('reference',$reference);

		$data->title = 'Appointment';
		$oScheme = new _pscheme\Specialty();
		$oSpecialty = $oScheme->_selectFilterArray(null,array('t.id','t.name'));
		$this->_setTemplateSelectOptions('specialty',$oSpecialty);
		$oScheme = new _pscheme\Visiting_Hours();
		$oHours = $oScheme->_selectFilterArray(null,array('t.id','t.name'));
		$this->_setTemplateSelectOptions('hours',$oHours);

	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_NEXT_ROW()
	 */
	protected function event_NEXT_ROW($data)
	{
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_PREV_ROW()
	 */
	protected function event_PREV_ROW($data)
	{
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_UPDATE()
	 */
	protected function event_UPDATE(\stdClass $data)
	{

	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_UPDATE_FORM()
	 */
	protected function event_UPDATE_FORM(\stdClass $data)
	{
		$form_data = parent::event_UPDATE_FORM($data);
		$data->title = 'Appointment';

		$this->_setTemplateTagValue('schedule_time',_utils\DT::_getLocalString($form_data['schedule_time']));

		$oScheme = new _pscheme\Specialty();
		$oSpecialty = $oScheme->_selectFilterArray(null,array('t.id','t.name'));
		$this->_setTemplateSelectOptions('specialty',$oSpecialty);
		$oScheme = new _pscheme\Visiting_Hours();
		$oHours = $oScheme->_selectFilterArray(null,array('t.id','t.name'));
		$this->_setTemplateSelectOptions('hours',$oHours);

		$doctorAttrs = new \stdClass();
		$doctorAttrs->specialty = $form_data['specialty'];
		$doctorAttrs->hours = $form_data['hours'];
		$doctorList = _pservlets\Utils::fetchDoctor($doctorAttrs);
//		$form_data['parent_id'] = $doctorList->options->fetchDoctor;
		$this->_setTemplateSelectOptions('parent_id',$doctorList->options->fetchDoctor);
		$form_data_extra = array(
			'specialty' => (array)$form_data['specialty'],
			'hours' => (array)$form_data['hours'],
			'parent_id' => (array)$form_data['parent_id'],
			'schedule_time' => _utils\DT::_getLocalString($form_data['schedule_time'])
		);
		$this->_setTemplateMultiTagValues($form_data_extra);
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_VIEW_FORM()
	 */
	protected function event_VIEW_FORM(\stdClass $data)
	{
		$form_data = parent::event_VIEW_FORM($data);
		$data->title = 'Appointment';

		$this->_setTemplateTagValue('schedule_time',_utils\DT::_getLocalString($form_data['schedule_time']));

		$where = sprintf(' AND t.id = %d',$form_data['parent2_id']);
		$oScheme = new _pscheme\Users();
		$oPatient = $oScheme->_selectFilterRecordsingle($where);

		$form_data_extra = array(
			'patient' => sprintf("%s %s",$oPatient->surname,$oPatient->given_name),
			'schedule_time' => _utils\DT::_getLocalString($form_data['schedule_time'])
		);
		$this->_setTemplateMultiTagValues($form_data_extra);
	}

}
?>