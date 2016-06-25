<?php

/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                       CSV Reader                                              //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                                                                               //
//                                                                                               //
//                                                                                               //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///

namespace CsvParser;

class CsvReader {


	/** CSV file parsed */
	private $_filename = "";

	/** Last formater used */
	private $_formater = null;

	/** Last result */
	private $_result = null;

	/** Last result (with GroupBy) */
	private $_resultGb = null;

	
	/**********************************************************************************************/
	/***                                  Initialization                                        ***/
	/**********************************************************************************************/

	public function __construct (){

	}
	

	/**********************************************************************************************/
	/***                                  Business Logic                                        ***/
	/**********************************************************************************************/

	/**
	 * Read a CSV file (parse / sanitize data) and convert it to array
	 * @param $filename 		Full path (local or HTTP)
	 * @param $formater 		describes how to format each line of the CSV
	 * @param $skipFirstLines 	Skip the header (number of lines to skip at first)
	 * @return Array
	 */
	public function read($filename, $formater, $skipFirstLines = 0){

		$this->_filename = $filename;
		$this->_formater = $formater;
		$aResult = array();

		$file  = new \SplFileObject($filename);
		while (!$file->eof()) {

			$line = $file->fgetcsv();
			if(--$skipFirstLines >= 0) {
				continue;
			}
			$aResult[] = $formater->read($line);
		}

		$this->_result = $aResult;
		return $aResult;
	}

	/**
	 * Group data by column (you need to call "read" method first)
	 * @param $columnName 		ID column to group by
	 * @param $removeColumn 	If true, remove the ID column value in the dataset
	 * @return Array
	 */
	public function groupBy($columnName, $removeColumn = false){

		$aResult = array();
		$lastId = null;

		foreach ($this->_result as $key => $val) {

			// if $columnName is empty => use last $columnName value founded
			if ($val[$columnName] == "") {
				if($lastId == null) {
					throw new Exception("CsvReader : " + $columnName + " is not defined !");
				}
				$val[$columnName] = $lastId;
			}

			$lastId = $val[$columnName];

			if ($removeColumn) {
				unset($val[$columnName]);
			}

			$aResult[$lastId][] = $val;
		}

		$this->_resultGb = $aResult;
		return $aResult;
	}
	

	/**********************************************************************************************/
	/***                                  Utility methods                                       ***/
	/**********************************************************************************************/

	/**
	 * Sava data to JSON
	 * @param $filename 		JSON path
	 * @param $data 			data to save
	 * @param $prettyPrint 		Pretty print the JSON if true
	 * @return True if the JSON file has been saved successfully
	 */
	public function saveToJson($filename, $data = array(), $prettyPrint = false){
		$handle = fopen($filename, "w");
		if($handle) {
			fwrite($handle, json_encode($data,  null));
			fclose($handle);
			return true;
		}
		return false;
	}	


	/**********************************************************************************************/
	/***                                                                                        ***/
	/**********************************************************************************************/

	public function __get($property) {
		
		switch($property){
			case "filename" : return $this->_filename; break;
			case "formater" : return $this->_formater; break;
			case "result"   : return $this->_result;   break;
			case "resultGb" : return $this->_resultGb; break;
		}
		
		// Accesseur non défini !
		throw new Exception('CsvReader : Invalid getter property : ' . $property . ' !');
	}

	public function __set($key, $val) {
		throw new Exception('CsvReader : Invalid setter property : ' . $key . ' !');
	}

	public function __toString(){
		return "[CsvReader]";
	}
	
}

?>