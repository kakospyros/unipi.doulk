<?php
namespace project\Exceptions;
/**
 * @final Specialty_Exception Class - Exception class for follow class													*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see project\Specialty Current class for which building this exception class									*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Specialty_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar\utils as _utils;
use phpJar\database as _database;
use project\scheme as _pdatabase;
use project\Exceptions as _pexceptions;
/**
 *  Specialty	 --  																																					*
 * @uses /phpJar/database/SchemeSpecs#_constructor()								*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Specialty extends SchemeQueries
{

	public function _deleteRecordsInclude($where = null, array $list = array())
	{
		try{
			parent::_deleteRecordsInclude($where,$list);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Specialty_Exceptions::throwException('user list deletion error !');
		}
	}

	public function _deleteRecordsNotInclude($where = null, array $childList = array())
	{
		try{
			parent::_deleteRecordsNotInclude($where,$childList);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Specialty_Exceptions::throwException('user list deletion error !');
		}
	}

}
?>