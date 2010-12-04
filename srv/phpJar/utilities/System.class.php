<?php
/*****************************************************************************************************
 * System implementation																															*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final System_Exceptions Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\utils\System Current class for which building this exception class						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class System_Exceptions extends PhpJar_Exception {}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\utils;
/**
 * System Class with mathematical functions																						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage utils																																*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class System
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *  System constructor,
	 *  @access public
	 * @return System object
	 */
	public function __construct(){}

	public static function _availableLanguage($path = null)
	{
		if(is_null($path))
			$path = \phpJar\LANGUAGE_PROJECT_PATH;
		return self::_ls_folder($path,false,'l',null,true);
	}
	/**
	 * Return class name or name space from a given class name
	 * @access public
	 * @static
	 * @param string $className
	 * @param boolean $namespace
	 * Return class name string on namespace = false,
	 * array with all namespace on namespace = true
	 */
	public static function _getClassName($className = null, $namespace = false)
	{
		$className = explode('\\',$className);
		if(!$namespace)
			return end($className);
		else
		{
			unset($className[count($className)-1]);
			return $className;
		}
	}
	/**
	 *
	 * @access public
	 * @static
	 * @param string $dir
	 * @param boolean $deep
	 * @param string $option
	 * @param string $match
	 * @param boolean $fullpath
	 * @return
	 */
	public static function _ls_folder ( $dir , $deep = true, $option = 'l', $match = null, $fullpath = false)
	{
		$contents = array();
		if(!is_dir($dir))
			return $contents;
		$directory = dir($dir);
		if(!$directory->handle)
			return false;
		if(is_null($match))
			$match = '*';
		if(!($fullpath === false))
			$fullpath = true;
		else
			$fullpath = null;
		if($option == 'l')
			self::_simpleLS($directory,$contents,$deep,$match,$fullpath);
		return $contents;
	}
	/**
	 *
	 * @access public
	 * @static
	 * @param \Directory $directory
	 * @param array $contents
	 * @param unknown_type $deep
	 * @param unknown_type $match
	 * @param unknown_type $fullpath
	 * @return
	 */
	private static function _simpleLS(\Directory $directory, array &$contents, $deep = false, $match = null, $fullpath = null)
	{
		$path = null;
		while( ($file = $directory->read()) !== false)
		{
			if($file == '.' || $file == '..' || fnmatch('.*',$file) || !fnmatch($match,$file))
				continue;
			$path = $directory->path.'/'.$file;
			if(is_file($path))
				$contents[] = $directory->path.'/'.$file;
			elseif(is_dir($path) && $deep)
				$contents[$file] = self::_ls_folder($path,$deep,'l',$match,$fullpath);
			elseif(is_dir($path))
				$contents[] = $file;
		}
	}
	/**
	 *
	 * @access public
	 * @static
	 * @param unknown_type $data
	 * @return
	 */
	public static function urldecode($data)
	{
		if(is_string($data))
			return urldecode($data);
		elseif(is_object($data) || is_array($data))
			foreach ($data as &$field)
				$field = System::urldecode($field);
		return $data;
	}

}
?>