<?php

/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                      Boot Loader                                              //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                                                                               //
//  Load :                                                                                       //
//   * Config object (INI file)                                                                  //
//   * PDO object (INI file)                                                                     //
//                                                                                               //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///

require_once(realpath(dirname(__FILE__)) . "/config.class.php");

class Loader {
	
	/** Singleton instance */
	protected static $_instance; 

	/** Configuration */
	protected $_cfg; 
	
	/** PDO object*/
	private $_db;
	
	/** INI file location */
	private $_iniPathLocation = "../config/config.ini"; 
	
	/** Real path */
	protected $_path = NULL;

	/** tables prefix */
	protected $_pref = NULL; 

	
	/**********************************************************************************************/
	/***                                  Initialisation                                        ***/
	/**********************************************************************************************/
	
	
	public static function getInstance($loadIni = true, $initDb = true) { 
		if(!self::$_instance) { self::$_instance = new self($loadIni, $initDb); }
		return self::$_instance; 
	}

	private function __construct ($loadIni = true, $initDb = true){
	
		$this->_path = $this->_getRealPath();
		
		if($loadIni || $initDb) $this->_loadConfig();
		if($initDb) $this->_loadConnection();

		// http://www.php.net/manual/en/timezones.europe.php
		date_default_timezone_set("Europe/Paris");
	}
	
	/**********************************************************************************************/
	/***                                    Code  métier                                        ***/
	/**********************************************************************************************/
	
	/**
	 * Load INI file
	 */
	private function _loadConfig(){

		$absolutePath = $this->_getRealPath($this->_iniPathLocation);
		$this->_cfg = Config::instance();
		$this->_cfg->importConfig($absolutePath);

	}
	
	/**
	 * MySQL connection
	 */
	private function _loadConnection(){

		$dbCfg = $this->_cfg->database;
		
		$dataSrcName    = 'mysql:host=' . $dbCfg["hostname"] . ';dbname=' . $dbCfg["database"]; 
		$username       = $dbCfg["username"];
		$password       = $dbCfg["password"];
		$this->_pref    = $dbCfg["prefix"];

		try{
			$db = new PDO($dataSrcName, $username, $password);
			$res = $db->query("SET NAMES 'utf8'"); // transactions (character_set_client, character_set_connection, character_set_results) en UTF8
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Lancer des exceptions quand les requêtes n'aboutissent pas
			
			$this->_db = $db;
			
		}
		
		catch(Exception $e){
			echo 'An error has occured : ' . $e->getMessage();
			die();
		}
		
	}
	
	/**********************************************************************************************/
	/***                               Méthodes utilitaires                                     ***/
	/**********************************************************************************************/
	
	/**
	 * Path relative to THIS file => absolute path
	 * @param relativePath 	path relative to this file
	 * @return absolute path.
	 */
	private function _getRealPath($relativePath = NULL){
		if(is_null($this->_path)) $this->_path = realpath(dirname(__FILE__)) . '/';
		return ($this->_path . (empty($relativePath) ? '' : $relativePath) );
	}
	
	/**********************************************************************************************/
	/***                                                                                        ***/
	/**********************************************************************************************/

	public function __get($property) {
		
		switch($property){
			case 'db'  : return $this->_db; break;
			case 'cfg' : return $this->_cfg; break;	
		}
		
		// Accesseur non défini !
		throw new Exception('Loader : Invalid getter property : ' . $property . ' !');
	}

	public function __set($key, $val) {
		throw new Exception('Loader : Invalid setter property : ' . $key . ' !');
	}

	public function __toString(){
		return '[Loader - ' . 
			'Config : ' . ( empty($this->_cfg) ? 'hs' : 'ok') .  ' - ' .
			'PDO : '    . ( empty($this->_db)  ? 'hs' : 'ok') .  ' ]'
		;
	}
	
}

?>