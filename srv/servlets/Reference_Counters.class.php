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
namespace project\servlets;

use phpJar;

class Reference_Counters	extends \phpJar\servlets\ServletScheme
{

	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'REFERENCE_COUNTERS';
	/**
	 * (non PHP-Doc)
	 * @see phpJar\servlets\ServletTemplate
	 */
	protected static $_tplFile = array(
																0 => 'action0',
													);
	/********************************
	 * Class templates method Area *
	 ********************************/
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @param unknown_type $attrs
	 */
	public function __construct($attrs = null)
	{
		parent::__construct($attrs);
		self::_setTemplateFolder('formactions');
	}

	public static function createCounter($prefix,$suffix,$length,$counter, $incr = 0)
	{
		return sprintf('%s%0'.$length.'d%s',$prefix,($counter+$incr),$suffix);
	}

	public static function build($type,$oServlet)
	{
		$oObject = new self();
		$oRecord = $oObject->_callScheme('_selectFilterRecordsingle',sprintf(' AND t.type = %d',$type));
		if(!is_object($oRecord))
			return null;
		$reg = sprintf('^(%s).+(%s)$',$oRecord->prefix,$oRecord->suffix);
		$oREG_Records = $oServlet->_callScheme('_selectFilterArrayREGEXP','t.reference',$reg,null,array('upper(t.reference)'));
		$incr = 0;
		$counter = self::createCounter($oRecord->prefix, $oRecord->suffix, $oRecord->length, $oRecord->current,$incr);
		while(!(in_array(mb_strtoupper($counter,'utf-8'),$oREG_Records) === false))
		{
			++$incr;
			$counter = self::createCounter($oRecord->prefix, $oRecord->suffix, $oRecord->length, $oRecord->current,$incr);
		}
		return $counter;
	}
}
?>