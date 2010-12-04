<?php
/*****************************************************************************************************
 * Template Popup implementation																											*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 *
 * @final TemplatePopop_Exceptions Class - Exception class for follow class								*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\servlets\ServletTemplate Current class for which building this exception class*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class TemplatePopup_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\html;
use phpJar;
use phpJar\Exceptions as _exceptions;
/**
 * TemplatePopup Class --	 																														*
 * @final																																						*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage servlets																															*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
abstract class TemplatePopup
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	public $options;
	public $wrapper_options;
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 *
	 */
	public function _getProperties(){return get_object_vars($this);}
	/**********************************
	 * Class Setters methods Area *
	 **********************************/
	/**
	 *
	 * @param string $event
	 */
	public function _setCloseEvent($event = null){$this->options->closeEvent = $event;}
	/**
	 *
	 * @param string $title
	 */
	public function _setTitle($title = null)
	{
		if(trim($title) == '')
		{
			$oLang = phpJar\Language::_getSpecificLanguage();
			$title = $oLang->popup->title;
		}
		$this->wrapper_options->title = trim($title);
	}
	/**
	 *
	 * @param float $px
	 */
	public function _setHeigth($px)
	{
		$px = (float)$px;
		$this->wrapper_options->heigth = $px.'px';
	}
	/**
	 *
	 * @param float $px
	 */
	public function _setWidth($px)
	{
		$px = (float)$px;
		$this->wrapper_options->width = $px.'px';
	}
	/**********************************
	 * Class Getters methods Area *
	 **********************************/
	/**
	 *
	 * @final
	 * @access public
	 * @return \stdClass instance
	 */
	final public function _getAttrs(){return (object)get_object_vars($this);}
	/**
	 *
	 */
	final public function _getCloseEvent(){return $this->options->closeEvent;}
	/**
	 *
	 */
	final public function _getTitle(){return $this->wrapper_options->title;}
	/**
	 *
	 */
	final public function _getHeigth(){return $this->wrapper_options->heigth;}
	/**
	 *
	 */
	final public function _getWidth(){return $this->wrapper_options->width;}
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 *
	 * @param array $attrs
	 */
	public function __construct($options = array(),$wrapper_options = array()){$this->_init((array)$options,(array)$wrapper_options);}
	/**
	 *
	 * @param array $attrs
	 */
	protected function _init(array $options, array $wrapper_options)
	{
		$this->options = new \stdClass();
		$this->wrapper_options = new \stdClass();

		if(!empty($options))
			foreach ($options as $attr => $value)
				$this->options->{$attr} = $value;

		if(!empty($wrapper_options))
			foreach ($wrapper_options as $attr => $value)
				$this->wrapper_options->{$attr} = $value;
	}

}
?>