<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
        <div class="panel-heading" style="background: #f8f8f8;border:1px solid #CCC">
			The user below is matched to make donation to you:
			<span class="pull-right">
                <?php if($rDonate["match_status"] == "Paid"){
                	echo '<span style="color:green;">Payment Confirmed!</span>'; 
                }elseif($checkPOPrcv['proof'] != ""){
                	echo '<span style="color:red;">POP Uploaded!</span>'; 
                }else{?>
					<div style="font-size:14px; color:red;">Awaiting Payment</div>
				<?php }?>
			</span>
        </div> 
        <div class="row" style="padding:20px;">

        <div class="col-md-6 col-sm-12 col-xs-12">
    		<div class="panel panel-default text-center">
    			<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<tbody align="center">
							<tr>
								<td style="background:#E39E70; color:#FFF;">
								Your expecting Payments</td>
							</tr>
							<?php if($rDonate['payment_method'] == "Bank"){?>
								<tr>
									<td>Expecting Amt: 
									₦<?php echo number_format($rDonate['request_amt']);?></td>
								</tr>
								<tr>
									<td>Received Amt:  
									₦<?php echo number_format($rDonate['received_amt']);?></td>
								</tr>
								<tr>
									<td>Balance: 
									₦<?php echo number_format($rDonate['request_amt'] - $rDonate['received_amt']);?> 
									</td>
								</tr>
								<tr>
									<td>Payment Amt: 
									₦<?php echo number_format($rDonate['m_amount']);?></td>
								</tr>
							<?php }else{?>
								<tr>
									<td>Expecting Amt: 
									$<?php echo number_format($rDonate['request_amt']);?></td>
								</tr>
								<tr>
									<td>Received Amt:  
									$<?php echo number_format($rDonate['received_amt']);?></td>
								</tr>
								<tr>
									<td>Balance: 
									$<?php echo number_format($rDonate['request_amt'] - $rDonate['received_amt']);?> 
									</td>
								</tr>
								<tr>
									<td>Payment Amt: 
									$<?php echo number_format($rDonate['m_amount']);?></td>
								</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
				</div>
    		</div>

    		<div class="col-md-6 col-sm-12 col-xs-12">
    		<div class="panel panel-default text-center">
    			<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<tbody align="center">
							<tr>
								<td style="background:#E39E70; color:#FFF;">
								Payer information</td>
							</tr>
							<tr>
								<td><i class="fa fa-user"></i> 
								<?php echo $payerInfo['username'];?></td>
							</tr>
							<tr>
								<td><i class="fa fa-mobile"></i> 
								<?php echo $payerInfo['phone'];?></td>
							</tr>
							<tr>
								<td><i class="fa fa-envelope"></i> 
								<?php echo $payerInfo['email'];?></td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>
				<?php if($checkPOPrcv['proof'] != "" AND $rDonate['match_status'] != "Paid"){?>
					<div align="center"> 
						<a target="_blank" href="<?php echo BASE_URL.str_replace('../', '', $checkPOPrcv['proof']) ?>">View POP</a>
						<a class="btn btn-success btn-small" href="<?php echo BASE_URL;?>user/payment-confirmation">Approve POP</a>
					</div>
				<?php }elseif($rDonate['match_status'] == "Paid"){?>
					<div align="center">  
						<a target="_blank" href="<?php echo BASE_URL.str_replace('../', '', $checkPOPrcv['proof']) ?>">View POP</a>
						<a class="btn btn-success btn-small" href="<?php echo BASE_URL;?>user/payment-confirmation">POP Approved</a>
					</div>
				<?php }else{?>
					<div align="center"> 
						<a class="btn btn-success btn-small" href="<?php echo BASE_URL;?>user/payment-confirmation">Approve POP</a>
					</div>
				<?php }?>
    		</div>


		</div>
    </div>

</div>