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

use phpJar;
use phpJar\utils as _utils;
use project\servlets\tabs as _ptabs;
use project\scheme as _pscheme;
use project\Exceptions as _pexceptions;

class Users	extends FormActionsExpand
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'USERS_FORM_ACTIONS';

	const _CHILD_ID = 'account';

	protected static $_tplFile = array(
																0 => 'action0',
																1 => 'view',
																2 => 'tab',
																3 => 'toggle',
													);
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::_buildDataRecords($response,$selected_fields)
	 */
	public function _buildDataRecords($response, array $selected_fields)
	{
		$action = _ptabs\UserLists::_callRegistryGet(_ptabs\UserLists::REG_ACTION);
		$inner = $where = $category = null;
		if($action == _ptabs\UserLists::_ACTION_DOCTOR)
			$category = _pscheme\User_Category::DOCTOR;
		elseif($action == _ptabs\UserLists::_ACTION_PATIENT)
			$category = _pscheme\User_Category::PATIENT;
		if(!is_null($category))
		{
			$inner = 'INNER JOIN user_category AS uc ON uc.parent_id = t.id';
			$where = sprintf(' AND uc.child_id = %d',$category);
		}

		$oRecords = $this->_callScheme('_selectInnerFilterArray',$inner,$where,$selected_fields);
		if($oRecords)
		{
			foreach ($oRecords as &$record)
			{
				$time = array_pop($record);
				array_push($record,_utils\DT::_getLocalString($time));
			}
			$response->updateDataTable->records= $oRecords;
		}
		else
			$response->updateDataTable->records= array();
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::_setOpenMethod($method = self::_METHOD_SINGLE)
	 */
	protected function _setOpenMethod($method = self::_METHOD_TABS){parent::_setOpenMethod($method);}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::setWrapperSettings()
	 */
	protected function _setWrapperSettings(\stdClass $data)
	{
		$wrapper_options = parent::_setWrapperSettings($data);
		$wrapper_options->minWidth = 800;
		return $wrapper_options;
	}

	public static function _toggle(\stdClass $data)
	{
		if($data->val == _pscheme\User_Category::DOCTOR)
		{
			$oObject = new self();
			$oObject->_setTemplate(self::$_tplFile[3]);
			$oScheme = new _pscheme\Specialty();
			$oSpeciality = $oScheme->_selectFilterArray(null,array('t.id','t.name'),array(),true);
			$oObject->_setTemplateSelectOptions('specialty',$oSpeciality);
			$oScheme = new _pscheme\Visiting_Hours();
			$oHours = $oScheme->_selectFilterArray(null,array('t.id','t.name'),array(),true);
			$oObject->_setTemplateSelectOptions('hours',$oHours);
			return $oObject->_getFormTpl();
		}
		return ' ';
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_ADD()
	 */
	protected function event_ADD(\stdClass $data)
	{
		if(!($data->data->passwd == $data->data->passwd_confirm))
			_pexceptions\Users_Exceptions::throwException('password missmatch');
		$data->data->passwd = _utils\Security::_getSaltedHash($data->data->passwd);
		unset($data->data->passwd_confirm);

		list($response,$oRecord) = parent::event_ADD($data,array('t.id','t.surname','t.given_name','t.amka','t.description','t.modify_time'),false);
		$form_data = (array)$data->data;
		$response->updateDataTable->notification->message = 'You have successfully add a user !';
		//check user category
		$action = _ptabs\UserLists::_callRegistryGet(_ptabs\UserLists::REG_ACTION);
		if($action == _ptabs\UserLists::_ACTION_FULL)
			$selectedCategory = (array)$form_data['category'];
		elseif($action == _ptabs\UserLists::_ACTION_DOCTOR)
			$selectedCategory = array(_pscheme\User_Category::DOCTOR);
		elseif($action == _ptabs\UserLists::_ACTION_PATIENT)
			$selectedCategory = array(_pscheme\User_Category::PATIENT);
		//doctor extra information
		$selectedSpecialty = array();
		$selectedHours = array();
		if(!(array_search(_pscheme\User_Category::DOCTOR,$selectedCategory) === false) )
		{
			$selectedSpecialty = (array)$form_data['specialty'];
			$selectedHours = (array)$form_data['hours'];
		}

		$where = sprintf(' AND t.parent_id = %d',$oRecord->id);
		//user category
		$oScheme = new _pscheme\User_Category();
		$oScheme->_deleteRecordsNotInclude($where,$selectedCategory);
		if(!empty($selectedCategory))
			$oScheme->_insertMultiRecords($oRecord->id,$selectedCategory);
		//doctor specialty
		$oScheme = new _pscheme\Doctor_Specialty();
		$oScheme->_deleteRecordsNotInclude($where,$selectedSpecialty);
		if(!empty($selectedSpecialty))
			$oScheme->_insertMultiRecords($oRecord->id,$selectedSpecialty);
		//doctor visiting hours
		$oScheme = new _pscheme\Doctor_Hours();
		$oScheme->_deleteRecordsNotInclude($where,$selectedHours);
		if(!empty($selectedHours))
			$oScheme->_insertMultiRecords($oRecord->id,$selectedHours);

		$this->_buildDataRecords($response,array('t.id','t.surname','t.given_name','t.amka','t.description','t.modify_time') );
		$response->populate = true;
		$response->_htmlTag['_tagValue']['category'] = $selectedCategory;
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_DELETE()
	 */
	protected function event_DELETE(\stdClass $data)
	{
		list($response,$oRecords) = parent::event_DELETE($data,array('t.id','t.surname','t.given_name','t.amka','t.description','t.modify_time'));
		if(!($response->close === true) )
		{
			$where = sprintf(' AND t.parent_id = %d',$response->_htmlTag['_tagValue']['id']);
			$fields = array('t.child_id');
			$oScheme = new _pscheme\User_Category();
			$response->_htmlTag['_tagValue']['category'] = $oScheme->_selectFilterArray($where,$fields);
			if($response->_htmlTag['_tagValue']['category'] == _pscheme\User_Category::DOCTOR)
			{
				$oScheme = new _pscheme\Doctor_Specialty();
				$response->_htmlTag['_tagValue']['specialty'] = $oScheme->_selectFilterArray($where,$fields);
				$oScheme = new _pscheme\Doctor_Hours();
				$response->_htmlTag['_tagValue']['hours'] = $oScheme->_selectFilterArray($where,$fields);
				$toggle_data = new \stdClass();
				$toggle_data->val = _pscheme\User_Category::DOCTOR;
//				$this->_setNestedTemplate('doctor_info',self::_toggle($toggle_data));
			}
		}
		$response->updateDataTable->notification->message = 'You have successfully delete a user !';
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_DELETE_MULTI()
	 */
	protected function event_DELETE_MULTI(\stdClass $data)
	{
		list($response,$oRecords) = parent::event_DELETE_MULTI($data,array('t.id','t.given_name','t.surname','t.amka','t.description','t.modify_time'));
		$response->updateDataTable->notification->message = sprintf('You have successfully delete %d user(s) !',count($data->data));
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_NEW_FORM()
	 */
	protected function event_NEW_FORM(\stdClass $data)
	{
		parent::event_NEW_FORM($data);
		$data->title = 'User Accounts';
		$action = _ptabs\UserLists::_callRegistryGet(_ptabs\UserLists::REG_ACTION);
		if($action == _ptabs\UserLists::_ACTION_FULL)
		{
			$oScheme = new _pscheme\Category();
			$oCategory = $oScheme->_selectFilterArray(null,array('t.id','t.name'),array(),true);
			$this->_setTemplateSelectOptions('category',$oCategory);
		}
		else
			$this->_setTemplateArgs('noCategory',true);
		$this->_setTemplate(self::$_tplFile[2]);
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_NEW_TAB_FORM()
	 */
	protected function event_NEW_FORM_TAB(\stdClass $data)
	{
		parent::event_NEW_TAB_FORM($data);
		$this->_setTemplateArgs('refBasic',0);
		$data->title = 'User Accounts';
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_NEXT_ROW()
	 */
	protected function event_NEXT_ROW($data)
	{
		$response = parent::event_NEXT_ROW($data);
		$action = _ptabs\UserLists::_callRegistryGet(_ptabs\UserLists::REG_ACTION);
		$form_data_extra = array(
			'passwd'=>_pscheme\Users::PASSWDMASK,
			'passwd_confirm'=>_pscheme\Users::PASSWDMASK
		);
		if($action == _ptabs\UserLists::_ACTION_FULL)
		{
			$where = sprintf(' AND t.parent_id = %d',$response->_htmlTag['_tagValue']['id']);
			$fields = array('t.child_id');
			$oScheme = new _pscheme\User_Category();
			$response->_htmlTag['_tagValue']['category'] = $oScheme->_selectFilterArray($where,$fields);
			if($response->_htmlTag['_tagValue']['category'] == _pscheme\User_Category::DOCTOR)
			{
				$oScheme = new _pscheme\Doctor_Specialty();
				$response->_htmlTag['_tagValue']['specialty'] = $oScheme->_selectFilterArray($where,$fields);
				$oScheme = new _pscheme\Doctor_Hours();
				$response->_htmlTag['_tagValue']['hours'] = $oScheme->_selectFilterArray($where,$fields);
				$toggle_data = new \stdClass();
				$toggle_data->val = _pscheme\User_Category::DOCTOR;
//				$this->_setNestedTemplate('doctor_info',self::_toggle($toggle_data));
			}
		}
		$response->_htmlTag['_tagValue']['passwd'] = _pscheme\Users::PASSWDMASK;
		$response->_htmlTag['_tagValue']['passwd_confirm'] = _pscheme\Users::PASSWDMASK;
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_PREV_ROW()
	 */
	protected function event_PREV_ROW($data)
	{
		$response = parent::event_PREV_ROW($data);
		$action = _ptabs\UserLists::_callRegistryGet(_ptabs\UserLists::REG_ACTION);
		$form_data_extra = array(
			'passwd'=>_pscheme\Users::PASSWDMASK,
			'passwd_confirm'=>_pscheme\Users::PASSWDMASK
		);
		if($action == _ptabs\UserLists::_ACTION_FULL)
		{
			$where = sprintf(' AND t.parent_id = %d',$response->_htmlTag['_tagValue']['id']);
			$fields = array('t.child_id');
			$oScheme = new _pscheme\User_Category();
			$response->_htmlTag['_tagValue']['category'] = $oScheme->_selectFilterArray($where,$fields);
		}
		$response->_htmlTag['_tagValue']['passwd'] = _pscheme\Users::PASSWDMASK;
		$response->_htmlTag['_tagValue']['passwd_confirm'] = _pscheme\Users::PASSWDMASK;
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_UPDATE()
	 */
	protected function event_UPDATE(\stdClass $data)
	{

		if(!($data->data->passwd == $data->data->passwd_confirm))
			_pexceptions\Users_Exceptions::throwException('password missmatch');
		unset($data->data->passwd_confirm);

		$oRegistry = self::_callRegistryGet(self::_REG_RECORD_ID);
		if(!($oRegistry > 0))
			_pexceptions\FormActions_Exceptions::throwException('record update incorrect selection');

		$where = sprintf(' AND t.id = %d',$oRegistry);
		$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);
		$oRecord->passwd = $data->data->passwd;
		$oRecord->encryptPasswd();
		unset($data->data->passwd);

		list($response,$oRecord) = parent::event_UPDATE($data,array('t.id','t.surname','t.given_name','t.amka','t.description','t.modify_time'),$oRecord);
		$form_data = (array)$data->data;
		$response->updateDataTable->notification->message = 'You have successfully update a user !';
		//update linked sector
		$action = _ptabs\UserLists::_callRegistryGet(_ptabs\UserLists::REG_ACTION);
		if($action == _ptabs\UserLists::_ACTION_FULL)
		{
			$selectedCategory = (array)$form_data['category'];
			$where = sprintf(' AND t.parent_id = %d',$oRecord->id);
			$oScheme = new _pscheme\User_Category();
			$oScheme->_deleteRecordsNotInclude($where,$selectedCategory);
			if(!empty($selectedCategory))
				$oScheme->_insertMultiRecords($oRecord->id,$selectedCategory);
		}
		$response->populate = true;
		$response->_htmlTag['_tagValue']['passwd'] = _pscheme\Users::PASSWDMASK;
		$response->_htmlTag['_tagValue']['passwd_confirm'] = _pscheme\Users::PASSWDMASK;
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_UPDATE_FORM()
	 */
	protected function event_UPDATE_FORM(\stdClass $data)
	{
		$form_data = parent::event_UPDATE_FORM($data);
		$data->title = 'User Accounts';
		$form_data_extra = array(
			'passwd'=>_pscheme\Users::PASSWDMASK,
			'passwd_confirm'=>_pscheme\Users::PASSWDMASK
		);
		$this->_setTemplateMultiTagValues($form_data_extra);
		$action = _ptabs\UserLists::_callRegistryGet(_ptabs\UserLists::REG_ACTION);

		if($action == _ptabs\UserLists::_ACTION_FULL)
		{
			$where = sprintf(' AND t.parent_id = %d',$data->data->id);
			$fields = array('t.child_id');

			$oScheme = new _pscheme\User_Category();
			$form_data_extra['category'] = $oScheme->_selectFilterArray($where,$fields);

			$oScheme = new _pscheme\Category();
			$oCategory = $oScheme->_selectFilterArray(null,array('t.id','t.name'),array(),true);
			$this->_setTemplateSelectOptions('category',$oCategory);

			if( !(array_search(_pscheme\User_Category::DOCTOR,$form_data_extra['category']) === false) )
			{
				$oScheme = new _pscheme\Doctor_Specialty();
				$form_data_extra['specialty'] = $oScheme->_selectFilterArray($where,$fields);
				$oScheme = new _pscheme\Doctor_Hours();
				$form_data_extra['hours'] = $oScheme->_selectFilterArray($where,$fields);
				$toggle_data = new \stdClass();
				$toggle_data->val = _pscheme\User_Category::DOCTOR;
				$this->_setNestedTemplate('doctor_info',self::_toggle($toggle_data));
			}
			$this->_setTemplateMultiTagValues($form_data_extra);
		}
		else
			$this->_setTemplateArgs('noCategory',true);
		$this->_setTemplate(self::$_tplFile[2]);
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.FormActions::event_UPDATE_TAB_FORM()
	 */
	protected function event_UPDATE_FORM_TAB(\stdClass $data)
	{
		parent::event_UPDATE_FORM_TAB($data);
		$this->_setTemplateArgs('refBasic',1);
		$data->title = 'User Accounts';
	}

}
?>