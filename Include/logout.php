<?php
	session_start();
	ob_start();
	
	include ("common.php");
	
	mydoctype();
	define("TITLE", "Logout MiddleClik");
	myheader();
	
	//Title of the website
	$html = "<div class=\"row-fluid\">";
	$html .= "<div class=\"span6\">";
	$html .= "<h3 class=\"text-left\">Logout</h3>";
	$html .= "</div><div class=\"span6\">";
	$html .= "<h3 class=\"text-right\"><a class=\"btn btn-primary\" href=\"../index.php\"><i class=\"icon-home icon-white\"></i> &nbspMain website</a>";
	$html .= " <a class=\"btn btn-primary\" href=\"../Administration/admin.php\"><i class=\"icon-cog icon-white\"></i> &nbspAdmin website</a>";
	$html .= " <a class=\"btn btn-success\" href=\"login.php\"><i class=\"icon-ok icon-white\"></i> &nbspLogin</a>";
	$html .= "</div></div><br />";
	
	//Logout goodbye
	//Check if the user was really logged in before unsetting the array $_SESSION
	$html .= "<div class=\"row-fluid\">";
	$html .= "<div class=\"span4\"></div><div class=\"span4\">";
	$html .= "<fieldset id=\"customfieldset\"><legend class=\"text-center\" id=\"customlegend\">Good-bye!</legend>";
	
	if (isset($_SESSION['user_name']) OR isset($_SESSION['loggedin']) OR isset($_SESSION['company'])) {
		// I destroy the session
		unset($_SESSION);
		$_SESSION = array();
		// To be completely sure
		session_destroy();
		
		$html .= "<p class=\"text-center\"><span class=\"label label-success\">You have been successfully logged out</span></p>";
	} else {
		$html .= "<p class=\"text-center\"><span class=\"label label-important\">It seems that you were already logged out</span></p>";
	}
	
	$html .= "</fieldset></div></div><hr>";
	
	
	mybody($html);
	
	myfooter();
	
	ob_end_flush();
?>
