<?php
	session_start();
	ob_start();
	
	include ("../Include/common.php");
	
	mydoctype();
	define("TITLE", "MiddleClik Admin");
	myheader();
	
	//Title of the website
	$html = "<div class=\"row-fluid\">";
	$html .= "<div class=\"span6\">";
	$html .= "<h3 class=\"text-left\">Administration site</h3>";
	$html .= "</div><div class=\"span6\">";
	$html .= "<h3 class=\"text-right\">";
	$html .= "<a class=\"btn btn-primary\" href=\"../index.php\"><i class=\"icon-home icon-white\"></i> &nbspMain website</a>";
	$html .= " <a class=\"btn disabled \"><i class=\"icon-cog icon-white\"></i> &nbspAdmin website</a>";
	// Check if display Login or Logout button
	if (is_loggedin()) {
		$html .= " <a class=\"btn btn-danger\" href=\"../Include/logout.php\"><i class=\"icon-off icon-white\"></i> &nbspLogout</a>";
	} else {
		$html .= " <a class=\"btn btn-success\" href=\"../Include/login.php\"><i class=\"icon-ok icon-white\"></i> &nbspLogin</a>";
	}
	$html .= "</h3>";
	$html .= "</div></div><br />";
	
	// LOGIN PAGE
	// If is not loggedin and is not and admin
	if (!is_loggedin() && !is_admin()) {
		$html .= "<fieldset id=\"customfieldset\"><legend class=\"text-center\" id=\"customlegend\">You must be logged in to access this part of the website</legend>";
		$html .= "<p class=\"text-center\">You need administrator privileges here</p>";
		$html .= "<p class=\"text-center\">Click the Login button at the top left corner of the page</p>";
		$html .= "</fieldset><hr>";
		mybody($html);
		myfooter();
		ob_end_flush();
		exit();
	} else if (is_loggedin() && !is_admin()){ // This case is for non-admin users who try to get here
		$html .= "<div class=\"text-center\"><img src=\"../Content/img/access_denied.jpg\" /></div>";
		$html .= "<h3 class=\"text-center\">Access denied</h3>";
		$html .= "<p class=\"text-center\">You don't have administrator privileges</p>";
		$html .= "<p class=\"text-center\"><a class=\"btn btn-primary\" href=\"../index.php\"><i class=\"icon-home icon-white\"></i> &nbspReturn to homepage</a></p>";
		$html .= "<hr>";
		mybody($html);
		myfooter();
		ob_end_flush();
		exit();
	}
	
	// Creating 2 columns
	$html .= "<div class=\"row-fluid\">";

	// Upload snippet FORM
	$html .= "<div class=\"span6\">";
	$html .= "<fieldset id=\"customfieldset\"><legend class=\"text-center\" id=\"customlegend\">Upload an HTML Snippet</legend>";
	
	
	// Handle the snippet upload
	if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['snippet_post'])) { // If POST requested AND the post was from the snippet form
		
		// Create a flag to see if there are any problems
		$flag_problem = FALSE;
		
		// This gets the company name and the marketing campaign name
		// Remove all white spaces so that the directory or the image name are created without them
		// Remove all the possible html tags
		$company_folder = strtolower(str_replace(" ", "", strip_tags($_POST['companyName'])));
		$marketing_name = strtolower(str_replace(" ", "", strip_tags($_POST['marketingCampaignName'])));
		
		if (empty($_POST['htmlSnippet'])) {
			$flag_problem = TRUE;
			$html .= "<p class=\"text-center\"><span class=\"label label-important\">You must enter an HTML Snippet</span></p>";
		}
		
		if (empty($company_folder)) {
			$flag_problem = TRUE;
			$html .= "<p class=\"text-center\"><span class=\"label label-important\">You must enter a valid Company name</span></p>";
		}
		
		if (empty($marketing_name)) {
			$flag_problem = TRUE;
			$html .= "<p class=\"text-center\"><span class=\"label label-important\">You must enter a valid Campaign name</span></p>";
		}
		
		if (!$flag_problem) {	
			
			// Check if the company exists. If not, create the directory
			if (!is_dir("../Company_directory/".$company_folder)){
				mkdir("../Company_directory/".$company_folder);
			}
			
			// Move the uploaded file from tmp to directory:
			$updir = "../Company_directory/".$company_folder."/".$marketing_name.".html";
			
			if(!is_file($updir)){ //Check if the file already exists
				
				if (file_put_contents($updir, $_POST['htmlSnippet'], LOCK_EX) > 0) { // Write the data.
					$html .= "<p class=\"text-center\"><span class=\"label label-success\">Your HTML snippet has been successfully uploaded</span></p>";	
				} else {
					$html .= "<p class=\"text-center\"><span class=\"label label-important\">An error ocurred while saving the data</span></p>";
				}
			
			} else {
				$html .= "<p class=\"text-center\"><span class=\"label label-important\">The Campaign name was already used for this Company</span></p>";
			}
		
		}
		
	} // End of submission if
		
	$html .= "<form class=\"form-horizontal\" action=\"admin.php\" method=\"post\">";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"htmlSnippet\">HTML Snippet: </label><div class=\"controls\"><textarea name=\"htmlSnippet\" class=\"span11 vert\" rows=\"4\" placeholder=\"Write code here...\" id=\"htmlSnippet\"></textarea></div>";
	$html .= "</div>";
	$html .= "<input type=\"hidden\" name=\"snippet_post\" />";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"companyNameSn\">Company: </label><div class=\"controls\"><input type=\"text\" name=\"companyName\" id=\"companyNameSn\" size=\"20\" /></div>";
	$html .= "</div>";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"marketingCampaignNameSn\">Campaign Name: </label><div class=\"controls\"><input type=\"text\" name=\"marketingCampaignName\" id=\"marketingCampaignNameSn\" size=\"20\" /></div>";
	$html .= "</div>";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"icon\"><i class=\"icon-circle-arrow-up\" id=\"icon\"></i> </label><div class=\"controls\"><input class=\"btn btn-success\" type=\"submit\" name=\"submit\" value=\"Submit snippet\" /></div>";
	$html .= "</div>";
	$html .= "</form></fieldset></div>";
	
	// Upload file FORM
	$html .= "<div class=\"span6\">";
	$html .= "<fieldset id=\"customfieldset\"><legend class=\"text-center\" id=\"customlegend\">Upload an image or a CSV file</legend>";
	
	// Handle the image upload
	if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['image_post'])) { // If POST requested AND the post was from the image form
		
		// Create a flag to see if there are any problems
		$flag_problem = FALSE;
		
		// This gets the company name, the marketing campaign name and the file extension
		// Remove all spaces so that the directory or the image name are created without them
		$company_folder = strtolower(str_replace(" ", "", strip_tags($_POST['companyName'])));
		$marketing_name = strtolower(str_replace(" ", "", strip_tags($_POST['marketingCampaignName'])));
		
		if ($_FILES['uploaded_file']['error']==4) {
			$flag_problem = TRUE;
			$html .= "<p class=\"text-center\"><span class=\"label label-important\">Please choose an image or a CSV file</span></p>";
		}
		
		if (empty($company_folder)) {
			$flag_problem = TRUE;
			$html .= "<p class=\"text-center\"><span class=\"label label-important\">You must enter a valid Company name</span></p>";
		}
		
		if (empty($marketing_name)) {
			$flag_problem = TRUE;
			$html .= "<p class=\"text-center\"><span class=\"label label-important\">You must enter a valid Campaign name</span></p>";
		}
		
		if (!$flag_problem) {
		
			$img_basename = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_BASENAME);
			$img_filename = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_FILENAME);
			$img_extension = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);
			
			// Check if the company exists. If not, create the directory
			if (!is_dir("../Company_directory/".$company_folder)){
				mkdir("../Company_directory/".$company_folder);
			}
			// Move the uploaded file from tmp to directory
			// Check to see if its a csv file or an img
			if ($img_extension == "csv") {
				$updir = "../Company_directory/".$company_folder."/marketing.csv";
			} else {
				$updir = "../Company_directory/".$company_folder."/".$marketing_name.".".$img_extension;
			}	
			if(!is_file($updir)){ //Check if the img already exists
			
				if (move_uploaded_file ($_FILES['uploaded_file']['tmp_name'], $updir)) {
					// Display message if CSV or img have been or not uploaded
					if ($img_extension == "csv") {
						$html .= "<p class=\"text-center\"><span class=\"label label-success\">Your CSV file ".$img_basename." has been successfully uploaded</span></p>";
					} else {
						$html .= "<p class=\"text-center\"><span class=\"label label-success\">Your image ".$img_basename." has been successfully uploaded</span></p>";
					}
					
				} else { // Error 
					$html .= "<p class=\"text-center\"><span class=\"label label-important\">Your file could not be uploaded because: ";
					// Print a message based upon the error:
					switch ($_FILES['uploaded_file']['error']) {
						case 1:
							$html .= 'The file exceeds the upload_max_filesize setting in php.ini';
							break;
						case 2:
							$html .= 'The file exceeds the MAX_FILE_SIZE setting in the HTML form';
							break;
						case 3:
							$html .= 'The file was only partially uploaded';
							break;
						case 4:
							$html .= 'No file was uploaded';
							break;
						case 6:
							$html .= 'The temporary folder does not exist';
							break;
						default:
							$html .= 'Something unforeseen happened';
							break;
					}
					$html .= "</span></p>"; // Complete the paragraph.
				} // End of the move if 
			} else {
				$html .= "<p class=\"text-center\"><span class=\"label label-important\">>The Campaign name was already used for this Company</span></p>";
			}
		} 
	} // End of submission if

	
	$html .= "<form class=\"form-horizontal\" action=\"admin.php\" method=\"post\" enctype=\"multipart/form-data\">";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"uploaded_file\">Choose Image: </label><div class=\"controls\"><input type=\"file\" name=\"uploaded_file\" id=\"uploaded_file\"></div>";
	$html .= "</div>";
	$html .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"2500000\" />";
	$html .= "<input type=\"hidden\" name=\"image_post\" value=\"1\"/>";
	$html .= "<div class=\"control-group\">";
	$html .= "<div class=\"controls\"><p>Maximum file size is 2Mb</p></div>";
	$html .= "</div>";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"companyNameIm\">Company: </label><div class=\"controls\"><input type=\"text\" name=\"companyName\" id=\"companyNameIm\" size=\"20\" /></div>";
	$html .= "</div>";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"marketingCampaignNameIm\">Campaign Name: </label><div class=\"controls\"><input type=\"text\" name=\"marketingCampaignName\" id=\"marketingCampaignNameIm\" size=\"20\" /></div>";
	$html .= "</div>";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"icon\"><i class=\"icon-circle-arrow-up\" id=\"icon\"></i> </label><div class=\"controls\"><input class=\"btn btn-success\" type=\"submit\" name=\"submit\" value=\"Submit image\" /></div>";
	$html .= "</div>";
	$html .= "</form></fieldset></div>";
	
	// Closing columns
	$html .= "</div><hr>";
	
	
	mybody($html);
	
	myfooter();
	
	ob_end_flush();
?>
