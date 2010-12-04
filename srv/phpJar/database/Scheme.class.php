<?php
/*****************************************************************************************************
 * Scheme "Database Table view"																											*
 * Control/use a database table. This class is a full representation of selected table from			*
 * database,																																					*
 * Include useful information for this table, as the full specifications for											*
 * each of fields that this table contains and the primary keys															*
 * Add/Delete record(s)																																*
 * Retrieve record(s)																																	*
 * Modify record(s)																																		*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Scheme_Exceptions Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see phpJar\database\Scheme Current class for which building this exception class			*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Scheme_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\database;
use phpJar;
use phpJar\Exceptions as _exceptions;
use phpJar\database as _database;
use phpJar\utils as _utils;
/**
 * @abstract Scheme	 -- Abstract Class																									*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage database																														*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
abstract class Scheme
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	const _DEFAULT_ID = 1;
	/**
	 * An associative array which carry all the fields name from the table and their type
	 * @access protected
	 * @var array $_attrs
	 */
	protected $_properties = array();
	/**
	 * Instance of the selected DB Class
	 * @access protected
	 * @see Scheme#$_dbDriver
	 * @see phpJar/database/DB#_getHostCertificate()
	 * @var mixed $_db
	 */
	protected $_db;
	/**
	 * the hostname of the remote server, where the database is
	 * the hostname can be as key on the _getHostCertificate of the
	 * server implementation on DB class or null if it is the default selection from
	 * DB class which has been declared on $_dbDriver
	 * @access protected
	 * @see Scheme#$_dbDriver
	 * @see phpJar/database/DB#_getHostCertificate()
	 * @var string $_dbHost
	 */
	private $_dbHost;
	/**
	 * The database name which this scheme has been referenced
	 * the database can be in  _getHostCertificate of the
	 * server implementation on DB class or null if it is the default selection from
	 * DB class which has been declared on $_dbDriver
	 * @access protected
	 * @see Scheme#$_dbDriver
	 * @see phpJar/database/DB#_getHostCertificate()
	 * @var string $_dbName
	 */
	private $_dbName;
	/**
	 * The database server type
	 * The type is the name of a class that extends the DB abstract class
	 * the class that described with this name must be under the namespace phpJar::database
	 * @access protected
	 * @see phpJar/database/DB#__constructor()
	 * @var string $_dbDriver
	 */
	private $_dbDriver;
	/**
	 *  A Flag, which declare if the using for class need authorization or not
	 *  if it set to true then when call this class instance
	 *  the SESSION checked if it set or not
	 * @static
	 * @access protected
	 * @see /phpJar/Authenticated#_sessionCheck()
	 * @var boolean $_needAuthorize
	 */
	protected static $_needAuthorize = true;
	/**
	 * An associative array where carry all the primary keys from the table and their type
	 * @access protected
	 * @var array $_primaryKeys
	 */
	protected $_primaryKeys;
	/**
	 * The table name where this class has been referenced
	 * This table name must appear on selected database
	 * @access protected
	 * @var string $_table
	 */
	private $_table;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 * Get the total of attributes that appearance on table as fields.
	 * the _properties array key with the current value
	 * @final
	 * @access public
	 * @return associative array fileds name with those values
	 */
	final public function _getAttrs()
	{
		$attrs = array();
		foreach ($this->_properties as $attr => $value)
			$attrs[$attr] = $this->{$attr};
		return $attrs;
	}
	/**
	 *  Get attribute value if the attribute exists on attribute array
	 * @final
	 * @access private
	 * @param string $key
	 * @uses /phpJar/database/Scheme#$_properties
	 * @uses /phpJar/database/servers/DB#getFieldMapType()
	 * @uses /phpJar/database/Scheme_Exceptions#throwException()
	 * @return in success if the key exists on _properties mixed, else if doesn't throw Scheme_Exceptions
	 */
	final private function _getAttributeValue($key)
	{
		if(!isset($this->_properties[$key]))
		{
			$msg = phpJar\Language::_getSpecificLanguage()->error->scheme->propertyTable;
			_exceptions\Scheme_Exceptions::throwException($msg);
		}
		$mapArray = $this->_db->_mapType();
		$type = $this->_properties[$key][_database\DB_PDO::_TABLE_FIELDS_TYPE];
		if(in_array($type,$mapArray['string']))
			return sprintf("'%s'",$this->{$key});
		return $this->{$key};
	}
	/**
	 * Get database driver string name
	 * @final
	 * @access protected
	 * @return string
	 */
	final protected function _getDBDriver(){return $this->_dbDriver;}
	/**
	 * Get database host name
	 * @final
	 * @access protected
	 * @return string
	 */
	final protected function _getDBHostName(){return $this->_dbHost;}
	/**
	 * Get database name
	 * @final
	 * @access protected
	 * @return string
	 */
	final protected function _getDBName(){return $this->_dbName;}
	/**
	 * Fill propertiew value with default scheme values or with given values
	 * @final
	 * @access public
	 * @param array $record
	 * @param boolean $fill_default
	 * @return null
	 */
	final public function _setProperties(array $record = array(), $fill_default = true)
	{
		if(!empty($this->_properties))
		{
			foreach ($this->_properties as $column => $info)
			{
				$exists_new = array_key_exists($column,$record);
				if($fill_default && !$exists_new)
					$this->{$column} = $info[_database\DB_PDO::_TABLE_FIELDS_DEFAULT];
				elseif($exists_new)
					$this->{$column} = $record[$column];
			}
		}
	}
	/**
	 * Get the defined table name
	 * @final
	 * @access public
	 * @return string
	 */
	final public function _getTable(){return $this->_table;}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 * Set Database driver
	 * @final
	 * @access protected
	 * @param string $dbHostName
	 * @return null
	 */
	final protected function _setDBDriver($dbDriver = 'Mysql'){$this->_dbDriver = $dbDriver;}
	/**
	 * Set Database host name
	 * @final
	 * @access protected
	 * @param string $dbHostName
	 * @return null
	 */
	final protected function _setDBHost($dbHostName = null){$this->_dbHost = $dbHostName;}
	/**
	 * Set Database name
	 * @final
	 * @access protected
	 * @param string $dbName
	 * @return null
	 */
	final protected function _setDBName($dbName = null){$this->_dbName = $dbName;}
	/**
	 * Set the table name that current scheme object represent
	 * @final
	 * @access protected
	 * @param string $tableName
	 */
	final protected function _setDBTable($tableName = null){$this->_table = $tableName;}
	/**
	 * Get all fields and specification type for the current table
	 * after the getting of table specifications set _properties array
	 * @final
	 * @access private
	 * @uses /phpJar/database/Scheme#$_properties
	 * @uses /phpJar/database/servers/DB#getFieldsInfo($table)
	 * @return false if table definition failed else return true
	 */
	final private function _setFieldsInfo()
	{
		$this->_db->_setTableFields($this->_getTable());
		$columns = $this->_db->_getTableColumn($this->_getTable());
		if(!empty($columns))
		{
			foreach ($columns as $property => $val)
				$this->_properties[$property] = $val;
			return true;
		}
		return false;
	}
	/**
	 * Get primary key fields and specification type for the current table
	 * after the getting of table specifications set _primaryKeys array
	 * @final
	 * @access private
	 * @uses /phpJar/database/Scheme#$_primaryKeys
	 * @uses /phpJar/database/servers/DB#getPrimaryKeyList($table)
	 * @return false if table definition failed else return true
	 */
	final private function _setPrimaryKeyList()
	{
		if(!empty($this->_properties))
		{
			foreach ($this->_properties as $prop => $val)
				if($val[_database\DB_PDO::_TABLE_FIELDS_PKEYS])
					$this->_primaryKeys[] = $prop;
			return true;
		}
		return false;
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *  Scheme constructor,
	 *  check the security level for using this class
	 *  create a connection instance to the current database
	 *  build class attributes
	 *  find the fields and the keys for current table
	 *  and create extra attribute from table fields name
	 *  @access public
	 *  @uses /phpJar/Authenticated#_sessionCheck()
	 *  @uses /phpJar/database/Scheme#_createDBLink()
	 *  @uses /phpJar/database/Scheme#_setFieldsInfo()
	 *  @uses /phpJar/database/Scheme#_setPrimaryKeyList()
	 *  @uses /phpJar/database/Scheme_Exceptions#throwException()
	 * @param array $record
	 * @return mixed a class instance
	 */
	public function __construct(array $record = array(), $defaultVal = true)
	{
		if(static::$_needAuthorize)
			phpJar\Authenticated::_sessionCheck();
		if(empty($this->_dbDriver))
			$this->_setDBDriver();
		$this->_db = $this->_createDBLink();
		$table = trim($this->_table);
		if(empty($table))
		{
			$table = mb_strtolower(\phpJar\utils\System::_getClassName(get_called_class()),'UTF-8');
			$this->_setDBTable($table);
		}
		$this->_setFieldsInfo();
		$this->_setPrimaryKeyList();
		$this->_setProperties($record,$defaultVal);
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param unknown_type $name
	 * @param unknown_type $value
	 * @uses /phpJar/database/Scheme_Exceptions#throwException()
	 */
	final public function __set($name, $value)
	{
		if(array_key_exists($name,(array)$this->_properties))
			$this->{$name} = trim($value);
		else
		{
			$msg = sprintf(phpJar\Language::_getSpecificLanguage()->error->scheme->propertyNotExists, get_class($this), $name);
			_exceptions\Scheme_Exceptions::throwException($msg);
		}
	}
	/**
	 *
	 * @param array $fields
	 */
	final public function _bind(array &$fields)
	{
		$types = array();
		if(!empty($fields))
		{
			foreach ($fields as $field => &$val)
			{
				$data_type = $this->_properties[$field][_database\DB_PDO::_TABLE_FIELDS_BTYPE];
				if(is_array($val))
				{
					foreach ($val as $k => &$v)
						$bind = $this->_db->_statement->bindParam(':'.$k,$v,$data_type|\PDO::PARAM_INPUT_OUTPUT);
				}
				else
					$bind = $this->_db->_statement->bindParam(':'.$field,$val,$data_type|\PDO::PARAM_INPUT_OUTPUT);
			}
		}
	}
	/**
	 *
	 * Start Transaction, turning off autocommit
	 * @final
	 * @access public
	 * @return null
	 */
	final public function _beginTransaction(){$this->_db->beginTransaction();}
	/**
	 * Create an instance on Database class which has been defined on $_dbDriver
	 * @final
	 * @access private
	 * @see /phpJar/database/Scheme#$_dbDriver
	 * @return on success the class instance from predefined class name, on failure return false
	 */
	final private function _createDBLink()
	{
		return \phpJar\database\DB_PDO::_getInstance($this->_getDBDriver(),$this->_getDBHostName(),$this->_getDBName());
	}
	/**
	 *
	 * Commit a transaction,back in autocommit mode
	 * @final
	 * @access public
	 * @return null
	 */
	final public function _commit(){$this->_db->commit();}
	/**
	 * Check if a record exists on the current table
	 * @access protected
	 * @param string $column
	 * @param mixed $value
	 * @param string $exclude
	 * @return true if the record exists else false
	 */
	protected function _elementExists($column, $value, $exclude){}
	/**
	 *
	 * Check error information according driver report
	 * if no error found then return false else return an object with code and driver message
	 * @final
	 * @access public
	 * @return false if no error found, else return an object with error code and driver message
	 */
	final public function _errorInfo()
	{
		if($this->_db->_statement)
			$info = $this->_db->_statement->errorInfo();
		//SQLSTATE check
		if($info[0] == '00000')
			$info = $this->_db->errorInfo();
		//SQLSTATE check
		if($info[0] == '00000')
			return false;
		$error = new \stdClass();
		$error->driver_code = $info[0];
		$error->code = $this->_db->_errorTranslation($info[1]);
		$error->msg = $info[2];
		return $error;
	}
	/**
	 *
	 *
	 * @access public
	 * @param array $data
	 * @param boolean $new
	 * @param integer $user_id
	 * @return null
	 */
	public function _fillBasic(array &$data, $new = false, $user_id = self::_DEFAULT_ID)
	{
		if(isset($this->_properties['create_time']) || isset($this->_properties['modify_time']))
		{
			$dt = new _utils\DT();
			$dt->_convertTzToGmt();
			$isoDate = $dt->_convertDateToIso()->date;
			if($new && isset($this->_properties['create_time']))
				$data['create_time'] = $isoDate;
			else
				unset($data['create_time']);
			if(isset($this->_properties['modify_time']))
				$data['modify_time'] = $isoDate;
		}
		if(isset($this->_properties['user_id']))
			$data['user_id'] = $user_id;
		return $data;
	}
	/**
	 * Check/Convert value to database format for insertion action
	 * encode the string values to utf-8 format
	 * convert the date,time values to database format
	 * @final
	 * @access protected
	 * @uses /phpJar/database/DB#escape($string)
	 * @uses /phpJar/utils/DT#_isIsoFormat($time)
	 * @param mixed $value
	 * @param string $element
	 * @uses /phpJar/database/Scheme_Exceptions#throwException()
	 * @return null
	 */
	final protected function _quoteValues(&$value, $element)
	{
		$mapArray = $this->_db->_mapType();
		if(isset($this->_properties[$element]))
		{
			$values = trim($values);
			$values = strip_tags($values,phpJar\STRIP_ALLOWABLE_TAGS);
			$type = $this->_properties[$element][_database\DB_PDO::_TABLE_FIELDS_TYPE];
			if(!(in_array($type,$mapArray['string']) === false))
			{
				if($type == 'timestamp')
				{
					if(!_utils\DT::_isValidFormat($value))
					{
						$timestamp = new _utils\DT();
					}
					else
					{
						$timestamp = new _utils\DT($value);
					}
					$value = $timestamp->_convertTzToGmt()->date;
				}
				elseif($type == 'date')
				{
					if(!_utils\DT::_isValidFormat($value))
					{
						if(!_utils\DT::_isValidFormat($value,_utils\DT::PART_DATE))
						{
							$timestamp = new _utils\DT();
						}
					}
					else
						$timestamp = new _utils\DT($value);
					$value = _utils\DT::_getPart($timestamp->_convertTzToGmt()->date,_utils\DT::PART_DATE);
				}
				else
					$value = $this->_db->_escape_string($value);
			}
			elseif(!(in_array($type,$mapArray['numeric']) === false))
			{
				if($type == 'float8')
					$value = (empty($value))?0:$value;
			}
			elseif(!(in_array($type,$mapArray['boolean']) === false))
			{
				if($type == 'bool')
					$value = $this->_db->_escape_string($value);
			}
			else
			{
				$value = (empty($value))?0:$value;
			}
		}
	}
	/**
	 * Set primary key values for queries
	 * @final
	 * @access private
	 * @uses /phpJar/database/Scheme#$_primaryKeys
	 *  @return string seperated with 'AND' and include correspondence with the primary keys names - values
	 */
	final private function _getPKattributeValues()
	{
		$clauses = array();
		foreach ($this->_primaryKeys as $info)
			$clauses[] = sprintf("%s = %s",$info,$this->_getAttributeValue($info));
		return implode(' AND ',$clauses);
	}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param unknown_type $k
	 * @return null
	 */
	final public static function _implodeQueryPrepare(&$k){ $k = sprintf('`%s` = :%s',$k,$k);}
	/**
	 * Join 2 variables as key = value, for query string
	 * @final
	 * @access public
	 * @static
	 * @param string $k
	 * @param string $v
	 * @return string
	 */
	final public static function _implodeQuerySet($k, $v){return sprintf('`%s` = %s',$k,$v);}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $v
	 * @return null
	 */
	final public static function _implodeQueryValues(&$v){ $v = sprintf('`%s`',$v);}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $v
	 * @return null
	 */
	final public static function _implodeQueryValuesPrepare(&$v){ $v = sprintf(':%s',$v);}
	/**
	 *
	 * @final
	 * @access public
	 * @param string $query
	 * @param array $fields
	 * @return null
	 */
	final public function _prepareExecute($query,array $fields = array())
	{
		try{
			$this->_db->_statement = $this->_db->prepare($query);
			$this->_bind($fields);
			$exec = $this->_db->_statement->execute();
			return $exec;
		}
		catch(\PDOException $e){_exceptions\Scheme_Exceptions::throwException($e->getMessage(),$this->_errorInfo());}
	}
	/**
	 *
	 * Rollback changes
	 * @final
	 * @access public
	 * @return null
	 */
	final public function _rollback(){$this->_db->rollBack();}
	/**
	 * Validate current object attributes values
	 * base on table fields format types
	 * @access public
	 */
	public function _validate()
	{
		$clone = clone $this;
		$data = $this->_getAttrs();
		array_walk($data, array($this, 'escape'));
		foreach ($data as $key => $value)
			$clone->{$key} = $value;
		return $clone;
	}

}
?>