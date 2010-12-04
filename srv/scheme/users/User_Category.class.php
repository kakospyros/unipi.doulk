<?php
namespace project\Exceptions;
/**
 * @final User_Category_Exception Class - Exception class for follow class							*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class User_Category_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar\database as _database;
use project\Exceptions as _pexceptions;
/**
 * User_Category	--																																*
 * @uses /phpJar/database/SchemeSpecs#_constructor()																*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class User_Category extends SchemeQueries
{

	const DOCTOR = 2;
	const PATIENT = 1;

	public function _deleteRecordsNotInclude($where = null, array $childList = array())
	{
		try{
			parent::_deleteRecordsNotInclude($where,$childList);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\User_Category_Exceptions::throwException('user category list deletion error !');
		}
	}

	public function _insertMultiRecords($parent_id, array $childList )
	{
		try{
			return parent::_insertMultiRecords($parent_id,$childList);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\User_Category_Exceptions::throwException('user category list insert error !');
		}
	}

}
?>