<?php
	session_start();
	ob_start();
	
	include ("common.php");
	include ("dbconnect.php");
	
	mydoctype();
	define("TITLE", "Login MiddleClik");
	myheader();
	
	//Title of the website
	$html = "<div class=\"row-fluid\">";
	$html .= "<div class=\"span6\">";
	$html .= "<h3 class=\"text-left\">Login</h3>";
	$html .= "</div><div class=\"span6\">";
	$html .= "<h3 class=\"text-right\"><a class=\"btn btn-primary\" href=\"../index.php\"><i class=\"icon-home icon-white\"></i> &nbspMain website</a>";
	$html .= " <a class=\"btn btn-primary\" href=\"../Administration/admin.php\"><i class=\"icon-cog icon-white\"></i> &nbspAdmin website</a>";
	// Check if display Login or Logout button
	if (is_loggedin()) {
		$html .= " <a class=\"btn btn-danger\" href=\"logout.php\"><i class=\"icon-off icon-white\"></i> &nbspLogout</a>";
	} else {
		$html .= " <a class=\"btn btn-success disabled\"><i class=\"icon-ok icon-white\"></i> &nbspLogin</a>";
	}
	$html .= "</h3>";
	$html .= "</div></div><br />";
	
	// Login form
	$html .= "<div class=\"row-fluid\">";
	$html .= "<div class=\"span4\"></div><div class=\"span4\">";
	$html .= "<fieldset id=\"customfieldset\"><legend class=\"text-center\" id=\"customlegend\">Login</legend>";
	
	
	// Handle the login
	if (($_SERVER['REQUEST_METHOD'] == 'POST')) { // If POST requested
		
		// Create a flag to see if there are any problems
		$flag_problem = FALSE;
		
		// This gets the username and pass 
		// Remove all white spaces 
		// Remove all the possible html tags
		$user_name = strip_tags($_POST['username']);
		$password_name = strip_tags($_POST['password']);
		
		if (empty($user_name)) {
			$flag_problem = TRUE;
			$html .= "<p class=\"text-center\"><span class=\"label label-important\">You must enter a valid username</span></p>";
		}
		
		if (empty($password_name)) {
			$flag_problem = TRUE;
			$html .= "<p class=\"text-center\"><span class=\"label label-important\">You must enter a valid password</span></p>";
		}
		
		if (!$flag_problem) {	
			
			//connect to the database
			$dbc = dbConnect();
			if ($dbc) {
				$tbl_name = "users";
				
				// Check username and password
				$query_pass = "SELECT password FROM ".mysql_real_escape_string($tbl_name)." WHERE username=\"".mysql_real_escape_string($user_name)."\"";
				$result = mysql_query($query_pass, $dbc);
				
				if ($result) {
					
					$password = mysql_fetch_array($result);
					// If a match of username was found...
					if (mysql_num_rows($result) >= 1) {
						// Check password
						if ($password_name == $password['password']) {	
							// SESSION
							$_SESSION['user_name'] = $user_name;
							$_SESSION['loggedin'] = TRUE;
							$query_company = "SELECT company FROM ".mysql_real_escape_string($tbl_name)." WHERE username=\"".mysql_real_escape_string($user_name)."\"";
							$result_comp = mysql_query($query_company, $dbc);
							$company = mysql_fetch_array($result_comp);
							$_SESSION['company'] = $company['company'];
							
							$html .= "<p class=\"text-center\"><span class=\"label label-success\">You have been successfully logged in</span></p>";
							
							$html .= "</fieldset></div></div><hr>";
							mybody($html);
							myfooter();
							ob_end_flush();
							exit();
						
						} else {
						$html .= "<p class=\"text-center\"><span class=\"label label-important\">Wrong password</span></p>";
						}
						
					} else {
						$html .= "<p class=\"text-center\"><span class=\"label label-important\">Wrong username</span></p>";
					}
				
				
				/* // This sentence makes 2 things at once
				// If result returns something this means that (1) username and password were correct and (2) it returns the name of the company
				$query = "SELECT company FROM ".mysql_real_escape_string($tbl_name)." WHERE username=\"".mysql_real_escape_string($user_name)."\" AND password=\"".mysql_real_escape_string($password_name)."\"";
				$result = mysql_query($query, $dbc);
				$company = mysql_fetch_array($result);
				
				if ($result) {
					
					// If a match was found...
					if (mysql_num_rows($result) >= 1) {
						// SESSION
						$_SESSION['user_name'] = $user_name;
						$_SESSION['loggedin'] = TRUE;
						$_SESSION['company'] = $company['company'];
						
						$html .= "<p class=\"text-center\"><span class=\"label label-success\">You have been successfully logged in</span></p>";
						
						$html .= "</fieldset></div></div><hr>";
						mybody($html);
						myfooter();
						ob_end_flush();
						exit();
						
					} else {
						$html .= "<p class=\"text-center\"><span class=\"label label-important\">Wrong username or password</span></p>";
					} */
					
				} else 	{
					$html .= "<p class=\"text-center\"><span class=\"label label-important\">Error: ".mysql_error()."</span></p>";
				}
				
			} else {
				$html .= "<p class=\"text-center\"><span class=\"label label-important\">Unable to connect to Database</span></p>";
			}
		} // End of flag
		
	} // End of submission if
	
	//Display the login form
	$html .= "<form class=\"form-horizontal\" action=\"login.php\" method=\"post\">";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"usernameId\">Username: </label><div class=\"controls\"><input type=\"text\" name=\"username\" id=\"usernameId\" size=\"10\" /></div>";
	$html .= "</div>";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"passwordId\">Password: </label><div class=\"controls\"><input type=\"password\" name=\"password\" id=\"passwordId\" size=\"15\" /></div>";
	$html .= "</div>";
	$html .= "<div class=\"control-group\">";
	$html .= "<label class=\"control-label\" for=\"icon\"><i class=\"icon-ok\" id=\"icon\"></i> </label><div class=\"controls\"><input class=\"btn btn-success\" type=\"submit\" name=\"submit\" value=\"Login\" /></div>";
	$html .= "</div>";
	$html .= "</form></fieldset></div></div><hr>";
	
	
	
	
	mybody($html);
	
	myfooter();
	
	ob_end_flush();
?>
