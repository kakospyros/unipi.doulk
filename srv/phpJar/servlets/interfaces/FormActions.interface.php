<?php
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\servlets;
/**
 * FormActions Interface 																															*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
interface FormActions
{
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

	const _METHOD_SINGLE =10;
	const _METHOD_TABS = 1;

	const _ROW_REQUEST_NONE = 0;
	const _ROW_REQUEST_NEXT = 1;
	const _ROW_REQUEST_PREV = 2;
	const _ROW_REQUEST_NEXT_PREV = 3;
	/**
	 *
	 * @param \stdClass $response
	 * @param array $selected_fields
	 */
	public function _buildDataRecords($response,array $selected_fields);
	/**
	 *
	 * @param integer $btn
	 * @param integer $rowPos
	 */
	public function _createCommands($btn,$rowPos);
	/**
	 *
	 * @param integer $btn
	 */
	public function _clearGarbage($btn);
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $formData
	 * @param unknown_type $throw
	 */
	public function _formValidation(\stdClass $formData, $throw = true);
	/**
	 *
	 * @param \stdClass $data
	 */
	public function _runEvent(\stdClass $data);
	/**
	 *
	 * @param \stdClass $data
	 * @param integer $btn
	 */
	public function _eventRelation(\stdClass $data, $btn);
	/**
	 *
	 * Enter description here ...
	 * @param \stdClass $data
	 */
	public function _setValidation(\stdClass $data = null);
}
?>