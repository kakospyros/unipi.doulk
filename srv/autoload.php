<?php
/**
 *
 * @author Kondylis Andreas
 *
 */
include_once ($_SERVER['DOCUMENT_ROOT'].'/srv/phpJar/Config.inc');
use phpJar\Exceptions;
ini_set('display_errors','On');
error_reporting(E_ALL^E_NOTICE);
/**
 *
 * @param unknown_type $folder
 * @param unknown_type $class
 */
function scan_Dir($folder,$class)
{
	$namespaces = explode('\\',$class);
	$_class = end($namespaces);
	$extention = array('.class.php','.interface.php');
	$contents = scandir($folder);
	foreach ($contents as $file)
	{
		if( $file == '.' || $file == '..' || (fnmatch(".*", $file)) )
			continue;
		$check = $folder.'/'.$file;
		if(is_file($check))
		{
			foreach ($extention as $suffix)
				if($file == $_class.$suffix || $file == str_replace('_Exceptions',null,$_class).$suffix)
					include_once ($check);
		}
		else
			scan_Dir($check,$class);
	}
	return false;
}
/**
 *
 * @param unknown_type $class
 */
function __autoload($class)
{
	try{
		$root = $_SERVER['DOCUMENT_ROOT'];
		$phpFolders = array('srv');
		foreach ($phpFolders as $folder)
			scan_Dir($root.'/'.$folder,$class);
		if(!class_exists($class) && !(interface_exists($class)))
			throw phpJar\Exceptions\PhpJar_Exception::throwException(sprintf('Error class %s not exists on this Server!',$class));
	}
	catch(phpJar\Exceptions\PhpJar_Exception $e){return false;}
}

?>