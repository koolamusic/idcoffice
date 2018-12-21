<?php 
	if(isset($checkUserrr['payee_id'])){
		//This file is included in includes/updator
		try {
	        //Grab Payee info
	        $stmt = $auth_user->runQuery("SELECT * FROM users WHERE login_id=:loginID");
	        $stmt->execute(array(':loginID'=>$checkUserrr['payee_id']));
	        $payeeInfo = $stmt->fetch(PDO::FETCH_ASSOC);
	    }catch(PDOException $e) {
	        echo $e->getMessage();
	    }

		$email = $payeeInfo["email"];
		$emailBody = "";
		$emailBody = $emailBody ."Hello  " . $payeeInfo["username"] . ", <br />";
		$emailBody = $emailBody .'Your account has be blocked for not Providing help on the system after 3 days of your last Receive Help or Joned the system. <br /> 
		Please contact the administrator if you think this is an error. <br />
		Thanks for choosing us!<br /> 
		System Administrator';

		// Sending mail
		$to = "$email"; 
		$from = "creativeweb.com.ng <info@creativeweb.com.ng>"; 
		$subject = 'Creativeweb! Your is blocked!'; 
		//$message = ""; 
		$headers = "From: $from\n"; 
		$headers .= "MIME-Version: 1.0\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1\n"; 
		mail($to, $subject, $emailBody, $headers);
	}
?>