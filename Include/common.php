<?php

	function mydoctype()
	{
		print "<!DOCTYPE html>";
	}

	function myheader($additionalHeaderContent = NULL)
	{
		print "<html lang=\"en\">";
		print "<head>";
		print "<meta charset=\"utf-8\">";
		print "<title>";
		if (defined("TITLE")) { 
			print TITLE;
		} else if ($additionalHeaderContent != NULL) { 
			print $additionalHeaderContent;
		} else {
			print "MiddleClik";
		}
		
		print "</title>";
		print "<!--this is where my css, js goes-->";
		
		if ((defined("TITLE")) && ((TITLE == "MiddleClik Admin") OR (TITLE == "Login MiddleClik") OR (TITLE == "Logout MiddleClik"))) {
			print "<link href=\"../Content/css/bootstrap.css\" rel=\"stylesheet\" type=\"text/css\">";
		}else{
			print "<link href=\"Content/css/bootstrap.css\" rel=\"stylesheet\" type=\"text/css\">";
		}
		print "</head>";
	}

	function mybody($bodyContents = '')
	{
		print "<body>";
		print "<div class=\"container-narrow\">";
		//print "<br /><h1 class=\"text-center \"><em>MiddleClik</em></h1><br /><hr>";
		if ((defined("TITLE")) && ((TITLE == "MiddleClik Admin") OR (TITLE == "Login MiddleClik") OR (TITLE == "Logout MiddleClik"))) {
			print "<img src=\"../Content/img/middleclik.jpg\">";
		}else{
			print "<img src=\"Content/img/middleclik.jpg\">";
		}
		
		print $bodyContents;
		print "</div>";
		print "</body>";
	}

	function myfooter()
	{
		print "<div class=\"container-narrow\"><footer class=\"text-right muted\">Rafael Tena (A20314078)</footer><br /></div>";
		print "</html>";
	}
	
	function is_admin() {
		if (isset($_SESSION['company']) && ($_SESSION['company'] == "middleclik")) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	function is_loggedin() {
		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

?>