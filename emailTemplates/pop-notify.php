<?php 
	//This file is included in both admin and user end.
	try {
        $stmt = $auth_user->runQuery("SELECT * FROM payment_proof WHERE match_id=:uploadID");
        $stmt->execute(array(':uploadID'=>$uploadID));
        $paymt = $stmt->fetch(PDO::FETCH_ASSOC);
        //Grab Payer info
        $stmt = $auth_user->runQuery("SELECT * FROM users WHERE login_id=:loginID");
        $stmt->execute(array(':loginID'=>$paymt["payer_id"]));
        $payerInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        //Grab Payee info
        $stmt = $auth_user->runQuery("SELECT * FROM users WHERE login_id=:loginID");
        $stmt->execute(array(':loginID'=>$paymt["payee_id"]));
        $payeeInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e) {
        echo $e->getMessage();
    }

	$email = $payeeInfo["email"];
	$emailBody = "";
	$emailBody = $emailBody ."Hello  " . $payeeInfo["username"] . ",\n<br />";
	$emailBody = $emailBody .'POP has been uploaded by the following user: \n<br /> 
	Username: '.$payerInfo["username"].'\n<br />
	Phone: '.$payerInfo["phone"].'\n<br />
	Lacation: '.$payerInfo["country"].' \n<br />
	Login in your account to view POP, you have option to either confirm or flag is fake.\n<br />
	Thanks for choosing us!\n<br /> 
	System Administrator';

	// Sending mail
	$to = "$email"; 
	$from = "creativeweb.com.ng <info@creativeweb.com.ng>"; 
	$subject = 'Good News! POP Uploaded for confirmation'; 
	//$message = ""; 
	$headers = "From: $from\n"; 
	$headers .= "MIME-Version: 1.0\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\n"; 
	mail($to, $subject, $emailBody, $headers); 
?>