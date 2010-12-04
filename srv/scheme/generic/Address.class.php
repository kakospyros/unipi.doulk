<?php
namespace project\Exceptions;
/**
 * @final Address_Exception Class - Exception class for follow class							*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Address_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar\database as _database;
use project\Exceptions as _pexceptions;
/**
 * Address	--																																*
 * @uses /phpJar/database/SchemeSpecs#_constructor()																*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Address extends SchemeQueries
{

	public function _deleteRecordsChildInclude($where = null, array $childList = array())
	{
		try{
			parent::_deleteRecordsChildInclude($where,$childList);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Address_Exceptions::throwException('address child list deletion error');
		}
	}

	public function _deleteRecordsNotInclude($where = null, array $childList = array())
	{
		try{
			parent::_deleteRecordsNotInclude($where,$childList);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Address_Exceptions::throwException('address list deletion error');
		}
	}

	public function _insertMultiRecords($parent_id, array $childList )
	{
		try{
			return parent::_insertMultiRecords($parent_id,$childList);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Address_Exceptions::throwException('address list is empty for insertion on link table');
		}
	}

	public function _selectFilterRecordsBaseOnType($oServlet,$parent_id = null, $where = null, array $fields = array(), array $limit = array())
	{
		$table = $this->_getTable();

		$ctable = $oServlet->_callScheme('_getTable');

		if(!empty($fields))
			$_fields = implode(' , ',$fields);
		else
			$_fields = 't.*';

		if(ctype_digit($parent_id))
			$where = sprintf(' AND c.parent_id = %d %s',$parent_id,$where);

		$query = sprintf("SELECT DISTINCT %s
										FROM %s AS t
										INNER JOIN %s AS c
										ON c.child_id = t.id
										WHERE t.id > 0
										%s
										",
										$_fields,$table,$ctable, $where);
		if(!empty($limit))
			$query .= sprintf(" LIMIT %d OFFSET %d",current($limit),next($limit));
//		print_r($query);
		$rs = $this->_db->_queryFetch($query,_database\DB_PDO::_FETCH_ALL,\PDO::FETCH_ASSOC);
		return new _database\SchemeObject($rs,__CLASS__);
	}

	public function _selectFilterArrayBaseOnType($oServlet,$parent_id = null, $where, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$class = get_called_class();
		$oObject = new $class();
		$table = $oObject->_getTable();

		$ctable = $oServlet->_callScheme('_getTable');

		if(!empty($fields))
			$_fields = implode(' , ',$fields);
		else
			$_fields = 't.*';

		if(!empty($fields))
			$_fields = implode(' , ',$fields);
		else
			$_fields = 't.*';

		if(ctype_digit($parent_id))
			$where = sprintf(' AND c.parent_id = %d %s',$parent_id,$where);

		$query = sprintf("SELECT DISTINCT %s
										FROM %s AS t
										INNER JOIN %s AS c
										ON c.child_id = t.id
										WHERE t.id > 0
										%s
										",
										$_fields,$table,$ctable, $where);
		if(!empty($limit))
			$query .= sprintf(" LIMIT %d OFFSET %d",current($limit),next($limit));
//		print $query;
		$rs = $oObject->_db->_queryFetch($query,_database\DB_PDO::_FETCH_ALL,\PDO::FETCH_ASSOC);

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

}
?>