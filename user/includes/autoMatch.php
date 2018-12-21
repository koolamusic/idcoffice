<?php 
  //
  $currentTime = date("Y-m-d H:i:s");
  $stmt = $auth_user->runQuery("SELECT * FROM donations, match_donations
      WHERE :amount >= donations.request_amt - (SELECT SUM(m_amount) FROM match_donations
            WHERE match_donations.match_to_donor_id=donations.donor_id)
      AND match_donations.match_to_donor_id=donations.donor_id
      AND donations.payment_status='Paid'
      AND NOT donations.login_id=:loginID
      AND donations.payment_method=:payMethod
      AND donations.matured_date<:currentTime
      AND (donations.donor_status!='Canceled' OR donations.donor_status!='Frozen')
      GROUP BY match_donations.match_to_donor_id      
      ORDER BY donations.date_paid ASC LIMIT 1");
  $stmt->execute(array(
    ':loginID'=>$loginID, 
    ':amount'=>$amount, 
    ':currentTime'=>$currentTime, 
    ':payMethod'=>$paymentMedoth));
  $matches = $stmt->fetch(PDO::FETCH_ASSOC);

  //Checks if match is found, if found then run this script
  if(isset($matches["amount"]) AND $matches["amount"] != ""){
    //Set timer
    $timer = date('Y-m-d H:i:s',strtotime('+2 days',strtotime(date("Y-m-d H:i:s"))));
    //Insert into donations
    $stmt = $auth_user->runQuery("INSERT INTO match_donations (match_to_donor_id, donating_id, payer_id, payee_id, m_amount, match_status, date_matched, period_timer, paymt_method) 
                    
    VALUES(:donorID, :donatingID, :payerID, :payeeID, :amount, 'Pending', now(), :timer, :paymentMedoth)");

    $stmt->bindparam(":payerID", $loginID);
    $stmt->bindparam(":payeeID", $matches["login_id"]);
    $stmt->bindparam(":donorID", $matches["donor_id"]);
    $stmt->bindparam(":donatingID", $donorID);
    $stmt->bindparam(":amount", $amount);
    $stmt->bindparam(":timer", $timer);
    $stmt->bindparam(":paymentMedoth", $paymentMedoth);
    $stmt->execute();
    $matchID = $auth_user->lastInsertId();

    //Grab payee info
    $stmt = $auth_user->runQuery("SELECT * FROM account_details, users 
      WHERE users.login_id=:payeeID
      AND account_details.login_id=:payeeID");
    $stmt->execute(array(':payeeID'=>$matches["login_id"]));  
    $payeeInfo = $stmt->fetch(PDO::FETCH_ASSOC);  

    //Payment info
    if($paymentMedoth == "Bank"){
      //Account info
      $acctInfo = '<b>Phone: </b>'.$payeeInfo["phone"].',<br><b>Acct Name: </b>'.$payeeInfo["account_name"].',<br> <b>Acct #</b>: '.$payeeInfo["account_number"].', <br> <b>Bank: </b>'.$payeeInfo["bank_name"];
    }else{
      //Account info
      $acctInfo = '<b>Phone: </b>'.$payeeInfo["phone"].',<br><b>Bitcoin: </b>'.$payeeInfo["bitcoin_id"];
    }

    // Insert into Proof of payment Table
    $stmt = $auth_user->runQuery("INSERT INTO payment_proof(match_id, payer_id, payee_id, amount, payment_info, pay_method, pay_status, date_added)

    VALUES(:matchID, :payerID, :payeeID, :payingAmt, :acctInfo, :payMethod, 'Pending', now())");
    
    $stmt->execute(array('matchID'=>$matchID, ':payerID'=>$loginID, ':payeeID'=>$matches["login_id"], ':payingAmt'=>$amount, ':acctInfo'=>$acctInfo, ':payMethod'=>$paymentMedoth)); 

    //set Payee as matched
    $stmt = $auth_user->runQuery("UPDATE donations 
      SET donor_status='Matched' WHERE donor_id=:recDonorID");
    $stmt->execute(array(':recDonorID'=>$matches['donor_id']));


    $auth_user->redirect(BASE_URL.'user/dashboard?match-found');
    exit();
  }
?>