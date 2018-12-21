<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
	require_once(ROOT_PATH . "user/includes/agree.php");
	require_once(ROOT_PATH . "user/includes/write-testimony.php");
	//
	$perctg = $auth_user->percentage();
	$countDnts = $auth_user->countMYdonations($loginID);
	$countMYpendingDos = $auth_user->countMYpendingDos($loginID);

//echo $countDnts;exit();
	//Checking
	try	{
		$stmt = $auth_user->runQuery("SELECT * FROM donations 
			WHERE login_id=:loginID AND NOT donor_status='Canceled' 
			ORDER BY donor_id DESC LIMIT 1");
		$stmt->execute(array(":loginID"=>$loginID));
		$lastDonation = $stmt->fetch(PDO::FETCH_ASSOC);

		//Checks Acclount is set.
		$stmt = $auth_user->runQuery("SELECT * FROM account_details 
			WHERE login_id=:loginID LIMIT 1");
		$stmt->execute(array(":loginID"=>$loginID));
		$userAcct = $stmt->fetch(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}

	if(!isset($userAcct["account_name"]) OR !isset($userAcct["bitcoin_id"])){
		$auth_user->redirect(BASE_URL.'user/account-details');
		exit();
	}

	if(isset($_POST['paymentMedoth'])){
		$paymentMedoth = strip_tags($_POST['paymentMedoth']);
		//$promoCode = strip_tags($_POST['promoCode']);
		$amount = strip_tags($_POST['amount']);
		
		//Php Validation
		if($userInfo["credibility_score"] < 1){
			$error[] = "Opps! Your Credibility Score is Less Than 1%, you can not provide help, please contact the admin!";	
		}elseif($countMYpendingDos >= 2){
			$error[] = "Opps! You can only have up to 2 pending donations at a time!";
		}elseif($paymentMedoth == "")	{
			$error[] = "Please select payment method!";	
		}elseif($amount < 1000 AND $paymentMedoth != 'BitCoin')	{
			$error[] = "The Minimum Donation Amount is ₦1,000";	
		}elseif($paymentMedoth == 'BitCoin' AND $amount < 10){
			$error[] = "The Minimum Bitcoin Donation Amount is $10";			
		}elseif($paymentMedoth == 'BitCoin' AND $amount > 1000){
			$error[] = "The Maximum Bitcoin Donation Amount is $1,000";
		}elseif($amount > 10000000)	{
			$error[] = "The Maximum Donation Amount is ₦10,000,000";	
		}elseif($lastDonation["payment_method"] == "Bank" AND $paymentMedoth == "Bank" AND $amount < $lastDonation["amount"]){
			$error[] = "Your last donated amount was ₦".number_format($lastDonation["amount"]).", You can not donate lesser amount!";	
		}elseif($lastDonation["payment_method"] == "BitCoin" AND $paymentMedoth == "BitCoin" AND $amount < $lastDonation["amount"]){
			$error[] = "Your last donated amount was $".number_format($lastDonation["amount"]).", You can not donate lesser amount!";	
		}elseif($amount == "")	{
			$error[] = "Please enter amount!";
		}else{
			try	{
				
				//Set different percentage on naira and bitcoin
				if($paymentMedoth == "Bank"){
					$yieldAmt = $perctg['local_donation'] * $amount / 100;
				}else{
					$yieldAmt = $perctg['btc_donation'] * $amount / 100;
				}
				//echo $perctg['bonus_local_t'];exit();
				//Condition handling first time donor  to check for bonus
				if(isset($lastDonation["amount"]) AND $lastDonation["amount"] != 0){
					$bonus = 0;
				}else{
					//Use bonus set by admin
					if($paymentMedoth == "Bank"){
			            if($amount >= 10000 AND $amount < 50000){
			              $bonus = $perctg['bonus_local_o'];
			            }elseif($amount >= 50000 AND $amount < 100000){
			              $bonus = $perctg['bonus_local_t'];
			            }elseif($amount >= 100000){
			              $bonus = $perctg['bonus_local_th'];
			            }else{
			              $bonus = 0;
			            }
			        }else{
			            if($amount >= 50 AND $amount < 500){
			              $bonus = $perctg['bonus_btc_o'];
			            }elseif($amount >= 500 AND $amount < 1000){
			              $bonus = $perctg['bonus_btc_t'];
			            }elseif($amount >= 1000){
			              $bonus = $perctg['bonus_btc_th'];
			            }else{
			              $bonus = 0;
			            }
			        }     				
				}

				//Grab Referral bonus if due for withdrawal
				$stmt = $auth_user->runQuery("SELECT SUM(bonus) AS refBonus FROM referral_bonus 
					WHERE referral_username=:username 
					AND status='Available'
					AND payment_method=:payMedoth
					ORDER BY donor_id DESC LIMIT 1");
				$stmt->execute(array(":username"=>$userInfo["username"], ':payMedoth'=>$paymentMedoth));
				$maturedBonus = $stmt->fetch(PDO::FETCH_ASSOC);

				//Check if Payment Method is Bank
				if($paymentMedoth == "Bank"){
					//Upate
					$stmt = $auth_user->runQuery("UPDATE referral_bonus 
						SET status='Used', date_used=now()
						WHERE referral_username=:username AND status='Available'");
					$stmt->execute(array(":username"=>$userInfo['username']));
					
					$refBonus = $userInfo["available_bonus"] + $maturedBonus['refBonus'];

					//Upate
					$stmt = $auth_user->runQuery("UPDATE users SET available_bonus='0'
						WHERE username=:username LIMIT 1");
					$stmt->execute(array(":username"=>$userInfo['username']));
				}else{
					//Upate
					$stmt = $auth_user->runQuery("UPDATE referral_bonus 
						SET status='Used', date_used=now()
						WHERE referral_username=:username AND status='Available'");
					$stmt->execute(array(":username"=>$userInfo['username']));
					
					$refBonus = $userInfo["available_dollar"] + $maturedBonus['refBonus'];

					//Upate
					$stmt = $auth_user->runQuery("UPDATE users SET available_dollar='0'
						WHERE username=:username LIMIT 1");
					$stmt->execute(array(":username"=>$userInfo['username']));					
				}

				//add to request amount
				$requestAmt = $yieldAmt + $amount + $bonus + $refBonus;
				//Set Mature date
                $matureDate = date('Y-m-d H:i:s',strtotime('+30 days',strtotime(date("Y-m-d H:i:s"))));
				//
				$stmt = $auth_user->runQuery("INSERT INTO donations (login_id, payment_method, amount, date_added, yield_amt, bonus, referral_bonus, request_amt, donor_status, payment_status, matured_date) 
				VALUES(:loginID, :paymentMedoth, :amount, now(), :yieldAmt, :bonus, :refBonus, :requestAmt, 'In Progress', 'Pending', :matureDate)");
				
				$stmt->execute(array(':loginID'=>$loginID, ':paymentMedoth'=>$paymentMedoth, ':amount'=>$amount, ':yieldAmt'=>$yieldAmt, ':bonus'=>$bonus, ':refBonus'=>$refBonus, ':requestAmt'=>$requestAmt, ':matureDate'=>$matureDate));
				$donorID = $auth_user->lastInsertId();

				//If its first time donation, run here
				if($perctg['ref_times'] > $countDnts){
					//Run here if referral is not empty
					if($userInfo["referral"] != ""){
						//Set Referral Bonus
						//Give 10% bonus of donated amount to referral
						$referralBonus = $perctg['referral_bonus'] * $amount / 100;

						$stmt = $auth_user->runQuery("INSERT INTO referral_bonus (login_id, donor_id, member, referral_username, donation_amt, bonus, payment_method, status, date_added) 
						
						VALUES(:loginID, :donorID, :donor, :referral, :amount, :referralBonus, :payMedoth, 'Pending', now())");
						
						$stmt->execute(array(':loginID'=>$loginID, ':donorID'=>$donorID, ':donor'=>$userInfo['username'], ':referral'=>$userInfo["referral"], ':amount'=>$amount, ':referralBonus'=>$referralBonus, ':payMedoth'=>$paymentMedoth));
					}
				}

				//Run auto Match
				include(ROOT_PATH."user/includes/autoMatch.php");

				$auth_user->redirect(BASE_URL.'user/transaction-history?donation');
				exit();
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
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
                            <small>Dashboard / Provide Help / New Donation</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <?php
					if(isset($error)){
						foreach($error as $error){
					?>
						 <div class="alert alert-danger">
							<i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?>
						 </div>
					  <?php
						}
					}
					elseif(isset($_GET['added'])){
						 ?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; New Donation Added!
						 </div>
						 <?php
					}
				?>
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>New Donation</h1>
                        <h4>Please indicate amount you are willing to provide</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
              <div class="row">
                <div class="col-md-6">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             My Bank Account Details  
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form role="form" method="post" action="" enctype="multipart/form-data">
                            	<div class="row">
                                	<div class="col-md-12">
                                        <div class="form-group">
                                          <label for="paymentMedoth">Payment Channel</label>
                                          <select class="form-control" name="paymentMedoth" id="paymentMedoth" required>
										<option value="">---Select Payment Method---</option>
                                          	<?php if($userAcct["account_name"] != ""){?>
											<option value="Bank">Bank Deposit (₦)</option>
                                          	<?php }if($userAcct["bitcoin_id"] != ""){?>
											<option value="BitCoin">BitCoin Payment ($)</option>
                                          	<?php }?>
                                          </select>
                                        </div>
                                    </div>                                    
                                	<!--<div class="col-md-12">
                                        <div class="form-group">
                                          <label for="promoCode">Promo Code <em>(Optional)</em></label>
                                          <input type="text" class="form-control" name="promoCode" id="promoCode"
                                            value="<?php if(isset($promoCode)) 
											echo htmlentities($promoCode)?>">
                                        </div>
                                    </div>-->
                					<div class="col-md-12">
                                        <div class="form-group">
                                          <label for="amount">Amount: &nbsp; &nbsp;
                                          <span style="font-size:12px; color: #B42C2E;">
                                          <em>eg 10000 (valid) NOT 10,000 (invalid)</em></span></label>
                                          <?php //if(isset($lastDonation["amount"]) AND $lastDonation["amount"] != ""){?>
                                          <!--<input type="number" class="form-control" name="amount" min="<?php //echo $lastDonation["amount"] ?>" 
                                          id="amount" value="<?php if(isset($amount)) echo htmlentities($amount)?>">-->
										<?php //}else{?>
											<input type="number" class="form-control" required name="amount" id="amount" value="<?php if(isset($amount)) echo htmlentities($amount)?>">
										<?php //}?>
	                                    <p style="font-size:12px;"><em>
	                                    	<b>Minimum Bank Deposit (₦):</b> => 1,000.00 <br>
	                                    	<b>Minimum BitCoin Payment ($):</b> => 10.00
	                                    	</em>
	                                    </p>
                                        </div>
                                    </div>
								</div>
								<button type="submit" class="btn btn-success btn-small">Confirm Donation</button>
                            </form>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>    
</div>
<!-- /. ROW  -->
<?php include(ROOT_PATH."user/includes/footer.php") ?>