<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");

	$payerLists = $auth_user->payerLists();
	$payeeLists = $auth_user->payeeLists();
	
	if(isset($_POST['match'])){
		$recDonorID = strip_tags($_POST['payee']);
		$cntPayer = array();
		$cntPayee = array();
		$cntPayer = count($_POST['payers']);
		$cntPayee = count($_POST['payee']);
		if($recDonorID == "" OR $cntPayee > 1){
			$error[] = "Opps: Please select single Payee!";	
		}elseif($cntPayer < 1){
			$error[] = "Opps: Please select at least one Payer!";
		}elseif($cntPayer > 7){
			$error[] = "Opps: You can only select up to 7 Payers!";
		}else{
			for($i = 0; $i < $cntPayer; $i++) {
				$senDonorID = $_POST['payers'][$i];
				try	{
					// Grab Payee Info
					$stmt = $auth_user->runQuery("SELECT * FROM donations, account_details, users 
						WHERE donations.donor_id=:recDonorID 
						AND donations.login_id=users.login_id 
						AND donations.login_id=account_details.login_id");
					$stmt->execute(array(':recDonorID'=>$recDonorID));	
					$payeeInfo = $stmt->fetch(PDO::FETCH_ASSOC);					
					
					// Grab Payer Info
					$stmt = $auth_user->runQuery("SELECT * FROM donations, users 
						WHERE donations.donor_id=:senDonorID
						AND donations.login_id=users.login_id");
					$stmt->execute(array(':senDonorID'=>$senDonorID));
					$payerInfo = $stmt->fetch(PDO::FETCH_ASSOC);

					//Check remaining amount if its less than minimum accepted donations
                    $mDonor = $auth_user->matchingDonations($recDonorID);
					$remning = $payeeInfo["request_amt"] - ($mDonor + $payerInfo["amount"]);
					//checks if receiving amout is grather than expected amount
					$bal = $payeeInfo["request_amt"] - $mDonor;
					
					if($payeeInfo["payment_method"] != $payerInfo["payment_method"]){			
						$error[] = "Oops! You can not match donations of different corrency!";	
					}elseif($payerInfo["amount"] > $bal) {						
						$error[] = "Oops! Payee can not receive amount grater than expected amount!";	
					}elseif($payeeInfo["payment_method"] == 'Bank' AND $remning != 0 AND $remning < 1000)	{
						$error[] = "Oops! Outstanding amount will fall below the minimum acceptable Donation!";	
					}elseif($payeeInfo["payment_method"] == 'BitCoin' AND $remning != 0 AND $remning < 10)	{
						$error[] = "Oops! Outstanding amount will fall below the minimum acceptable Donation!";	
					}elseif($payerInfo["login_id"] == $payeeInfo["login_id"])	{
						$error[] = "Opps: It seems one of more of your selected Payers is matching to itself as Payee!";	
					}else{
						//Set match date
						$penaltyDate = date('Y-m-d H:i:s', strtotime('+2 days', strtotime(date("Y-m-d H:i:s"))));
						
						if($payeeInfo['payment_method'] == "Bank"){
							//Account info
							$acctInfo = '<b>Phone: </b>'.$payeeInfo["phone"].',<br><b>Acct Name: </b>'.$payeeInfo["account_name"].',<br> <b>Acct #</b>: '.$payeeInfo["account_number"].', <br> <b>Bank: </b>'.$payeeInfo["bank_name"];
						}else{
							//Account info
							$acctInfo = '<b>Phone: </b>'.$payeeInfo["phone"].',<br><b>Bitcoin: </b>'.$payeeInfo["bitcoin_id"];
						}
						

						// Insert into match_donations Table
						$stmt = $auth_user->runQuery("INSERT INTO match_donations(match_to_donor_id, donating_id, payer_id, payee_id, match_status, date_matched, period_timer, m_amount, paymt_method) 

						VALUES(:recDonorID, :senDonorID, :payerID, :payeeID,  'Pending', now(), :penaltyDate, :payingAmt, :payMethod)");
						
						$stmt->execute(array(':recDonorID'=>$recDonorID, ':senDonorID'=>$senDonorID, ':payerID'=>$payerInfo["login_id"], ':payeeID'=>$payeeInfo["login_id"], ':payingAmt'=>$payerInfo["amount"], ':penaltyDate'=>$penaltyDate, ':payMethod'=>$payeeInfo['payment_method']));
						$matchID = $auth_user->lastInsertId();

						// Insert into Proof of payment Table
						$stmt = $auth_user->runQuery("INSERT INTO payment_proof(match_id, payer_id, payee_id, amount, payment_info, pay_method, pay_status, date_added)

						VALUES(:matchID, :payerID, :payeeID, :payingAmt, :acctInfo, :payMethod, 'Pending', now())");
						
						$stmt->execute(array('matchID'=>$matchID, ':payerID'=>$payerInfo["login_id"], ':payeeID'=>$payeeInfo["login_id"], ':payingAmt'=>$payerInfo["amount"], ':acctInfo'=>$acctInfo, ':payMethod'=>$payeeInfo['payment_method']));	

						//set Payee as matched
						$stmt = $auth_user->runQuery("UPDATE donations 
							SET donor_status='Matched' WHERE donor_id=:recDonorID");
						$stmt->execute(array(':recDonorID'=>$recDonorID));

						//set Payer as matched
						/*$stmt = $auth_user->runQuery("UPDATE donations
							SET donating_status='Payout' WHERE donor_id=:senDonorID");
						$stmt->execute(array(':senDonorID'=>$senDonorID));*/

						//Email notification to Payee
						include(ROOT_PATH . "emailTemplates/match-notify-payee.php");
						//Email notification to Payer
						include(ROOT_PATH . "emailTemplates/match-notify-payer.php");
					}
				}
				catch(PDOException $e) {
					echo $e->getMessage();
				}			
			}
			if(!isset($error)){
				$auth_user->redirect(BASE_URL.'administrator/match-donors?matched');
				exit();
			}
		}	
	}
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Match Donors</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                 <?php
					if(isset($error)){
						foreach($error as $error){ ?>
						 <div class="alert alert-danger">
							<i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?>
						 </div>
				<?php } }elseif(isset($_GET["matched"])){?>
						<div class="alert alert-success">
							<i class="fa fa-check-o"></i> &nbsp; Selected donors successfully matched!
						 </div>
				<?php }?>
                <style type="text/css">
					select {
						min-height: 250px;
					}
					select option {padding: 5px;border-bottom: 1px solid #CCC;}
					select .noteOptn{color: #999;font-style: italic;}
				</style>
				<h3>Manual Matching</h3>
				<br>
                <form role="form" method="post" action="" enctype="multipart/form-data" onsubmit="validate()">
                <div class="row" style="min-height: 350px;">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Select Payer(s)
                            </div>
                            <div class="panel-body">
                                <div class="list-group">
		<select name="payers[]" id="payers" multiple required class="form-control">
			<option value="" class="noteOptn">--- Press hold "Ctrl" Key to Select upto 7 Payers ---</option>
			<?php 
		        if (!empty($payerLists)) {
		            foreach($payerLists as $payer) {
		         $payiInfo = $auth_user->userInfo($payer["login_id"]);?>
		         <?php if($payer["payment_method"] == "Bank"){?>
					<option value="<?php echo $payer["donor_id"];?>"><?php echo $payiInfo["username"].' - Offer Amount: ₦'.number_format($payer["amount"]);?></option>
				<?php }else{?>
					<option value="<?php echo $payer["donor_id"];?>"><?php echo $payiInfo["username"].' - Offer Amount: $'.number_format($payer["amount"]);?></option>
				<?php }?>
		<?php  } }?>
		</select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                               Select Payee
                               <span class="pull-right">
                               <a class="btn btn-success btn-small" href="<?php echo BASE_URL; ?>administrator/add-donors">Add Payee</a></span>
                            </div> 
                            <div class="panel-body">
                                <div class="list-group">
	<select name="payee" id="payee" multiple required class="form-control">
    	<option value="" class="noteOptn">--- Select Payee ---</option>
    	<?php 
            if (!empty($payeeLists)) {
                foreach($payeeLists as $payee) {
             $mDonor = $auth_user->matchingDonations($payee["donor_id"]);
             $payiInfo = $auth_user->userInfo($payee["login_id"]);
             $bal = $payee["request_amt"] - $mDonor;
        ?>
        <?php if(isset($payiInfo["username"])){?>
        	<?php if($payee["payment_method"] == "Bank"){?>
        		<?php if($bal != 0){?>
		    		<option value="<?php echo $payee["donor_id"];?>">
		    		<?php echo $payiInfo["username"].' - Request Amount: ₦'.number_format($bal);?>
		    		</option>
	    		<?php }?>
    		<?php }else{?>
    			<?php if($bal != 0){?>
		    		<option value="<?php echo $payee["donor_id"];?>">
		    		<?php echo $payiInfo["username"].' - Request Amount: $'.number_format($bal);?>
		    		</option>
	    		<?php }?>
    		<?php }?>
    	<?php }?>
    	<?php  } }?>
    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
						<div style="width: 80px; margin: auto;">
							<button type="submit" name="match" class="btn btn-success btn-large">Proceed to Match</button>
						</div>
                    </div>
                </div>
                <!-- /. ROW  -->
			</form>
        </div>
     <!-- /. ROW  -->

<?php include(ROOT_PATH."user/includes/footer.php") ?>