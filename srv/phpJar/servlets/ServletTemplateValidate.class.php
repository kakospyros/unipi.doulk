<?php
/*****************************************************************************************************
 * Servlet emplate validation Implementation																						*
 * Extend ServletScheme, adding wizard support 																				*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final ServletTemplateValidate_Exceptions Class - Exception class for follow class				*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\servlets\ServletTemplateValidate Current class for which building this exception class	*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class ServletTemplateValidate_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\servlets;
use phpJar;
use phpJar\Exceptions as _pexceptions;
/**
 * ServletTemplateValidate Class --	 																										*
 * @abstract																																					*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class ServletTemplateValidate
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_alph;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_full;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_integer;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_length;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_match;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_notEmpty;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_notNull;
	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_number;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 *
	 * @return stdClass
	 */
	public function _get_alph(){return $this->_alph;}
	/**
	 *
	 * @return stdClass
	 */
	public function _getI_full(){return $this->_full;}
	/**
	 *
	 * @return stdClass
	 */
	public function _get_integer(){return $this->_integer;}
	/**
	 * @return stdClass
	 */
	public function _get_length(){return $this->_length;}
	/**
	 * @return stdClass
	 */
	public function _get_match(){return $this->_match;}
	/**
	 *
	 * @return stdClass
	 */
	public function _get_notEmpty(){return $this->_notEmpty;}
	/**
	 *
	 * @return stdClass
	 */
	public function _get_notNull(){return $this->_notNull;}
	/**
	 *
	 * @return stdClass
	 */
	public function _get_number(){return $this->_number;}
	/**
	 *
	 * @access public
	 * @return integer
	 */
	public function _getTotal()
	{
		$fields = array('_alph','_integer','_length','_match','_notEmpty','_notNull','_number');
		$total = 0;
		foreach ($fields as $f)
			$total += $this->{$f}->total;
		return $total;
	}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 *
	 * @final
	 * @access protected
	 * @param string $field
	 * @param mixed $value
	 */
	final protected function _set($field,$value)
	{
		if(is_array($value))
			$this->{$field}->values = $value;
		elseif(is_object($value))
			$this->{$field}->values = (array)$value;
		else
			$this->{$field}->values[] = $value;
		$this->{$field}->total = count($this->{$field}->values);
	}
	/**
	 *
	 * @access public
	 * @param string $value
	 * @return null
	 */
	public function _set_alph($value){$this->_set('_alph',$value);}
	/**
	 *
	 * @access public
	 * @param string $value
	 * @return null
	 */
	public function _set_full($value){$this->_set('_full',$value);}
	/**
	 *
	 * @access public
	 * @param string $value
	 * @return null
	 */
	public function _set_integer($value){$this->_set('_integer',$value);}
	/**
	 *
	 * @access public
	 * @param string $value
	 * @param integer $min
	 * @param integer $max
	 * @return null
	 */
	public function _set_length($value, $min = 0, $max = 0)
	{
		if($min <= 0 && $max <= 0)
			return false;
		if( ($max < $min) && ($max > 0) )
			return false;

		if($min > 0)
			$val['min'] = $min;
		if($max > 0)
			$val['max'] = $max;

		if(is_array($value) || is_object($value))
			$value = (array)$value;
		else
			$value = array($value);
		foreach ($value as $v)
			$this->_length->values[$v] = $val;
		$this->_length->total = count($this->_length->values);
	}
	/**
	 *
	 * @access public
	 * @param string $value
	 * @param array $matches
	 * @return null
	 */
	public function _set_match($value, array $matches)
	{
		if(empty($matches) || trim($value) == '')
			return false;
		$this->_match->values[$value] = $matches;
		$this->_match->total = count($this->_match->values);
	}
	/**
	 *
	 * @access public
	 * @param string $value
	 * @return null
	 */
	public function _set_notEmpty($value){$this->_set('_notEmpty',$value);}
	/**
	 *
	 * @access public
	 * @param string $value
	 * @return null
	 */
	public function _set_notNull($value){$this->_set('_notNull',$value);}
	/**
	 *
	 * @access public
	 * @param string $value
	 * @return null
	 */
	public function _set_number($value){$this->_set('_number',$value);}
	/********************************
	 * Class implementation Area *
	 ********************************/
	public function __construct()
	{
		$this->_init();
	}
	/**
	 *
	 * @param string $name
	 * @param string $value
	 *
	 */
	public function __set($name,$value)
	{
		if(!property_exists($this,$name))
			_pexceptions\ServletTemplateValidate_Exceptions::throwException(sprintf(phpJar\Language::_getSpecificErrorLanguage()->class->property,__CLASS__,$name));
	}
	/**
	 * @final
	 * @access protected
	 * @param string $search
	 * @return true if search element exists else return false
	 */
	final protected function _contain_alph($search)
	{
		$total = $this->_alph->total;
		return ( ($total > 0) && in_array($search,$this->_alph->values));
	}
	/**
	 * @final
	 * @access protected
	 * @param string $search
	 * @return true if search element exists else return false
	 */
	final protected function _contain_full($search)
	{
		$total = $this->_full->total;
		return ( ($total > 0) && in_array($search,$this->_full->values));
	}
	/**
	 * @final
	 * @access protected
	 * @param string $search
	 * @return true if search element exists else return false
	 */
	final protected function _contain_integer($search)
	{
		$total = $this->_integer->total;
		return ( ($total > 0) && in_array($search,$this->_integer->values));
	}
	/**
	 * @final
	 * @access protected
	 * @param string $search
	 * @return true if search element exists else return false
	 */
	final protected function _contain_length($search)
	{
		$total = $this->_length->total;
		return ( ($total > 0) && array_key_exists($search,$this->_length->values));
	}
	/**
	 * @final
	 * @access protected
	 * @param string $search
	 * @return true if search element exists else return false
	 */
	final protected function _contain_match($search){}
	/**
	 * @final
	 * @access protected
	 * @param string $search
	 * @return true if search element exists else return false
	 */
	final protected function _contain_notEmpty($search)
	{
		$total = $this->_notEmpty->total;
		return ( ($total > 0) && in_array($search,$this->_notEmpty->values));
	}
	/**
	 * @final
	 * @access protected
	 * @param string $search
	 * @return true if search element exists else return false
	 */
	final protected function _contain_notNull($search)
	{
		$total = $this->_notNull->total;
		return ( ($total > 0) && in_array($search,$this->_notNull->values));
	}
	/**
	 * @final
	 * @access protected
	 * @param string $search
	 * @return true if search element exists else return false
	 */
	final protected function _contain_number($search)
	{
		$total = $this->_number->total;
		return ( ($total > 0) && in_array($search,$this->_number->values));
	}
	/**
	 *
	 * @final
	 * @access protected
	 * @return null
	 */
	final protected function _init()
	{
		$object = new \stdClass();
		$object->total = 0;
		$object->values = array();
		$this->_alph = clone $object;
		$this->_full = clone $object;
		$this->_integer = clone $object;
		$this->_length = clone $object;
		$this->_match = clone $object;
		$this->_notEmpty = clone $object;
		$this->_notNull = clone $object;
		$this->_number = clone $object;
	}
	/**
	 *
	 * @access protected
	 * @param string $value
	 * @return if it is valid return true else return false
	 */
	protected function _validate_alph($value)
	{
		if(is_string($value))
		{
			if(ctype_alnum($value) )
				return true;
		}
		return false;
	}
	/**
	 *
	 * @access protected
	 * @param string $value
	 * @return if it is valid return true else return false
	 */
	protected function _validate_integer($value)
	{
		if(intval($value) == $value)
			return true;
		return false;
	}
	/**
	 *
	 * @access protected
	 * @param mixed $value
	 * @return if it is valid return true else return false
	 */
	protected function _validate_length($value,$preffix)
	{
		$length = $this->_length->values[$preffix];
		$min = (int)$length['min'];
		$max = (int)$length['max'];
		if(is_string($value))
		{
			$search_str = strlen($value);
			if($min > 0 && $max > 0)
				return ($search_str >= $min && $search_str <= $max);
			if($min > 0)
				return ($search_str >= $min);
			return ($search_str <= $max);

		}
		if($min > 0 && $max > 0)
			return ($value >= $min && $value <= $max);
		if($min > 0)
			return ($value >= $min);
		return ($value <= $max);
	}
	/**
	 *
	 * @access protected
	 * @param string $value
	 * @return if it is valid return true else return false
	 */
	protected function _validate_match($value,\stdClass $data)
	{

	}
	/**
	 *
	 * @access protected
	 * @param string $value
	 * @return if it is valid return true else return false
	 */
	protected function _validate_notEmpty($value)
	{
		if(empty($value))
			return false;
		return true;
	}
	/**
	 *
	 * @access protected
	 * @param string $value
	 * @return if it is valid return true else return false
	 */
	protected function _validate_notNull($value)
	{
		if(is_null($value))
			return false;
		return true;
	}
	/**
	 *
	 * @access protected
	 * @param string $value
	 * @return if it is valid return true else return false
	 */
	protected function _validate_number($value)
	{
		if(is_numeric($value))
			return true;
		return false;
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param \stdClass $data
	 * @param boolean $onePerTime
	 * @return if it is valide return true else return an error array
	 * which contains the last error or the total validation errors
	 */
	final public function _validate(\stdClass $data, $onePerTime = true)
	{
		if(empty($data) || ($this->_getTotal() == 0))
			return true;

		$validators = array(
			'type'=>array('_alph','_integer'),
			'format'=>array('_length','_match','_notEmpty','_notNull','_number')
		);
		$error = array();

		foreach ($data as $key => $value)
		{
			$_check = array();
			if($this->_contain_alph($key))
				$_check[] = '_alph';
			elseif($this->_contain_integer($key))
				$_check[] = '_integer';

			foreach ($validators['format'] as $f)
			{
				if($this->{'_contain'.$f}($key))
					$_check[] = $f;
			}

			$fullValidate = $this->_contain_full($key);

			if(count($_check))
			{
				foreach ($_check as $prop )
				{
					if(!$fullValidate || (!(is_array($value) && is_object($value))) )
					{
						$value = trim($value);
						if(($prop == '_match'))
							$validate = $this->{'_validate'.$prop}($value,$data);
						elseif(($prop == '_length'))
							$validate = $this->{'_validate'.$prop}($value,$key);
						else
							$validate = $this->{'_validate'.$prop}($value);
						if(!($validate === true))
						{
							$error[$key] = array('value'=>$value,'validate'=>$prop);
							if($onePerTime)
								return $error;
						}
					}
					else
					{
						foreach ($value as $_key => $val)
						{
							$val = trim($val);
							if(($prop == '_match'))
								$validate = $this->{'_validate'.$prop}($val,$data);
							elseif(($prop == '_length'))
								$validate = $this->{'_validate'.$prop}($val,$key);
							else
								$validate = $this->{'_validate'.$prop}($val);
							if(!($validate === true))
							{
								$error[$key] = array('value'=>$val,'validate'=>$prop);
								if($onePerTime)
									return $error;
							}
						}
					}
				}
			}
		}
		if(count($error))
			return $error;
		return true;
	}

}
?>