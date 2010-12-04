<?php
namespace phpJar\Exceptions;
/*****************************************************************************************************
 *  DT class implementation																														*
 *****************************************************************************************************/
/**
 * @final DT_Exceptions Class - Exception class for follow class														*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\DT Current class for which building this exception class										*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class DT_Exceptions extends PhpJar_Exception {}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\utils;
use phpJar;
use phpJar\Exceptions as _exceptions;
use phpJar\utils as _utils;
/**
 * DT Class --	 																																				*
 * @author Kondylis Andreas																													*
 * @see \DateTime																																		*
 * @package phpJar																																	*
 * @subpackage utils																																*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class DT	extends \DateTime
					implements phpJar\Registry_I
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/*
	 *
	 */
	const REG_INDEX = 'DT';
	/**
	 * Constraint for time stamp full
	 * @var integer
	 */
	const FULL_TIMESTAMP = 0;
	/**
	 * Constraint for time stamp Date part
	 * @var integer
	 */
	const PART_DATE = 1;
	/**
	 * Constraint for time stamp Time part
	 * @var integer
	 */
	const PART_TIME = 2;
	/**
	 * Constraint for time stamp validation any format
	 * @var integer
	 */
	const FORMAT_ANY = 0;
	/**
	 * Constraint for time stamp validation ISO format
	 * @var integer
	 */
	const FORMAT_ISO = 1;
	/**
	 * Constraint for time stamp validation local format
	 * @var integer
	 */
	const FORMAT_LOCAL = 2;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 * Return Registered ISO date Format
	 * @final
	 * @access protected
	 * @uses /phpJar/utils/DT#_callRegistryGet($key)
	 * @return string registered Local Date format
	 */
	final protected function _getIsoDateFormat(){return self::_callRegistryGet('_ISO_TIMESTAMP_DATE_FORMAT');}
	/**
	 * Return Registered Local time stamp Format
	 * @final
	 * @access protected
	 * @uses /phpJar/utils/DT#_callRegistryGet($key)
	 * @return string registered ISO time stamp format
	 */
	final protected function _getIsoFormat(){return self::_callRegistryGet('_ISO_TIMESTAMP_FORMAT');}
	/**
	 * Return Registered ISO time Format
	 * @final
	 * @access protected
	 * @uses /phpJar/utils/DT#_callRegistryGet($key)
	 * @return string registered ISO time format
	 */
	final protected function _getIsoTimeFormat(){return self::_callRegistryGet('_ISO_TIMESTAMP_TIME_FORMAT');}
	/**
	 * Return Registered Local date Format
	 * @final
	 * @access protected
	 * @uses /phpJar/utils/DT#_callRegistryGet($key)
	 * @return string registered Local Date format
	 */
	final protected function _getLocalDateFormat(){return self::_callRegistryGet('_LOCAL_TIMESTAMP_DATE_FORMAT');}
	/**
	 * Return Registered Local time stamp Format
	 * @final
	 * @access protected
	 * @uses /phpJar/utils/DT#_callRegistryGet($key)
	 * @return string registered Local time stamp format
	 */
	final protected function _getLocalFormat(){return self::_callRegistryGet('_LOCAL_TIMESTAMP_FORMAT');}
	/**
	 * Return Registered Local time Format
	 * @final
	 * @access protected
	 * @uses /phpJar/utils/DT#_callRegistryGet($key)
	 * @return string registered Local time format
	 */
	final protected function _getLocalTimeFormat(){return self::_callRegistryGet('_LOCAL_TIMESTAMP_TIME_FORMAT');}
	/**
	 * Check if given time is already part of ISO/local format,
	 * If not return the requested part,
	 * if the given time is not a part of ISO/local format and it is not a full time stamp throw error
	 * @final
	 * @access public
	 * @static
	 * @param string $time
	 * @param integer $type
	 * @uses /phpJar/utilities/DT_Exceptions#throwException()
	 * @return in success the requested part of time stamp, exception in failed
	 */
	final public static function _getPart($time, $type = self::PART_DATE)
	{
		if(!self::_isValidFormat($time))
		{
			if(!self::_isValidFormat($time,$type))
			{
				$error = sprintf(phpJar\Language::_getSpecificLanguage()->error->dt->date,$time);
				_exceptions\DT_Exceptions::throwException($error);
			}
			return $time;
		}
		else
		{
			if(!self::_isIsoFormat($time))
				$format = ($type == self::PART_DATE)?self::_getLocalDateFormat():self::_getLocalTimeFormat();
			else
				$format = ($type == self::PART_DATE)?self::_getIsoDateFormat():self::_getIsoTimeFormat();
			return date($format,strtotime($time));
		}
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param string $timezone
	 * @uses /phpJar/Config#SET_TIMEZONE
	 * @uses /phpJar/Config#DEFAULT_TIMEZONE
	 * @return string
	 */
	final public function _getTimeZoneString($timezone = null)
	{
		if(is_null($timezone))
		{
			if(phpJar\SET_TIMEZONE)
				$timezone = phpJar\DEFAULT_TIMEZONE;
			else
				$timezone = date_default_timezone_get();
		}
		return $timezone;
	}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 * Set default ISO format for date part
	 * @access public
	 * @uses /phpJar/Config#DATE_ISO_DATE_FORMAT
	 * @uses /phpJar/utils/DT#_callRegistrySet($key,$value)
	 * @return null
	 */
	public function _setIsoDateFormat($format = phpJar\DATE_ISO_DATE_FORMAT){self::_callRegistrySet('_ISO_TIMESTAMP_DATE_FORMAT',$format);}
	/**
	 * Set default ISO format for time stamp
	 * @access public
	 * @uses /phpJar/Config#DATE_ISO_FORMAT
	 * @uses /phpJar/utils/DT#_callRegistrySet($key,$value)
	 * @return null
	 */
	public function _setIsoFormat($format = phpJar\DATE_ISO_FORMAT){self::_callRegistrySet('_ISO_TIMESTAMP_FORMAT',$format);}
	/**
	 * Set default ISO format for time part
	 * @access public
	 * @uses /phpJar/Config#DATE_ISO_TIME_FORMAT
	 * @uses /phpJar/utils/DT#_callRegistrySet($key,$value)
	 * @return null
	 */
	public function _setIsoTimeFormat($format = phpJar\DATE_ISO_TIME_FORMAT){self::_callRegistrySet('_ISO_TIMESTAMP_TIME_FORMAT',$format);}
	/**
	 * Set default local format for date part
	 * @access public
	 * @uses /phpJar/Config#DATE_LOCAL_DATE_FORMAT
	 * @uses /phpJar/utils/DT#_callRegistrySet($key,$value)
	 * @return null
	 */
	public function _setLocalDateFormat($format = phpJar\DATE_LOCAL_DATE_FORMAT){self::_callRegistrySet('_LOCAL_TIMESTAMP_DATE_FORMAT',$format);}
	/**
	 * Set default local format for time stamp
	 * @access public
	 * @uses /phpJar/Config#_LOCAL_TIMESTAMP_FORMAT
	 * @uses /phpJar/utils/DT#_callRegistrySet($key,$value)
	 * @return null
	 */
	public function _setLocalFormat($format = null)
	{
		if(is_null($format))
		{
			$dateFormat = self::_getLocalDateFormat();
			$timeFormat = self::_getLocalTimeFormat();
			$format = $dateFormat.' '.$timeFormat;
			if(trim($format))
				$format = phpJar\DATE_LOCAL_FORMAT;
		}
		self::_callRegistrySet('_LOCAL_TIMESTAMP_FORMAT',$format);
	}
	/**
	 * Set default Local format for time part
	 * @access public
	 * @uses /phpJar/Config#DATE_LOCAL_TIME_FORMAT
	 * @uses /phpJar/utils/DT#_callRegistrySet($key,$value)
	 * @return null
	 */
	public function _setLocalTimeFormat($format = phpJar\DATE_LOCAL_TIME_FORMAT){self::_callRegistrySet('_LOCAL_TIMESTAMP_TIME_FORMAT',$format);}
	/**
	 * Set working time zone
	 * @access public
	 * @param string $timezone
	 * @uses /phpJar/DT#_getTimeZoneString
	 * @return  \DateTimeZone object
	 */
	public function _setTimezoneString($timezone = null)
	{
		$timezone = static::_getTimeZoneString();
		date_default_timezone_set($timezone);
		return new \DateTimeZone($timezone);
	}
	/********************************
	 * Class Registry methods Area *
	 ********************************/
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param integer $seconds
	 * @return
	 */
	final public static function _calcInterval($seconds)
	{
		$negative = false;
		if($seconds < 0)
		{
			$negative = true;
			$seconds = abs($seconds);
		}
		else
			return false;
		$part['D'] = floor($seconds/86400);
		$seconds -= $part['D']*86400;
		$part['H'] = floor($seconds/3600);
		$seconds -= $part['H']*3600;
		$part['M'] = floor($seconds/60);
		$part['S'] = $seconds-$part['M']*60;
		$part['negative'] = $negative;
		return $part;
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistryGet($key)
	 * @uses /phpJar/Register#_get($key = null)
	 * @return mixed requested index value
	 */
	final public static function _callRegistryGet($key)
	{
		$oReg = array();
		phpJar\Registry::_set(self::REG_INDEX,$oReg,false);
		$oReg = phpJar\Registry::_get(self::REG_INDEX);
		return $oReg[$key];
	}
	/**
	 *  (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistrySet($key,$value)
	 * @uses /phpJar/Register#_set($key,$value,$replace = true)
	 * @uses /phpJar/Register#_get($key = null)
	 * @return null
	 */
	final public static function _callRegistrySet($key,$value)
	{
		$DT_Registry = array();
		phpJar\Registry::_set(self::REG_INDEX,$DT_Registry,false);
		$DT_Registry = phpJar\Registry::_get(self::REG_INDEX);
		$DT_Registry[$key] = $value;
		phpJar\Registry::_set(self::REG_INDEX,$DT_Registry);
	}
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistryUnSet($key)
	 * @return null
	 */
	public static function _callRegistryUnSet($key)
	{
		$oReg = self::_callRegistryGet();
		unset($oReg[$key]);
		phpJar\Registry::_set(self::REG_INDEX,$oReg);
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @access public
	 * @param string $time, String in a format accepted by strtotime()
	 * @param string $timezone, Time zone of the time
	 * @uses \DateTime
	 * @uses /phpJar/utils/DT#_init()
	 * @return new DT object.
	 */
	public function __construct($time = "now", $timezone = null)
	{
		$oDate = parent::__construct($time,$this->_setTimezoneString($timezone));
		$this->_init();
	}
	/**
	 * Convert current time stamp from current format to given format
	 * @final
	 * @access protected
	 * @param string $time
	 * @param string $format
	 * @return DT object
	 */
	final protected function _convertFormat($time = null,$format)
	{
		if(is_null($time))
			$time = $this->date;
		$this->date = date($format,strtotime($time));
		return $this;
	}
	/**
	 *  Converts this date to a new time zone
	 * @final
	 * @access protected
	 * @param string $time
	 * @param string $timezone
	 * @return DT object
	 */
	final protected function _convertTZ($time = null,$timezone = null)
	{
		if(is_null($time))
			$time = $this->date;
		if(is_null($timezone))
			$timezone =static::_getTimeZoneStringgetTimezone();
		$this->setTimezone(new \DateTimeZone($timezone));
		if(self::_isIsoFormat($this->date))
			$format = self::_getIsoFormat();
		else
			$format = self::_getLocalFormat();
		$date = mktime($this->format('H'),$this->format('i'),$this->format('s'),$this->format('m'),$this->format('d'),$this->format('Y'));
		$this->date = date($format,$date);
		return $this;
	}
	/**
	 * Convert current time stamp from current format to ISO format
	 * @final
	 * @access public
	 * @param string $time
	 * @uses /phpJar/utils/DT#_convertFormat()
	 * @uses /phpJar/utils/DT#_getIsoFormat()
	 * @return DT object
	 */
	final public function _convertDateToIso($time = null){return $this->_convertFormat($time,self::_getIsoFormat());}
	/**
	 * Convert current time stamp from current format to Local format
	 * @final
	 * @access public
	 * @param string $time
	 * @uses /phpJar/utils/DT#_convertFormat()
	 * @uses /phpJar/utils/DT#_getIsoFormat()
	 * @return DT object
	 */
	final public function _convertDateToLocal($time = null){return $this->_convertFormat($time,self::_getLocalFormat());}
	/**
	 * Convert current time stamp from current time zone to GMT
	 * @final
	 * @access public
	 * @param string $time
	 * @uses /phpJar/utils/DT#_getIsoFormat()
	 * @return DT object
	 */
	final public function _convertTzToGmt($time = null){return $this->_convertTZ($time,'GMT');}
	/**
	 * Convert current time stamp from current format to Local time zone
	 * @final
	 * @access public
	 * @param string $time
	 * @param string $timezone
	 * @uses /phpJar/utils/DT#_getIsoFormat()
	 * @return DT object
	 */
	final public function _convertTzToLocal($time = null,$timezone = null)
	{
		$timezone = $this->_getTimeZoneString($timezone);
		return $this->_convertTZ($time,$timezone);
	}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param $data
	 * @return string
	 */
	final public static function _createIntervalString(array $data)
	{
		$string = 'P';
		if(array_key_exists('Y',$data))
			$string .= intval($data['Y']).'Y';
		$string .= intval($data['D']).'D';
		$string .= 'T';
		$string .= intval($data['H']).'H';
		$string .= intval($data['M']).'M';
		$string .= intval($data['S']).'S';
		return $string;
	}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $timestamp
	 * @return string
	 */
	final public static function _getIsoString($timestamp = null, $tz = 'GMT')
	{
		$dt = new self();
		if($tz === 'GMT')
			return $dt->_convertTzToGmt()->_convertDateToIso($timestamp)->date;
		elseif ($tz === 'LOCAL')
			return $dt->_convertTzToLocal()->_convertDateToIso($timestamp)->date;
		return null;
	}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $timestamp
	 * @return string
	 */
	final public static function _getLocalString($timestamp = null, $tz = 'LOCAL')
	{
		$dt = new self();
		if($tz === 'GMT')
			return $dt->_convertTzToGmt()->_convertDateToLocal($timestamp)->date;
		elseif ($tz === 'LOCAL')
			return $dt->_convertTzToLocal()->_convertDateToLocal($timestamp)->date;
		return null;
	}
	/**
	 *
	 * @access protected
	 * @uses /phpJar/utils/DT#_setIsoFormat()
	 * @uses /phpJar/utils/DT#_setLocalFormat()
	 * @return null
	 */
	protected function _init()
	{
		static::_setIsoDateFormat();
		static::_setIsoTimeFormat();
		static::_setIsoFormat();
		static::_setLocalDateFormat();
		static::_setLocalTimeFormat();
		static::_setLocalFormat();
		foreach ($this as $key => $value)
		{
			$this->{$key} = $value;
		}
	}
	/**
	 * Check if given time is formated currently based on given $format
	 * @final
	 * @access protected
	 * @static
	 * @param string $time
	 * @param string $format
	 * @return true if the time is formated based on given format, false if don't
	 */
	final protected static function _isFormated($time,$format)
	{
		$check = date_parse_from_format($format,$time);
		if($check['error_count']> 0)
			return false;
		return true;
	}
	/**
	 * Check if given time is in ISO format
	 * @final
	 * @static
	 * @access public
	 * @param string $time
	 * @uses /phpJar/utils/DT#_isFormated($time,$format)
	 * @return true if the time is ISO formated, false if don't
	 */
	final public static function _isIsoFormat($time){return self::_isFormated($time,self::_getIsoFormat());}
	/**
	 * Check if given time is in LOCAL format
	 * @final
	 * @static
	 * @access public
	 * @param string $time
	 * @uses /phpJar/utils/DT#_isFormated($time,$format)
	 * @return true if the time is LOCAL formated, false if don't
	 */
	final public static function _isLocalFormat($time){return self::_isFormated($time,self::_getLocalFormat());}
	/**
	 * Validate given time/date
	 * @final
	 * @access public
	 * @static
	 * @param string $time
	 * @param integer $type
	 * @param integer $formatType
	 * @uses /phpJar/utilities/DT_Exceptions#throwException()
	 * @return if time is valid true, false if not, throw exception if type code is undefined
	 */
	final public static function _isValidFormat($time, $type = self::FULL_TIMESTAMP, $formatType = self::FORMAT_ANY)
	{
		//full time stamp validation
		if($type == self::FULL_TIMESTAMP)
		{
			if($formatType == self::FORMAT_ANY || $formatType == FORMAT_ISO)
				$format = self::_getIsoFormat();
			elseif($formatType == FORMAT_LOCAL)
				$format = self::_getLocalFormat();
			else
				_exceptions\DT_Exceptions::throwException(phpJar\Language::_getSpecificLanguage()->error->dt->dateFormat);
			if(!self::_isFormated($time,$format))
			{
				if($formatType == self::FORMAT_ANY)
				{
					$format = self::_getLocalFormat();
					if(!self::_isFormated($time,$format))
						return false;
				}
			}
		}
		//part time stamp validtion
		elseif($type == self::PART_DATE || $type == self::PART_TIME)
		{
			if($formatType == self::FORMAT_ANY || $formatType == FORMAT_ISO)
				$format = ($type == self::PART_DATE)?self::_getIsoDateFormat():self::_getIsoTimeFormat();
			elseif($formatType == FORAMT_LOCAL)
				$format = ($type == self::PART_DATE)?self::_getLocalDateFormat():self::_getLocalTimeFormat();
			else
				_exceptions\DT_Exceptions::throwException(phpJar\Language::_getSpecificLanguage()->error->dt->dateFormat);
			if(!self::_isFormated($time,$format))
			{
				if($formatType == self::FORMAT_ANY)
				{
					$format = ($type == self::PART_DATE)?self::_getLocalDateFormat():self::_getLocalTimeFormat();
					if(!self::_isFormated($time,$format))
						return false;
				}
			}
		}
		else
			_exceptions\DT_Exceptions::throwException(phpJar\Language::_getSpecificLanguage()->error->dt->dateType);
		return true;
	}

}
?>