<?php
namespace project\Exceptions;
/**
 * @final Recipe_Exception Class - Exception class for follow class											*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see project\Users Current class for which building this exception class									*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Recipe_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar\database as _database;
use project\Exceptions as _pexceptions;
/**
 * Recipe	 --  																																			*
 * @uses /phpJar/database/SchemeSpecs#_constructor()																*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Recipe extends SchemeQueries
{

	public function _deleteRecordsInclude($where = null, array $list = array())
	{
		try{
			parent::_deleteRecordsInclude($where,$list);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Recipe_Exceptions::throwException('Recipe list deletion error ! ');
		}
	}

}
?>