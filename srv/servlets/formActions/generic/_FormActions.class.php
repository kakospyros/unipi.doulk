<?php
namespace project\Exceptions;
/**
 * @final FormActions_Exception Class - Exception class for follow class													*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see project\servlets\forms\FormActions Current class for which building this exception class									*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class FormActions_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
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

class FormActions	extends \phpJar\servlets\ServletScheme
	implements \phpJar\servlets\FormActions
{

	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'FORM_ACTIONS_FORM_ACTIONS';

	const _ACTION_ADD = 'add-row';
	const _ACTION_DELETE = 'delete-row';
	const _ACTION_DELETE_MULTI = 'delete-multi';
	const _ACTION_NEW_FORM = 'new-form';
	const _ACTION_NEW_FORM_TAB = 'new-form-tab';
	const _ACTION_NEXT_ROW = 'next-row';
	const _ACTION_PREV_ROW = 'prev-row';
	const _ACTION_UPDATE = 'update-row';
	const _ACTION_UPDATE_FORM = 'update-form';
	const _ACTION_UPDATE_FORM_TAB = 'update-form-tab';
	const _ACTION_VIEW_FORM = 'view-form';

	const _METHOD_SINGLE = 0;
	const _METHOD_TABS = 1;

	const _ROW_REQUEST_NONE = 0;
	const _ROW_REQUEST_NEXT = 1;
	const _ROW_REQUEST_PREV = 2;
	const _ROW_REQUEST_NEXT_PREV = 3;

	const _CHILD_ID = null;

	const _REG_RECORD_ID = 'record-id';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	protected $_oReflectionCounter;
	private $_openMethod;
	/**
	 * (non PHP-Doc)
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => 'action0',
																1 => 'view',
																2 => 'tab'
													);
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	protected function _getOpenMethod(){return $this->_openMethod;}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	protected function _setOpenMethod($method = self::_METHOD_SINGLE){$this->_openMethod = $method;}
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
		$this->_setOpenMethod();
		self::_setTemplateFolder('formactions');
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar\servlets\FormActions::_buildDataRecords($response,$selected_fields)
	 */
	public function _buildDataRecords($response, array $selected_fields = array('t.id','t.name','t.modify_time'))
	{
		$oRecords = $this->_callScheme('_selectFilterArray',null,$selected_fields,array(),true);
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
	 * @see phpJar\servlets\FormActions::_createCommands($btn,$rowPos)
	 */
	public function _createCommands($btn,$rowPos)
	{
		if($btn === self::_ACTION_UPDATE_FORM || $btn === self::_ACTION_NEW_FORM)
		{
			if($btn === self::_ACTION_UPDATE_FORM)
			{
				if($rowPos == self::_ROW_REQUEST_NONE || $rowPos == self::_ROW_REQUEST_NEXT)
					$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_PREVIOUS,null,'.ui-state-default ui-state-disabled');
				else
					$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_PREVIOUS);
				$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_UPDATE);

				if($rowPos == self::_ROW_REQUEST_NONE || $rowPos == self::_ROW_REQUEST_PREV)
					$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_NEXT,null,'.ui-state-default ui-state-disabled');
				else
					$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_NEXT);
				$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_DELETE);
			}
			else
				$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_ADD);

			if($btn != self::_ACTION_UPDATE_FORM)
				$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_CLEAR);
			$this->_setTemplateDefButtons(_html\HtmlButton::_ACTION_CLOSE);
		}
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar\servlets\FormActions::_runEvent(\stdClass $data)
	 */
	public function _runEvent(\stdClass $data)
	{
		try{
			$this->_callScheme('_beginTransaction');
			$options = new \stdClass();
			$wrapper_options = new \stdClass();
			$btn = $data->action;
			$rowPos = $data->data->_perm;
			$this->_createJSBoxRegistryObj($options,$data);
			$this->_setTemplateAsChild(static::_CHILD_ID);
			//store data in case that contents open on tabs
			if($btn === self::_ACTION_UPDATE_FORM || $btn === self::_ACTION_NEW_FORM)
			{
				if($this->_openMethod === self::_METHOD_TABS)
				{
					if($data->data->_openMethod == self::_METHOD_TABS)
						static::storeMethod($data);
					$btn .= '-tab';
				}
			}

			$response = $this->_eventRelation($data,$btn);
			//display single template for non-popup
			if($btn === self::_ACTION_UPDATE_FORM || $btn === self::_ACTION_NEW_FORM)
			{
				$this->_createCommands($btn,$rowPos);
			}
			if(($btn === self::_ACTION_UPDATE_FORM || $btn === self::_ACTION_NEW_FORM || $btn === self::_ACTION_VIEW_FORM) ||
				($btn === self::_ACTION_UPDATE_FORM_TAB || $btn === self::_ACTION_NEW_FORM_TAB) )
			{
				$wrapper_options = $this->_setWrapperSettings($data);
				$this->_setPopup($options,$wrapper_options);
				$response = self::_getFormTpl();
			}
			$this->_callScheme('_commit');
			return $response;
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			$e = $e->_createException($this, $data->data);
			$this->_callScheme('_rollback');
			$options = new \stdClass();
			$this->_createJSBoxRegistryObj($options,$data);
			$e->attachAttr($options);
			throw $e;
		}
	}

	public function _runTabEvent()
	{
		$data = static::loadMethod();
		$btn = $data->action;
		$rowPos = $data->data->_perm;
		$response = $this->_eventRelation($data,$btn);
		if($btn === self::_ACTION_UPDATE_FORM || $btn === self::_ACTION_NEW_FORM)
		{
			$this->_createCommands($btn,$rowPos);
			$response = self::_getFormTpl();
		}
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar\servlets\FormActions::_eventRelation(\stdClass $data, $btn)
	 */
	public function _eventRelation(\stdClass $data, $btn)
	{
		if($btn === self::_ACTION_NEW_FORM)
			$response = $this->event_NEW_FORM($data);

		elseif($btn === self::_ACTION_NEW_FORM_TAB)
			$response = $this->event_NEW_FORM_TAB($data);

		elseif($btn === self::_ACTION_UPDATE_FORM)
			$response = $this->event_UPDATE_FORM($data);

		elseif($btn === self::_ACTION_UPDATE_FORM_TAB)
			$response = $this->event_UPDATE_FORM_TAB($data);

		elseif($btn === self::_ACTION_VIEW_FORM)
			$response = $this->event_VIEW_FORM($data);

		elseif($btn === self::_ACTION_ADD)
			$response = $this->event_ADD($data);

		elseif($btn === self::_ACTION_DELETE)
			$response = $this->event_DELETE($data);

		elseif($btn === self::_ACTION_DELETE_MULTI)
			$response = $this->event_DELETE_MULTI($data);

		elseif($btn === self::_ACTION_NEXT_ROW)
			$response = $this->event_NEXT_ROW($data);

		elseif($btn === self::_ACTION_PREV_ROW)
			$response = $this->event_PREV_ROW($data);

		elseif($btn === self::_ACTION_UPDATE)
			$response = $this->event_UPDATE($data);
		return $response;
	}

	protected function _setWrapperSettings(\stdClass $data)
	{
		$wrapper_options = new \stdClass();
		$wrapper_options->title = $data->title;
		$wrapper_options->minHeight = 220;
		$wrapper_options->minWidth = 400;
		$wrapper_options->position = array('center',112);
		return $wrapper_options;
	}
	/**
	 *
	 * @access protected
	 * @param \stdClass $data
	 * @return on success object, on fail false
	 */
	protected function event_ADD(\stdClass $data,array $selected_fields = array('t.id','t.name','t.modify_time'),$buildData = true)
	{
		$response = new \stdClass();
		$form_data = (array)$data->data;
		$form_data = $this->_callScheme('_fillBasic',$form_data,true);
		//create new record
		$oRecord = $this->_oReflectionScheme->newInstanceArgs(array($form_data));
		$oRecord->_save();
		self::_callRegistrySet(self::_REG_RECORD_ID,$oRecord->id);

		$response->reset = true;
		$response->populate = true;
		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->selector = sprintf('table.%s\\:Records',$this->_getReflectionName());
		$response->updateDataTable->rowID = $oRecord->id;
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = 'You have successfully add a new Record !';

		if($buildData)
			$this->_buildDataRecords($response,$selected_fields);
		$this->_createJSBoxRegistryObj($response,$data);
		return array($response,$oRecord);
	}

	protected function event_DELETE(\stdClass $data, array $selected_fields = array('t.id','t.name','t.modify_time'))
	{
		$response = new \stdClass();
		$new_record = $data->data->_record;

		$oRegistry = static::_callRegistryGet(self::_REG_RECORD_ID);
		if(!($oRegistry > 0))
			_pexceptions\FormActions_Exceptions::throwException('record update incorrect selection');
		$id = (array)$oRegistry;
		$this->_callScheme('_deleteRecordsInclude',null,$id);

		if($new_record > 0)
		{
			$where = sprintf(' AND t.id = %d',$new_record);
			$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);
			$form_data = $oRecord->_getAttrs();
			self::_callRegistrySet(self::_REG_RECORD_ID,$oRecord->id);
			$response->_htmlTag['_tagValue'] = $form_data;
		}
		else
		{
			self::_callRegistrySet(self::_REG_RECORD_ID,0);
			$response->close = true;
		}

		$response->reset = true;
		$response->populate = true;
		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->selector = sprintf('table.%s\\:Records',$this->_getReflectionName());
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = 'You have successfully delete a Record !';
		$this->_buildDataRecords($response,$selected_fields);
		$this->_createJSBoxRegistryObj($response,$data);
		return array($response,$oRecords);
	}

	protected function event_DELETE_MULTI(\stdClass $data, array $selected_fields = array('t.id','t.name','t.modify_time'))
	{
		$response = new \stdClass();
		$response->close = false;
		$form_data = (array)$data->data;
		if(!(count($form_data) > 0))
			_pexceptions\FormActions_Exceptions::throwException('record delete incorrect selection');

		$this->_callScheme('_deleteRecordsInclude',null,$form_data);
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
		$this->_createJSBoxRegistryObj($response,$data);
		return $response;
	}

	protected function event_NEW_FORM(\stdClass $data)
	{
		self::_callRegistrySet(self::_REG_RECORD_ID,0);
	}

	protected function event_NEW_FORM_TAB(\stdClass $data){}

	protected function event_NEXT_ROW(\stdClass $data)
	{
		$response = new \stdClass();
		$new_record = $data->data->_record;
		if($new_record > 0)
		{
			$where = sprintf(' AND t.id = %d',$new_record);
			$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);
			self::_callRegistrySet(self::_REG_RECORD_ID,$oRecord->id);
			$form_data = $oRecord->_getAttrs();

			$response->reset = true;
			$response->populate = true;
			$response->updateDataTable = new \stdClass();
			$response->updateDataTable->rowID = $oRecord->id;
			$response->_htmlTag['_tagValue'] = $form_data;
			$this->_createJSBoxRegistryObj($response,$data);
			return $response;
		}
		return false;
	}

	protected function event_PREV_ROW(\stdClass $data)
	{
		$response = new \stdClass();
		$new_record = $data->data->_record;
		if($new_record > 0)
		{
			$where = sprintf(' AND t.id = %d',$new_record);
			$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);
			self::_callRegistrySet(self::_REG_RECORD_ID,$oRecord->id);
			$form_data = $oRecord->_getAttrs();

			$response->reset = true;
			$response->populate = true;
			$response->updateDataTable = new \stdClass();
			$response->updateDataTable->rowID = $oRecord->id;
			$response->_htmlTag['_tagValue'] = $form_data;
			$this->_createJSBoxRegistryObj($response,$data);
			return $response;
		}
		return false;
	}
	/**
	 *
	 * @access protected
	 * @param \stdClass $data
	 * @param \phpJar\database\Scheme $oRecord
	 * @return on success object, on fail false
	 */
	protected function event_UPDATE(\stdClass $data,array $selected_fields = array('t.id','t.name','t.modify_time'))
	{
		$response = new \stdClass();
		$form_data = (array)$data->data;
		$oRegistry = static::_callRegistryGet(static::_REG_RECORD_ID);
		if(!($oRegistry > 0))
			_pexceptions\FormActions_Exceptions::throwException('record update incorrect selection');
		$form_data = $this->_callScheme('_fillBasic',$form_data,true);
		//update old record
		$where = sprintf(' AND t.id = %d',$oRegistry);
		$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);
		foreach ($form_data as $prop => $val)
		{
			if(property_exists($oRecord,$prop))
				$oRecord->{$prop} = $val;
		}
		$oRecord->_save();

		$response->updateDataTable = new \stdClass();
		$response->updateDataTable->selector = sprintf('table.%s\\:Records',$this->_getReflectionName());
		$response->updateDataTable->rowID = $oRecord->id;
		$response->updateDataTable->delete = true;
		$response->updateDataTable->notification = new \stdClass();
		$response->updateDataTable->notification->header = 'Success';
		$response->updateDataTable->notification->message = 'You have successfully update a Record !';

		$this->_buildDataRecords($response,$selected_fields);
		$this->_createJSBoxRegistryObj($response,$data);
		return array($response,$oRecord);
	}

	protected function event_UPDATE_FORM(\stdClass $data)
	{
		$where = sprintf(' AND t.id = %d',$data->data->id);
		$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);
		self::_callRegistrySet(self::_REG_RECORD_ID,$oRecord->id);
		$form_data = $oRecord->_getAttrs();
		$this->_setTemplateMultiTagValues($form_data);
		return $form_data;
	}

	protected function event_UPDATE_FORM_TAB(\stdClass $data)
	{
		self::_callRegistrySet(self::_REG_RECORD_ID,$data->data->id);
	}

	protected function event_VIEW_FORM(\stdClass $data)
	{
		$where = sprintf(' AND t.id = %d',$data->data->id);
		$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);
		self::_callRegistrySet(self::_REG_RECORD_ID,$oRecord->id);
		$form_data = $oRecord->_getAttrs();
		$this->_setTemplateMultiTagValues($form_data);
		$this->_setTemplate(static::$_tplFile[1]);
		return $form_data;
	}

	protected static function loadMethod(){return static::_callRegistryGet('storeMethodData');}

	protected static function storeMethod(\stdClass $data){static::_callRegistrySet('storeMethodData', $data);}

}
?>