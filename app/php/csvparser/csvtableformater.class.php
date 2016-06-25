<?php

/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                     CSV Formater                                              //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                                                                               //
//                                                                                               //
//                                                                                               //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///

namespace CsvParser;

abstract class CsvFormaterType {
	const RAW    = 0;
	const INT    = 1;
	const STRING = 2;
	const HTML   = 3;
	const FLOAT  = 4;
	const URL    = 5;
	const EMAIL  = 6;
	const BOOL   = 7;
}


class CsvTableFormater {
	
	/** tables prefix */
	protected $_cols = array();

	
	/**********************************************************************************************/
	/***                                  Initialization                                        ***/
	/**********************************************************************************************/

	public function __construct (){

	}
	

	/**********************************************************************************************/
	/***                                  Business Logic                                        ***/
	/**********************************************************************************************/

	/**
	 * Configure a column
	 * @param column		Column ID (spreadsheet letter or index number)
	 * @param key			Column name
	 * @param type			Column type (String by default)
	 */
	public function addColumn($column, $key, $type = CsvFormaterType::STRING){
		$index = (gettype($column) === "integer") ? $column : $this->_letterToNumber($column);
		$this->_cols[$index] = array("key" => strtolower($key), "type" => $type);
	}

	/**
	 * Group data by column (you need to call "read" method first)
	 * @param $columnName 		ID column to group by
	 * @param $removeColumn 	If true, remove the ID column value in the dataset
	 * @return Array
	 */
	public function groupBy($columnName, $removeColumn = false){

	}

	/**
	 * Read an array of data CSV file, parse / sanitize data, and return an array
	 * @param aLine		Array
	 * @return new Array
	 */
	public function read($aLine) {
		$aResult = array();
		//$aLine = (gettype($line) === "array") ? $line : $this->_letterToNumber($column);

		foreach ($aLine as $key => $val) {

			if (!array_key_exists($key, $this->_cols)) {
				continue;
			}

			$col = $this->_cols[$key];
			$aResult[$col["key"]] = $this->_sanitize($val, $col["type"]);
		}

		return $aResult;
	}

	
	/**********************************************************************************************/
	/***                                  Utility methods                                       ***/
	/**********************************************************************************************/
	
	/**
	 * Convert some character into numeric (a = 0, b = 1, C = 2, d = 3, etc)
	 * @param letter 	letter to convert
	 * @return numeric value
	 */
	private function _letterToNumber($letter){
		return ord(strtolower(strval($letter))) - 97;
	}

	/**
	 * Filters a variable with a specified filter
	 * @param $value 	Value to filter
	 * @param $type 	The type of the filter to apply
	 * @return The filtered data
	 */
	private function _sanitize($value, $type = CsvFormaterType::STRING) {
		$value = ($type === CsvFormaterType::RAW) ? $value : trim(strval($value));
		switch($type) {
			case CsvFormaterType::INT   : return intval($value);
			case CsvFormaterType::STRING: return filter_var($value, FILTER_SANITIZE_STRING);
			case CsvFormaterType::HTML  : return filter_var($value, FILTER_UNSAFE_RAW);
			case CsvFormaterType::FLOAT : return floatval(str_replace(",", ".", filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND)));
			case CsvFormaterType::URL   : return filter_var($value, FILTER_SANITIZE_URL);
			case CsvFormaterType::EMAIL : return filter_var($value, FILTER_SANITIZE_EMAIL);
			case CsvFormaterType::RAW   : return $value;
			case CsvFormaterType::BOOL  : return boolval($value);
			default                     : return $value;
		}
	}

	/*
	private _getColumnByName($name) {
		var $key = array_search(strtolower($name), array_map('strtolower', $this->_cols));
	}
	*/
	
	/**********************************************************************************************/
	/***                                                                                        ***/
	/**********************************************************************************************/

	public function __get($property) {
		
		switch($property){
			case 'cols' : return $this->_cols; break;	
		}
		
		// Accesseur non défini !
		throw new Exception('CsvFormaterType : Invalid getter property : ' . $property . ' !');
	}

	public function __set($key, $val) {
		throw new Exception('CsvFormaterType : Invalid setter property : ' . $key . ' !');
	}

	public function __toString(){
		return "[CsvFormater - " . 
			count($this->_cols) . " column" . (count($this->_cols) > 1 ? "s" : "") .
		"]"
		;
	}
	
}

// PHP 5 >= 5.5.0 
if (! function_exists('boolval')) {
	/**
	 * Get the boolean value of a variable
	 *
	 * @param mixed The scalar value being converted to a boolean.
	 * @return boolean The boolean value of var.
	 */
	function boolval($var) {
		return !! $var;
	}
}

?>