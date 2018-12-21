<?php
	try	{
		 $stmt = $auth_user->runQuery("SELECT COUNT(*) AS cnt FROM match_donations
		    WHERE payee_id=:loginID AND match_status='Paid'
			AND match_id NOT IN (SELECT pay_id FROM testimonies)");
        $stmt->execute(array(':loginID'=>$loginID));
         $tesResult = $stmt->fetch(PDO::FETCH_ASSOC);       
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
	
	//Force user to write testimony
	//Redirect to Writestimony page
	if($tesResult['cnt'] > 0){
		$auth_user->redirect(BASE_URL.'user/payment-testimonies');
		exit();
	}
?>