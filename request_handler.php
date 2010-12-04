<?php
include_once ($_SERVER['DOCUMENT_ROOT']."/srv/autoload.php");

use phpJar\Exceptions;

class RequestHandler
{
	private static $PASSWD = 'andreas';
	private static $USERNAME = 'techmind';
	private $class;
	private $args;
	private $oReflection;
	private $oReflectionMethod;
	private $http_method;

	function __construct($request_method)
	{
		$this->http_method = $request_method;
		$this->setAttrs();
	}

	private function realm()
	{
		header('WWW-Authenticate: Basic realm="Techmind Authentication"');
		header('HTTP/1.0 401 Unauthorized');
	}

	private function authenticate()
	{
		if($_SERVER['PHP_AUTH_USER'] != self::$USERNAME || $_SERVER['PHP_AUTH_PW'] != self::$PASSWD)
			return false;
		session_start();
		$_SESSION['username'] = 'Remote Caldera RIP station';
		return true;
	}

	private function setAttrs()
	{
		if($this->http_method == 'POST')
		{
			if (isset($_POST['data']))
			{
				$ob = stripslashes(htmlentities($_POST['data']));
				if (!isset($_SERVER['PHP_AUTH_USER']))
					$this->realm();
				else
					if (!$this->authenticate())
					{
						header('HTTP/1.0 401 Unauthorized');
						return;
					}
			}
			else
				$ob = stripslashes($GLOBALS['HTTP_RAW_POST_DATA']);
			$ob = json_decode($ob)->json;
			$namespace = $ob->namespace;
			$class = $ob->class;
			$method = $ob->method;
			$this->args = (isset($ob->args))?array($ob->args):array();
		}
		else
		{
			$namespace = $_GET['namespace'];
			$class = $_GET['class'];
			$method = $_GET['method'];
			$this->args = (isset($_GET['args']))?$_GET['args']:array();
		}
		if(is_string($namespace) )
			$namespace = '\\'.$namespace.'\\';
		else
			$namespace = null;
		$this->class = $namespace.$class;
		$this->method = $method;
	}

	private function callConstructor(){return $this->oReflection->getConstructor()->getNumberOfRequiredParameters() > 0;}

	private function passMethodArgs(){return $this->oReflectionMethod->getNumberOfParameters() > 0;}

	public function call()
	{
		try{
			$this->oReflection = new \ReflectionClass($this->class);
			$this->oReflectionMethod = $this->oReflection->getMethod($this->method);
			$this->oResp = null;
			if ($this->callConstructor())
				$oResp = $this->oReflectionMethod->invoke($this->oReflection->newInstanceArgs($this->args));
			elseif ($this->passMethodArgs())
				$oResp = $this->oReflectionMethod->invokeArgs($this->oReflection->newInstance(),$this->args);
			else
				$oResp = $this->oReflectionMethod->invoke($this->oReflection->newInstance());
			if ($this->http_method == 'POST')
				$oResp = json_encode($oResp);
			echo $oResp;
		}
		catch (phpJar\Exceptions\PhpJar_Exception $e)
		{
			echo json_encode(phpJar\Exceptions\PhpJar_Exception::returnJSON_Exception($e),true);
		}
	}
}

$req = new RequestHandler($_SERVER['REQUEST_METHOD']);
$req->call();

?>