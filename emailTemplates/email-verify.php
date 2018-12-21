<?php
	$emailBody = "";
	$emailBody = $emailBody ."Hello  " . $username . ",\n<br />";
	$emailBody = $emailBody .'Thanks so much for joining us. You are on your way to super-donation exchange globally! <br><br>To get started <a href="http://creativeweb.com.ng/naijahelpers.com/verification?true='.$loginID.'&tnd=3kj931sDaj">Verify your account</a><br><br> OR copy and past the following code on your browser:<br>http://creativeweb.com.ng/naijahelpers.com/verification?true='.$loginID.'&tnd=3kj931sDaj <br><br>and start your journey right way.<br> We are a worldwide channel to connect participants, share donations and receive incentives in return.<br> The group of ordinary people, selflessly helping each other. We warmly welcome you and we hope you find joy and happiness through donation exchange.<br> We are here to help one another to accomplish dreams.<br><br>Thanks for signing up into the system. <br />';

	// Sending mail
	$to = "$email"; 
	$from = "CreativeWeb Nigeria <info@creativeweb.com.ng>"; 
	$subject = 'CreativeWeb: Email Verification'; 
	//$message = ""; 
	$headers = "From: $from\n"; 
	$headers .= "MIME-Version: 1.0\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\n"; 
	mail($to, $subject, $emailBody, $headers); 
?>