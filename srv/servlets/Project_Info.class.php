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
namespace project\servlets;

use phpJar;

class Project_Info	extends \phpJar\servlets\ServletScheme
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'PROJECT_INFO';

	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $info
	 * @return if $info = '*' return an array with all information for the project on the other hand return specific information
	 */
	final public static function _getInfo($info = '*')
	{
		$info = trim($info);
		$oObject = new self();
		$where = ' AND t.id = 1';
		$oRecord = $oObject->_callScheme('_selectFilterRecordsingle',$where);
		$form_data = $oRecord->_getAttrs();
		if($info === '*')
			return $form_data;
		else
			return $form_data[$info];
	}

}
?>