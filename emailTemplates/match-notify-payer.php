<?php 
	$email = $payerInfo["email"];
	$emailBody = "";
	$emailBody = $emailBody ."Hello  " . $payerInfo["username"] . ",<br />";
	$emailBody = $emailBody .'You have been matched to make donation the following user: <br /> 
	Username: '.$payeeInfo["username"].'<br />
	Phone: '.$payeeInfo["phone"].'<br />
	Lacation: '.$payeeInfo["country"].' <br /> 
	 <br />------------Payment Information ----------------<br />
	Lacation: '.$acctInfo.' <br />
	You are advised to follow up with he/she till your uploaded POP is confirmed by payee. <br />
	Thanks for choosing us! <br /> 
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