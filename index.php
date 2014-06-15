<?php
//Air MVC framework
//Adrian Balcan
//start date: 19.07.2012
//last update: 12.03.2014
//version 12

define('DS', DIRECTORY_SEPARATOR);
define('ROOT',dirname(__FILE__));
define('APP_DIR', ROOT);

//Paths
define('APP_CONTROLLER', APP_DIR . DS . 'controllers');
define('APP_MODEL', APP_DIR . DS . 'models');
define('APP_VIEWS', APP_DIR . DS . 'views');

require_once('config.php');

//Autoload
function __autoload($className)
{ 
	if (file_exists(APP_CONTROLLER  . DS . strtolower($className) . '.php'))
		require_once(APP_CONTROLLER . DS . strtolower($className) . '.php');
	
	else if (file_exists(APP_MODEL . DS . strtolower($className) . '.php'))
		require_once(APP_MODEL . DS . strtolower($className) . '.php');

	else		
		throw new Exception('Class ' . $className . ' not found');
		//trigger_error('Class ' . $className . ' not found', E_USER_ERROR);
}

function display404(){
	header('HTTP/1.0 404 Not Found');
	echo '<h1>404 Not Found</h1>';
	echo 'The page that you have requested could not be found.';
	exit();
}

class loader{
	public $resources = array();

	//Load model
	public function model($name){
		$this->resources[$name] = new $name();
	}

	//Load controller
	public function controller($name){
		$this->resources[$name] = new $name();
	}

	//Load view
	public function view($name, $params = array(), $returnOutput = false){
		
		if($returnOutput){
			ob_start();
		}

		if(count($params) > 0){
			foreach($params as $key => $param){
				$$key = $param;
			}
		}

		if(file_exists(APP_VIEWS . DS . $name . '.php')){
			require(APP_VIEWS . DS . $name . '.php');
		}else{
			trigger_error($name . ' doesn\'t exists', E_USER_ERROR);			
		}

		if($returnOutput){
			$output = ob_get_clean();
			ob_end_clean();
			return $output;
		}
	}

	public function __get($name){
		if(isset($this->resources[$name])){
			return $this->resources[$name];
		}else{
			trigger_error($name . ' doesn\'t exists', E_USER_ERROR);
		}
	}
}

//Main controller
class controller{
	public $load;

	public function __construct(){
		$this->load = new loader();
		header("Cache-Control: public");
		header("Pragma: public");
	}

	public function __get($name){
		if(isset($this->$name)){
			return $this->$name;
		}else{
			return $this->load->$name;
		}		
	}
}

//Main model
class model{
	public $db;
	public $load;

	public function __construct(){
		$this->load = new loader();
		$this->db = new db();
	}

	public function __get($name){
		if(isset($this->$name)){
			return $this->$name;
		}else{
			return $this->load->$name;
		}		
	}
}

class db{
	public $conn;
	public function __construct(){
		$this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
		mysqli_select_db($this->conn, DB_DATABASE);
	}

	public function query($str){
		$res = mysqli_query($this->conn, $str) or trigger_error(mysql_error(), E_USER_ERROR);
		return $res;
	}

	public function getArray($query){
		$res 	= $this->query($query);
		$result	= array();
		$i 		= 0;

		while($row = mysqli_fetch_assoc($res)){
			$result[$i] = $row;
			$i++;
		}

		return $result;
	}
}

//Router
if(!empty($_GET['url'])){

	$routed = false;
	foreach ($routes as $key => $route) {
		if(preg_match($route['url'], $_GET['url'])){
			$inst = new $route['controller']();
			$inst->$route['action']();
			$routed = true;
			break;
		}
	}

	$segment = explode('/', $_GET['url']);
	
	if(!$routed){
		try{
			if(class_exists($segment[0]) && get_parent_class($segment[0]) == 'controller'){
				$inst = new $segment[0]();

				if(!empty($segment[1])){
					if(method_exists($inst, $segment[1])){
						if(!empty($segment[2])){
							$inst->$segment[1]($segment[2]);
						}else{
							$inst->$segment[1]();
						}						
					}else{
						display404();
					}
				}else{
					if(method_exists($inst, 'index')){
						$inst->index();
					}else{
						display404();
					}					
				}
			}else{
				display404();
			}
		}catch(Exception $e){
			display404();
		}
	}
}else{
	$inst = new $default['controller']();
	$inst->$default['action']();
}

