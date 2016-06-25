<?php

/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                     CSV Converter                                             //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///
//                                                                                               //
//                                                                                               //
//                                                                                               //
/// /////////////////////////////////////////////////////////////////////////////////////////// ///

namespace CsvParser;

class CsvConverter {
	
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
	 * Read an array of data CSV file, parse / sanitize data, and return an array
	 * @param aLine		Array
	 * @return new Array
	 */
	public function toHtmlTable($reader) {
		print_r($reader->result);

		$aResult   = $reader->result;
		$oFormater = $reader->formater;
?>
		<div class="responsive">
		<table border="0" width="100%">
			<thead>
				<tr>
					<?php foreach ($oFormater->cols as $col): ?>
						<th><?php echo $col["key"] ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($aResult as $line): ?>
				<tr>
					<?php foreach ($oFormater->cols as $col): ?>
						<td>
							<?php // echo mb_strimwidth(filter_var($line[$col["key"]], FILTER_SANITIZE_SPECIAL_CHARS), 0, 50, "...", "UTF-8") ?>
							<?php echo filter_var($line[$col["key"]], FILTER_SANITIZE_SPECIAL_CHARS); ?>
						</td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>
<?php
	}

	
	/**********************************************************************************************/
	/***                                  Utility methods                                       ***/
	/**********************************************************************************************/
	
	
	
	/**********************************************************************************************/
	/***                                                                                        ***/
	/**********************************************************************************************/

	public function __toString(){
		return "[CsvConverter - " . 
			count($this->_cols) . " column" . (count($this->_cols) > 1 ? "s" : "") .
		"]"
		;
	}
	
}

?>