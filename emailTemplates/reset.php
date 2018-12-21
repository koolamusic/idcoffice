<?php
	$emailBody = "";
	$emailBody = $emailBody ."Hello  " . $row['username'] . ",\n<br />";
	$emailBody = $emailBody .'You requested password reset, please ignore if this is not you otherwise click on the link below to reset your password.<br><br><a href="http://creativeweb.com.ng/naijahelpers.com/password-reset?true='.$row['login_id'].'&tnd=reset9879">Reset Password</a><br><br> OR copy and past the following code on your browser:<br>http://creativeweb.com.ng/naijahelpers.com/password-reset?true='.$row['login_id'].'&tnd=tnd=reset9879 <br><br><br><br>Thanks. <br />';

	// Sending mail
	$to = "$email"; 
	$from = "IDC Office<noreply@idcoffice.com>"; 
	$subject = 'IDCOFFICE: Password Reset'; 
	//$message = ""; 
	$headers = "From: $from\n"; 
	$headers .= "MIME-Version: 1.0\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\n"; 
	mail($to, $subject, $emailBody, $headers); 
?>