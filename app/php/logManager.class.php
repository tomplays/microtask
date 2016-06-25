<?php

/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                       Log Manager                                             //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                                                                               //
// Example :                                                                                     //
// $logger         = LogManager::getLogger();                                                    //
// $logger->level  = "debug";                                                                    //
// $logger->html   = true;                                                                       //
// $logger->output = true;                                                                       //
// echo $logger;                                                                                 //
//                                                                                               //
// $logger->debug("debug!");                                                                     //
// $logger->info("info!");                                                                       //
// $logger->warn("warn!");                                                                       //
// $logger->error("error!");                                                                     //
// $logger->fatal("fatal!");                                                                     //
// $logger->breakLine("info");                                                                   //
//                                                                                               //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///


class LogManagerException extends Exception { }

class LogManager {
	
	/** Singleton instance */
	protected static $_instance; 

	/** Display logs with colors */
	protected $html = true;

	/** Add <br /> after each log */
	protected $autoBr = false;

	/** Logs to display */
	protected $level = "debug";

	/** Output logs directly */
	protected $output = false;

	protected $aColors = array (
		"debug" => "#8e44ad",
		"info"  => "#2980b9",
		"warn"  => "#f1c40f",
		"error" => "orangeRed",
		"fatal" => "red"
	);

	protected $buffer = "";

	
	/**********************************************************************************************/
	/***                                  Initialisation                                        ***/
	/**********************************************************************************************/
	
	
	public static function getLogger() { 
		if(!self::$_instance) { self::$_instance = new self(); }
		return self::$_instance; 
	}

	private function __construct (){

	}


	/**********************************************************************************************/
	/***                                                                                        ***/
	/**********************************************************************************************/

	public function debug($text = "") {
		$this->_print("DEBUG", $text, $this->aColors["debug"]);
	}

	public function info($text = "") {
		$this->_print("INFO", $text, $this->aColors["info"]);
	}

	public function warn($text = "") {
		$this->_print("WARN", $text, $this->aColors["warn"]);
	}

	public function error($text = "") {
		$this->_print("ERROR", $text, $this->aColors["error"]);
	}

	public function fatal($text = "", $exit = true) {
		if ($exit && !$this->output) {
			$this->flush();
		}
		$this->_print("FATAL", $text, $this->aColors["fatal"]);
		if ($exit) {
			$this->flush();
			exit();
		}
	}

	public function breakLine($level = "debug") {
		$this->_printOrBuffer($level, $this->html ? "<br />" : "\n");
	}

	public function flush(){
		echo $this->buffer;
		$this->buffer = "";
	}


	/**********************************************************************************************/
	/***                                                                                        ***/
	/**********************************************************************************************/

	private function _print($level = "", $text = "", $color = "black") { 

		if($this->_levelToNumber($level) < $this->_levelToNumber($this->level)) {
			return;
		}

		$out = sprintf("%s[%5s] %s%s%s\n", 
			$this->html ? "<span style='color:$color'>" : "",
			$level,
			$this->html ? "</span>" : "",
			$text,
			$this->html && $this->autoBr ? "<br />" : ""
		);

		$this->_printOrBuffer($level, $out);
	}

	private function _printOrBuffer($level, $text) {

		if($this->_levelToNumber($level) < $this->_levelToNumber($this->level)) {
			return;
		}

		if($this->output) {
			echo $text;
		}
		else {
			$this->buffer .= $text;
		}
	}

	private function _levelToNumber($level) {
		switch(strtolower($level)) {
			case "debug": return 1;
			case "info" : return 2;
			case "warn" : return 3;
			case "error": return 4;
			case "fatal": return 5;
			default     : return 0;
		}
	}
	

	/**********************************************************************************************/
	/***                                                                                        ***/
	/**********************************************************************************************/

	public function a2s($array) {
		return substr(print_r($array, true), 0, -1);
	}


	/**********************************************************************************************/
	/***                                                                                        ***/
	/**********************************************************************************************/

	public function __get($key) {
		switch($key){
			case 'level'  : return $this->level;  break;
			case 'output' : return $this->output; break;
			case 'html'   : return $this->html;   break;
			case 'autoBr' : return $this->autoBr; break;
		}
		
		// Accesseur non défini !
		throw new Exception('LogManager : Invalid getter property : ' . $key . ' !');
	}

	public function __set($key, $val) {

		switch($key){
			case 'level'  : return $this->level  = $val; break;
			case 'output' : return $this->output = $val; break;
			case 'html'   : return $this->html   = $val; break;
			case 'autoBr' : return $this->autoBr = $val; break;
		}

		throw new Exception('LogManager : Invalid setter property : ' . $key . ' !');
	}
	
	public function __toString(){
		return "[LogManager| {$this->level}"
			. ", " . ($this->output ? "output" : "no-output")
			. ", " . ($this->html   ? "html"   : "no-html"  )
			. ", " . ($this->autoBr ? "autoBr" : "no-autoBr")
			. "]" ;
	}
	
}

?>