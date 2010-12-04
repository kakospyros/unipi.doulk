<?php
/*****************************************************************************************************
 * Registry_I Interface Implementation																							*
 *****************************************************************************************************/
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar;
/**
 * Registry_I Interface 																															*
 * @see phpJar\servlet\ServletTemplate					 																				*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
interface Registry_I
{
	/**
	 * Fetch data from registry mechanism
	 * @param string $key
	 */
	public static function _callRegistryGet($key);
	/**
	 * Store data to registry mechanism
	 * @param string $key
	 * @param mixed $value
	 */
	public static function _callRegistrySet($key,$value);
	/**
	 *
	 * @param string $key
	 */
	public static function _callRegistryUnSet($key);

}
?>