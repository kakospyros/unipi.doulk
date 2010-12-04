<?php
/*****************************************************************************************************
 * Servlet Wizard Scheme Implementation																								*
 * Extend ServletScheme, adding wizard support 																				*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final ServletWizardScheme_Exceptions Class - Exception class for follow class						*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\servlets\ServletScheme Current class for which building this exception class	*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class ServletWizardScheme_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\servlets;
use phpJar;
use phpJar\utils as _utils;
use phpJar\html\plugins as _htmlPlugins;
/**
 * ServletWizardScheme Class --	 																											*
 * @abstract																																					*
 * @see phpJar\servlet\ServletScheme	 																								*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
abstract class ServletWizardScheme	extends ServletScheme
																		implements _htmlPlugins\FormWizard_I
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const _HTML_MODE_POPUP = 1;
	protected $_oWizard;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 *
	 * @final
	 * @access public
	 * @return Class instance || null
	 */
	final public function _getWizard(){return $this->_oWizard;}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 *
	 * @final
	 * @access protected
	 * @return null
	 */
	final protected function _setWizard()
	{
		$currentClass = get_called_class();
		$className = $this->_getReflectionName();
		$className = phpJar\WIZARD_NAME_SPACE.$className;
		if(class_exists($className))
			$this->_oWizard = new $className();
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * ServletWizardScheme constructor,
	 * @access public
	 * @uses /phpJar/ServletScheme#__construct()
	 * @return mixed a class instance
	 */
	public function __construct($attrs = null)
	{
		parent::__construct($attrs);
		$this->_setWizard();
	}
	/**
	 * (non PHP-Doc)
	  * @see phpJar/html/plugins/FormWizard_I#_wizardCall(\stdClass $attrs = null)
	 */
	public function _wizardCall(\stdClass $attrs = null)
	{
		if(!empty($this->_oWizard))
		{
			$this->_oWizard->_clearRegistry();
			$result = $this->_oWizard->_getFormTpl();
			return $result;
		}
		return false;
	}
	/**
	 * (non PHP-Doc)
	  * @see phpJar/html/plugins/FormWizard_I#_wizardDelete()
	 */
	public function _wizardDelete(\stdClass $attrs)
	{

	}
	/**
	 * (non PHP-Doc)
	  * @see phpJar/html/plugins/FormWizard_I#_wizardLoad()
	 */
	public function _wizardLoad($id)
	{
		$oRecord = $this->_callScheme('_getRecordByField','id',$id);
		return $oRecord;
	}
	/**
	 * (non PHP-Doc)
	  * @see phpJar/html/plugins/FormWizard_I#_wizardMode()
	 */
	public function _wizardMode(){}
	/**
	 * (non PHP-Doc)
	  * @see phpJar/html/plugins/FormWizard_I#_wizardAfterSave(\phpJar\database\Scheme $oRecoord, \stdClass $formData)
	 */
	public function _wizardAfterSave(\phpJar\database\Scheme $oRecoord, \stdClass $formData)
	{

	}
	/**
	 * (non PHP-Doc)
	  * @see phpJar/html/plugins/FormWizard_I#_wizardPreSave(\stdClass $formData)
	 */
	public function _wizardPreSave(\stdClass &$formData)
	{
		$data = (array)$formData;
		if($formData->id > 0)
		{
			$oRecord = $this->_callScheme('_getRecordByField','id',$formData->id);
			$oRecord->_setProperties($data,false);
			$new = false;
		}
		else
		{
			$oRecord = $this->_oReflectionScheme->newInstanceArgs(array($data));
			$new = true;
		}
		return array($oRecord,$new);
	}
	/**
	 * (non PHP-Doc)
	  * @see phpJar/html/plugins/FormWizard_I#_wizardSave(\stdClass $formData)
	 */
	public function _wizardSave(\stdClass $formData)
	{
		list($oRecord,$isNew) = $this->_wizardPreSave($formData);
		if($isNew)
		{
			if(property_exists($oRecord,'create_time'))
			{
				$timestamp = new _utils\DT();
				$oRecord->create_time = $timestamp->_convertTzToGmt()->_date;
			}
		}
		$oRecord->_save();
		$this->_wizardAfterSave($oRecord,$formData);
		return array($oRecord,$isNew);
	}

}
?>