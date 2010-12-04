<?php
/*****************************************************************************************************
 * Mysql Database implementation where is based on DB class,														*
 * implements connections query save and all important function for the database					*
 *****************************************************************************************************/
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\database\drivers;
use phpJar\database;
/**
 * @final Mysql
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage database																														*
 * @subpackage drivers																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Mysql_PDO
{

	public function __construct(){}

	public function _getHostCertificate()
	{
		$available_dbs = array(
												'localhost'=>array(
																				'db' =>array('user'=>'user','passwd'=>'passwd'),
																		)
										);
		return $available_dbs;
	}

	public function _describeTable($table){return sprintf("DESCRIBE %s",$table);}

	public function _describeTableColumnType($table,$column)
	{
		$query = sprintf("SELECT attr.DATA_TYPE AS DATA_TYPE, attr.COLUMN_TYPE AS COLUMN_TYPE, attr.COLUMN_DEFAULT AS COLUMN_DEFAULT
				FROM INFORMATION_SCHEMA.COLUMNS AS attr
				WHERE attr.TABLE_NAME ='%s'
				AND attr.COLUMN_NAME LIKE '%s%%'
		",$table,$column);
		return $query;
	}

	public function _describeTablePrimaryKeys($table)
	{
		$query = sprintf('SHOW INDEXES FROM %s WHERE Key_name = "PRIMARY"',$table);
		return $query;
	}

	public function _dbScan($dbString,$prefix = false)
	{
		$query = sprintf('SHOW TABLES FROM %s',$dbString);
		if(!is_bool($prefix) && is_string($prefix))
			$query .= sprintf(" LIKE '%s'",$prefix.'%');
		return $query;
	}

	public function _errorTranslation($errorCode)
	{
		$errorList = array(
			1011 => \phpJar\database\DB_PDO::_ERROR_DELETE,
			1012 => \phpJar\database\DB_PDO::_ERROR_FIND_SYSTEM_REC,
			1021 => \phpJar\database\DB_PDO::_ERROR_DISK_FULL,
			1022 => \phpJar\database\DB_PDO::_ERROR_DUBLICATE_KEY,
			1024 => \phpJar\database\DB_PDO::_ERROR_READ,
			1062 => \phpJar\database\DB_PDO::_ERROR_DUBLICATE
		);
		return $errorList[$erroroCode];
	}
}
?>
