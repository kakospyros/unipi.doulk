<?php
/*****************************************************************************************************
 * SchemeQueries "Database Table view"																								*
 *****************************************************************************************************/
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\database;
use phpJar;
use phpJar\database as _database;
use phpJar\Exceptions as _exceptions;
use phpJar\database\drivers as _drivers;
/**
 * @abstract SchemeQueries	 -- Abstract Class																					*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage database																														*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
abstract class SchemeQueries	extends Scheme
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/***************************
	 * Abstract Method Area 	*
	 ***************************/
	/***********************************
	 * Queries implementation Area *
	 ***********************************/

	public static function _queryDelete($table, $fields)
	{
		array_walk($fields,array(self,'_implodeQueryPrepare'));
		$query = sprintf('DELETE FROM %s WHERE %s'
										,$table
										,implode(',',$fields)
										);
		return $query;
	}

	public static function _querySave($table, $fields)
	{
		$vals = $fields;
		array_walk($vals,array(self,'_implodeQueryValues'));
		array_walk($fields,array(self,'_implodeQueryValuesPrepare'));
		$query = sprintf('INSERT INTO %s (%s) VALUES (%s)'
										,$table
										,implode(', ',$vals)
										,implode(', ',$fields)
										);
		return $query;
	}

	public static function _querySelect($table, $fields)
	{
		$query = sprintf('SELECT * FROM %s WHERE %s'
										,$table
										,self::_implodeQuerySet(key($fields),current($fields))
										);
		return $query;
	}

	public static function _queryUpdate($table, $fields, array $pk = array())
	{
		array_walk($fields,array(self,'_implodeQueryPrepare'));
		array_walk($pk,array(self,'_implodeQueryPrepare'));
		$query = sprintf('UPDATE %s SET %s WHERE %s'
										,$table
										,implode(',',$fields)
										,implode(',',$pk)
										);
//		print_r(array($fields,$pk,$query));
		return $query;
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *  SchemeQueries constructor,
	 *  @access public
	 * @param array $record
	 * @see /phpJar/database/SchemeSpecs#$_needAuthorize
	 * @return mixed a class instance
	 */
	public function __construct(array $data =array() ){return parent::__construct($data);}
	/**
	 *  Delete one Record
	 *  Delete the current record from table based on primary keys values
	 *  in case where the table does not have primary key, the method throw an exception
	 * @access public
	 * @uses /phpJar/database/Scheme_Exceptions#throwException()
	 * @uses /phpJar/database/Scheme#_execute($query,$SchemeObject)
	 * @return on success true, on failure throw an exception
	 */
	public function _delete()
	{
		foreach ($this->_primaryKeys as $info)
		{
			if(is_null($this->{$info}))
				_exceptions\Scheme_Exceptions::throwException('delete action has been denied from system');
			$fields[$info] = $this->{$info};
		}
		$query = static::_queryDelete($this->_getTable(),$this->_primaryKeys);
		$this->_prepareExecute($query,$fields);
		return true;
	}
	/**
	 * Insert a new record on the table
	 * And get the primary keys values for this record
	 * @final
	 * @access private
	 * @param array $data
	 * @uses /phpJar/database/Scheme#_execute($query,$SchemeObject)
	 * @uses /phpJar/database/server/DB#fetchArray($result)
	 * @return on success true, on failure DB_Exceptions
	 */
	final private function _doSave(array $data)
	{
		$fields = array_keys($data);
		$query = static::_querySave($this->_getTable(),$fields,$this->_primaryKeys);
		$this->_prepareExecute($query,$data);
		if(property_exists($this,phpJar\PRIMARY_KEY) && !(in_array(phpJar\PRIMARY_KEY,$this->_primaryKeys) === false) )
			$data[phpJar\PRIMARY_KEY] = $this->_db->lastInsertId();
		$this->_setProperties($data,false);

		return true;
	}
	/**
	 * Update an existing record on the scheme table
	 * @final
	 * @access private
	 * @param array $data
	 * @uses /phpJar/database/Scheme#$_primaryKeys
	 * @uses /phpJar/database/Scheme#_execute($query,$SchemeObject)
	 * @return null
	 */
	final private function _doUpdate(array $data)
	{
		$_data = $data;
		foreach ($this->_primaryKeys as $info)
		{
			if(isset($_data[$info]))
				unset($_data[$info]);
		}
		$fields = array_keys($_data);
		$query = static::_queryUpdate($this->_getTable(),$fields,$this->_primaryKeys);
		$this->_prepareExecute($query,$data);
//		$this->_db->_statement->debugDumpParams();
	}
	/**
	 *
	 * @param unknown_type $field
	 * @param unknown_type $value
	 * @param unknown_type $column
	 */
	final public function _getRecordByField($field,$value,$column = 0)
	{
		$fields = array($field=>$value);
		$query = static::_querySelect($this->_getTable(),$fields);
		$this->_prepareExecute($query,$fields);
		if($column === 0)
		{
//			$this->_db->_statement->debugDumpParams();
			$rs = $this->_db->_statement->fetch(\PDO::FETCH_ASSOC);
			return new static($rs);
		}
		else
		{
			$rs = $this->_db->_statement->fetchAll(\PDO::FETCH_ASSOC);
			return new SchemeObject($rs,__CLASS__);
		}
	}
	/**
	 *  Automatic save or update a record from current table
	 *  if primary keys are been set then proceed with update else id primary key values
	 *  missing or $force argument is true proceed with save action
	 * @access public
	 * @param boolean $force
	 * @uses /phpJar/database/Scheme#_getAttrs()
	 * @uses /phpJar/database/Scheme#_quoteValues($key,$value)
	 * @see /phpJar/database/Scheme#_doSave($data)
	 * @see /phpJar/database/Scheme#_update($data)
	 * @return null
	 */
	public function _save($force = false)
	{
		if(!$force)
		{
			foreach ($this->_primaryKeys as $info)
			{
				if(trim($this->{$info}) == null)
				{
					$force = true;
					break;
				}
			}
		}
		$data = $this->_getAttrs();
		array_walk($data,array($this, '_quoteValues'));
		if($force)
		{
			$this->_doSave($data);
		}
		else
		{
			$this->_doUpdate($data);
		}
	}

}
?>