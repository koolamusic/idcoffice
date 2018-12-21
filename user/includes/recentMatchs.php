<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="panel panel-default">
        <div class="panel-heading" style="background: #f8f8f8;border:1px solid #CCC">
			Pay to the user below on or before:
			<span class="pull-right">
                <?php if($sDonate["match_status"] == "Paid"){
                	echo '<span style="color:green;">Payment Confirmed!</span>'; 
                }elseif($checkPOP["proof"] != ""){?>
					<div style="font-size:14px; color:red;">POP Uploaded</div>
				<?php }else{?>
					<div id="paymentTimer" style="font-size:20px; color:red;"></div>
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
								<td style="background:#E39E70; color:#FFF;">Payment Information</td>
							</tr>
							<?php if($sDonate['payment_method'] == "Bank"){?>
								<tr>
									<td><b>Amount:</b> ₦<?php echo number_format($sDonate['amount']);?></td>
								</tr>
								<tr>
								<td><b>Account Name:</b> 
									<?php echo $acctInfo['account_name'];?></td>
								</tr>
								<tr>
									<td><b>Acct No.</b> <?php echo $acctInfo['account_number'];?></td>
								</tr>
								<tr>
									<td><b>Bank:</b> <?php echo $acctInfo['bank_name'];?></td>
								</tr>
							<?php }else{?>
								<tr>
									<td><b>Amount:</b> $<?php echo number_format($sDonate['amount']);?></td>
								</tr>
								<tr>
									<td><b>Bitcoin Address:</b> 
									<?php echo $acctInfo['bitcoin_id'];?></td>
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
								Receiver's Contact information</td>
							</tr>
							<tr>
								<td><i class="fa fa-user"></i> 
								<?php echo $payeeInfo['username'];?></td>
							</tr>
							<tr>
								<td><i class="fa fa-mobile"></i> 
								<?php echo $payeeInfo['phone'];?></td>
							</tr>
							<tr>
								<td><i class="fa fa-envelope"></i> 
								<?php echo $payeeInfo['email'];?></td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>
				<?php if($checkPOP['proof'] != ""){?>
					<a target="_blank" href="<?php echo BASE_URL.str_replace('../', '', $checkPOP['proof']) ?>">View POP</a>
				<?php }elseif($sDonate['match_status'] != "Paid"){?>
					<div align="center">  
						<a class="btn btn-success btn-small" href="<?php echo BASE_URL;?>user/make-payment">Upload POP</a>
						<a class="btn btn-danger btn-small" href="<?php echo BASE_URL;?>user/transaction-history">I Can not Make Payment</a>
					</div>
				<?php }?>
    		</div>


		</div>
    </div>

</div>

<script>
// Set the date we're counting down to
<?php
	$dbTimer = $sDonate['period_timer'];
	$datetime = new DateTime($dbTimer);
    $timerT = $datetime->format('M d, Y  H:i:s');
?>
var countDownDate = new Date("<?php echo $timerT; ?>").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="paymentTimer"
    document.getElementById("paymentTimer").innerHTML = days + '<span style="font-size:16px;color:#999;">d </span>' + hours + '<span style="font-size:14px;color:#999;">h </span>'
    + minutes + '<span style="font-size:14px;color:#999;">m </span>' + seconds + '<span style="font-size:14px;color:#999;">s</span> ';

     // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("paymentTimer").innerHTML = "#LATE#";
    }
}, 1000);
</script>