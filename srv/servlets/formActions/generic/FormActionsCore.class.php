<?php
/*****************************************************************************************************
 * ---------------------------------------------------------------------------------------------------------------------------*/
namespace project\Exceptions;
/**
 * @final FormActionsCore_Exception Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class																*
 * @see project\servlets\forms\FormActionsCore Current class for which building this exception class	*
 * @author Kondylis Andreas																																	*
 * @package project																																					*
 * @subpackage Exceptions																																	*
 * @version 1.0																																							*
 * @copyright Copyright (c) 2010, Kondylis Andreas																							*
 * @license																																									*
 ***************************************************************************************************************/
final class FormActionsCore_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
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

class FormActionsCore	extends \phpJar\servlets\ServletScheme
	implements \phpJar\servlets\FormActions
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const REG_INDEX = 'FORM_ACTIONS_FORM_ACTIONS';
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const _CHILD_ID = null;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const _REG_RECORD_ID = 'record-id';
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	private $_openMethod;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_needValidation = true;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_oTemplateValidate;
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
	/**
	 *
	 * Enter description here ...
	 */
	protected function _getOpenMethod(){return $this->_openMethod;}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $method
	 */
	protected function _setOpenMethod($method = self::_METHOD_SINGLE){$this->_openMethod = $method;}
	/********************************
	 * Class templates method Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see srv/phpJar/servlets/phpJar\servlets.ServletTemplate::_getFormTpl()
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

		if($this->_needValidation)
		{
			$this->_oTemplateValidate = new \phpJar\servlets\ServletTemplateValidate();
		}
		else
			$this->_oTemplateValidate = null;

		self::_setTemplateFolder('formactions');
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar\servlets\FormActions::_buildDataRecords($response,$selected_fields)
	 */
	public function _buildDataRecords($response, array $selected_fields = array('t.id','t.reference','t.name','t.modify_time')){}
	/**
	 * (non-PHPdoc)
	 * @see phpJar\servlets\FormActions::_createCommands($btn,$rowPos)
	 */
	public function _createCommands($btn,$rowPos){}
	/**
	 * (non-PHPdoc)
	 * @see phpJar\servlets\FormActions::_clearGarbage($btn)
	 */
	public function _clearGarbage($btn){}
	/**
	 * (non-PHPdoc)
	 * @see srv/phpJar/servlets/interfaces/phpJar\servlets.FormActions::_formValidation()
	 */
	public function _formValidation(\stdClass $formData, $throw = true)
	{
		$validate = true;
		if($this->_needValidation)
			$validate = $this->_oTemplateValidate->_validate($formData,true);
		if(!($validate === true) && ($throw === true) )
		{
			$msg = array();
			$lang =  phpJar\Language::_getSpecificErrorLanguage($this->_getReflectionName());
			$lang = $lang->validation;
			if(is_object($lang) )
			{
				foreach ($validate as $field => $v_info)
				{
					if(is_object($lang->{$field}))
						$msg[] = $lang->{$field}->{$v_info['validate']};
					elseif (is_string($lang->{$field}))
						$msg[] = $lang->{$field};
				}
			}
			else
			{
				$lang = phpJar\Language::_getSpecificErrorLanguage();
				$msg[] = sprintf($lang->generic);
			}
			$msg = implode('\n',$msg);
			_pexceptions\FormActionsCore_Exceptions::throwException($msg);
		}
		return $validate;
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar\servlets\FormActions::_runEvent(\stdClass $data)
	 */
	public function _runEvent(\stdClass $data){}
	/**
	 *
	 * Enter description here ...
	 */
	public function _runTabEvent(){}
	/**
	 * (non-PHPdoc)
	 * @see phpJar\servlets\FormActions::_eventRelation(\stdClass $data, $btn)
	 */
	public function _eventRelation(\stdClass $data, $btn)
	{
		if($btn === static::_ACTION_NEW_FORM)
			$response = $this->event_NEW_FORM($data);
		elseif($btn === static::_ACTION_NEW_FORM_TAB)
			$response = $this->event_NEW_FORM_TAB($data);
		elseif($btn === static::_ACTION_UPDATE_FORM)
			$response = $this->event_UPDATE_FORM($data);
		elseif($btn === static::_ACTION_UPDATE_FORM_TAB)
			$response = $this->event_UPDATE_FORM_TAB($data);
		elseif($btn === self::_ACTION_VIEW_FORM)
			$response = $this->event_VIEW_FORM($data);
		elseif($btn === static::_ACTION_ADD)
			$response = $this->event_ADD($data);
		elseif($btn === static::_ACTION_DELETE)
			$response = $this->event_DELETE($data);
		elseif($btn === static::_ACTION_DELETE_MULTI)
			$response = $this->event_DELETE_MULTI($data);
		elseif($btn === static::_ACTION_NEXT_ROW)
			$response = $this->event_NEXT_ROW($data);
		elseif($btn === static::_ACTION_PREV_ROW)
			$response = $this->event_PREV_ROW($data);
		elseif($btn === static::_ACTION_UPDATE)
			$response = $this->event_UPDATE($data);
		return $response;
	}
	/**
	 * (non-PHPdoc)
	 * @see srv/phpJar/servlets/interfaces/phpJar\servlets.FormActions::_setValidation()
	 */
	public function _setValidation(\stdClass $data = null){}
	/**
	 *
	 * Enter description here ...
	 * @param $data
	 */
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
	protected function event_ADD(\stdClass $data,array $selected_fields = array('t.id','t.reference','t.name','t.modify_time'),$buildData = true){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 * @param array $selected_fields
	 */
	protected function event_DELETE(\stdClass $data, array $selected_fields = array('t.id','t.reference','t.name','t.modify_time')){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 * @param array $selected_fields
	 */
	protected function event_DELETE_MULTI(\stdClass $data, array $selected_fields = array('t.id','t.reference','t.name','t.modify_time')){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	protected function event_NEW_FORM(\stdClass $data){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	protected function event_NEW_FORM_TAB(\stdClass $data){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	protected function event_NEXT_ROW(\stdClass $data){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	protected function event_PREV_ROW(\stdClass $data){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 * @param array $selected_fields
	 */
	protected function event_UPDATE(\stdClass $data,array $selected_fields = array('t.id','t.reference','t.name','t.modify_time')){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	protected function event_UPDATE_FORM(\stdClass $data){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	protected function event_UPDATE_FORM_TAB(\stdClass $data){}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	protected function event_VIEW_FORM(\stdClass $data){}
	/**
	 *
	 * Enter description here ...
	 */
	protected static function loadMethod(){return static::_callRegistryGet('storeMethodData');}
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	protected static function storeMethod(\stdClass $data = null){static::_callRegistrySet('storeMethodData', $data);}

}
?>