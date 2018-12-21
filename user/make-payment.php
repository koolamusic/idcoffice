<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
    require_once(ROOT_PATH . "user/includes/write-testimony.php");
	
	$payments = $auth_user->makePayments($loginID);

    //Upload
	if(isset($_POST['uploadID'])){
		$uploadID = strip_tags($_POST['uploadID']);
		// Upload product Images
		if(isset($_FILES['paymentProof']['name']) AND $_FILES['paymentProof']['name'] != "") {
			try	{
				$stmt = $auth_user->runQuery("SELECT * FROM payment_proof WHERE match_id=:uploadID");
				$stmt->execute(array(':uploadID'=>$uploadID));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
			}catch(PDOException $e) {
				echo $e->getMessage();
			}
            if($row['proof'] != ""){
    			$toDelete = ROOT_PATH.str_replace('../', '', $row['proof']);
    			if(file_exists($toDelete)){
    				unlink($toDelete);
    			}
            }

			$target_path = "../user/img/payment-proof/";
			$validextensions = array("jpeg", "jpg", "png", "pdf", "doc");
			$ext = explode('.', basename($_FILES['paymentProof']['name'])); 
			$file_extension = end($ext); 
			$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
			if (($_FILES["paymentProof"]["size"] < 300000000000)
			&& in_array($file_extension, $validextensions)) {
				if (move_uploaded_file($_FILES['paymentProof']['tmp_name'], $target_path)) {
					// Insert into table
					$stmt = $auth_user->runQuery("UPDATE payment_proof 
                        SET proof=:payProof, pay_status='Awaiting Confirmation' 
                        WHERE match_id=:uploadID");
					$stmt->execute(array(':payProof'=>$target_path, ':uploadID'=>$uploadID));

                    //Send POP upload email
                    include(ROOT_PATH . "emailTemplates/pop-notify.php");
                    
					$auth_user->redirect(BASE_URL.'user/make-payment?uploaded');
                    exit();

				} else {     //  If File Was Not Moved.
					$error[] = '). please try again!.<br/>';
				}
			} else {     //   If File Size And File Type Was Incorrect.
				$error[] = '). ***Invalid file Size or Type***<br/>';
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
                            <small>Dashboard / Provide Help / Confirmation</small>
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
					elseif(isset($_GET['uploaded'])){
						 ?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Proof of payment uploaded successfully!
						 </div>
						 <?php
					}
				?>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Provide Help Confirmation</h1>
                        <h4>The records below are unconfirmed requests matched to your account. Please select each and attach proof of payment. Also ensure receipient confirms on the portal.</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Request history  
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" 
        id="dataTables-example">
            <thead>
                <tr>
                    <th>Match Date</th>
                    <th>Penalty Date</th>
                    <th>Amount</th>
                    <th>Recipient</th>
                    <th>Payment Details</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody style="font-size: 12px; font-weight: 800;">
            	<?php 
                    if (!empty($payments)) {
                        foreach($payments as $payment) {
                    $grabDonorInfo = $auth_user->grabDonorInfo($payment["match_id"])?>
				<tr>
                    <td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($grabDonorInfo["date_matched"])) ;?></td>

                    <td>
                        <?php if($grabDonorInfo['t_extension'] == ""){?>
                        <?php echo strftime("%d/%m/%Y %I:%M", strtotime($grabDonorInfo["period_timer"])) ;?>
                        <?php }else{?>
                         <?php echo strftime("%d/%m/%Y %I:%M", strtotime($grabDonorInfo["t_extension"])) ;?>
                        <?php }?>
                    </td>

                    <?php if($payment["paymt_method"] == "Bank"){?>
                        <td>₦<?php echo number_format($payment["m_amount"]);?>.00</td>
                    <?php }else{?>
                        <td>$<?php echo number_format($payment["m_amount"]);?>.00</td>
                    <?php }?>
                    <td><?php echo $payment["username"];?></td>
                    <td><?php echo $grabDonorInfo["payment_info"];?></td>
                    <td><?php if($grabDonorInfo["proof"] != ""){?>
                    	<a target="_blank" href="<?php echo BASE_URL.str_replace('../', '', $grabDonorInfo['proof']) ?>">View POP</a><br>
                    <?php } if($payment["match_status"] != "Paid"){?>
					<form role="form" method="post" action="" enctype="multipart/form-data">
						<label for="paymentProof" class="btn btn-success btn-sm">Upload POP</label>
						<input style="display:none" type="file" name="paymentProof" id="paymentProof" onchange="this.form.submit()";>
						<input type="hidden" name="uploadID" value="<?php echo  $payment["match_id"];?>">
					</form>
                  <?php }?>                                     
                   </td>
                    <td><?php if($payment["match_status"] == "Paid"){?>
                    	<span style="color: #08B340;"><?php echo  $payment["match_status"];?></span>
                    <?php }else{?>
                    	<span style="color:#C53234;"><?php echo  $payment["match_status"];?></span>
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
<script type="application/javascript">
	$("input[name='paymentProof']").change(function() { this.form.submit(); });
</script>
<?php include(ROOT_PATH."user/includes/footer.php") ?>