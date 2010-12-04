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
use phpJar\servlets as _servlets;
use project\scheme as _pscheme;
use project\Exceptions as _pexceptions;

class Address extends FormActionsSameLayer
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'ADDRESS_FORM_ACTIONS';
	/**
	 * (non PHP-Doc)
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => 'address',
													);
	/********************************
	 * Class templates method Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActionsSinglePage::_getFormTpl()
	 */
	public function _getFormTpl(\stdClass $args = null)
	{
		$parentId = $this->loadParentId();
		if(!($parentId > 0) )
			_pexceptions\Address_Exceptions::throwException('address child list deletion error');

		$oAddress = new _pscheme\Address();
		$oRecords = $oAddress->_selectFilterRecordsBaseOnType($this,$parentId);
		$template = $this->_getRecordsFormTpl($oRecords);
		$this->_setNestedRecords($template);

		return parent::_getFormTpl($args);
	}
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
		self::_setTemplateFolder('address');
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActions::_buildDataRecords()
	 */
	public function _buildDataRecords($response, array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.modify_time'))
	{
		$oScheme = new _pscheme\Address();
		$oRecords = $oScheme->_selectFilterArrayBaseOnType($this,$this->loadParentId(),null,$selected_fields);
		if($oRecords)
		{
			foreach ($oRecords as &$record)
			{
				$id = array_shift($record);
				$street = array_shift($record);
				$number = array_shift($record);

				$time = array_pop($record);

				array_unshift($record,sprintf('%s (%s)',$street,$number));
				array_unshift($record,$id);
				array_push($record,_utils\DT::_getLocalString($time));
			}
			$response->updateDataTable->records= $oRecords;
		}
		else
			$response->updateDataTable->records= array();
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActionsSinglePage::event_ADD()
	 */
	protected function event_ADD(\stdClass $data,array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.modify_time'),$buildData = true)
	{
		$response = parent::event_ADD($data,$selected_fields,false);
		$form_data = (array)$data->data;
		$form_data = $this->_callScheme('_fillBasic',$form_data,true);
		$oAddressRecord = new _pscheme\Address($form_data);
		$oAddressRecord->_save();

		$new_data = array();
		$new_data = $this->_callScheme('_fillBasic',$new_data,true);
		$oRecord = $this->_oReflectionScheme->newInstanceArgs(array($new_data));
		$oRecord->child_id = $oAddressRecord->id;
		$oRecord->parent_id = $this->loadParentId();
		$oRecord->_save();

		$response->updateDataTable->rowID = $oAddressRecord->id;
		$response->updateDataTable->notification->message = 'You have successfully add a new Record !';

		$this->_buildDataRecords($response,$selected_fields);
		return array($response,$oAddressRecord,$oRecord);
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActionsSinglePage::event_DELETE()
	 */
	protected function event_DELETE(\stdClass $data,array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.modify_time'))
	{
		$response = parent::event_DELETE($data,$selected_fields);
		$new_record = $data->data->_record;
		$oRegistry = self::_callRegistryGet(static::_REG_RECORD_ID);

		$oAddress = new _pscheme\Address();
		$where = sprintf(' AND t.id = %d',$oRegistry);
		$oAddressRecord = $oAddress->_selectFilterRecordsingle($where);
		$this->_callScheme('_deleteRecordsChildInclude',null,(array)$oAddressRecord->id);
		$oAddressRecord->_delete();

		if($response->close === false)
		{
			self::_callRegistrySet(static::_REG_RECORD_ID,$new_record);
			$where = sprintf(' AND t.id = %d',$new_record);
			$oAddressRecord = $oAddress->_selectFilterRecordsingle($where);

			$where = sprintf(' AND t.child_id = %d',$oAddressRecord->id);
			$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);

			$form_data = $oAddressRecord->_getAttrs();
			$response->_htmlTag['_tagValue'] = $form_data;
		}
		$response->updateDataTable->notification->message = 'You have successfully delete a Record !';
		$this->_buildDataRecords($response,$selected_fields);
		return array($response,$oAddressRecord,$oRecord);
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActionsSinglePage::event_DELETE_MULTI()
	 */
	protected function event_DELETE_MULTI(\stdClass $data,array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.modify_time'))
	{
		$form_data = (array)$data->data;
		$this->_callScheme('_deleteRecordsChildInclude',null,(array)$form_data);
		$oAddress = new _pscheme\Address();
		$oAddress->_deleteRecordsInclude(null,$form_data);
		$response = parent::event_DELETE_MULTI($data,$selected_fields);
		$response->updateDataTable->notification->message = sprintf('You have successfully delete %d Record(s) !',count($data->data));
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActionsSinglePage::event_UPDATE()
	 */
	protected function event_UPDATE(\stdClass $data,array $selected_fields = array('t.id','t.street','t.number','t.area','t.post_code','t.modify_time'))
	{
		$response = parent::event_UPDATE($data,$selected_fields);
		$oRegistry = self::_callRegistryGet(static::_REG_RECORD_ID);
		$form_data = (array)$data->data;
		$form_data = $this->_callScheme('_fillBasic',$form_data,true);

		$where = sprintf(' AND t.id = %d',$oRegistry);
		$oAddress = new _pscheme\Address();

		$where = sprintf(' AND t.id = %d',$oRegistry);
		$oAddressRecord = $oAddress->_selectFilterRecordsingle($where);
		foreach ($form_data as $prop => $val)
		{
			if(property_exists($oAddressRecord,$prop))
				$oAddressRecord->{$prop} = $val;
		}
		$oAddressRecord->_save();

		$where = sprintf(' AND t.child_id = %d',$oAddressRecord->id);
		$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);

		$response->updateDataTable->rowID = $oRecord->id;
		$response->updateDataTable->notification->message = 'You have successfully update a Record !';

		$this->_buildDataRecords($response,$selected_fields);
		return array($response,$oAddressRecord,$oRecord);
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/servlets/formActions/generic/project\servlets\forms.FormActionsSinglePage::event_UPDATE_FORM()
	 */
	protected function event_UPDATE_FORM(\stdClass $data)
	{
		$response = parent::event_UPDATE_FORM($data);

		$where = sprintf(' AND t.id = %d',$data->data->id);
		$oAddress = new _pscheme\Address();
		$oAddressRecord = $oAddress->_selectFilterRecordsingle($where);
		$form_data = $oAddressRecord->_getAttrs();
//		$this->_setTemplateMultiTagValues($form_data);

		$where = sprintf(' AND t.child_id = %d',$oAddressRecord->id);
		$oRecord = $this->_callScheme('_selectFilterRecordsingle',$where);

		$response->updateDataTable->rowID = $oAddressRecord->id;
		$response->_htmlTag['_tagValue'] = $form_data;
		return array($response,$oAddressRecord,$oRecord);
	}

}
?>