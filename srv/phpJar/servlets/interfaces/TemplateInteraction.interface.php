<?php
/*****************************************************************************************************
 *****************************************************************************************************/
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\servlets;
/**
 * TemplateInteraction Interface 																												*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
interface TemplateInteraction
{
	/**
	 *
	 * Delete current wizard record
	 * @param \stdClass $formData
	 */
	public function _actionDelete(\stdClass $formData);
	/**
	 * fill template values
	 * @access public
	 */
	public function _setTemplateData();
	/**
	 * Method for store template received data
	 */
	public function _formSave(\stdClass $formData);
	/**
	 *  Method for form validation
	 */
	public function _formValidate(\stdClass $formData);

	public function _getRegistryData($type = null);
	/**
	 *
	 * @access public
	 * @static
	 * @param \stdClass $data
	 * @param integer $type
	 */
	public function _setRegistryData($data, $type);

	public function _setValidation($step);
	/**
	 * Method for check/validate form on every step
	 * @param stdClass $formData
	 * @param integer $step
	 */
	public function _stepValidation(\stdClass$formData, $step);

}
?>