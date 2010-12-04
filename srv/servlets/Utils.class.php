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
use project\scheme as _pscheme;

class Utils extends \phpJar\servlets\Servlet
{

	/***************************
	 * Class attributes Area	*
	 ***************************/
	const REG_INDEX = 'UTILS';
	/**
	 *
	 * @var unknown_type
	 */
	protected static $_needAuthorize = true;
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @param \stdClass $data
	 */
	public static function fetchURL(\stdClass $data)
	{
		$url = $data->url;
		if (strstr($url, 'http://') == false)
			$url = 'http://'.$url;

		$fp = @fopen($url,'r');
		$text = fread($fp,25384);
		$text = mb_convert_encoding($text,'UTF-8','auto');
		if($data->request[0] === 'title')
		{
			preg_match('/<title>(.*?)<\/title>/is', $text, $match );
			return array('response'=>$match[1]);
		}
		return false;
	}

	public static function fetchSubCategories(\stdClass $data)
	{
		$response = new \stdClass();
		return $response;
	}

	public static function fetchDoctor(\stdClass $data)
	{
		$response = new \stdClass();
		$response->options = new \stdClass();

		$oScheme = new _pscheme\Users();
		$response->options->fetchDoctor = $oScheme->_selectFilterDoctorList($data->specialty,$data->hours);

		return $response;
	}

	public static function getInfo(\stdClass $data)
	{
		$class = phpJar\SCHEME_NAME_SPACE.$data->object;
		$oScheme = new $class();
		$oRecord = $oScheme->_selectFilterRecordsingle(sprintf(' AND t.id=%d',$data->val) );
		if(empty($oRecord))
			return array();
		$getAttrs = $oRecord->_getAttrs();
		unset($getAttrs['id']);
		if($getAttrs['social_network'] == 0)
			$getAttrs['social_network'] = null;
		if($getAttrs['web_publish'] == 0)
			$getAttrs['web_publish'] = null;
		if(isset($getAttrs['name']) )
		{
			$getAttrs[$data->object.'_name'] = $getAttrs['name'];
			unset($getAttrs['name']);
		}
		if(isset($getAttrs['description']) )
		{
			$getAttrs[$data->object.'_description'] = $getAttrs['description'];
			unset($getAttrs['description']);
		}
		if(isset($getAttrs['reference']) )
		{
			$getAttrs[$data->object.'_reference'] = $getAttrs['reference'];
			unset($getAttrs['reference']);
		}

		$response = new \stdClass();
		$response->_tagValue = $getAttrs;
		return $response;
	}

	public static function getInfoForm(\stdClass $data)
	{
		$oClass = new \ReflectionClass('project\servlets\forms\\'.$data->object);
		if(!$oClass->hasMethod(__FUNCTION__))
			return false;
		$oReflectionMethod = $oClass->getMethod(__FUNCTION__);
		$instance = ($oReflectionMethod->isStatic())?null:$this->_oReflectionScheme->newInstance();
		return $oReflectionMethod->invoke($instance,$data);
	}
}
?>