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
use project\scheme as _pscheme;
use project\servlets\forms as _pforms;

class User_Contact extends Contacts
{

	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'User_CONTACT_SERVLET';
	/********************************
	 * Class templates method Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.Contacts::_getFormTpl()
	 */
	public function _getFormTpl(\stdClass $args = null)
	{
		$oScheme = new _pscheme\Users();
		$oUser = $oScheme->_selectFilterRecordsingle(sprintf(' AND t.id = %d',$this->loadParentId()) );
		$form_data = array();
		if(!empty($oUser))
			$form_data = $oUser->_getAttrs();
		$this->_setTemplateMultiTagValues($form_data);
		return parent::_getFormTpl($args);
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.Contacts::event_ADD()
	 */
	protected function event_ADD(\stdClass $data,array $selected_fields = array('t.id','t.name','t.position','t.telephone','t.fax','t.mobile','t.email','t.modify_time'))
	{
		list($response,$oContact,$oRecord) = parent::event_ADD($data,$selected_fields);
		$form_data = (array)$data->data;
		$response->updateDataTable->notification->message = 'You have successfully add a user contact !';
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.Contacts::event_DELETE()
	 */
	protected function event_DELETE(\stdClass $data,array $selected_fields = array('t.id','t.name','t.position','t.telephone','t.fax','t.mobile','t.email','t.modify_time'))
	{
		list($response,$oContact,$oRecord) = parent::event_DELETE($data,$selected_fields);
		$form_data = (array)$data->data;
		$response->updateDataTable->notification->message = 'You have successfully delete a user contact !';
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.Contacts::event_DELETE_MULTI()
	 */
	protected function event_DELETE_MULTI(\stdClass $data,array $selected_fields = array('t.id','t.name','t.position','t.telephone','t.fax','t.mobile','t.email','t.modify_time'))
	{
		$response = parent::event_DELETE_MULTI($data,$selected_fields);
		$response->updateDataTable->notification->message = sprintf('You have successfully delete %d user contact(s) !',count($data->data));
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.Contacts::event_UPDATE()
	 */
	protected function event_UPDATE(\stdClass $data,array $selected_fields = array('t.id','t.name','t.position','t.telephone','t.fax','t.mobile','t.email','t.modify_time'))
	{
		list($response,$oContact,$oRecord) = parent::event_UPDATE($data,$selected_fields);
		$form_data = (array)$data->data;
		$response->updateDataTable->notification->message = 'You have successfully update a user contact !';
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/project\servlets\forms.Contacts::event_UPDATE_FORM()
	 */
	protected function event_UPDATE_FORM(\stdClass $data)
	{
		list($response,$oContact,$oRecord) = parent::event_UPDATE_FORM($data,$selected_fields);
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActionsSinglePage::loadParentId()
	 */
	protected function loadParentId(){return _pforms\Users::_callRegistryGet(_pforms\Users::_REG_RECORD_ID);}

}
?>