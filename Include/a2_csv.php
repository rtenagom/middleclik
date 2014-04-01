<?php

	// assignment grad specific functions
	function loadCSVData($pathToCSV) {
		$dataFile = file($pathToCSV);
		$returned_data = array();
		foreach($dataFile as $line) {
			$returned_data[] = str_getcsv($line);
		}
		return $returned_data;
		
	}

	function readCSVData($data_array) {
		
		$html = "<table class=\"table table-bordered table-striped\">";
		
		foreach($data_array as $row => $dataInRow) {
			$columnType = "td";
			if ($row == 0) {
				$columnType = "th";
			}
			
			$html .= "<tr>";
			foreach($dataInRow as $columnValue) {
				$html .= "<$columnType>".$columnValue."</$columnType>";
			}
			$html .= "</tr>";
		}
		$html .= "</table>";
		return $html;
		
	}

?>
