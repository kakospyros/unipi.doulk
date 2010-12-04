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
use phpJar\html as _html;
use phpJar\utils as _utils;
use phpJar\servlets as _servlets;
use project\scheme as _pscheme;
use project\Exceptions as _pexceptions;

class FormActionsSinglePage extends FormActions
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'FORM_ACTIONS_SINGLE_PAGE';
	/**
	 * (non PHP-Doc)
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => 'action0',
													);
	/********************************
	 * Class templates method Area *
	 ********************************/
	/**
	 *
	 */
	public function _getFormTpl(\stdClass $args = null)
	{
		$this->_createCommands($btn, $rowPos);
		self::_callRegistrySet(self::_REG_RECORD_ID,null);
		return parent::_getFormTpl($args);
	}
		/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::_createCommands()
	 */
	public function _createCommands($btn,$rowPos)
	{
		$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_ADD);
		$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_UPDATE);
		$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_DELETE);
		$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_DELETE_MULTI_2);
		$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_CLOSE);
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::_runEvent()
	 */
	public function _runEvent(\stdClass $data)
	{
		try{
			$this->_callScheme('_beginTransaction');
			$options = new \stdClass();
			$wrapper_options = new \stdClass();
			$btn = $data->action;
			$rowPos = $data->data->_perm;
			$this->_setTemplateAsChild(static::_CHILD_ID);
			$response = $this->_eventRelation($data,$btn);
			$this->_callScheme('_commit');
			$this->_createJSBoxRegistryObj($response,$data);
			return $response;
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			$e = $e->_createException($this, (object)$data->data);
			$this->_callScheme('_rollback');
			$options = new \stdClass();
			$this->_createJSBoxRegistryObj($options,$data);
			$e->attachAttr($options);
			throw $e;
		}
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::_eventRelation()
	 */
	public function _eventRelation(\stdClass $data, $btn)
	{
		if($btn === self::_ACTION_UPDATE_FORM)
			$response = $this->event_UPDATE_FORM($data);
		elseif($btn === self::_ACTION_ADD)
			$response = $this->event_ADD($data);
		elseif($btn === self::_ACTION_DELETE)
			$response = $this->event_DELETE($data);
		elseif($btn === self::_ACTION_DELETE_MULTI)
			$response = $this->event_DELETE_MULTI($data);
		elseif($btn === self::_ACTION_UPDATE)
			$response = $this->event_UPDATE($data);
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::_runTabEvent()
	 */
	public function _runTabEvent(){return array();}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::event_ADD()
	 */
	protected function event_ADD(\stdClass $data,array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.country','t.modify_time'), $buildRecords = true)
	{
		$response = new \stdClass();
		$response->reset = true;
		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->selector = sprintf('table.%s\\:Records',$this->_getReflectionName());
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = 'You have successfully add a new Record !';
//		$this->_createJSBoxRegistryObj($response,$data);
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::event_DELETE()
	 */
	protected function event_DELETE(\stdClass $data,array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.country','t.modify_time'))
	{
		$response = new \stdClass();
		$response->close = false;
		$new_record = $data->data->_record;
		$oRegistry = self::_callRegistryGet(static::_REG_RECORD_ID);
		if(!($oRegistry > 0))
			_pexceptions\Address_Exceptions::throwException('record update incorrect selection');

		if( !($new_record > 0) )
			$response->close = true;

		$response->reset = true;
		$response->populate = true;
		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->selector = sprintf('table.%s\\:Records',$this->_getReflectionName());
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = 'You have successfully delete a Record !';

//		$this->_createJSBoxRegistryObj($response,$data);
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::event_DELETE_MULTI()
	 */
	protected function event_DELETE_MULTI(\stdClass $data,array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.country','t.modify_time'))
	{
		$form_data = (array)$data->data;
		$response = new \stdClass();
		$response->close = false;
		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->selector = sprintf('table.%s\\:Records',$this->_getReflectionName());
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = sprintf('You have successfully delete %d Record(s) !',count($form_data));
		$oRegistry = static::_callRegistryGet(self::_REG_RECORD_ID);
		if($oRegistry > 0)
		{
			if(in_array($oRegistry,$form_data))
				$response->close = true;
		}
		$this->_buildDataRecords($response,$selected_fields);
//		$this->_createJSBoxRegistryObj($response,$data);
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::event_UPDATE()
	 */
	protected function event_UPDATE(\stdClass $data,array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.country','t.modify_time'))
	{
		$response = new \stdClass();
		$form_data = (array)$data->data;
		$oRegistry = self::_callRegistryGet(static::_REG_RECORD_ID);
		if(!($oRegistry > 0))
			_pexceptions\FormActions_Exceptions::throwException('record update incorrect selection');

		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->selector = sprintf('table.%s\\:Records',$this->_getReflectionName());
		$response->updateDataTable->delete = true;
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = 'You have successfully update a Record !';

//		$this->_createJSBoxRegistryObj($response,$data);
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::event_UPDATE_FORM()
	 */
	protected function event_UPDATE_FORM(\stdClass $data)
	{
		self::_callRegistrySet(static::_REG_RECORD_ID,$data->data->id);

		$response->reset = true;
		$response->populate = true;
		$response->updateDataTable = new \stdClass();
//		$this->_createJSBoxRegistryObj($response,$data);
		return $response;
	}
	/**
	 *
	 * Enter description here ...
	 */
	protected function loadParentId(){return 0;}
}
?>