<?php 
	$emailBody = "";
	$emailBody = $emailBody ."Hello  " . $username . ", <br />";
	$emailBody = $emailBody .'Thanks so much for joining us. You are on your way to super-donation exchange globally! <br> We are a worldwide channel to connect participants, share donations and receive incentives in return.<br>  The group of ordinary people, selflessly helping each other. We warmly welcome you and we hope you find joy and happiness through donation exchange.<br>  We are here to help one another to accomplish dreams. Thanks for signing up into the system.  <br />';

	// Sending mail
	$to = "$email"; 
	$from = "creativeweb.com.ng <info@creativeweb.com.ng>"; 
	$subject = 'Welcome to creativeweb.com.ng'; 
	//$message = ""; 
	$headers = "From: $from\n"; 
	$headers .= "MIME-Version: 1.0\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\n"; 
	mail($to, $subject, $emailBody, $headers); 
?>