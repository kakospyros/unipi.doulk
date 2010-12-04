<?php
namespace project\Exceptions;
/**
 * @final Users_Exception Class - Exception class for follow class													*
 * @see phpJar\Exceptions\PhpJar_Exception Basic Exception Class												*
 * @see project\Users Current class for which building this exception class									*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Users_Exceptions extends \phpJar\Exceptions\PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace project\scheme;
use phpJar\utils as _utils;
use phpJar\database as _database;
use project\scheme as _pdatabase;
use project\Exceptions as _pexceptions;
/**
 *  Users	 --  																																					*
 * @uses /phpJar/database/SchemeSpecs#_constructor()								*
 * @author Kondylis Andreas																													*
 * @package project																																	*
 * @subpackage scheme																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Users extends SchemeQueries
{
	/**
	 * String to represent the password on the templates
	 * @var string PASSWDMASK
	 */
	const	PASSWDMASK = '*************';
	/**
	 * (non-PHPdoc)
	 * @see /phpJar/database/SchemeSpecs#$_needAuthorize
	 */
	protected static $_needAuthorize = false;
	/**
	 *
	 * @param string $passwd
	 */
	final public function _passwdValidation($passwd, $crypt = false)
	{
		if(!$crypt)
			$hash = _utils\Security::_getSaltedHash($passwd,$this->passwd);
		else
			$hash = $passwd;
		if($hash == $this->passwd)
			return true;
		return false;
	}
	/**
	 *
	 * @final
	 * @access public
	 * @return true if password match else set crypted passwd
	 */
	final public function encryptPasswd()
	{
		if(!empty($this->id))
		{
			$oRecord = $this->_selectFilterRecordsingle(sprintf(' AND t.id = %d',$this->id));
			if ($this->passwd == self::PASSWDMASK)
				$this->passwd = $oRecord->passwd;
			if ($this->passwd == $oRecord->passwd)
				return true;
		}
		$this->passwd =_utils\Security::_getSaltedHash($this->passwd);
	}

	public function _deleteRecordsInclude($where = null, array $list = array())
	{
		try{
			$where = sprintf(' AND t.id > 1 %s',$where);
			parent::_deleteRecordsInclude($where,$list);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Users_Exceptions::throwException('user list deletion error !');
		}
	}

	public function _deleteRecordsNotInclude($where = null, array $childList = array())
	{
		try{
			$where = sprintf(' AND t.id > 1 %s',$where);
			parent::_deleteRecordsNotInclude($where,$childList);
		}
		catch(\phpJar\Exceptions\PhpJar_Exception $e)
		{
			_pexceptions\Users_Exceptions::throwException('user list deletion error !');
		}
	}

	public function _selectFilterArray($where, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$where = sprintf(' AND t.id > 1 %s',$where);
		return parent::_selectFilterArray($where,$fields,$limit, $singleValues);
	}

	public function _selectInnerFilterArray($inner,$where = null, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$where = sprintf(' AND t.id > 1 %s',$where);
		return parent::_selectInnerFilterArray($inner,$where,$fields,$limit, $singleValues);
	}

	public function _selectFilterRecords($where = null, array $fields = array(), array $limit = array())
	{
		$where = sprintf(' AND t.id > 1 %s',$where);
		return parent::_selectFilterRecords($where,$fields,$limit);
	}

	public function _selectInnerFilterRecords($inner, $where = null, $fields = array(), array $limit = array())
	{
		$where = sprintf(' AND t.id > 1 %s',$where);
		return parent::_selectInnerFilterRecords($inner,$where,$fields,$limit);
	}

	public function _selectRecordByCategory($category = '*')
	{
		$inner = $where = null;
		if(($category === User_Category::DOCTOR) || ($category === User_Category::PATIENT) )
		{
			$inner = 'INNER JOIN user_category AS uc ON uc.parent_id = t.id';
			$where = sprintf(' AND uc.child_id = %d',$category);
		}
		elseif(! ($category === '*') )
			return array();
		return $this->_selectInnerFilterRecords($inner,$where);
	}

	public function _selectFilterArrayByCategory($category = '*',$where = null, array $fields = array(), array $limit = array(), $singleValues = true)
	{
		$inner = null;
		if(($category === User_Category::DOCTOR) || ($category === User_Category::PATIENT) )
		{
			$inner = 'INNER JOIN user_category AS uc ON uc.parent_id = t.id';
			$where .= sprintf(' AND uc.child_id = %d %s',$category,$where);
		}
		elseif(! ($category === '*') )
			return array();
		return $this->_selectInnerFilterArray($inner,$where,$fields,$limit,$singleValues);
	}

	public function _selectFilterDoctorList($specialty,$hours = null,$where = null, array $fields = array('t.id','concat(t.surname,\' \',t.given_name)'), array $limit = array(), $singleValues = true)
	{
		$inner = null;
		if(! is_null($hours))
		{
			$inner .= ' INNER JOIN doctor_hours AS dh ON dh.parent_id = t.id';
			$where .= sprintf(' AND dh.child_id = %d',$hours);
		}
		if(! is_null($specialty))
		{
			$inner .= sprintf(' INNER JOIN doctor_specialty AS ds ON ds.parent_id = t.id');
			$where .= sprintf(' AND ds.child_id = %d',$specialty);
		}

		return $this->_selectInnerFilterArray($inner,$where,$fields,$limit,$singleValues);
	}
}
?>