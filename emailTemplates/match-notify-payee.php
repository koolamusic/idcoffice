<?php 
	$email = $payeeInfo["email"];
	$emailBody = "";
	$emailBody = $emailBody ."Hello  " . $payeeInfo["username"] . ", <br />";
	$emailBody = $emailBody .'The following user have been matched to make donation to you: <br /> 
	Username: '.$payerInfo["username"].'<br />
	Phone: '.$payerInfo["phone"].'<br />
	Lacation: '.$payerInfo["country"].' <br /> 
	You are advised to follow up with he/she till payment is made and POP uploaded. <br />
	Thanks for choosing us!<br /> 
	System Administrator';

	// Sending mail
	$to = "$email"; 
	$from = "creativeweb.com.ng <info@creativeweb.com.ng>"; 
	$subject = 'Good News! You have a new match'; 
	//$message = ""; 
	$headers = "From: $from\n"; 
	$headers .= "MIME-Version: 1.0\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\n"; 
	mail($to, $subject, $emailBody, $headers); 
?>