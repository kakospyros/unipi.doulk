<?php
/*****************************************************************************************************
 * Language Class implementation 																											*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Language_Exceptions Class - Exception class for follow class											*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\Language Current class for which building this exception class							*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Language_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar;
use phpJar;
use phpJar\Exceptions;
/**
 * Language Class --	 																																*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Language	implements Registry_I
{
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 * Fetch already stored language object
	 * @access public
	 * @static
	 * @uses /phpJar/Register#_get($key = null)
	 * @return stdClass object
	 */
	public static function _getDisplayLanguage()
	{
		$oLanguage = phpJar\Registry::_get(self::REG_INDEX);
		if(!empty($oLanguage))
			return $oLanguage;
		$msg = 'Language Object does not exists';
		\phpJar\Exceptions\Language_Exceptions::throwWarning($msg);
		return new \stdClass();
	}
	/**
	 * Get Specific object property from language object
	 * @access public
	 * @static
	 * @param string $property
	 * @return \stdClass
	 */
	public static function _getSpecificLanguage($property = self::oGeneric)
	{
		$oLanguage = self::_getDisplayLanguage();
		if($property === '*')
			return $oLanguage;
		if(property_exists($oLanguage,$property))
			return $oLanguage->{$property};
		if(property_exists($oLanguage,self::oGeneric) && $property != self::oGeneric)
			$msg = sprintf($oLanguage->{self::oGeneric}->error->language->property,$property);
		else
			$msg = sprintf('Language Property \'%s\' does not exists',$property);
		\phpJar\Exceptions\Language_Exceptions::throwWarning($msg);
		return new \stdClass();
	}
	/**
	 *
	 * @param string $property
	 */
	public static function _getSpecificErrorLanguage($property = self::oGeneric)
	{
		$oLang = self::_getSpecificLanguage($property);
		return $oLang->error;
	}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 * Search and include language file with all neccessery language variable
	 * for display message and for template building.
	 * The language file must be exist in define language folder
	 * @access public
	 * @static
	 * @param string $folder
	 * @param string $code
	 * @uses /lib/php/phpJar/utilities/System#_ls_folder($path)
	 * @uses /lib/php/phpJar/Registry#_get($key = null)
	 * @uses /lib/php/phpJar/Registry#_set($key,$value,$replace = true)
	 * @return
	 */
	public static function _setDisplayLanguage($folder, $code = 'GB')
	{
		$code = trim($code);
		if(empty($code))
			$code = phpJar\DEFAULT_LANGUAGE;
		$langFiles = \phpJar\utils\System::_ls_folder($folder.$code);
		$_Language = new \stdClass();
		if(!empty($langFiles))
		{
			foreach ($langFiles as $file)
				include_once $file;
		}

		if(count(get_object_vars($_Language)))
			phpJar\Registry::_set(self::REG_INDEX,$_Language);
	}
	/********************************
	 * Class Registry methods Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistryGet($key)
	 * @uses /phpJar/Register#_get($key = null)
	 * @return mixed requested index value
	 */
	final public static function _callRegistryGet($key){}
	/**
	 *  (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistrySet($key,$value)
	 * @uses /phpJar/Register#_set($key,$value,$replace = true)
	 * @uses /phpJar/Register#_get($key = null)
	 * @return null
	 */
	final public static function _callRegistrySet($key,$value){}
	/**
	 * (non-PHPdoc)
	 * @see phpJar/RegisryFace#_callRegistryUnSet($key)
	 * @return null
	 */
	public static function _callRegistryUnSet($key){}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @var unknown_type
	 */
	const REG_INDEX = '_GlobalLanguage';
	/**
	 *
	 * @var string
	 */
	const oGeneric = 'oGeneric';
	/**
	 * Language constructor,
	 * @access public
	 * @return mixed a class instance
	 **/
	public function __construct(){}

}
?>