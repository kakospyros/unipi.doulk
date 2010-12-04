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
use phpJar\database as _database;
use project\scheme as _pscheme;
use project\Exceptions as _pexceptions;
use project\servlets as _pservlets;

class FormActionsSimple extends FormActionsCore
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'FORM_ACTIONS_NO_RECORDS';
	/********************************
	 * Class templates method Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::_getFormTpl()
	 */
	public function _getFormTpl(\stdClass $args = null)
	{
		$this->_createCommands($btn, $rowPos);
		static::_callRegistrySet(static::_REG_RECORD_ID,null);
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
		$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_UPDATE);
		$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_DELETE);
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
			$this->_clearGarbage($btn);
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
		if($btn === static::_ACTION_DELETE)
			$response = $this->event_DELETE($data);
		elseif($btn === static::_ACTION_UPDATE)
			$response = $this->event_UPDATE($data);
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::event_DELETE()
	 */
	protected function event_DELETE(\stdClass $data,array $selected_fields = array('t.id','t.name'))
	{
		$response = new \stdClass();
		$response->close = false;
		$new_record = $data->data->_record;
		$oRegistry = static::_callRegistryGet(static::_REG_RECORD_ID);
		if(!($oRegistry > 0))
		{
			_pexceptions\FormActionsCore_Exceptions::throwException('record update incorrect selection');
		}

		$response->reset = true;
		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = 'You have successfully delete a Record !';

		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::event_UPDATE()
	 */
	protected function event_UPDATE(\stdClass $data,array $selected_fields = array('t.id','t.name'))
	{
		$this->_setValidation();
		$this->_formValidation($data->data);
		$response = new \stdClass();
		$response->populate = true;
		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = 'You have successfully update a Record !';
		return $response;
	}
	/**
	 *
	 * Enter description here ...
	 */
	protected function loadParentId(){return 0;}
}
?>