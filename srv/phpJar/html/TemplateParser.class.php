<?php
/*****************************************************************************************************
 * Template Parse Engine																															*
 * Search for proper template file, load it and add to it the right variables										*
 *****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final TemplateParser_Exceptions Class - Exception class for follow class									*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\html\TemplateParser Current class for which building this exception class		*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage Exceptions																													*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class TemplateParser_Exceptions extends PhpJar_Exception {}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar\html;
use phpJar;
/**
 * TemplateParser Class -- 																															*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @subpackage html																																*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class TemplateParser
{
	/***************************
	 * Class attributes Area	*
	 ***************************/
	/**
	 *
	 */
	protected $_fileName;
	/**
	 * template file path
	 * @access private
	 * @var string $_template
	 */
	private $_template;
	/**
	 * template Arguments these which will be exported in template file
	 * @access protected
	 * @var array $_templateArgs
	 */
	protected $_templateArgs;
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * TemplateParser constructor method
	 * @access public
	 * @param string $template
	 * @uses /phpJar/TemplateParser_init($template)
	 * @return
	 */
	public function __construct($template){$this->_init($template);}
	/**
	 * set template variables to _templateArgs array
	 * @final
	 * @access public
	 * @param string $member
	 * @param mixed $value
	 * @return null
	 */
	final public function __set($member, $value){$this->_templateArgs[$member] = $value;}
	/**
	 * attribute initialization,
	 * add the template extension , set template path, check if template file exists
	 * @final
	 * @access private
	 * @param string $template
	 * @uses /phpJar/TemplateParser#$_template)
	 * @return null
	 */
	final private function _init($template)
	{
			$this->_template = $template;
			$this->_fileName = basename($template);
	}
	/**
	 *
	 * Include requested or ruuning template
	 * if template file exists then load it and return the stream buffer
	 * @final
	 * @access public
	 * @param string $tpl
	 * @return string
	 */
	final public function _parse($tpl = null)
	{
		$buffer = null;
		$fh = null;
		$tpl = trim($tpl);
		if(is_null($tpl) || empty($tpl))
			$tpl = $this->_template;

		$rootDir = phpJar\HTML_TPL_PROJECT_PATH;
		$extension = phpJar\HTML_TPL_EXT;
		$tpl = $rootDir.$tpl.$extension;
		if(file_exists($tpl))
		{
//			print_r(array($tpl,'1'));
			ob_start();
//			print_r(array($tpl,'2'));
			if(is_array($this->_templateArgs))
				extract($this->_templateArgs);
			/*
			 * If the PHP installation does not support short tags we'll
			 * do a little string replacement, changing the short tags
			 * to standard PHP echo statements.
			 */
//			print_r(array($tpl,'3'));
			$fh = fopen($tpl,'r');
			$contents = fread($fh,filesize($tpl));
			fclose($fh);
			$preg = <<<preg
/;*\s*\?>/
preg;
			if((bool) @ini_get('short_open_tag') === false)
				$contents = str_replace('<?=','<?php echo ',$contents);

			echo eval('?>'.preg_replace($preg,'; ?>',$contents));
			$buffer = ob_get_contents();
			@ob_end_clean();
		}
		return $buffer;
	}

}
?>