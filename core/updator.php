<?php
	//The file is included in all files
	//and it art as a cron job which automatically monitor user activaties
	// and take approciate actions, such as auto block user, auto mark as matured, etc

	//set current time to compare with user activities
	$currentTime = date("Y-m-d H:i:s");
	
	// ------------Cancel any active PH/GH of a blocked user-------------------//
	try	{
		 $stmt = $auth_user->runQuery("UPDATE donations SET donor_status='Canceled' 
				WHERE login_id IN (SELECT login_id FROM users WHERE status='Blocked')");
        $stmt->execute();        
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}

// ---------This section checks for matured donation for auto match-----------//
	try	{
		//Set as matured
		$stmt = $auth_user->runQuery("UPDATE donations SET donor_status='Matured' 
				WHERE matured_date<:currentTime 
				AND donor_status='In Progress'
				AND payment_status='Paid'");
		$stmt->execute(array(':currentTime'=>$currentTime));

		//Checks for pending donation
		//Who is ready to make donation to another user
		$stmt = $auth_user->runQuery("SELECT * FROM donations
			WHERE donor_status='In Progress' 
			AND NOT payment_status='Paid'
			AND donor_id NOT IN (SELECT donating_id FROM match_donations)");
		$stmt->execute();	
		$donatingID = $stmt->fetch(PDO::FETCH_ASSOC);

		//Check if record is found, then run this script if found.
		if(isset($donatingID['donor_id']) AND $donatingID['donor_id'] != ''){
			//Checks for Receiving donation
			//Who has already donated and ready to receive donation from another user
			$stmt = $auth_user->runQuery("SELECT * FROM donations, match_donations
			    WHERE :amount >= donations.request_amt - (SELECT SUM(m_amount) FROM match_donations
			            WHERE match_donations.match_to_donor_id=donations.donor_id)
			    AND match_donations.match_to_donor_id=donations.donor_id
			    AND donations.payment_status='Paid'
			    AND NOT donations.login_id=:userID
			    AND donations.payment_method=:payMethod
			    AND donations.matured_date<:currentTime
			    AND (donations.donor_status!='Canceled' OR donations.donor_status!='Frozen')
			    GROUP BY match_donations.match_to_donor_id      
			    ORDER BY donations.date_paid ASC LIMIT 1");
			$stmt->execute(array(':amount'=>$donatingID['amount'], ':payMethod'=>$donatingID['payment_method'], ':userID'=>$donatingID['login_id'], ':currentTime'=>$currentTime));	
			$receivingID = $stmt->fetch(PDO::FETCH_ASSOC);

			//Checks if sponsor is found
			if(isset($receivingID['donor_id']) AND $receivingID['donor_id'] != ''){
				// Grab Payee Info
				$stmt = $auth_user->runQuery("SELECT * FROM donations, account_details, users 
					WHERE donations.donor_id=:receivingID 
					AND donations.login_id=users.login_id 
					AND donations.login_id=account_details.login_id");
				$stmt->execute(array(':receivingID'=>$receivingID["donor_id"]));	
				$payeeInfo = $stmt->fetch(PDO::FETCH_ASSOC);					
				
				// Grab Payer Info
				$stmt = $auth_user->runQuery("SELECT * FROM donations, users 
					WHERE donations.donor_id=:donatingID
					AND users.login_id=donations.login_id");
				$stmt->execute(array(':donatingID'=>$donatingID["donor_id"]));
				$payerInfo = $stmt->fetch(PDO::FETCH_ASSOC);

				//Run this if payer is found.
				if(isset($payerInfo["login_id"]) AND $payerInfo["login_id"] != ''){
					//payment info
					if($payeeInfo['payment_method'] == "Bank"){
						//Account info
						$acctInfo = '<b>Phone: </b>'.$payeeInfo["phone"].',<br><b>Acct Name: </b>'.$payeeInfo["account_name"].',<br> <b>Acct #</b>: '.$payeeInfo["account_number"].', <br> <b>Bank: </b>'.$payeeInfo["bank_name"];
					}else{
						//Account info
						$acctInfo = '<b>Phone: </b>'.$payeeInfo["phone"].',<br><b>Bitcoin: </b>'.$payeeInfo["bitcoin_id"];
					}

					//Set Penalty date
					$penaltyDate = date('Y-m-d H:i:s', strtotime('+2 days', strtotime(date("Y-m-d H:i:s"))));

					// Insert into match_donations Table
					$stmt = $auth_user->runQuery("INSERT INTO match_donations(match_to_donor_id, donating_id, payer_id, payee_id, match_status, date_matched, period_timer, m_amount, paymt_method) 

					VALUES(:receivingID, :donatingID, :payerID, :payeeID, 'Pending', now(), :penaltyDate, :payingAmt, :payMethod)");
					
					$stmt->execute(array(':receivingID'=>$receivingID["donor_id"], ':donatingID'=>$donatingID["donor_id"], ':payerID'=>$payerInfo["login_id"], ':payeeID'=>$payeeInfo["login_id"], ':payingAmt'=>$payerInfo["amount"], ':penaltyDate'=>$penaltyDate, ':payMethod'=>$payeeInfo['payment_method']));
					$matchID = $auth_user->lastInsertId();

					// Insert into Proof of payment Table
					$stmt = $auth_user->runQuery("INSERT INTO payment_proof(match_id, payer_id, payee_id, amount, payment_info, pay_status, date_added, pay_method)

					VALUES(:matchID, :payerID, :payeeID, :payingAmt, :acctInfo, 'Pending', now(), :payMethod)");
					
					$stmt->execute(array('matchID'=>$matchID, ':payerID'=>$payerInfo["login_id"], ':payeeID'=>$payeeInfo["login_id"], ':payingAmt'=>$payerInfo["amount"], ':acctInfo'=>$acctInfo, ':payMethod'=>$payeeInfo['payment_method']));	

					//set Payee as matched
					$stmt = $auth_user->runQuery("UPDATE donations SET donor_status='Matched' 
						WHERE donor_id=:receivingID");
					$stmt->execute(array(':receivingID'=>$receivingID["donor_id"]));

					//Email notification to Payee
					include(ROOT_PATH . "emailTemplates/match-notify-payee.php");
					//Email notification to Payer
					include(ROOT_PATH . "emailTemplates/match-notify-payer.php");
				}				
			}
		}
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}



// ------------------This section checks on late payment-------------------//
	try	{
		$stmt = $auth_user->runQuery("SELECT * FROM match_donations, payment_proof
			WHERE match_donations.period_timer<:currentTime
			AND NOT match_donations.match_to_donor_id='0'
			AND payment_proof.match_id=match_donations.match_id
			AND payment_proof.proof='' LIMIT 1");
		$stmt->execute(array(':currentTime'=>$currentTime));
		$lateTimer = $stmt->fetch(PDO::FETCH_ASSOC);

		//Check if record is found, then run script if found
		if(isset($lateTimer['proof']) AND $lateTimer['proof'] == ''){
            $stmt = $auth_user->runQuery("DELETE FROM match_donations WHERE match_id=:matchID");
            $stmt->execute(array(':matchID'=>$lateTimer['match_id']));
            //
            $stmt = $auth_user->runQuery("DELETE FROM payment_proof WHERE match_id=:matchID");
            $stmt->execute(array(':matchID'=>$lateTimer['match_id']));
            //
            $stmt = $auth_user->runQuery("DELETE FROM donations WHERE donor_id=:donatingID");
            $stmt->execute(array(':donatingID'=>$lateTimer['donating_id']));
            //
            $stmt = $auth_user->runQuery("UPDATE donations SET donor_status='Matured'
                WHERE donor_id=:recievingDID");
            $stmt->execute(array(':recievingDID'=>$lateTimer['match_to_donor_id']));

            //Empty payer credibility score
             $stmt = $auth_user->runQuery("UPDATE users 
                SET credibility_score='0'
                WHERE login_id=:payerID");
            $stmt->execute(array(':payerID'=>$lateTimer['payer_id']));
		}
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}



// ------------------This section handles recommitments-------------------//
	try	{
		$stmt = $auth_user->runQuery("SELECT *
			FROM match_donations WHERE date_paid < (CURDATE() - INTERVAL 5 DAY)
			AND match_status='Paid'
			AND NOT match_to_donor_id='0'
			AND payee_id NOT IN (SELECT login_id FROM users WHERE status='Blocked')
		    ORDER BY date_paid ASC LIMIT 1");
		$stmt->execute();
		$checkUserrr = $stmt->fetch(PDO::FETCH_ASSOC);

		//Check if record is found, if yes then check payee ID
		//Then block user and email
		if(isset($checkUserrr['payee_id']) AND $checkUserrr['payee_id'] != ''){            
            //block user who have not PH after 5 days of last GH
             $stmt = $auth_user->runQuery("UPDATE users 
                SET status='Blocked' WHERE login_id=:payeeID");
            $stmt->execute(array(':payeeID'=>$checkUserrr['payee_id']));

            //Send blocked notification
			include(ROOT_PATH . "emailTemplates/blocked-notRecommitUser.php");
		}
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}

// ---------This section Block users who have not PH after 5days of signup ----------//

	try	{
		$stmt = $auth_user->runQuery("SELECT *
			FROM users WHERE user_timer < (CURDATE() - INTERVAL 5 DAY)
			AND status='Active'
			AND login_id NOT IN (SELECT login_id FROM donations)
		    ORDER BY user_timer ASC LIMIT 1");
		$stmt->execute();
		$checkUserrr = $stmt->fetch(PDO::FETCH_ASSOC);

		//Check if record is found, if yes then check payee ID
		//Then block user and email
		if(isset($checkUserrr['login_id']) AND $checkUserrr['login_id'] != ''){            
            //block user who have not PH after 3 days of last GH
             $stmt = $auth_user->runQuery("UPDATE users 
                SET status='Blocked' WHERE login_id=:loginID");
            $stmt->execute(array(':loginID'=>$checkUserrr['login_id']));

            //Send blocked notification
			include(ROOT_PATH . "emailTemplates/blocked-notRecommitUser.php");
		}
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
?>