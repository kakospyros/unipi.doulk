<?php
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\Exceptions;
/**
 * @final SchemeQueries_Exception Class - Exception class for follow class								*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class SchemeQueries_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar;
use phpJar\database as _database;
use project\Exceptions as _pexceptions;
/**
 * @abstract SchemeQueries	 -- Abstract Class																					*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage database																														*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
abstract class SchemeQueries	extends \phpJar\database\SchemeQueries
{
	/***********************************
	 * Queries implementation Area *
	 ***********************************/
	/**
	 *
	 * @access public
	 * @param string $where
	 * @param array $childList
	 * @return null
	 */
	public function _deleteRecordsInclude($where = null, array $list = array())
	{
		try{
			$table = $this->_getTable();

			if(empty($list))
				$list = array(0);

			foreach ($list as $k => $v)
			{
				$key[] = ':id_'.$k;
				$val['id_'.$k] = $v;
			}
			$inClause = implode(',',$key);

			$query = sprintf("DELETE t FROM
											%s AS t
											WHERE t.id > 0
											%s
											AND t.id IN (%s)
											",$table, $where,$inClause);
			$data = array('id' => $val);
			$rs = $this->_prepareExecute($query,$data);
			if(!$rs)
				_pexceptions\SchemeQueries_Exceptions::throwException('deletion error');
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\SchemeQueries_Exceptions::throwException($e->getMessage());
		}
	}

	public function _deleteRecordsChildInclude($where = null, array $list = array())
	{
		try{
			$table = $this->_getTable();

			if(empty($list))
				$list = array(0);

			foreach ($list as $k => $v)
			{
				$key[] = ':id_'.$k;
				$val['id_'.$k] = $v;
			}
			$inClause = implode(',',$key);

			$query = sprintf("DELETE t FROM
											%s AS t
											WHERE t.id > 0
											%s
											AND t.child_id IN (%s)
											",$table, $where,$inClause);
			$data = array('id' => $val);
			$rs = $this->_prepareExecute($query,$data);
			if(!$rs)
				_pexceptions\SchemeQueries_Exceptions::throwException('deletion error');
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\SchemeQueries_Exceptions::throwException($e->getMessage());
		}
	}
	/**
	 *
	 * @access public
	 * @param string $where
	 * @param array $childList
	 * @return null
	 */
	public function _deleteRecordsNotInclude($where = null, array $childList = array())
	{
		try{
			$table = $this->_getTable();

			if(empty($childList))
				$childList = array(0);

			foreach ($childList as $k => $v)
			{
				$key[] = ':_id'.$k;
				$val['_id'.$k] = $v;
			}
			$inClause = implode(',',$key);

			$query = sprintf("DELETE t FROM
											%s AS t
											WHERE t.id > 0
											%s
											AND t.child_id NOT IN (%s)
											",$table, $where,$inClause);
			$data = array('id' => $val);
			$rs = $this->_prepareExecute($query,$data);
			if(!$rs)
				_pexceptions\SchemeQueries_Exceptions::throwException('list deletion error');
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\SchemeQueries_Exceptions::throwException($e->getMessage());
		}
	}
	/**
	 *
	 * @access public
	 * @param integer $parent_id
	 * @param array $childList
	 * @return TRUE on success or FALSE on failure
	 */
	public function _insertMultiRecords($parent_id, array $childList )
	{
		try{
			$table = $this->_getTable();

			if(empty($childList))
				_exceptions\Scheme_Exceptions::throwException('list is empty for insertion on link table');
			$data = $this->_getAttrs();
			unset($data['id']);
			$fields = array_keys($data);
			$data['parent_id'] = $parent_id;
			$this->_fillBasic($data,true);
			$query = static::_querySave($this->_getTable(),$fields,$this->_primaryKeys);
			$this->_db->_statement = $this->_db->prepare($query);
			foreach ($childList as $child)
			{
				$data['child_id'] = $child;
				$this->_bind($data);
				$exec = $this->_db->_statement->execute();
			}
			if(!$exec)
				_pexceptions\SchemeQueries_Exceptions::throwException('list deletion error');
			return $exec;
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\SchemeQueries_Exceptions::throwException($e->getMessage());
		}
	}
	/**
	 *
	 * @access public
	 * @param string $where
	 * @param array $fields
	 * @param array $limit
	 * @param boolean $singleValues
	 * @return array
	 */
	public function _selectFilterArray($where, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$table = $this->_getTable();

		if(!empty($fields))
			$_fields = implode(' , ',$fields);
		else
			$_fields = 't.*';

		$query = sprintf("SELECT DISTINCT %s
										FROM %s AS t
										WHERE t.id > 0
										%s
										",
						$_fields,$table, $where);
		if(!empty($limit))
			$query .= sprintf(" LIMIT %d OFFSET %d",current($limit),next($limit));
//		print $query;
		$rs = $this->_db->_queryFetch($query,_database\DB_PDO::_FETCH_ALL,\PDO::FETCH_ASSOC);
		if($singleValues && count($fields) == 1 && count($rs))
		{
			$_rs = array();
			foreach ($rs as $row)
				$_rs[] = current($row);
			return $_rs;
		}
		if($singleValues && count($fields) == 2 && count($rs))
		{
			$_rs = array();
			foreach ($rs as $row)
				$_rs[array_shift($row)] = array_shift($row);
			return $_rs;
		}
		else if($singleValues && count($fields) > 1 && count($rs))
		{
			$_rs = array();
			foreach ($rs as $row)
			{
				$_rs[] = array_values($row);
			}
			return $_rs;
		}
		return $rs;
	}
	/**
	 *
	 * @access public
	 * @param string $inner
	 * @param string $where
	 * @param array $fields
	 * @param array $limit
	 * @param boolean $singleValues
	 * @return array
	 */
	public function _selectInnerFilterArray($inner,$where = null, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$table = $this->_getTable();
		if(!empty($fields))
			$_fields = implode(' , ',$fields);
		else
			$_fields = 't.*';

		$query = sprintf("SELECT DISTINCT %s
										FROM %s AS t
										%s
										WHERE t.id > 0
										%s
										",
						$_fields,$table, $inner, $where);
		if(!empty($limit))
			$query .= sprintf(" LIMIT %d OFFSET %d",current($limit),next($limit));
//		print $query;
		$rs = $this->_db->_queryFetch($query,_database\DB_PDO::_FETCH_ALL,\PDO::FETCH_ASSOC);
		if($singleValues && count($fields) == 1 && count($rs))
		{
			$_rs = array();
			foreach ($rs as $row)
				$_rs[] = current($row);
			return $_rs;
		}
		if($singleValues && count($fields) == 2 && count($rs))
		{
			$_rs = array();
			foreach ($rs as $row)
				$_rs[array_shift($row)] = array_shift($row);
			return $_rs;
		}
		else if($singleValues && count($fields) > 1 && count($rs))
		{
			$_rs = array();
			foreach ($rs as $row)
			{
				$_rs[] = array_values($row);
			}
			return $_rs;
		}
		return $rs;
	}
	/**
	 *
	 * @access public
	 * @param string $where
	 * @param array $fields
	 * @param array $limit
	 * @return new instance of SchemeObject
	 */
	public function _selectFilterRecords($where = null, array $fields = array(), array $limit = array())
	{
		$table = $this->_getTable();
		if(!empty($fields))
			$_fields = implode(' , ',$fields);
		else
			$_fields = 't.*';
		$query = sprintf("SELECT DISTINCT %s
										FROM %s AS t
										WHERE t.id > 0
										%s
										",
										$_fields,$table, $where);
		if(!empty($limit))
			$query .= sprintf(" LIMIT %d OFFSET %d",current($limit),next($limit));
//		print_r($query);
		$rs = $this->_db->_queryFetch($query,_database\DB_PDO::_FETCH_ALL,\PDO::FETCH_ASSOC);
		return new _database\SchemeObject($rs,get_called_class());
	}
	/**
	 *
	 * @access public
	 * @param string $inner
	 * @param string $where
	 * @param array $fields
	 * @param array $limit
	 * @return new instance of SchemeObject
	 */
	public function _selectInnerFilterRecords($inner, $where = null, array $fields = array(), array $limit = array())
	{
		$table = $this->_getTable();
		if(!empty($fields))
			$_fields = implode(' , ',$fields);
		else
			$_fields = 't.*';

		$query = sprintf("SELECT DISTINCT %s
										FROM %s AS t
										%s
										WHERE t.id > 0
										%s
										",
										$_fields,$table, $inner,$where);
		if(!empty($limit))
			$query .= sprintf(" LIMIT %d OFFSET %d",current($limit),next($limit));
//		print_r($query);
		$rs = $this->_db->_queryFetch($query,_database\DB_PDO::_FETCH_ALL,\PDO::FETCH_ASSOC);
		return new _database\SchemeObject($rs,get_called_class());
	}
	/**
	 *
	 * @access public
	 * @param string $where
	 * @return new instance of current scheme or false on failure
	 */
	public function _selectFilterRecordsingle($where)
	{
		$table = $this->_getTable();
		$query = sprintf("SELECT DISTINCT t.*
										FROM %s AS t
										WHERE t.id > 0
										%s
										",$table, $where);
		$rs = $this->_db->_queryFetch($query,_database\DB_PDO::_FETCH_ROW,\PDO::FETCH_ASSOC);
		if($rs)
			return new static($rs);
		return false;
	}
	/**
	 *
	 * @access public
	 * @param string $reg_field
	 * @param string $regExp
	 * @param string $where
	 * @param array $fields
	 * @param array $limit
	 * @param boolean $singleValues
	 * @return _selectFilterArray result
	 */
	public function _selectFilterArrayREGEXP($reg_field, $regExp, $where, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$regExp = sprintf(" AND %s REGEXP '%s' ",$reg_field,$regExp);
		$where .= $regExp;
		return $this->_selectFilterArray($where,$fields,$limit,$singleValues);
	}
}
?>