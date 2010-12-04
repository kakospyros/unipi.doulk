<?php
namespace project\Exceptions;
/**
 * @final Reference_Counters_Exception Class - Exception class for follow class							*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see project\Users Current class for which building this exception class									*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Reference_Counters_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar\database as _database;
use project\Exceptions as _pexceptions;
/**
 * Reference_Counters	--																																*
 * @uses /phpJar/database/SchemeSpecs#_constructor()																*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Reference_Counters extends SchemeQueries
{
	const _TYPE_APPOINTMENT = 1;
	const _TYPE_SPECIALITY = 2;
}
?>