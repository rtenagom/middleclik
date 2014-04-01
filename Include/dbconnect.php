<?php
	
	function dbConnect() 
	{
		$host = "lake13.rice.iit.edu:3306";
		$user = "iituser";
		$password = "-8iituser!";
		$dbc = mysql_connect($host,$user,$password);
		mysql_select_db("middleclik");
		return $dbc;
	}

?>