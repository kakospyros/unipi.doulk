<?php
/*****************************************************************************************************
 * Servlet Template implementation																											*
 * Extend Basic Servlet Class adding Template functions, for presentation, and display				*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 *
 * @final ServletTemplate_Exceptions Class - Exception class for follow class								*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\servlets\ServletTemplate Current class for which building this exception class*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class ServletTemplate_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\servlets;
use phpJar;
use phpJar\html as _html;
use phpJar\html\plugins as _htmlPlugins;
use phpJar\Exceptions as _pexceptions;
use phpJar\utils as _utils;
/**
 * ServletTemplate Class --	 																														*
 * @abstract																																					*
 * @see phpJar\servlet\Servlet					 																								*
 * @see phpJar\servlet\TemplateAccess	 																								*
 * @see phpJar\servlet\TemplateBuild		 																								*
 * @see phpJar\html\Template					 																								*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
abstract class ServletTemplate	extends Servlet
															implements TemplateBuild
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 *
	 * index for registry  where we store all register form tokens
	 * @var unknown_type
	 */
	const _REG_FORM_TOKEN = 'form_token';
	/**
	 * Template object
	 * @access protected
	 * @var Template $_oTemplate
	 */
	protected $_oTemplate;
	/**
	 * Basic template folder for servlet
	 * @var unknown_type
	 */
	protected $_tplFolder;
	/**
	 * Basic template path
	 * @access protected
	 * @var string $_tplFile
	 */
	protected static $_tplFile = null;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 *
	 * @final
	 * @access public
	 * @return
	 */
	final public function _getTemplateFolder(){return $this->_tplFolder;}
	/**
	 *
	 * @access public
	 * @return mixed array or string
	 */
	public function _getTplFile(){return static::$_tplFile;}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 * (non-PHPdoc)
	 * @final
	 * @uses phpJar/servlet/ServletTemplate#_setNestedTemplate($name, $oNested, $scope = phpJar\html\Template::_SCOPE_BASIC)
	 * @see phpJar/servlet/interfaces/TemplateBuild#_setNestedRecords($oNested)
	 */
	final public function _setNestedRecords($oNested){$this->_setNestedTemplate('oRecords',$oNested);}
	/**
	 * Create menu li element
	 * @access protected
	 * @param \stdClass $object
	 * @param array $menu
	 * @param boolean $active
	 * @return array
	 */
	final protected function _setTabLi($object, &$menu, $active = false)
	{
		if(empty($menu) || !is_array($menu))
			$menu = array();
		$tab = clone $object;
		$tab->active = $active;
		$menu[] = $tab;
		return $menu;
	}
	/**
	 * (non-PHPdoc)
	 * @final
	 * @uses /lib/php/phpJar/html/Template#_setTemplate($tplPath,$check)
	 * @see phpJar/servlet/interfaces/TemplateBuild#_setTemplate($tplPath, $check = true)
	 */
	final public function _setTemplate($tplPath){$this->_oTemplate->_setTemplate($this->_tplFolder.$tplPath);}
	/**
	 * Set Template folder which contains all template files for current servlet
	 * @param unknown_type $path
	 */
	protected function _setTemplateFolder($path = null, $strick = false)
	{
		$path = trim($path);
		if(!$strick)
		{
			$name = $this->_getReflectionName(true);
			$this->_tplFolder = $path.'/'.$name.'/';
		}
		else
		{
			$this->_tplFolder = $path.'/';
		}
	}
	/**
	 *
	 *
	 * @access public
	 * @param \stdClass $settings
	 * @param \stdClass $data
	 * @return \stdClass $settings
	 */
	public function _createJSBoxRegistryObj(\stdClass $settings, \stdClass $data)
	{
		$settings->oRegistry = new \stdClass();
		$settings->oRegistry->method = $this->_getReflectionName();
		$settings->oRegistry->action = $data->action;
		return $settings;
	}
	/********************************
	 * Class templates method Area *
	 ********************************/
	/**
	 * (non-PHPdoc)
	 * @uses /lib/php/phpJar/servlet/ServletTemplate#_getLayerCommand()
	 * @see phpJar/servlet/interfaces/TemplateBuild#_getCommandsFormTpl()
	 */
	public function _getCommandsFormTpl()
	{
		$this->_checkTpl();
		return $this->_oTemplate->_getLayerCommand();
	}
	/**
	 *
	 * Get default error template
	 * @param string $message
	 * @uses /lib/php/phpJar/servlet/ServletTemplate#_getLayerError($message)
	 * @see  phpJar/servlet/interfaces/TemplateBuild#_getFormTpl(\stdClass $args = null)
	 */
	public function _getFormErrorTpl($message){return $this->_getLayerError($message);}
	/**
	 * (non-PHPdoc)
	 * @uses /lib/php/phpJar/servlet/ServletTemplate#_getLayerBasic()
	 * @see  phpJar/servlet/interfaces/TemplateBuild#_getFormTpl(\stdClass $args = null)
	 */
	public function _getFormTpl(\stdClass $args = null)
	{
		$this->_checkTpl();
		$tpl = $this->_oTemplate->_getLayerBasic();
		if($args->getLang == true )
			$tpl->js_lang = phpJar\Language::_getSpecificLanguage('*');
		if($args->getDefaults == true )
		{
			$tpl->js_defaults = new \stdClass();
			$tpl->js_defaults->numberFormat = new \stdClass();
			$tpl->js_defaults->numberFormat->format = '#,##0.00';
			$tpl->js_defaults->numberFormat->locale = 'gr';
		}
		return $tpl;
	}
	/**
	 * (non-PHPdoc)
	 * @uses /lib/php/phpJar/servlet/ServletTemplate#_getLayerRecord()
	 * @see phpJar/servlet/interfaces/TemplateBuild#_getRecordsFormTpl()
	 */
	public function _getRecordsFormTpl($oRecords = null)
	{
		$this->_checkTpl();
		if(!empty($oRecords) && count($oRecords))
			$this->_setTemplateLoopRecords($oRecords);
		return $this->_oTemplate->_getLayerRecord();
	}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * ServletTemplate constructor,
	 * @access public
	 * @uses /phpJar/html/Template#__construct($tplFile = null)
	 * @uses /phpJar/servlet/Servlet#__construct($attrs)
	 * @return mixed a class instance
	 **/
	public function __construct($attrs = null)
	{
		parent::__construct($attrs);
		$this->_setTemplateFolder($attrs['_tplFolder']);
		$this->_includeLanguageFiles($attrs['_oLanguage']);
		$this->_oTemplate = new _html\Template();
		//pass to template the called class object as 1st argument
//		$tmp = clone($this);
//		$this->_setTemplateArgs('_this',$tmp);
	}
	/**
	 *
	 * Use magic method __call, to extend current class methods with Template class
	 * available methods
	 * @param string $name
	 * @param array $arguments
	 * @return
	 */
	public function __call($name,$arguments)
	{
		if(method_exists($this->_oTemplate,$name))
			return call_user_func_array(array($this->_oTemplate,$name),$arguments);
	}
	/**
	 *
	 */
	final private function _checkTpl()
	{
		$tpl = $this->_oTemplate->_getTemplate();
		if(empty($tpl))
		{
			if(is_array(static::$_tplFile))
				$tplFile = current(static::$_tplFile);
			else
				$tplFile = static::$_tplFile;
			$this->_setTemplate($tplFile);
		}
		return true;
	}
	/**
	 *
	 * @param string $className
	 */
	final protected static function _createHash($className)
	{
		$key = _utils\Html::_createHash($className);
		static::_callRegistrySet($key,$className);
		return $key;
	}
	/**
	 *
	 * Create/store and finally return the form token
	 */
	final protected function _createFormToken()
	{
		$oRegistry = (array)static::_callRegistryGet(static::_REG_FORM_TOKEN);
		$index = self::_getReflectionName();
		$tokenArray = (array)$oRegistry [$index];
		if(empty($oRegistry[$index]) || self::_checkTokenExpairation($tokenArray['create']))
			$tokenArray = array('token' => phpJar\utils\Security::_createFormToken(), 'create' => time());
		else
			$tokenArray['create'] = time();
		$oRegistry[$index] = $tokenArray;
		static::_callRegistrySet(static::_REG_FORM_TOKEN,$oRegistry);
		return $tokenArray['token'];
	}
	/**
	 *
	 * @param string $compareToken
	 */
	final public function _checkToken($compareToken)
	{
		$oRegistry = (array)static::_callRegistryGet(static::_REG_FORM_TOKEN);
		$index = self::_getReflectionName();
		$tokenArray = (array)$oRegistry [$index];
		if(empty($tokenArray))
			return true;
		if(self::_checkTokenExpairation($tokenArray['create']))
			return false;
		return ($tokenArray['token'] == $compareToken);
	}
	/**
	 *
	 *
	 * @param timestamp $timestamp
	 * @param integer $range
	 * @param string $token
	 */
	final static function _checkTokenExpairation($timestamp,$range = phpJar\HTML_TOKEN_EXPAIRATION_SEC ,$token = null)
	{
		if($timestamp == null || ($timestamp+$range - time()) <= 0 )
			return true;
		return false;
	}
	/**
	 * (non-PHPdoc)
	 * @final
	 * @param string $folder
	 * @uses /lib/php/phpJar/Language#_setDisplayLanguage($folder, $code = 'GB')
	 * @see phpJar/servlet/AervletTemplate#$language
	 * @see phpJar/servlet/interfaces/TemplateBuild#_includeLanguageFiles($folder = 'GB')
	 * @return null
	 */
	final public function _includeLanguageFiles($folder = 'GB')
	{
		$root = phpJar\LANGUAGE_PROJECT_PATH;
		phpJar\Language::_setDisplayLanguage($root,$folder);
	}
	/**
	 *
	 * Return physical template file
	 * @param integer $id
	 * @param boolean $includeFolder
	 * @return
	 */
	final public function _includeTplFile($id = 0, $includeFolder = true)
	{
		$_tplFile = static::$_tplFile;
		if(!is_array($_tplFile))
			$_tplFile = (array)$_tplFile;
		$include_tpl = $_tplFile[$id];
		if($includeFolder)
			$include_tpl = $this->_tplFolder.$include_tpl;
		return $this->_includeTpl($include_tpl);
	}
	/**
	 * @final
	 * @access protected
	 * @static
	 * @param string $elementID
	 * @return array
	 */
	final protected static function _splitSelection($elementID){return _utils\Html::_splitHash($elementID);}
	/**
	 * Validate form Token
	 * @param string $token
	 */
	public function _validateToken($token)
	{
		$validForm = static::_checkToken($token);
		if(!$validForm)
			_pexceptions\ServletTemplate_Exceptions::throwException(phpJar\Language::_getSpecificErrorLanguage()->form->token);
	}

}
?>