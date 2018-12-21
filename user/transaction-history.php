<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
    require_once(ROOT_PATH . "user/includes/write-testimony.php");

	$transactionHistories = $auth_user->transactionHistory($loginID);

	if(isset($_GET["trn"])){
		$trn = intval($_GET["trn"]);
		try	{
            //Check for first time cancellation
            $stmt = $auth_user->runQuery("SELECT COUNT(*) AS countDonor FROM donations 
                WHERE login_id=:loginID AND NOT donor_status='Canceled'");
            $stmt->execute(array(":loginID"=>$loginID));
            $chec = $stmt->fetch(PDO::FETCH_ASSOC);
            //Grab Donor ID 
            $stmt = $auth_user->runQuery("SELECT donor_id FROM referral_bonus 
                WHERE login_id=:loginID");
            $stmt->execute(array(":loginID"=>$loginID));
            $donorRef = $stmt->fetch(PDO::FETCH_ASSOC);

            //Delete from Referral bonus, if donation is canceled
            if($chec['countDonor'] > 1 AND $donorRef["donor_id"] == $trn){
                $auth_user->redirect(BASE_URL.'user/transaction-history?error');
                exit();
            }elseif($chec['countDonor'] == 1){
                $stmt = $auth_user->runQuery("DELETE FROM referral_bonus 
                    WHERE login_id=:loginID LIMIT 1");
                $stmt->execute(array(':loginID'=>$loginID));
            }

            //Grab credited referral Bonus and re-add to available bonus
            $stmt = $auth_user->runQuery("SELECT * FROM donations WHERE donor_id=:trn");
            $stmt->execute(array(":trn"=>$trn));
            $rBonus = $stmt->fetch(PDO::FETCH_ASSOC);

            if($rBonus["payment_method"] == "Bank"){
                $stmt = $auth_user->runQuery("UPDATE users 
                    SET available_bonus=available_bonus + :rBonus
                    WHERE login_id=:loginID");
                $stmt->execute(array(':loginID'=>$loginID, ':rBonus'=>$rBonus["referral_bonus"]));
            }else{
                $stmt = $auth_user->runQuery("UPDATE users 
                    SET available_dollar=available_dollar + :rBonus
                    WHERE login_id=:loginID");
                $stmt->execute(array(':loginID'=>$loginID, ':rBonus'=>$rBonus["referral_bonus"]));
            }

            //Reduce credibility by 5%
			$stmt = $auth_user->runQuery("UPDATE users 
                SET credibility_score=credibility_score -5 
                WHERE login_id=:loginID");
			$stmt->execute(array(':loginID'=>$loginID));

            //Delete from match donations if already matched
            $stmt = $auth_user->runQuery("DELETE FROM match_donations WHERE donating_id=:trn");
            $stmt->execute(array(':trn'=>$trn));    

			//Delete
			$stmt = $auth_user->runQuery("DELETE FROM donations WHERE donor_id=:trn");
			$stmt->execute(array(':trn'=>$trn));		
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
		$auth_user->redirect(BASE_URL.'user/transaction-history?canceled');
		exit();
	}
	
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Provide Help / Transaction History</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Transaction History (Provide Help)</h1>
                        <h4>The records below details real-time details of all the provide help requests you have placed via the portal and gives realtime status of each. Click on status for In-depth analysis.</h4>
                        <h4>Please note that time extensions will cost you 25% of your credibility score and should you not pay and expiration date elapses, you will need to contact support to unlock your account</h4>
                        <br>
                        <br>
                    </div>                    
                </div>
                <!-- /. ROW  -->                
                <?php
					if(isset($_GET['donation'])){?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; New Donation Added!
						 </div>
				<?php }?>
                
                <?php if(isset($_GET['canceled'])){?>
						<div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Donation Canceled!
						 </div>
				<?php }?>
                <?php if(isset($_GET['error'])){?>
                        <div class="alert alert-danger">
                              <i class="fa fa-check-square"></i> &nbsp; You can not cancel your first Donation, except other pending donation is canceled!
                         </div>
                <?php }?>
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             My transaction history 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" 
    id="dataTables-example">
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount Offered</th>
                <th>Amount Paid</th>
                <th>Total Confirmed</th>
                <th>Outstanding</th>
                <th>Credit Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px; font-weight: 800;">
			<?php 
                if (!empty($transactionHistories)) {
                    foreach($transactionHistories as $trn) {
					//
					$userInfo = $auth_user->userInfo($loginID);
					$ifPaid = $auth_user->ifPaid($trn["donor_id"]);
					$trnStatus = $auth_user->trnStatus($trn["payment_status"], $trn["donor_id"]);?>
                 <tr>
					<td><?php echo strftime("%b %d, %Y", strtotime($trn["date_added"]));?></td>

                    <?php if($trn["payment_method"] == "Bank"){?>
    					<td>₦<?php echo number_format($trn["amount"]);?>.00</td>
    					<td>₦<?php echo number_format($ifPaid["m_amount"]); ?>.00</td>
    					<td>₦<?php echo number_format($ifPaid["m_amount"]); ?>.00</td>
    					<td>₦<?php $bal = $trn["amount"] - $ifPaid["m_amount"]; echo number_format($bal);?>.00</td>
                    <?php }else{?>
                        <td>$<?php echo number_format($trn["amount"]);?>.00</td>
                        <td>$<?php echo number_format($ifPaid["m_amount"]); ?>.00</td>
                        <td>$<?php echo number_format($ifPaid["m_amount"]); ?>.00</td>
                        <td>$<?php $bal = $trn["amount"] - $ifPaid["m_amount"]; echo number_format($bal);?>.00</td>
                    <?php }?>

					<td><?php echo $userInfo["credibility_score"];?>%</td>
					<td>
                        <?php echo $trnStatus;?>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#addList").click(function(){
        $(".hiddenWraper").show(300);
    });
});
</script>
<?php include(ROOT_PATH."user/includes/footer.php") ?>