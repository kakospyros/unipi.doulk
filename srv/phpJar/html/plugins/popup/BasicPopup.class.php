<?php
/*****************************************************************************************************
 * Basic Template Popup implementation																								*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 *
 * @final BasicPopup_Exceptions Class - Exception class for follow class										*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\servlets\ServletTemplate Current class for which building this exception class*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class BasicPopup_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\html;
use phpJar;
use phpJar\Exceptions as _exceptions;
/**
 * BasicPopup Class --			 																														*
 * @final																																						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class BasicPopup extends TemplatePopup
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 *
	 * @param float $px
	 */
	final public function _setWidth($px = 900){parent::_setWidth($px);}
	/********************************
	 * Class templates method Area *
	 ********************************/
	/********************************
	 * Class implementation Area *
	 ********************************/

}
?>