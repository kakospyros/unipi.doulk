<?php
/*****************************************************************************************************
 * Config.inc class file
 * contains all constants which phpJar needs for correct
 *****************************************************************************************************/
/**
 * @author Kondylis Andreas																													*
 * @package phpJar																																	*
 * @version 1.0																																			*
 * @copyright Copyright (c) 2010, Kondylis Andreas																			*
 * @license																																					*
 *****************************************************************************************************/
namespace phpJar;
/**#@+
 * Constants
 * Root folder for phpJar package
 */
define(__NAMESPACE__.'\PHPJAR_PATH',$_SERVER['DOCUMENT_ROOT'].'/srv/phpJar/');
/**
 * phpJar log file path
 **/
define(__NAMESPACE__.'\LOG_FILE_PATH', \phpJar\PHPJAR_PATH.'/.log/phpJar_'.gethostname().'.log');
/**
 * project default language folder path
 */
define(__NAMESPACE__.'\LANGUAGE_PROJECT_PATH', $_SERVER['DOCUMENT_ROOT'].'/srv/lang/');
/**
 * working project path
 */
define(__NAMESPACE__.'\HTML_TPL_PROJECT_PATH', $_SERVER['DOCUMENT_ROOT'].'/src/htm/');
/**
 *default namespace for scheme
 */
define(__NAMESPACE__.'\SCHEME_NAME_SPACE','\project\scheme\\');
/**
 *default namespace for scheme
 */
define(__NAMESPACE__.'\WIZARD_NAME_SPACE','\project\wizards\\');
/**
 * extension for template files
 */
define(__NAMESPACE__.'\HTML_TPL_EXT', '.tpl.php');
/**
 * default command template
 */
define(__NAMESPACE__.'\HTML_TPL_DEFAULT_ERROR', '/generic/error');
/**
 * default command template
 */
define(__NAMESPACE__.'\HTML_TPL_DEFAULT_COMMAND', '/generic/commands');
/**
 * default language folder
 */
define(__NAMESPACE__.'\DEFAULT_LANGUAGE', 'GB');
/**
 * default folder for wizard templates
 */
define(__NAMESPACE__.'\HTML_TPL_FOLDER_DEFAULT_WIZARD', 'wizards');
/**
 * default folder for wizard templates
 */
define(__NAMESPACE__.'\HTML_TPL_DEFAULT_PREFFIX_WIZARD', 'wizard_%s_');
/**
 * form token life time in seconds
 */
define(__NAMESPACE__.'\HTML_TOKEN_EXPAIRATION_SEC', 5*60);
/**
 * cache folder name
 */
define(__NAMESPACE__.'\CACHE_NAME', '.cache');
/**
 * cache timeclock
 */
define(__NAMESPACE__.'\CACHE_TIME', 30*60);
/**
 * cache folder path
 */
define(__NAMESPACE__.'\CACHE_PATH', \phpJar\PHPJAR_PATH.'/'.\phpJar\CACHE_NAME.'/');
/**
 *Use timezone from phpjar
 */
define(__NAMESPACE__.'\SET_TIMEZONE',true);
/**
 *Use timezone from phpjar
 */
define(__NAMESPACE__.'\DEFAULT_TIMEZONE','Europe/Helsinki');
/**
 *Use timezone from phpjar
 */
define(__NAMESPACE__.'\DATE_ISO_DATE_FORMAT','Y-m-d');
/**
 *Use timezone from phpjar
 */
define(__NAMESPACE__.'\DATE_ISO_TIME_FORMAT','H:i:s');
/**
 *Use timezone from phpjar
 */
define(__NAMESPACE__.'\DATE_ISO_FORMAT',\phpJar\DATE_ISO_DATE_FORMAT.' '.\phpJar\DATE_ISO_TIME_FORMAT);
/**
 *Use timezone from phpjar
 */
define(__NAMESPACE__.'\DATE_LOCAL_DATE_FORMAT','d-m-Y');
/**
 *Use timezone from phpjar
 */
define(__NAMESPACE__.'\DATE_LOCAL_TIME_FORMAT','H:i:s');
/**
 *Use timezone from phpjar
 */
define(__NAMESPACE__.'\DATE_LOCAL_FORMAT',\phpJar\DATE_LOCAL_DATE_FORMAT.' '.\phpJar\DATE_LOCAL_TIME_FORMAT);
/**
 *STRIP_ALLOWABLE_TAGS
 */
define(__NAMESPACE__.'\STRIP_ALLOWABLE_TAGS','<a><b><p><em><span><b><i>');
/**
 *Default column for primary key
 */
define(__NAMESPACE__.'\PRIMARY_KEY','id');

?>