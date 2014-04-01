<?php
	session_start();
	ob_start();
	
	include ("Include/common.php");
	include ("Include/a2_csv.php");
	
	mydoctype();
	define("TITLE", "MiddleClik");
	myheader();
	
	//Title of the website
	$html = "<div class=\"row-fluid\">";
	$html .= "<div class=\"span6\">";
	$html .= "<h3 class=\"text-left\">Welcome to our site</h3>";
	$html .= "</div><div class=\"span6\">";
	$html .= "<h3 class=\"text-right\">";
	$html .= "<a class=\"btn disabled\"><i class=\"icon-home icon-white\"></i> &nbspMain website</a>";
	
	// Check if is admin or not to display two different buttons 
	//Blue means is admin and will have access there. Grey means no admin and will get an access denied error
	if (!is_loggedin() OR (is_loggedin() && is_admin())) {
		$html .= " <a class=\"btn btn-primary\" href=\"Administration/admin.php\"><i class=\"icon-cog icon-white\"></i> &nbspAdmin website</a>";
	} else {
		$html .= " <a class=\"btn\" href=\"Administration/admin.php\"><i class=\"icon-cog\"></i> &nbspAdmin website</a>";
	}
	// Check if display Login or Logout button
	if (is_loggedin()) {
		$html .= " <a class=\"btn btn-danger\" href=\"Include/logout.php\"><i class=\"icon-off icon-white\"></i> &nbspLogout</a>";
	} else {
		$html .= " <a class=\"btn btn-success\" href=\"Include/login.php\"><i class=\"icon-ok icon-white\"></i> &nbspLogin</a>";
	}
	$html .= "</h3>";
	$html .= "</div></div><br />";
	
	// LOGIN page
	// Displayed if no user has logged in
	if (!is_loggedin()) {
		$html .= "<fieldset id=\"customfieldset\"><legend class=\"text-center\" id=\"customlegend\">You must be logged in to access this part of the website</legend>";
		$html .= "<p class=\"text-center\">Click the Login button at the top left corner of the page</p>";
		$html .= "</fieldset><hr>";
		mybody($html);
		myfooter();
		ob_end_flush();
		exit();
	}
	
	// user welcome
	$html .= "<h3 class=\"text-center\">Hello, ".$_SESSION['user_name']." from ".$_SESSION['company']."!</h3><hr>";
	
	// Display the files of the company
	if (is_dir("Company_directory/".$_SESSION['company'])){
		// Set the directory name and scan it:
		$folder_to_scan = "Company_directory/".$_SESSION['company'];
		$file_array = scandir($folder_to_scan);
		
		// Print the CSV first if it exists
		$csv_path = $folder_to_scan."/marketing.csv";
		if (is_file($csv_path)) {
			$load_csv_array = loadCSVData($csv_path);
			$html .= readCSVData($load_csv_array);
		}
		
		// Print all the items in fieldsets
		foreach ($file_array as $file) {
			
			$file_path = $folder_to_scan."/".$file;
			if ((is_file($file_path)) AND (substr($file, 0, 1) != '.')) { // If it is a file 
				
				$file_name = pathinfo($file, PATHINFO_FILENAME);
				$file_extension = pathinfo($file, PATHINFO_EXTENSION);
				
				// Show the img or the snippet
				if ($file_extension == "html") {
					//Print the Campaign name as a title of the fieldset
					$html .= "<fieldset id=\"customfieldset\"><legend class=\"text-center\" id=\"customlegend\">".$file_name."</legend>";
					$html .= "<div class=\"text-center\"><iframe src=\"$file_path\" style=\"background-color:white; border-radius:9px;\" width=\"1000px\" height=\"300px\"></iframe></div>";
				} else if ($file_extension != "csv"){
					//Print the Campaign name as a title of the fieldset
					$html .= "<fieldset id=\"customfieldset\"><legend class=\"text-center\" id=\"customlegend\">".$file_name."</legend>";
					$html .= "<div class=\"text-center\"><img class=\"img-polaroid\" src =\"".$file_path."\" /></div>";
				}
				
				$html .= "</fieldset>";
						
			} // if

		} // for
		
	} else {
		$html .= "<p class=\"text-center\">There are no files to show for this company</p>";
	}

	$html .= "<hr>";
	
	mybody($html);
	
	myfooter();
	
	ob_end_flush();
?>


