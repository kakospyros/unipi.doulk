<?php
/*****************************************************************************************************
 * Database Layer Abstract class, Singleton mode																				*
 * Use PHP-PDO Servlet as basic layer 																										*
 * Implement one connection per database and return this on every request								*
 * include all neccessery functions for basic action for database using											*
 * auto recognize the table fields, each field type, primary and foreign keys									*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final DB_Exceptions Class - Exception class for follow class														*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see phpJar\database\DB Current class for which building this exception class						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class DB_PDO_Exceptions extends PhpJar_Exception {}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\database;
use phpJar\database\drivers;

use phpJar\Exceptions as _exceptions;
use phpJar;
/**
 * @abstract DB -- Abstract Class which implement database layer												*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage database																														*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class DB_PDO
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 * constant for fetch all result at onces
	 * @var unknown_type
	 */
	const _FETCH_ALL = 'all';
	/**
	 * constant for fetch all result row per row
	 * @var string
	 */
	const _FETCH_ROW = 'row';

	const _ERROR_DELETE = 1011;
	const _ERROR_FIND_SYSTEM_REC = 1012;
	const _ERROR_DISK_FULL = 1021;
	const _ERROR_DUBLICATE_KEY = 1022;
	const _ERROR_READ = 1024;
	const _ERROR_DUBLICATE = 1062;

	/**
	 * index for column type of current table
	 * @var string
	 */
	const _TABLE_FIELDS_BTYPE = 'bind_type';
	/**
	 * index for column type of current table
	 * @var string
	 */
	const _TABLE_FIELDS_CTYPE = 'c_type';
	/**
	* index for default values of current table
	 * @var string
	 */
	const _TABLE_FIELDS_DEFAULT = 'default';
	/**
	 * index for primary key of current table
	 * @var string
	 */
	const _TABLE_FIELDS_PKEYS = 'primary';
	/**
	 * index for field type of current table
	 * @var string
	 */
	const _TABLE_FIELDS_TYPE = 'type';
	/**
	 * character for escaping string on database query
	 * @var string
	 */
	protected $_escape_char;
	/**
	 * Database module Object
	 * @var object
	 */
	protected $_oDriver;
	/**
	 * String which curry database host name
	 * @var string
	 */
	private $_dbHost;
	/**
	 * String which curry database name
	 * @var string
	 */
	private $_dbName;
	/**
	 * String which curry database port
	 * @var string
	 */
	private $_dbPort;
	/**
	 * the singleton instance for class
	 * @access protected
	 * @static
	 * @var $instance Class instance object
	 */
	protected static $_instance;
	/**
	 * PDO object for communication with databases
	 * @var object
	 */
	private $_PDO;
	/**
	 *
	 * @var PDOSTATEMENT object
	 */
	public $_statement;
	/**
	 * all table wich are available on current instance
	 * structure table name as index
	 * field as second index wixh contain an array with default values, primary key flag,
	 * column type and field type
	 * @var array
	 */
	protected $_tableList;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 * Return full table lsit or contents of index table
	 * @final
	 * @access public
	 * @param string $index
	 * @return array
	 */
	final public function _getTableList($index = false)
	{
		if(!$prefix)
			return $this->_tableList;
		else
			return $this->_tableList[$index];
	}
	/**
	 * Return specific table full contents, or from given table return specifict column iinfo
	 * @final
	 * @access public
	 * @param string $table
	 * @param string $column
	 * @return array
	 */
	final public function _getTableColumn($table,$column = '')
	{
		if($column === '')
			return $this->_tableList[$table];
		return $this->_tableList[$table][$column];
	}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 * Set fields info on table list array for given table
	 * @final
	 * @access public
	 * @param string $table
	 * @return null
	 */
	final public function _setTablefields($table)
	{
		$fields = $this->_describeTable($table);
		$this->_tableList[$table] = $fields;
	}
	/**
	 * Set table list for current instance
	 * with all table from scheme or with table which theirs name likes with prefix
	 * @final
	 * @access public
	 * @param string $prefix
	 * @return null
	 */
	final public function _setTableList($prefix = false)
	{
		$list = $this->_dbScan($prefix);
		if(!empty($this->_tableList) && count($this->_tableList) > 0)
		{
			$diff = array_dif($list,$this->_tableList);
			$diff1 = array_dif($this->_tableList,$list);
			if(!empty($diff) || !empty($diff1))
				$this->_tableList = $list;
		}
	}
	/**
	 * DB Constructor
	 * @access public
	 * @param object $oDriver
	 * @param string $dbDriver
	 * @param string $dbHost
	 * @param string $dbName
	 * @param integer $dbPort
  	 * @return DB_PDO object
	 */
	protected function __construct($oDriver,$dbDriver,$dbHost,$dbName,$dbPort)
	{
		$this->_oDriver = $oDriver;
		$this->_dbHost = $dbHost;
		$this->_dbName = $dbName;
		$this->_dbPort = $dbPort;
		$this->_connect($dbDriver);

		if(method_exists($this->_oDriver,'_get_escape_char'))
			$this->_escape_char = $this->_oDriver->_get_escape_char();
		else
			$this->_escape_char = '`';

//		$this->_setTableList();
	}
	/**
	 * Overloading methods
	 * make all PDO class method and PDOStatement methods
	 * accessibale from this class
	 * @param sting $name
	 * @param array $arguments
	 */
	public function __call($name, $arguments)
	{
		if(method_exists($this->_PDO,$name))
			return call_user_func_array(array($this->_PDO,$name),$arguments);
	}
	/**
	 * Disable clone method
	 * @final
	 * @access protected
	 * @return null
	 */
	final protected  function __clone(){}
	/**
	 * Destroy the instance
	 * @access public
	 * @return null
	 */
	final public function __destruct()
	{
		if(!empty($this->_statement))
			$this->_statement->closeCursor();
		$this->_PDO = null;
		static::$_instance = null;
	}
	/**
	 * Connect to database, accordind selected module
	 * @final
	 * @access private
	 * @param string $dbDriver
	 * @return null
	 */
	final private function _connect($dbDriver)
	{
		$_HostCertificate = $this->_getHostCertificate();
		if($dbDriver == 'Mysql')
			$this->_PDO = new \PDO(
															sprintf('mysql:host=%s;dbname=%s',$this->_dbHost,$this->_dbName),
															$_HostCertificate['user'],$_HostCertificate['passwd'],
															array(
																		\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
																		\PDO::ATTR_PERSISTENT => true,
															)
														);
		elseif($dbDriver == 'Postgres')
			$this->_PDO = new \PDO(
															sprintf('psql:host=%s;dbname=%s;user=%s;password=%s',
															$this->_dbHost,$this->_dbName,$_HostCertificate['user'],$_HostCertificate['passwd']),
															array(
																		\PDO::ATTR_PERSISTENT => true,
															)
														);
		if(!$this->_PDO)
		{
			$msg = sprintf(phpJar\Language::_getSpecificErrorLanguage()->db->connect,get_class($this->_oDriver));
			_exceptions\DB_PDO_Exceptions::throwException($msg);
		}
		$this->_setAttribute();
	}
	/**
	 * find all table from database and create an array with index  theirs name
	 * @final
	 * @access protected
	 * @param string $prefix
	 * @return array
	 */
	final protected function _dbScan($prefix = false)
	{
		$list = array();
		$dbName = $this->_escape_char.$this->_dbName.$this->_escape_char;
		$query = $this->_oDriver->{__FUNCTION__}($dbName,$prefix);
		$list = current($this->_queryFetch($query,self::_FETCH_ALL,\PDO::FETCH_NAMED));
		if(!empty($list))
		{
			sort($list);
			$list = array_flip($list);
			foreach ($list as &$table)
				$table = array();
		}
		return $list;
	}
	/**
	 * Describe selected table and create an array with each column information
	 * @final
	 * @access protected
	 * @param unknown_type $table
	 * @return array
	 */
	final protected function _describeTable($table)
	{
		$this->_tableExists($table);
		$columnList = array();
		$query = $this->_oDriver->{__FUNCTION__}($table);
		$columns = $this->_queryFetch($query,self::_FETCH_ALL);
		if(!empty($columns))
		{
			foreach ($columns as $column)
			{
				$t_query = $this->_oDriver->{__FUNCTION__.'ColumnType'}($table,$column->field);
				$t_columns = $this->_queryFetch($t_query,self::_FETCH_ROW,\PDO::FETCH_ASSOC);

				$columnList[$column->field] = array(
																					self::_TABLE_FIELDS_BTYPE => $this->_findBindType(current($t_columns)),
																					self::_TABLE_FIELDS_CTYPE => current($t_columns),
																					self::_TABLE_FIELDS_TYPE => next($t_columns),
																					self::_TABLE_FIELDS_DEFAULT => next($t_columns),
																					self::_TABLE_FIELDS_PKEYS => false
																		);
			}
			ksort($columnList);
			$p_query = $this->_oDriver->{__FUNCTION__.'PrimaryKeys'}($table);
			$p_columns = $this->_queryFetch($p_query,self::_FETCH_ALL,\PDO::FETCH_ASSOC);
			if(!empty($p_columns))
				foreach ($p_columns as $c)
					$columnList[$c['column_name']][self::_TABLE_FIELDS_PKEYS] = true;

		}
		return $columnList;
	}
	/**
	 *
	 *
	 * @param integer $errorCode
	 */
	public function _errorTranslation($errorCode)
	{
		$newCode = $this->_oDriver->{__FUNCTION__}($errorCode);
		if($newCode == '')
			$newCode = $errorCode;
		return $newCode;
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param string $string
	 * @return string|1|0
	 */
	final public function _escape_string($string)
	{
		if(is_string($string))
			return $this->_PDO->quote($string);
		if(is_bool($string))
			return ($string === false)?0:1;
		elseif(is_null($string))
			return $this->_PDO->quote(null);
		return $string;
	}
	/**
	 * chcek if given column exists on table description
	 * if not re scan table for changes
	 * @final
	 * @access protected
	 * @param string $table
	 * @param string $name
	 * @return true
	 */
	final protected function _fieldExists($table,$name)
	{
		$this->_tableExists($table);
		if(!array_key_exists($name,(array)$this->_tableList[$table]))
		{
			$columns = $this->_describeTable($table);
			if(!array_key_exists($name,$columns))
			{
				$msg = sprintf(phpJar\Language::_getSpecificErrorLanguage()->db->existsColumn,$table,$name);
				_exceptions\DB_PDO_Exceptions::throwException($msg);
			}
			$this->_tableList[$table] = $columns;
		}
		return true;
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param string $type
	 * @return integer
	 */
	final public function _findBindType($type , array $mapArray = array())
	{
		if(empty($mapArray))
			$mapArray = self::_mapType();
		if(!(in_array($type,$mapArray['string']) === false) || !(in_array($type,$mapArray['numeric']) === false))
			return  \PDO::PARAM_STR;
		elseif(!(in_array($type,$mapArray['int']) === false))
			return \PDO::PARAM_INT;
		elseif(!(in_array($type,$mapArray['boolean']) === false))
			return \PDO::PARAM_BOOL;
		elseif(!(in_array($type,(array)$mapArray['null']) === false))
			return \PDO::PARAM_NULL;
		else
			return \PDO::PARAM_LOB;
	}
	/**
	 *Singleton instance
	 * @access public
	 * @static
	 * @param string $dbDriver
	 * @param string $dbHost
	 * @param string $dbName
	 * @param integer $dbPort
	 * @return DB object instance
	 */
	final public static function _getInstance($dbDriver,$dbHost = null,$dbName = null,$dbPort = null)
	{
		try
		{
			$driverClass = "\\phpJar\\database\\drivers\\".$dbDriver.'_PDO';
			if(!class_exists($driverClass))
			{
				$msg = sprintf(phpJar\Language::_getSpecificErrorLanguage()->db->driver_missing,$dbDriver);
				_exceptions\DB_PDO_Exceptions::throwException($msg);
			}
			$_oDriver = new $driverClass();
			$_HostCertificate = array();
			if(empty($dbHost) || empty($dbName) || empty($dbPort))
			{
				if(!method_exists($_oDriver,'_getHostCertificate'))
				{
					$msg = sprintf(phpJar\Language::_getSpecificErrorLanguage()->db->driver_method,'_getHostCertificate');
					_exceptions\DB_PDO_Exceptions::throwException($msg);
				}
				$_HostCertificate = $_oDriver->_getHostCertificate();
			}
			if(empty($dbHost))
				$dbHost = key($_HostCertificate);
			if(empty($dbName))
				$dbName = key($_HostCertificate[$dbHost]);
			if(empty($dbPort))
				$dbPort = $_HostCertificate[$dbHost][$dbName]['port'];
			if(!self::$_instance[$dbHost][$dbName])
				self::$_instance[$dbHost][$dbName] = new self($_oDriver,$dbDriver,$dbHost,$dbName,$dbPort);
			return self::$_instance[$dbHost][$dbName];
		}
		catch (_exceptions\PhpJar_Exception $e){die($e->getMessage());}
	}
	/**
	 *
	 * @final
	 * @access private
	 * @param unknown_type $full
	 * @return false if module class does not have '_getHostCertificate' as method
	 * else return method result
	 */
	final private function _getHostCertificate($full = false)
	{

		$available_dbs = $this->_oDriver->_getHostCertificate();
		if(array_key_exists($this->_dbHost,$available_dbs))
		{
			if(array_key_exists($this->_dbName,$available_dbs[$this->_dbHost]))
				return $available_dbs[$this->_dbHost][$this->_dbName];
		}
		return false;
	}
	/**
	 * Map type
	 * @access public
	 * @return array
	 */
	public function _mapType()
	{
		return array(
								'int' => array('int','int2','int4','int8','bigint','smallint','tinyint'),
								'numeric' => array('numeric','float8'),
								'string' => array('varchar','text','character','date','time','timestamp'),
								'boolean' => array('bool')
					);
	}
	/**
	 * Execute given query
	 * @final
	 * @access public
	 * @param string $query
	 * @return PDO statement object
	 */
	final public function _query($query)
	{
		$this->_statement = $this->query($query);
		return $this->_statement;
	}
	/**
	 * Execute and fetch given query
	 * @final
	 * @access public
	 * @param string $query
	 * @param string $record
	 * @param int $fetch
	 * @return fetch result
	 */
	final public function _queryFetch($query, $record = self::_FETCH_ROW, $fetch = \PDO::FETCH_OBJ)
	{
		$this->_query($query);
		if(empty($this->_statement))
			return false;
		if($record == self::_FETCH_ROW)
			$row = $this->_statement->fetch($fetch);
		elseif($record == self::_FETCH_ALL)
			$row = $this->_statement->fetchAll($fetch);
		return $row;
	}
	/**
	 * Set connection options
	 * @final
	 * @access private
	 */
	final private function _setAttribute()
	{
		$this->_PDO->setAttribute(\PDO::ATTR_CASE,\PDO::CASE_LOWER);
		$this->_PDO->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
	}
	/**
	 * Check if given table name exists on database
	 * @final
	 * @access public
	 * @param string $table
	 * @return return true if table exists else throw error
	 */
	final public function _tableExists($table)
	{
		if(!array_key_exists($table,(array)$this->_tableList))
		{
			$list = $this->_dbScan($table);
			if(!isset($list[$table]))
			{
				$msg = sprintf(phpJar\Language::_getSpecificErrorLanguage()->db->existsTable,$table);
				_exceptions\DB_PDO_Exceptions::throwException($msg);
			}
			$this->_tableList[$table] = $list[$table];
		}
		return true;
	}
}
?>