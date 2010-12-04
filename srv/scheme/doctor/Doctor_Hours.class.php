<?php
namespace project\Exceptions;
/**
 * @final Doctor_Hours_Exception Class - Exception class for follow class										*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class													*
 * @see project\Doctor_Hours_Exceptions Current class for which building this exception class	*
 * @author Kondylis Andreas																														*
 * @package project																																		*
 * @subpackage Exceptions																														*
 * @version 1.0																																				*
 * @copyright Copyright (c) 2010, Kondylis Andreas																				*
 * @license																																						*
 *******************************************************************************************************/
final class Doctor_Hours_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
/**
 * Doctor_Hours	--																																*
 * @uses /phpJar/database/SchemeSpecs#_constructor()																*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Doctor_Hours extends SchemeQueries
{

	public function _deleteRecordsNotInclude($where = null, array $childList = array())
	{
		try{
			parent::_deleteRecordsNotInclude($where,$childList);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Doctor_Hours_Exceptions::throwException('doctor visiting hours  list deletion error');
		}
	}
}
?>