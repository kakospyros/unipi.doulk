<?php
/*****************************************************************************************************
 * TEMPLATE ENGINE																																	*
 * With this we initialize all the neccessery variables that we need for the template display		*
 *  action.																																						*
 * support one basic template file with autoload records file and command file with extra		*
 *  code for display.,																																		*
 * multilanguage, nested templates, pass argument directly on 3 basic template file,				*
 * set template html elements style, also can be enable/disable the pager or the alphabet		*
 *  template																																					*
 * set the contents for select elements																									*
 * for each template file																																*
 * by default this 2 extra file needs to be on the same folder/path as the basic template			*
 * if we need to use others file, supported by nested functions														*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Template_Exceptions Class - Exception class for follow class											*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\html\Template Current class for which building this exception class					*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Template_Exceptions extends PhpJar_Exception {}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\html;
use phpJar;
/**
 * Template Class -- 																																		*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage html																																*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Template
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 * Constraint for Default prefix for the template file with the commands button
	 * by default each basic template file can have one command button
	 * file as extension on same folder
	 * @var string _COMMAND_PREFIX
	 */
	const _COMMAND_PREFIX = '_command';
	/**
	 * Constraint for
	 * @var integer _DEFAULT
	 */
	const _DEFAULT = 0;
	/**
	 * Constraint for Default prefix for the template file
	 * with the repeated code like record list
	 * by default each basic template file can have one record file
	 * as extension on same folder
	 * @var string _LOOP_PREFIX
	 */
	const _LOOP_PREFIX = '_records';
	/**
	 *
	 */
	const _RETURNED_FORM_TOKEN = '_form_token';
	/**
	 *
	 * @var unknown_type
	 */
	const _RETURNED_HTMLTAG = '_htmlTag';
	/**
	 *
	 * @var unknown_type
	 */
	const _RETURNED_POPUP_STATUS = '_popupBox';
	/**
	 * Constraint for default array index of export template html code
	 * @var string _RETURNED_TEMPLATE
	 */
	const _RETURNED_TEMPLATE = 'template';
	/**
	 * Constraint for variable scope have effect only on basic template
	 * @var string _SCOPE_BASIC
	 */
	const _SCOPE_BASIC = '_basic';
	/**
	 * Constraint for variable scope have effect only on commands template
	 * @var string _SCOPE_COMMANDS
	 */
	const _SCOPE_COMMANDS = '_commands';
	/**
	 * Constraint for variable scope has effect on all level of the current template
	 * @var string _SCOPE_GLOBAL
	 */
	const _SCOPE_GLOBAL = '_global_';
	/**
	 * Constraint for variable scope have effect only on record template
	 * @var string _SCOPE_RECORDS
	 */
	const _SCOPE_RECORDS = '_records';
	/**
	 * Constraint for template language, with that index is stored on Registry
	 * @var string _TPL_LANGUAGE
	 */
	const _TPL_LANGUAGE = 'language';
	/**
	 * Flag for records template, if it is true then on record template will be able to see the
	 * alphabet template else alphabet template are been disabled
	 * @access protected
	 * @var boolean
	 */
	protected $_alphabetEnable = true;
	/**
	 * @access protected
	 * @var unknown_type
	 */
	protected $_oButtons;
	/**
	 * @access protected
	 * @var unknown_type
	 */
	protected $_htmlTag;
	/**
	 *
	 * @access protected
	 * @var string
	 */
	protected $_htmlToken;
	/**
	 * flag for template display mode, if it is true the
	 * html display it as pop-up box, else as nested template
	 * @var boolean
	 */
	protected $_popupBox;
	/**
	 * use this on _records template files to build the records view
	 * @access protected
	 * @var unknown_type
	 */
	protected $_oLoop;
	/**
	 * Total number Loop Records
	 * @access protected
	 * @var unknown_type
	 */
	protected $_oLoopCount = 0;
	/**
	 * if we need to add an external template inside to current template we add this as nest
	 * @access protected
	 * @var $_oNested unknown_type
	 */
	protected $_oNested;
	/**
	 * Template object, for be able to use the Template class
	 * @access protected
	 * @var $_oTemplate unknown_type
	 */
	protected $_oTemplate;
	/**
	 * flag for records template, if it is true then on record template will be able
	 * to see the pager template else pager template are been disabled
	 * @access protected
	 * @var unknown_type
	 */
	protected $_pagingEnable = true;
	/**
	 * the full path for template file
	 * @access protected
	 * @var unknown_type
	 */
	protected $_template;
	/**
	 * template arguments, any variable which we want to pass to template directly
	 * @access protected
	 * @var unknown_type
	 */
	protected $_tplArguments;
	/**
	 *
	 * @access protected
	 * @var unknown_type
	 */
	protected $_tplOptions;
	/**
	 * template language
	 * @access protected
	 * @var unknown_type
	 */
	protected $_tplLanguages;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 * check if paging system is enable or not
	 * @final
	 * @access private
	 * @uses /phpJar/html/Template#$_pagingEnable
	 * @return boolean
	 */
	final private function _getPaging(){return $this->_pagingEnable;}
	/**
	 * Return if template have been prepared for popup use or not
	 * @final
	 * @access protected
	 * @return true in case which template is popup or false in other hand
	 */
	final protected function _getPopupmode(){return $this->_popupBox;}
	/**
	 * Return template path
	 * @final
	 * @access public
	 * @return string if it in not empty or empty if template path have not been set yet
	 */
	final public function _getTemplate(){return $this->_template;}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 *  Set record Paging status
	 * @final
	 * @access protected
	 * @param boolean $status
	 * @uses /phpJar/html/Template#$_pagingEnable
	 * @return null
	 */
	final protected function _setPagging($status = true){$this->_pagingEnable = $status;}
	/**
	 * Add an external template object into current template
	 * @final
	 * @access public
	 * @param string $name, nested template name
	 * @param array $oNested, the nested object return of _parse()
	 * @param string $scope, in which template view (scope) need to use
	 * @see /phpJar/html/Template#_parse()
	 * @uses /phpJar/html/Template#$_oNested
	 * @return true
	 */
	final public function _setNestedTemplate($name, $oNested, $scope = self::_SCOPE_BASIC)
	{
		if(!property_exists($this->_oNested,$scope))
			$this->_oNested->{$scope} = new \stdClass();
		$this->_oNested->{$scope}->{$name} = $oNested;
		return true;
	}
	/**
	 *
	 * @param \stdClass $attrs
	 * @return true
	 */
	final public function _setPopup(\stdClass $settings = null, \stdClass $wrapperSetings = null)
	{
		if((!empty($settings) && $settings) || (!empty($wrapperSetings) && $wrapperSetings) )
		{
			$oPopupBox = new BasicPopup($settings,$wrapperSetings);
			$this->_popupBox = $oPopupBox;
			$cssOptions = $this->{self::_RETURNED_HTMLTAG}->_tplCssOptions;
			if(!is_null($cssOptions) && is_object($cssOptions))
			{
				foreach ($cssOptions as $key => $opt)
					$this->_setPopupAttrs($key,$opt,'options');
			}
			$this->{self::_RETURNED_HTMLTAG}->_tplCssOptions = null;
		}
		else
			$this->_popupBox = null;
	}
	/**
	 *
	 *
	 * @param string $attrName
	 * @param string $attrValue
	 * @return null
	 */
	public function _setPopupAttrs($attrName, $attrValue, $setting = 'wrapper_options')
	{
		if(!empty($this->_popupBox) && $this->_popupBox)
			$this->_popupBox->{$setting}->{$attrName} = $attrValue;
	}
	/**
	 * Add HTML tag value and property value on htmltag list
	 * @final
	 * @param string $id
	 * @param string $property
	 * @param mixed $value
	 * @uses /phpJar/html/Template#$_htmlTag
	 * @return null
	 */
	final private function _setTag($id, $property= null, $value)
	{
		if(!property_exists($this,'_htmlTag'))
			$this->_htmlTag = new \stdClass();
		if(!isset($this->_htmlTag->{$id}))
			$this->_htmlTag->{$id} = new \stdClass();
		if(!is_null($property))
			$this->_htmlTag->{$id}->{$property} = $value;
		else
			$this->_htmlTag->{$id} = $value;
	}
	/**
	 * Set tag elements value,style, information
	 * @final
	 * @access protected
	 * @param string $id
	 * @param \stdClass $attrs
	 * @uses /phpJar/html/Template#_setTag($id,$property = null,$value)
	 * @return null
	 */
	final protected function _setTagFull($id,$attrs){$this->_setTag($id,null,$attrs);}
	/**
	 * set template filename
	 * @final
	 * @access public
	 * @param string $tplPath , current template path
	 * @uses /phpJar/html/Template#$_template
	 * @return null
	 */
	final public function _setTemplate($tplPath){$this->_template = $tplPath;}
	/**
	 * Set arguments for template files throw this argument we pass values directly to file
	 * @final
	 * @access public
	 * @param string $name, argument names
	 * @param mixed $value, argument values
	 * @param string $scope, template scope, in which template view (scope) need to use
	 * @uses /phpJar/html/Template#$_tplArguments
	 * @return null
	 */
	final public function _setTemplateArgs($name, $value, $scope = self::_SCOPE_GLOBAL)
	{
		if(!property_exists($this->_tplArguments,$scope))
			$this->_tplArguments->{$scope} = new \stdClass();
		$this->_tplArguments->{$scope}->{$name} = $value;
	}
	/**
	 *
	 * @param string $identification
	 */
	final public function _setTemplateAsParent($identification)
	{
		$settings = new \stdClass();
		$settings->field_options = new \stdClass();
		$settings->isParent = true;
		$settings->field_options->parent = $identification;
		$this->{self::_RETURNED_HTMLTAG}->_tplCssOptions = $settings;
	}
	/**
	 *
	 * @param string $identification
	 * @param integer $grade
	 */
	final public function _setTemplateAsChild($identification, $grade = 1)
	{
		$settings = new \stdClass();
		$settings->field_options = new \stdClass();
		$settings->isChild = true;
		$settings->field_options->child = $identification;
		$this->{self::_RETURNED_HTMLTAG}->_tplCssOptions = $settings;
	}
	/**
	 * Store button for curent template
	 * @param $value
	 * @param $id
	 * @param $name
	 * @param $class
	 * @param $event
	 */
	final public function _setTemplateButtons($value, $name = null, $id = null, $type= 'button', $class = 'wpbutton', \stdClass $event = null)
	{
		if($name == 'close')
			$type = null;
		$attrs = array(
									'value'=>$value,
									'events'=>$events,
									'id'=>$id,
									'name'=>$name,
									'class'=>$class,
									'type'=>$type,
						);
		$this->_oButtons[] = new HtmlButton($attrs);
	}
	/**
	 *
	 * Create template buttons base on predefined actions
	 * @param integer $action
	 * @param string $value
	 * @return null
	 */
	final public function _setTemplateDefButtons($action,$value = null,$class = null)
	{
		$class = trim($class);
		if(substr($class,0,1) == '.')
		{
			$class = 'wpbutton '.substr($class,1);
		}
		$this->_oButtons[] = HtmlButton::_createButton($action,$value,$class);
		return null;
	}
	/**
	 * set template file languages values,
	 * @final
	 * @access public
	 * @param string $language, language names
	 * @param string $contents, language values
	 * @uses /phpJar/html/Template#$_tplLamguages
	 * @return null
	 */
	final public function _setTemplateLanguage($language, $attr = null)
	{
		$attr = trim($attr);
		if( empty($attr) )
			$attr = 'language';
		$this->_tplLanguages->{$attr} = phpJar\Language::_getSpecificLanguage($language);
	}
	/**
	 *  Set the loop count for scope "_record" template
	 * @final
	 * @access public
	 * @param unknown_type $total
	 * @uses /phpJar/html/Template#$_oLoopCount
	 * @return null
	 */
	final public function _setTemplateLoopCount($total = null)
	{
		if(is_null($total))
			$this->_oLoopCount = count($this->_oLoop);
		else
			$this->_oLoopCount = intval($total);
	}
	/**
	 * set the loop records, for presentation of _record template files
	 * @final
	 * @access public
	 * @param mixed $records, array, object
	 * @uses /phpJar/html/Template#$_oLoop
	 * @return null
	 */
	final public function _setTemplateLoopRecords($records = null){$this->_oLoop = $records;}
	/**
	 *
	 * @final
	 * @access public
	 * @param mixed $assosiative
	 * @param array $value
	 */
	final public function _setTemplateMultiSelectOptions($assosiative, array $value = array())
	{
		if(!(is_object($assosiative) || is_array($assosiative)))
			return false;
		$assosiative = (array)$assosiative;
		foreach ($assosiative as $key => $options)
		{
			$set_val = (in_array($key,$value));
			$this->_setTemplateSelectOptions($key,$options,$set_val);
		}
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param mixed $assosiative
	 */
	final public function _setTemplateMultiTagValues($assosiative)
	{
		if(!(is_object($assosiative) || is_array($assosiative)))
			return false;
		$assosiative = (array)$assosiative;
		foreach ($assosiative as $key => $options)
			$this->_setTemplateTagValues($key,$options);
	}
	/**
	 * template options for display e.g. popup
	 * @final
	 * @access public
	 * @param string $option, template option names
	 * @param mixed $value, template option values
	 * @uses /phpJar/html/Template#$_tplOptions
	 * @return null
	 */
	final public function _setTemplateOptions($option, $value){$this->_tplOptions->{$option} = $value;}
	/**
	 * set select tag values with the option list
	 * @final
	 * @access public
	 * @param string $id, html tag select id
	 * @param array $options, html tag select contents list
	 * @param boolean $value, selected value
	 * @uses /phpJar/html/Template#_setTag($id,$property = null,$value)
	 * @uses /phpJar/html/Template#_setTemplateTagValues
	 * @return null
	 */
	final public function _setTemplateSelectOptions($id, array $options = array(), $value = false)
	{
		$this->{self::_RETURNED_HTMLTAG}->_selectOptions->{$id} = (array)$options;
		if(!($value === false))
			$this->_setTemplateTagValues($id,$value);
	}
	/**
	 * set the class name for html tag using  id
	 * @final
	 * @access public
	 * @param string $id, html tag id
	 * @param array $classname, element class name(s)
	 * @uses /phpJar/html/Template#$_htmlTag
	 * @return null
	 */
	final public function _setTemplateTagClass($id, $classname){$this->_setTag($id,'_classCollection',$classname);}
	/**
	 * set tag list with as associative list with html tag id and html tag value
	 * @final
	 * @access public
	 * @param string $id, html tag id
	 * @param string $value, html tag value
	 * @uses /phpJar/html/Template#_setTag($id,$property = null,$value)
	 * @return null
	 */
	final public function _setTemplateTagValues($id,$value = null)
	{
		$this->{self::_RETURNED_HTMLTAG}->_tagValue->{$id} = $value;
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param string $id
	 * @param string $value
	 * @see Template#_setTemplateTagValues($id,$value = null)
	 */
	final public function _setTemplateTagValuesDates($id,$value = null)
	{
		$this->_setTemplateTagValues($id,$value);
	}
	/**
	 *
	 * @final
	 * @access public
	 * @param string $id
	 * @param string $value
	 * @see Template#_setTemplateTagValues($id,$value = null)
	 */
	final public function _setTemplateTagValuesNumeric($id,$value = null)
	{
		$this->_setTemplateTagValues($id,$value);
	}
	/**
	 *
	 * Set Template token
	 * @param string $value
	 */
	final public function _setTemplateToken($value = null){$this->_htmlToken = $value;}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * Template constructor method
	 * @access public
	 * @param string $tplFile
	 * @uses /phpJar/html/Template#_init($tplFile = null)
	 * @uses /phpJar/Config#HTML_TPL_EXT
	 * @return mixed instance of Template class
	 */
	public function __construct($tplFile = null)
	{
		$this->_init($tplFile);
		$this->_getPaging();
	}
	/**
	 * Basic class method,
	 * Parse each request template,
	 * find the template file, create template attributes,arguments, pass on this the variables,
	 * build elements values, merge all requested templates,
	 * Add languages to current template
	 * @access private
	 * @param string $scope, template scope, in which template view (scope) need to use it
	 * @param boolean $throw, flag
	 * @uses /phpJar/Language#_getSpecificLanguage()
	 * @uses /phpJar/html/TemplateParser#_constructor()
 	 * @uses /phpJar/html/Template#$_tagValue
	 * @uses /phpJar/html/Template#$_selectOptions
	 * @uses /phpJar/html/Template#$_styleCollection
	 * @return null
	 */
	private function _buildAttrs($scope = self::_SCOPE_BASIC, $throw = false)
	{
		//FILES
		//check if access is from basic or other file and build the filename base on this
		$fileName = basename($this->_template, \phpJar\HTML_TPL_EXT);
		$basicName = (preg_replace(sprintf('/%s$/',self::_SCOPE_RECORDS),null,$this->_template));
		$basicName = (preg_replace(sprintf('/%s$/',self::_SCOPE_COMMANDS),null,$basicName));
		if($scope == self::_SCOPE_RECORDS  || $scope == self::_SCOPE_COMMANDS)
		{
			$this->_setTemplate($basicName.$scope);
			$fileName = $basicName.$scope;
		}
		elseif(!($basicName == $this->_template))
		{
			$this->_setTemplate($basicName);
			$fileName = $basicName;
		}

		$old_basicName = $basicName;
		if($scope == self::_SCOPE_COMMANDS)
		{
			if(!file_exists($this->_template.\phpJar\HTML_TPL_EXT))
			{
				$this->_setTemplate(phpJar\HTML_TPL_DEFAULT_COMMAND);
				$basicName = basename($this->_template, \phpJar\HTML_TPL_EXT);
			}
//			print_r(array($this->_template,file_exists($this->_template.\phpJar\HTML_TPL_EXT)));
		}
//		print_r(array($this->_template));
		$this->_oTemplate = new TemplateParser($this->_template);
		$this->_setTemplateArgs('_selfName',basename($basicName, \phpJar\HTML_TPL_EXT));
//		$this->_setTemplateArgs('_form_token',$this->_htmlToken);

		//NESTED TEMPLATES
		$this->_buildTemplateNested($scope);
		//TEMPLATE ARGUMENTS
		//set template file argument variables
		$this->_buildTemplateArgs($scope);
		//LANGUAGES
		//set template Language
		$this->_buildTemplateLanguage($scope);
		//RECORDS TEMPALTE
		//get total loop
		$this->_buildTemplateRecords();
		$this->_buildTemplateButtons($scope);
		if(!($basicName == $old_basicName))
			$this->_setTemplate($old_basicName);
	}
	/**
	 *
	 * @param unknown_type $scope
	 */
	final private function _buildTemplateArgs($scope = self::_SCOPE_BASIC)
	{
		$argsList = (object)array_merge(
													(array)(property_exists($this->_tplArguments,self::_SCOPE_GLOBAL)?$this->_tplArguments->{self::_SCOPE_GLOBAL}:array()),
													(array)(property_exists($this->_tplArguments,$scope)?$this->_tplArguments->{$scope}:array())
							);
		if(count($argsList))
			foreach ($argsList as $name => $value)
				$this->_oTemplate->{$name} = $value;
	}
	/**
	 *
	 * @param unknown_type $scope
	 */
	final private function _buildTemplateButtons($scope = self::_SCOPE_BASIC)
	{
		$this->_oTemplate->_oButtons = $this->_oButtons;
	}
	/**
	 *
	 * @param unknown_type $scope
	 */
	final private function _buildTemplateLanguage($scope = self::_SCOPE_BASIC)
	{
		$fileName = basename($this->_template, \phpJar\HTML_TPL_EXT);
		$basicName = (preg_replace(sprintf('/%s$/',self::_SCOPE_RECORDS),null,$this->_template));
		$basicName = (preg_replace(sprintf('/%s$/',self::_SCOPE_COMMANDS),null,$basicName));
		if(empty($this->_oTemplate->language))
			$this->_oTemplate->language = phpJar\Language::_getSpecificLanguage(basename($basicName));
		if(empty($this->_oTemplate->language_common))
			$this->_oTemplate->language_common = phpJar\Language::_getSpecificLanguage('commons');
		$langList= $this->_tplLanguages;
		if(count($langList))
		{
			foreach ($langList as $name => $value)
				$this->_oTemplate->{$name} = $value;
		}
	}
	/**
	 *
	 * @param unknown_type $scope
	 */
	final private function _buildTemplateNested($scope = self::_SCOPE_BASIC)
	{
		if(property_exists($this->_oNested,$scope))
		{
			if(count($this->_oNested->{$scope}))
			{
//				print_r($scope);
				foreach ($this->_oNested->{$scope} as $nestedName =>  $nestedArgs)
				{
					if(empty($nestedArgs->{self::_RETURNED_TEMPLATE}))
						continue;
					$this->_setTemplateArgs($nestedName,$nestedArgs->{self::_RETURNED_TEMPLATE});
					if(!empty($nestedArgs->{self::_RETURNED_HTMLTAG}))
						foreach ($nestedArgs->{self::_RETURNED_HTMLTAG} as $key => $attrs)
							$this->_setTagFull($key,$attrs);
				}
			}
		}
	}
	/**
	 *
	 */
	final private function _buildTemplateRecords()
	{
		if(!empty($this->_oLoop) && ($this->_oLoopCount == -1))
			$this->_setTemplateLoopCount(count($this->_oLoop));
		elseif(empty($this->_oLoop))
			$this->_setTemplateLoopCount();
		$this->_oTemplate->loopCount = $this->_oLoopCount;
		$this->_oTemplate->_oLoop = $this->_oLoop;
	}
	/**
	 *
	 * Get physical template file
	 * @param string $tpl
	 * @uses \phpJar\html\TemplateParser#_parse($tpl = null)
	 * @return string
	 */
	final public function _includeTpl($tpl)
	{
		$_oTemplate = new TemplateParser($tpl);
		return $_oTemplate->_parse($tpl);
	}
	/**
	 *
	 * @final
	 * @access public
	 * @return
	 */
	final public function _getPopup(){return $this->_popupBox;}
	/**
	 * Get _command output for the current template file
	 * @final
	 * @access public
	 * @uses /phpJar/html/Template#_buildAttrs()
	 * @uses /phpJar/html/Template#_parse()
	 * @return an associative array with the template information and values
	 */
	final public function _getLayerCommand()
	{
		$scope = self::_SCOPE_COMMANDS;
		$this->_buildAttrs($scope);
		return $this->_parse();
	}
	/**
	 *
	 * Get default error template
	 * @param string $message
	 */
	final public function _getLayerError($message)
	{
		$this->_setTemplate(phpJar\HTML_TPL_DEFAULT_ERROR);
		$this->_setTemplateArgs('error', $message);
		return $this->_getLayerBasic();
	}
	/**
	 * Get the core template output for the current template file
	 * @final
	 * @access public
	 * @param string $type
	 * @param boolean $throw
	 * @uses /phpJar/html/Template#_buildAttrs()
	 * @uses /phpJar/html/Template#_parse()
	 * @return an associative array with the template information and values
	 */
	final public function _getLayerBasic($type = self::_DEFAULT, $thorw = true)
	{
		if(count((array)$this->_oLoop) > 0)
		{
			$oRecords = $this->_getLayerRecord();
			$this->_setNestedTemplate(self::_SCOPE_RECORDS,$oRecords);
		}
		if(count((array)$this->_oButtons) > 0)
		{
			$oCommands = $this->_getLayerCommand();
			$this->_setNestedTemplate(self::_SCOPE_COMMANDS,$oCommands);
		}
		$scope = self::_SCOPE_BASIC;
		$this->_buildAttrs($scope);
		return $this->_parse();
	}
	/**
	 * Get the _records output for the current template file
	 * @final
	 * @access public
	 * @uses /phpJar/html/Template#_buildAttrs()
	 * @uses /phpJar/html/Template#_parse()
	 * @return an associative array with the template information and values
	 */
	final public function _getLayerRecord()
	{
		$scope = self::_SCOPE_RECORDS;
		$this->_buildAttrs($scope);
		$rs = $this->_parse();
		return $rs;
	}
	/**
	 * initialize class variables and attributes, if template filename given then set and this else
	 * set tpl = null
	 * @final
	 * @access private
	 * @param string $tplFile, template filename
	 * @uses /phpJar/html/Template#_setTemplate()
	 * @return null
	 */
	final private function _init($tplFile = null)
	{
		if(!is_null($tplFile))
			$this->_setTemplate($tplFile);
		else
			$this->_tpl = null;
		$this->_tplOptions = new \stdClass();
		$this->_tplLanguages = new \stdClass();
		$this->_tplArguments = new \stdClass();
		$this->_tagValue = new \stdClass();
		$this->_selectOptions = new \stdClass();
		$this->_styleCollection = new \stdClass();
		$this->_classCollection = new \stdClass();
		$this->_oNested = new \stdClass();
		$this->_oLoop = new \stdClass();
		$this->_oLoopCount = -1;
		$this->_oButtons = array();
		$this->_htmlToken = null;
		$this->_setPopup();
	}
	/**
	 * Build class output, build an associative array with index for template code,
	 * elements, values, selected options tag values, and elements style
	 * @final
	 * @access private
	 * @param unknown_type $scope
	 * @uses /phpJar/html/TemplateParser#_get()
	 * @uses /phpJar/html/Template#$_tagValue
	 * @uses /phpJar/html/Template#$_selectOptions
	 * @uses /phpJar/html/Template#$_styleCollection
	 * @uses /phpJar/html/Template#$_classCollection
	 * @return mixed associative array
	 */
	final private function _parse($scope = self::_SCOPE_BASIC)
	{
		$rs = new \stdClass();
		$rs->{self::_RETURNED_TEMPLATE} = $this->_oTemplate->_parse();
		$rs->{self::_RETURNED_HTMLTAG} = $this->_htmlTag;
		$rs->{self::_RETURNED_POPUP_STATUS} = $this->_getPopupmode();
		return $rs;
	}
	/**
	 * unset an argument from template
	 * @final
	 * @access public
	 * @param string $name
	 * @param string $scope
	 * @return null
	 */
	final public function _unsetTemplateArg($name, $scope = self::_SCOPE_GLOBAL)
	{
		if(!property_exists($this->_tplArguments,$scope))
			unset($this->_tplArguments->{$scope}->{$name});
	}
}
?>