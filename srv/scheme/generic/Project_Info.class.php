<?php
namespace project\Exceptions;
/**
 * @final Project_Info_Exception Class - Exception class for follow class										*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see project\Project_Info Current class for which building this exception class						*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Project_Info_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
/**
 * Project_Info	 --  																																			*
 * @uses /phpJar/database/SchemeSpecs#_constructor()																*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Project_Info extends SchemeQueries{}
?>