<?php

	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
	require_once(ROOT_PATH . "user/includes/agree.php");
	require_once(ROOT_PATH . "user/includes/write-testimony.php");

	$Offered = $auth_user->recentlyOffered();
	$recvd = $auth_user->recentlyrecvd();
	$maturingDonations = $auth_user->maturingDonations($loginID);
	$comfirmedPayments = $auth_user->comfirmedPayments();
	$recentTestimonies = $auth_user->recentTestimonies();
	$news = $auth_user->dashboardNews();
	$recentHelps = $auth_user->recentHelps($loginID);
	$recentProvideHelps = $auth_user->recentProvideHelps($loginID);

	//
	$rDonate = $auth_user->receivingDonateSingle($loginID);
	$payerInfo = $auth_user->userInfo($rDonate["payer_id"]);
	$checkPOPrcv = $auth_user->checkPOP($rDonate["match_id"]);

	//
	$sDonate = $auth_user->myDonationsSingle($loginID);
	$payeeInfo = $auth_user->userInfo($sDonate["payee_id"]);
	$acctInfo = $auth_user->acctInfo($sDonate["payee_id"]);
	$checkPOP = $auth_user->checkPOP($sDonate["match_id"]);

	// Calculate Maturing Donation Percentage
	
	//Total donation sent (Naira)
    try {
        $stmt = $auth_user->runQuery("SELECT SUM(m_amount) AS myDonats FROM match_donations 
            WHERE payer_id=:loginID AND match_status='Paid' AND paymt_method='Bank'");
        $stmt->execute(array(':loginID'=>$loginID));
        $nairaSent = $stmt->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $e) {
        echo $e->getMessage();
    }

    //Total donation sent (Dollar)
    try {
        $stmt = $auth_user->runQuery("SELECT SUM(m_amount) AS myDonats FROM match_donations 
            WHERE payer_id=:loginID AND match_status='Paid' AND paymt_method='BitCoin'");
        $stmt->execute(array(':loginID'=>$loginID));
        $dollarSent = $stmt->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $e) {
        echo $e->getMessage();
    }


    //Total Donations Received (Naira)
    try {
        $stmt = $auth_user->runQuery("SELECT SUM(m_amount) AS receivedD FROM match_donations 
            WHERE payee_id=:loginID AND match_status='Paid' AND paymt_method='Bank'");
        $stmt->execute(array(':loginID'=>$loginID));
        $receivedNaira = $stmt->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $e) {
        echo $e->getMessage();
    }

    //Total Donations Received (Dollar)
    try {
        $stmt = $auth_user->runQuery("SELECT SUM(m_amount) AS receivedD FROM match_donations 
            WHERE payee_id=:loginID AND match_status='Paid' AND paymt_method='BitCoin'");
        $stmt->execute(array(':loginID'=>$loginID));
        $receivedDollar = $stmt->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $e) {
        echo $e->getMessage();
    }


?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                        	<div class="row">
                    			<div class="col-md-4">
                            		<small>Dashboard</small>
                            	</div>
                            	<div class="col-md-8">
                            <?php if (!empty($news)) {
									foreach($news as $new) {?>
	                        <p style="padding-bottom: 0px;padding-top: 0px; padding-bottom: 0px; color: #C01518"><?php echo substr($new["note"], 0, 120) ?>... 
	                        <a href="<?php echo BASE_URL.'user/single?id='.$new["id"] ?>">read more!</a>
	                        </p>
	                       <?php }}?>
                        	</div>
                        	</div>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->

                <div class="row">
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div style="background: #FFF; height: 169px; width: 100%; padding: 20px;">
                        	<h4 style="padding: 0px 0px 5px; margin: 0px;">General Stats</h4>
                        		<div class="row">
                    				<div class="col-md-6 col-sm-12 col-xs-12">
                    					<div style="width:100px; height:100px; border-radius:50%; border:5px solid #CCC; text-align: center; padding: 20px 0px;">
                    						<span style="font-size: 18px; font-weight: 700;">
                    						<?php echo intval($userInfo["credibility_score"]);?>%</span><br>
                    						<span style="font-size: 11px;">Credit Score</span>
                    					</div>
                    				</div>

                    				<div class="col-md-6 col-sm-12 col-xs-12">
                    					<div style="width:100px; height:100px; border-radius:50%; border:5px solid #CCC; text-align: center; padding: 20px 0px;">
                    						<span style="font-size: 13px;">
                    						Account:</span><br>
                    						<span style="font-size: 18px; font-weight: 700;">
                    						<?php echo $userInfo["status"];?></span><br>
                    					</div>
                    				</div>
                    			</div>
                        </div>
                    </div>                    
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="list-group" style="background: #294A7E; height: 170px; width: 100%;padding: 0px 10px;">
                                <?php if(!empty($Offered)){
                                        foreach($Offered as $offer){ 
                                        	$userInfor = $auth_user->userInfo($offer["login_id"]);?>
                                    <h3 style="padding: 0px; margin: 0px; font-size:15px; color: #FFF; font-weight: 400;border-bottom: 1px solid #999; padding: 10px 0px;">
                                    <span style="font-size: 22px;"><?php echo $userInfor["username"];?> - </span><br>
                                     <?php if($offer['payment_method'] == "Bank"){?>
                                     <em>Offered : ₦<?php echo number_format($offer["amount"]);?></em><br>
                                     <?php }else{?>
                                     <em>Offered : $<?php echo number_format($offer["amount"]);?></em><br>
                                     <?php }?>
                                    <span style="color: #CCC;"><?php echo strftime("%d/%m/%Y %I:%M", strtotime($offer["date_added"]));?></span></h3>
                                    <?php }}?>
                                    
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="list-group" style="background:#2B6A3E; height: 170px; width: 100%;padding: 0px 10px;">
                                <?php if(!empty($recvd)){
                                        foreach($recvd as $recd){ 
                                        $userInfor = $auth_user->userInfo($recd["payee_id"]);?>
                                    <h3 style="padding: 0px; margin: 0px; font-size: 15px; color: #FFF; font-weight: 400;border-bottom: 1px solid #999; padding: 10px 0px;">
                                    <span style="font-size: 22px;"><?php echo $userInfor["username"];?> - </span><br>
                                     <?php if($recd['paymt_method'] == "Bank"){?>
                                     <em>Received : ₦<?php echo number_format($recd["m_amount"]);?></em><br>
                                     <?php }else{?>
                                     <em>Received : $<?php echo number_format($recd["m_amount"]);?></em><br>
                                     <?php }?>
                                    <span style="color: #CCC;"><?php echo strftime("%d/%m/%Y %I:%M", strtotime($recd["date_paid"]));?></span></h3>
                                    <?php }}?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>          
                <!-- /. ROW  -->
<div class="row">
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-primary text-center no-boder bg-color-red">
            <div class="panel-body">
                <i class="fa fa-gift fa-5x"></i>
                <h3>₦<?php echo number_format($nairaSent["myDonats"]);?></h3>
            </div>
            <div class="panel-footer back-footer-red" style="font-size:12px;">
                Total Donations Sent (₦)
            </div>
        </div>
	</div>
	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-primary text-center no-boder bg-color-red">
            <div class="panel-body">
                <i class="fa fa-dollar fa-5x"></i>
                <h3>$<?php echo number_format($dollarSent["myDonats"]);?></h3>
            </div>
            <div class="panel-footer back-footer-red" style="font-size:12px;">
                Total Donations Sent ($)
            </div>
        </div>
	</div>

	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-primary text-center no-boder bg-color-green">
            <div class="panel-body">
                <i class="fa fa-money fa-5x"></i>
                <h3>₦<?php echo number_format($receivedNaira["receivedD"]);?></h3>
            </div>
            <div class="panel-footer back-footer-green" style="font-size:12px;">
                Total Donations Received (₦)
            </div>
        </div>
	</div>

	<div class="col-md-3 col-sm-12 col-xs-12">
		<div class="panel panel-primary text-center no-boder bg-color-green">
            <div class="panel-body">
                <i class="fa fa-money fa-5x"></i>
                <h3>$<?php echo number_format($receivedDollar["receivedD"]);?></h3>
            </div>
            <div class="panel-footer back-footer-green" style="font-size:12px;">
                Total Donations Received ($)
            </div>
        </div>
	</div>
</div>
                   
                    <div class="row">
                    	<div class="col-md-4 col-sm-12 col-xs-12">
                    		<div class="row">
                    			
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					My Maturing Donation
				</div>
				<div class="panel-body">
					<div class="list-group">
					<?php if(!empty($maturingDonations)){
							foreach($maturingDonations as $donor){
								//								
								$donationDate = strtotime($donor["date_added"]);
								$matureDate = strtotime($donor["matured_date"]);
								$currentTime = strtotime(date("Y-m-d H:i:s"));

								$percentage = (($currentTime - $donationDate) / ($matureDate - $donationDate)) * 100;?>
						 <a href="#" class="list-group-item">
							<span class="badge" 
							style="background:#063D64;">
							<?php if(round($percentage) > '100'){
									echo '100%';
								}elseif(round($percentage) < '1'){
										echo '0%';
								}else{echo round($percentage).'%';}?></span>
							<?php if($donor["payment_method"] == "Bank"){?>
								₦<?php echo number_format($donor["amount"]);?>
							<?php }else{?>
								$<?php echo number_format($donor["amount"]);?>
							<?php }?>
						</a>
						<?php }}?>
					</div>
					<div class="text-right">
						<a href="transaction-history">View all <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			</div>
		</div>
                    			
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											Recent Testimonies
										</div>
										<div class="panel-body">
						<div class="list-group">
						 	<?php if(!empty($recentTestimonies)){
										foreach($recentTestimonies as $testimony){
						$checkPaymethd = $auth_user->testimonyPayMethodCheck($testimony['pay_id']);?>
							 <a href="#" class="list-group-item" style="padding: 0px; border: none; padding-bottom: 10px;">
								<?php echo $testimony["message"];?>
								<br>
								<span class="badge" style="background:#063D64;color:#FFF; padding: 3px 10px; text-align: right; border-radius: 6px;"><?php echo $testimony["member"];?></span><br>
							<?php if($checkPaymethd['paymt_method'] == 'Bank'){?>
								<span class="badge" style="background:#109E55;color:#FFF; padding: 3px 10px; text-align: right; border-radius: 6px;">₦<?php echo number_format($testimony["amount"]);?></span>
							<?php }else{?>
								<span class="badge" style="background:#109E55;color:#FFF; padding: 3px 10px; text-align: right; border-radius: 6px;">$<?php echo number_format($testimony["amount"]);?></span>
							<?php }?>
								<br>
								<hr>
							</a>
							<?php }}?>
						</div>
											<div class="text-right">
												<a href="testimonies">View all Testimonies <i class="fa fa-arrow-circle-right"></i></a>
											</div>
										</div>
									</div>
								</div>
                   			</div>
                    	</div>
                    	<div class="col-md-8 col-sm-12 col-xs-12">
							<div class="row">
								<?php if(isset($sDonate['match_id'])){?>
									<?php include(ROOT_PATH."user/includes/recentMatchs.php");?>
								<?php }?>
								<?php if(isset($rDonate['match_id'])){?>
									<?php include(ROOT_PATH."user/includes/recentRecving.php");?>
								<?php }?>
							
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											Recent "Provide Help"
										</div> 
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Recipient</th>
															<th>Amount</th>
															<th>Penalty Date</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
							<?php 
								if (!empty($recentProvideHelps)) {
									foreach($recentProvideHelps as $help) {?> 
								<tr>
									<td><?php echo $help["username"];?></td>

									<td>
										<?php if($help["paymt_method"] == "Bank"){?>
											<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">₦<?php echo number_format($help["m_amount"]);?></span>
										<?php }else{?>
											<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">$<?php echo number_format($help["m_amount"]);?></span>
										<?php }?>
									</td>

									<td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($help["period_timer"]));?></td>
									<td><?php echo $help["match_status"];?></td>
								</tr>
							<?php }}?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											Recent "Get Help"
										</div> 
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Payment By</th>
															<th>Paired Amount</th>
															<th>Penalty Date</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
						  <?php 
								if (!empty($recentHelps)) {
									foreach($recentHelps as $help) {?> 
								<tr>
									<td><?php echo $help["username"];?></td>
									<td>
										<?php if($help["paymt_method"] == "Bank"){?>
										<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">₦<?php echo number_format($help["m_amount"]);?></span>
										<?php }else{?>
										<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">$<?php echo number_format($help["m_amount"]);?></span>
									<?php }?>
									</td>


									<td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($help["period_timer"]));?></td>
									<td><?php echo $help["match_status"];?></td>
								</tr>
							<?php }}?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
										   Recently Confirmed Payments
										</div> 
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Date</th>
															<th>Member</th>
															<th>Amount</th>
													  </tr>
													</thead>
													<tbody>
					   <?php 
							if (!empty($comfirmedPayments)) {
								foreach($comfirmedPayments as $comfirmed) {?> 
							<tr>
								<td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($comfirmed["date_paid"]));?></td>
								<td><?php echo $comfirmed["username"];?>*******</td>

								<td>
									<?php if($comfirmed["paymt_method"] == "Bank"){?>
										<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">₦<?php echo number_format($comfirmed["m_amount"]);?></span>
									<?php }else{?>
										<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">$<?php echo number_format($comfirmed["m_amount"]);?></span>
									<?php }?>
								</td>
							</tr>
						<?php }}?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
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