<?php
namespace project\Exceptions;
/**
 * @final Visiting_Hours Class - Exception class for follow class											*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see project\Users Current class for which building this exception class									*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Visiting_Hours extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar\database as _database;
use project\Exceptions as _pexceptions;
/**
 * Visiting_Hourss	 --  																																			*
 * @uses /phpJar/database/SchemeSpecs#_constructor()																*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Visiting_Hours extends SchemeQueries
{

	public function _deleteRecordsInclude($where = null, array $list = array())
	{
		try{
			parent::_deleteRecordsInclude($where,$list);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Visiting_Hours::throwException('Visiting Hours list deletion error ! ');
		}
	}

}
?>