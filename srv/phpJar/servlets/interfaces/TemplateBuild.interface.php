<?php
/*****************************************************************************************************
 * TemplateBuild Interface Implementation																							*
 * All neccessery functions for a servlet which implement/extend template Servlet,					*
 * for display template files and set arguments to it																			*
 *****************************************************************************************************/
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\servlets;
use phpJar\html as _html;
//use phpJar\servlets as _servlets;
/**
 * TemplateBuild Interface 																															*
 * @see phpJar\servlet\ServletTemplate					 																				*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
interface TemplateBuild
{
	/**
	 * Basic method to fetch template file (command part) for current template file
	 */
	public function _getCommandsFormTpl();
	/**
	 * Basic method to fetch template file (main body) for current template file
	 */
	public function _getFormTpl(\stdClass $args = null);
	/**
	 * Basic method to fetch template file (repeated part) for current template file
	 * @param mixed $oRecords
	 */
	public function _getRecordsFormTpl($oRecords = null);
	/**
	 * Search and include language file with all neccessery language variable
	 * for display message and for template building.
	 * The language file must be exist in define language folder
	 * @access public
	 * @param string $folder
	 * @uses /lib/php/phpJar/Config#LANGUAGE_PROJECT_PATH
	 * @return null
	 */
	public function _includeLanguageFiles($folder = 'GB');
	/**
	 * Set Records Nested Template
	 * @final
	 * @access public
	 * @param mixed $oNested
	 * @return null
	 */
	public function _setNestedRecords($oNested);
	/**
	 *  Set template for current class
	 * @access public
	 * @param string $tplPath
	 * @return null
	 */
	public function _setTemplate($tplPath);

}
?>