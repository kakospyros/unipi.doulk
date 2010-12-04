<?php
/*****************************************************************************************************
 * ---------------------------------------------------------------------------------------------------------------------------*/
/**
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage database																														*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\utils;
/**
 *
 *
 */
class Html
{
	/**
	 *
	 * @var string
	 */
	const _OPTION_GRP = 'optgroup_';
	const _OPTION_GRP_NAME = 'name';
	const _OPTION_GRP_MEMBERS = 'members';
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $className
	 * @return string
	 */
	final public static function _createHash($className){return sha1($className);}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $hash
	 * @return array
	 */
	final public static function _splitHash($hash){return str_split($hash,40);}
	/**
	 *
	 * @final
	 * @access public
	 * @static
	 * @param string $name
	 * @param array $members
	 * @param array $union
	 * @param boolean $union_after
	 * @return array
	 */
	final public static function _createOptGroup($name, array $members = array(), array $union = array(), $union_after = true)
	{
		$opt_name = self::_OPTION_GRP.(string)$name;
		$tmp[$opt_name][self::_OPTION_GRP_NAME] = $name;
		$tmp[$opt_name][self::_OPTION_GRP_MEMBERS] = (array)$members;
		if(empty($union))
			return $tmp;
		if($union_after === true)
		{
			foreach ($union as $k => $v)
				$tmp[$k] = $v;
			return $tmp;
		}
		else
		{
			foreach ($tmp as $k => $v)
				$union[$k] = $v;
			return $union;
		}
	}

}
?>