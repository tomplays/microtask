<?php 

/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                           Config                                              //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///

/**
 * Configuration object (singleton), loaded form INI file
 * Usage :
 * <code>
 *     $config = Config::instance();
 *     echo $config->version;
 *     $allConfig = $config->settings;
 *     echo $allConfig["version"];
 *     
 *     // Multidimensional ini file
 *     echo $config->database;
 *     echo $config->database["hostname"];
 * </code>
 */
class Config {
	
	private static $instance;
	
	private $settings = array(
		"version" => 1.0
	);

	private function __construct() {

	}

	/**
	* @return Config object
	*/
	public static function instance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Import variables from an INI file
	 * @param $iniFile file path
	 */
	public function importConfig($iniFile) {
		$this->settings = array_merge($this->settings, parse_ini_file($iniFile, true));
		$this->_fixIniMulti($this->settings);
	}

	// convert data.a.query, data.a.since, ... data.b.query, data.b.since, ... to multidimensional array
	// => data = [ a => [query, since, ...], b => [query, since, ...] ]
	private function _fixIniMulti(&$ini_arr) {
		foreach ($ini_arr AS $key => &$value) {
			if (is_array($value)) {
				$this->_fixIniMulti($value);
			}
			if (strpos($key, '.') !== FALSE) {
				$key_arr = explode('.', $key);
				$last_key = array_pop($key_arr);
				$cur_elem = &$ini_arr;
				foreach ($key_arr AS $key_step) {
					if (!isset($cur_elem[$key_step])) {
						$cur_elem[$key_step] = array();
					}
					$cur_elem = &$cur_elem[$key_step];
				}
				$cur_elem[$last_key] = $value;
				unset($ini_arr[$key]);
			}
		}
	}

	/**
	 * Get a specific value
	 * @param key configuration key (or special key "settings" for getting all variables - by copy)
	 */
	public function __get($key) {
		switch ($key) {
			case "settings": return $this->settings;
			default        : return $this->settings[$key];
		}
	}

	public function __toString() {
		return "[Config | " . count($this->settings) . " variable(s)]";
	}

}
