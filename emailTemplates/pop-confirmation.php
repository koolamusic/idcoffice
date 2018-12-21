<?php 
	//This file is included in both admin and user end.
	try {
        //$stmt = $auth_user->runQuery("SELECT * FROM payment_proof WHERE match_id=:uploadID");
        //$stmt->execute(array(':uploadID'=>$uploadID));
        //$paymt = $stmt->fetch(PDO::FETCH_ASSOC);

        //Grab Payer info
        $stmt = $auth_user->runQuery("SELECT * FROM users WHERE login_id=:loginID");
        $stmt->execute(array(':loginID'=>$payerID));
        $payerInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        //Grab Payee info
        $stmt = $auth_user->runQuery("SELECT * FROM users WHERE login_id=:loginID");
        $stmt->execute(array(':loginID'=>$payeeID));
        $payeeInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e) {
        echo $e->getMessage();
    }

	$email = $payerInfo["email"];
	$emailBody = "";
	$emailBody = $emailBody ."Hello  " . $payerInfo["username"] . ",\n<br />";
	$emailBody = $emailBody .'Congratulations: Your uploaded POP has been confirmed by the receiver, you will be automatically matched to receive payment from another user shortly.\n<br />
	Thanks for choosing us!\n<br /> 
	System Administrator';

	// Sending mail
	$to = "$email"; 
	$from = "creativeweb.com.ng <info@creativeweb.com.ng>"; 
	$subject = 'Payment Confirmation'; 
	//$message = ""; 
	$headers = "From: $from\n"; 
	$headers .= "MIME-Version: 1.0\n"; 
	$headers .= "Content-type: text/html; charset=iso-8859-1\n"; 
	mail($to, $subject, $emailBody, $headers); 
?>