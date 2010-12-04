<?php
/*****************************************************************************************************
 * Logger class implementation																												*
*****************************************************************************************************/
namespace phpJar\Exceptions;
/**
 * @final Logger_Exceptions Class - Exception class for follow class												*
 * @see phpJar\Exceptions\PhpJar_Exceptions Basic Exception Class												*
 * @see phpJar\Logger Current class for which building this exception class									*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
final class Logger_Exceptions extends PhpJar_Exception{}
/*****************************************************************************************************
 * Namespace alias area																															*
 *****************************************************************************************************/
namespace phpJar;
/**
 * Logger Class --	 																																		*
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
class Logger
{
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * Singleton instance
	 * @access private
	 * @static
	 * @var Logger object
	 */
	private static $_instance;
	/**
	 * log file, full path
	 * @access private
	 * @var string
	 */
	private $file;
	/**
	 * file pointer for log file
	 * @access private
	 * @var file pointer
	 */
	private $handle;
	/********************************
	 * Class implementation Area *
	 ********************************/
	/**
	 * Logger Constructor
	 * @access private
  	 * @return Logger object
	 */
	private function __construct()
	{
		$this->file = \phpJar\LOG_FILE_PATH;
		$this->handle = fopen($this->file,'a+');
	}
	/**
	 * Logger destruct function
	 * @access public
	 * @return null
	 */
	public function __destruct()
	{
		if(file_exists($this->file)){fclose($this->handle);}
		self::$_instance = null;
		return true;
	}
	/**
	 *
	 * @access private
	 * @return null
	 */
	private function __clone(){}
	/**
	 * Singleton instance creaion
	 * @access protected
	 * @static
	 * @return Logger object
	 */
	protected static function _getInstance()
	{
		if(empty(self::$_instance))
			self::$_instance = new self();
		return self::$_instance;
	}
	/**
	 * write the log into the log file
	 * @param string $message
	 * @param string $type
	 * @return true on success, false on failed
	 */
	public static function _create($message, $type = 'error')
	{
		$instance = self::_getInstance();
		$dt = new \phpJar\utils\DT();
		$dt->_convertDateToLocal();
		$str = sprintf("[%s] [%s] %s\n", mb_strtoupper($type,'utf-8'), $dt->date, $message);
		fwrite($instance->handle, $str);
	}

}
?>