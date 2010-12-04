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
namespace phpJar\database;
/**
 *
 *
 */
class SchemeObject extends \SplObjectStorage
{
	/**
	 *
	 * @param $rs
	 * @param $object
	 */
	function __construct($rs,$object = \stdClass)
	{
		$this->_init($rs,$object);
	}

	private function _init($data,$ReflectionClass = \stdClass)
	{
		$oClass = new \ReflectionClass($ReflectionClass);
		if(!empty($data))
			foreach ($data as $row)
				$this->attach($oClass->newInstance($row),'Class '.$ReflectionClass.' instance');
	}
}
?>