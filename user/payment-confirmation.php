<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
    require_once(ROOT_PATH . "user/includes/write-testimony.php");
	
	$payments = $auth_user->confirmPayments($loginID);
	
	//Approve POP and update related Tables
    if(isset($_POST['approve'])){
        $matchID = strip_tags($_POST['matchID']);
        $payerID = strip_tags($_POST['payerID']);
        $payeeID = strip_tags($_POST['payeeID']);
        $donatingID = strip_tags($_POST['donatingID']);
        $recievingDID = strip_tags($_POST['recievingDID']);
        $mAmount = strip_tags($_POST['mAmount']);
        $requestAmount = strip_tags($_POST['requestAmout']);
        $curtRecdAmount = strip_tags($_POST['curtRecdAmount']);
        $matchStatus = strip_tags($_POST['matchStatus']);
        //Add to current received amount
        $totalRecAmt = $mAmount + $curtRecdAmount;

        //Check if the total received amount is equal to the expected amount
        if($totalRecAmt == $requestAmount){
            $donorStatus = 'Completed';
        }else{
            $donorStatus = 'Partial';
        }
        
        try {
            //
            if($matchStatus != "Paid"){
                $stmt = $auth_user->runQuery("UPDATE payment_proof, match_donations, donations 
                    SET payment_proof.pay_status='Paid', 
                        payment_proof.confirmed_date=now(), 
                        match_donations.match_status='Paid',
                        donations.date_paid=now(),
                        donations.payment_status='Paid',
                        match_donations.date_paid=now()
                    WHERE payment_proof.match_id=:matchID
                    AND match_donations.match_id=:matchID
                    AND donations.donor_id=:donatingID");
                $stmt->execute(array(':matchID'=>$matchID, ':donatingID'=>$donatingID));

                $stmt = $auth_user->runQuery("UPDATE donations 
                    SET received_amt=:totalRecAmt,
                        donor_status=:donorStatus
                    WHERE donor_id=:recievingDID");
                $stmt->execute(array(':recievingDID'=>$recievingDID, ':totalRecAmt'=>$totalRecAmt, ':donorStatus'=>$donorStatus));

        //echo $donatingID.'<br>'.$recievingDID;exit();
                //Update referral bonus table
                $stmt = $auth_user->runQuery("UPDATE referral_bonus SET status='Available'
                    WHERE login_id=:payerID AND status='Pending'");
                $stmt->execute(array(':payerID'=>$payerID));

                //Add to payee credibility score
                 $stmt = $auth_user->runQuery("UPDATE users 
                    SET credibility_score=credibility_score + 10
                    WHERE login_id=:payeeID");
                $stmt->execute(array(':payeeID'=>$payeeID));

                //Add to payer credibility score
                 $stmt = $auth_user->runQuery("UPDATE users 
                    SET credibility_score=credibility_score + 10
                    WHERE login_id=:payerID");
                $stmt->execute(array(':payerID'=>$payerID));

                //Send POP confirmation email
                include(ROOT_PATH . "emailTemplates/pop-confirmation.php");

                $auth_user->redirect(BASE_URL.'user/payment-confirmation?confirmed');
                exit();
            }

        }catch(PDOException $e) {
            echo $e->getMessage();
        }   
    }

    //Flag POP as fake
	if(isset($_POST['flag'])){
        $matchID = strip_tags($_POST['matchID']);
        $donatingID = strip_tags($_POST['donatingID']);

        //
		try	{
			$stmt = $auth_user->runQuery("UPDATE match_donations, payment_proof
                SET match_donations.match_status='Flagged', 
                    payment_proof.pay_status='Flagged'
                WHERE match_donations.match_id=:matchID
                AND payment_proof.match_id=:matchID");
			$stmt->execute(array(':matchID'=>$matchID));

            $stmt = $auth_user->runQuery("UPDATE donations SET donor_status='Flagged'
                    WHERE donor_id=:donatingID");
            $stmt->execute(array(':donatingID'=>$donatingID));

		}catch(PDOException $e) {
			echo $e->getMessage();
		}

        //Send flag POP email
        //include(ROOT_PATH . "emailTemplates/flagged.php");

		$auth_user->redirect(BASE_URL.'user/payment-confirmation?flagged');
		exit();
	}

    //Time Extension
    if(isset($_GET['ext'])){
        $matchID = $_GET['ext'];
        try {
            //
            $stmt = $auth_user->runQuery("SELECT * FROM match_donations 
                WHERE match_id=:matchID");
            $stmt->execute(array(':matchID'=>$matchID));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //
            $datetime = new DateTime($row['period_timer']);
            $datetime->modify('+1 day');
            $extendedTime =  $datetime->format('Y-m-d H:i:s');

            //Update extended time
            $stmt = $auth_user->runQuery("UPDATE match_donations SET t_extension=:extendedTime 
                WHERE match_id=:matchID");
            $stmt->execute(array(':matchID'=>$matchID, ':extendedTime'=>$extendedTime));

            //Deduct 25% from credibility score
            $stmt = $auth_user->runQuery("UPDATE users 
                SET credibility_score=credibility_score - 25 
                WHERE login_id=:loginID");
            $stmt->execute(array(':loginID'=>$row['payer_id']));

            $auth_user->redirect(BASE_URL.'user/payment-confirmation?extended');
            exit();
        }catch(PDOException $e) {
            echo $e->getMessage();
        }  
    }

?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Receive Help / Payment Confirmation</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                
                <?php if(isset($_GET['confirmed'])){?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Payment successfully confirmed!
						 </div>
						 <?php
					}elseif(isset($_GET['flagged'])){?>
						<div class="alert alert-danger">
							  <i class="fa fa-check-square"></i> &nbsp; The payment report has been sent to the admin!
						 </div>
               <?php }?>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Payment Confirmation</h1>
                        <h4>Please confirm that you have received the amounts below into your personal account. Please note that delays in confirmation will affect your Credibility Score</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Payment Confirmation
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" 
        id="dataTables-example">
            <thead>
                <tr>
                    <th width="213">Match/Expire Date</th>
                    <th width="61">Amount</th>
                    <th width="96">Paid by</th>
                    <th width="101">Type</th>
                    <th width="134">Action</th>
                    <th width="80">Staus</th>
                </tr>
            </thead>
            <tbody style="font-size: 12px; font-weight: 800;">
            	<?php 
                    if (!empty($payments)) {
                        foreach($payments as $payment) {
                        $payInfo = $auth_user->payInfo($payment["match_id"]);?>
				<tr>
                    <td>
                    <?php echo strftime("%d/%m/%Y %I:%M", strtotime($payment["date_matched"])) ;?> <b>/</b>
                    <?php if($payment['t_extension'] == ""){?>
                        <?php echo strftime("%d/%m/%Y %I:%M", strtotime($payment["period_timer"])) ;?>
                        <br>
                        <a href="<?php echo BASE_URL.'user/payment-confirmation?ext='.$payment["match_id"].'&true';?>">24hrs Extension</a>
                        <?php }else{?>
                         <?php echo strftime("%d/%m/%Y %I:%M", strtotime($payment["t_extension"])) ;?>
                    <?php }?>
                        
                    </td>
                    
                    <?php if($payment["pay_method"] == "Bank"){?>
                        <td>₦<?php echo number_format($payment["m_amount"]);?>.00</td>
                    <?php }else{?>
                        <td>$<?php echo number_format($payment["m_amount"]);?>.00</td>
                    <?php }?>
                    <td><?php echo  $payInfo["username"].'<br>Tel:'.$payInfo["phone"];?></td>
                    <td><?php echo $payment["paymt_method"];?></td>
                    <td align="center"><?php if($payInfo["proof"] == ""){?>
                    		Awaiting Payment
                    	<?php }else{?>
						<a target="_blank" href="<?php echo BASE_URL.str_replace('../', '', $payInfo['proof']) ?>">View POP</a> <br> 
						<?php if($payInfo["pay_status"] != "Paid"){?>
							<form method="post" action="">
                                <input type="hidden" name="matchID" value="<?php echo $payment["match_id"];?>">

                                <input type="hidden" name="payerID" value="<?php echo  $payment["payer_id"];?>">

                                <input type="hidden" name="payeeID" value="<?php echo  $payment["payee_id"];?>">

                                <input type="hidden" name="donatingID" value="<?php echo $payment["donating_id"];?>">

                                <input type="hidden" name="recievingDID" value="<?php echo $payment["match_to_donor_id"];?>">

                                <input type="hidden" name="mAmount" value="<?php echo $payment["m_amount"];?>">

                                <input type="hidden" name="curtRecdAmount" value="<?php echo $payment["received_amt"];?>">

                                <input type="hidden" name="requestAmout" value="<?php echo $payment["request_amt"];?>">

                                <input type="hidden" name="matchStatus" value="<?php echo $payment["match_status"];?>">

                                <button type="submit" name="approve" style="font-size: 12px; padding: 0px 20px; margin: 0px; color: green;">Confirm Payment</button>
                        	</form>
							 <form method="post" action="">
                                <input type="hidden" name="matchID" value="<?php echo $payment["match_id"];?>">

                                <input type="hidden" name="donatingID" value="<?php echo $payment["donating_id"];?>">

                                <button type="submit" name="flag" style="font-size: 8px; padding: 0px 10px; color: red;">Flag as Fake</button>
                            </form></td>
						<?php } }?>
                    <td>
                    <?php if($payment["match_status"] == "Paid"){?>
                    	<span style="color: #00B966;">Confirmed</span>
                    	<?php if($tsmy["tsmony"] < 1){?>
                    		<br><a style="color: #BD2325" href="<?php echo BASE_URL.'user/testimonies?payID='.$payInfo["id"]?>">Write Testimony</a>
                    	<?php }?>
                    <?php }elseif($payment["match_status"] == "Flagged"){?>
                    	<span style="color: #f00;">Flagged</span>
                    <?php }else{?>
                    	<span style="color:#682F30;"><?php echo $payment["match_status"];?></span>
                    <?php }?>
                    </td>
                </tr>
                <?php  } }?>
            </tbody>
        </table>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>                    
	</div>
	<!-- /. ROW  -->
<?php include(ROOT_PATH."user/includes/footer.php") ?>