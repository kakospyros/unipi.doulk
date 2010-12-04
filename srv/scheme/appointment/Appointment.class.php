<?php
namespace project\Exceptions;
/**
 * @final Appointment_Exception Class - Exception class for follow class											*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see project\Users Current class for which building this exception class									*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Appointment_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar\database as _database;
use project\Exceptions as _pexceptions;
/**
 * Appointment	 --  																																			*
 * @uses /phpJar/database/SchemeSpecs#_constructor()																*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Appointment extends SchemeQueries
{

	const _ACTION_ARCHIVED = -1;
	const _ACTION_DAY = 0;
	const _ACTION_MONTH = 30;
	const _ACTION_WEEK = 7;
	const _ACTION_YEAR = 365;

	public function _deleteRecordsInclude($where = null, array $list = array())
	{
		try{
			parent::_deleteRecordsInclude($where,$list);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Appointment_Exceptions::throwException('Appointment list deletion error ! ');
		}
	}

	public function _selectInnerFilterArrayByTime($time = '*',$where = null, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$inner = array();
		$inner[] = 'INNER JOIN users AS d ON d.id = t.parent_id';
		$inner[] = 'INNER JOIN doctor_specialty AS ds ON ds.parent_id = d.id';
		$inner = implode(' ',$inner);
		if(!($time === '*'))
		{
			if($time == self::_ACTION_DAY)
				$where .= sprintf(' AND DATEDIFF(CURDATE(),t.schedule_time) < 1 AND DATEDIFF(CURDATE(),t.schedule_time) >= 0');
			elseif($time == self::_ACTION_WEEK)
				$where .= sprintf(' AND DATEDIFF(CURDATE(),t.schedule_time) < 7 AND DATEDIFF(CURDATE(),t.schedule_time) >= 0');
			elseif($time == self::_ACTION_MONTH)
				$where .= sprintf(' AND MONTH(CURDATE()) = MONTH(t.schedule_time)');
			elseif($time == self::_ACTION_YEAR)
				$where .= sprintf(' AND YEAR(CURDATE()) = YEAR(t.schedule_time)');
		}
		return $this->_selectInnerFilterArray($inner,$where,$fields,$limit,$singleValues);
	}

	public function _selectInnerFilterRecordsByTime($time = '*',$where = null, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$inner = array();
		$inner[] = 'INNER JOIN users AS d ON d.id = t.parent_id';
		$inner[] = 'INNER JOIN doctor_specialty AS ds ON ds.parent_id = d.id';
		$inner = implode(' ',$inner);
		if(!($time === '*'))
		{
			if($time == self::_ACTION_DAY)
				$where .= ' AND ( DAYOFYEAR(NOW()) = DAYOFYEAR(t.schedule_time) ) AND ( YEAR(CURDATE()) = YEAR(t.schedule_time) ) AND ( UNIX_TIMESTAMP(NOW()) < UNIX_TIMESTAMP(t.schedule_time) )';
			elseif($time == self::_ACTION_WEEK)
				$where .= ' AND ( WEEKOFYEAR(NOW()) = WEEKOFYEAR(t.schedule_time) ) AND ( YEAR(CURDATE()) = YEAR(t.schedule_time) ) AND ( UNIX_TIMESTAMP(NOW()) < UNIX_TIMESTAMP(t.schedule_time) )';
			elseif($time == self::_ACTION_MONTH)
				$where .= ' AND ( MONTH(NOW()) = MONTH(t.schedule_time) ) AND ( YEAR(CURDATE()) = YEAR(t.schedule_time) ) AND ( UNIX_TIMESTAMP(NOW()) < UNIX_TIMESTAMP(t.schedule_time) )';
			elseif($time == self::_ACTION_YEAR)
				$where .= ' AND YEAR(NOW()) = YEAR(t.schedule_time) AND ( UNIX_TIMESTAMP(NOW()) < UNIX_TIMESTAMP(t.schedule_time) ) AND ( UNIX_TIMESTAMP(NOW()) < UNIX_TIMESTAMP(t.schedule_time) )';
			elseif($time == self::_ACTION_ARCHIVED)
				$where .= ' AND  ( UNIX_TIMESTAMP(NOW()) > UNIX_TIMESTAMP(t.schedule_time) )';
		}
		return $this->_selectInnerFilterRecords($inner,$where,$fields,$limit,$singleValues);
	}
}
?>