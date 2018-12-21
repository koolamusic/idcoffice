<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");

	$matchedDonors = $auth_user->matchedDonors();

    //Upload POP by admin
    if(isset($_POST['uploadID'])){
        $uploadID = strip_tags($_POST['uploadID']);

        // Upload 
        if(isset($_FILES['paymentProof']['name']) AND $_FILES['paymentProof']['name'] != "") {
            try {
                $stmt = $auth_user->runQuery("SELECT * FROM payment_proof WHERE id=:uploadID");
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
                    
                    //Update table
                    $stmt = $auth_user->runQuery("UPDATE payment_proof 
                        SET proof=:payProof,
                            pay_status='Awaiting Confirmation' 
                        WHERE match_id=:uploadID");
                    $stmt->execute(array(':payProof'=>$target_path, ':uploadID'=>$uploadID));

                    //Send POP upload email
                    include(ROOT_PATH . "emailTemplates/pop-notify.php");

                    $auth_user->redirect(BASE_URL.'administrator/matched-donors?uploaded');
                    exit();

                } else {     //  If File Was Not Moved.
                    $error[] = '). please try again!.<br/>';
                }
            } else {     //   If File Size And File Type Was Incorrect.
                $error[] = '). ***Invalid file Size or Type***<br/>';
            }           
        }   
    }

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

        //Check if the total received amount is equal to the espected amount
        if($totalRecAmt == $requestAmount){
            $donorStatus = 'Completed';
        }else{
            $donorStatus = 'Partial';
        }
        
        try {
            //
            if($matchStatus != 'Paid'){
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

                //Update referral bonus table if any.
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

                $auth_user->redirect(BASE_URL.'administrator/matched-donors?confirmed');
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
        $recievingPID = strip_tags($_POST['recievingPID']);
        
        try {

            $stmt = $auth_user->runQuery("UPDATE payment_proof, donations 
                SET payment_proof.pay_status='Flagged', 
                    payment_proof.confirmed_date=now(), 
                    donations.donor_status='Flagged',
                    donations.date_paid=now()
                WHERE payment_proof.match_id=:matchID
                AND donations.donor_id=:donatingID");
            $stmt->execute(array(':matchID'=>$matchID, ':donatingID'=>$donatingID));

            $auth_user->redirect(BASE_URL.'administrator/matched-donors?Flagged');
            exit();

        }catch(PDOException $e) {
            echo $e->getMessage();
        }   
    }

    //Delete
	if(isset($_POST["delete"])) {
		if(!isset($_POST['tick'])) {
			$auth_user->redirect(BASE_URL.'administrator/matched-donors?warning');
			exit();
		}
		$cnt = array();
		$cnt = count($_POST['tick']);
		for($i = 0; $i < $cnt; $i++) {
			$rowID = $_POST['tick'][$i];
			try	{
				$stmt = $auth_user->runQuery("DELETE FROM match_donations 
                    WHERE match_id=:rowID limit 1");
				$stmt->execute(array(':rowID'=>$rowID));				
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
		$auth_user->redirect(BASE_URL.'administrator/matched-donors?Deleted');
        exit();
	}
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Matched Donors</small>
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
                <?php } }elseif(isset($_GET['unblock'])){?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Un-Blocked successfully!
						 </div>
				<?php }elseif(isset($_GET['Deleted'])){?>
              			<div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Deleted successfully!
						 </div>
               <?php }elseif(isset($_GET['warning'])){?>
               			<div class="alert alert-danger">
							<i class="fa fa-exclamation-triangle"></i> &nbsp; Please select a row to Block / Un-blocked
						 </div>
                <?php }elseif(isset($_GET['blocked'])){?>
               			<div class="alert alert-success">
							<i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Blocked successfully!
						 </div>
                <?php }elseif(isset($_GET['uploaded'])){?>
                        <div class="alert alert-success">
                            <i class="fa fa-check-square"></i> &nbsp; POP Uploaded successfully!
                         </div>
                <?php }elseif(isset($_GET['confirmed'])){?>
                        <div class="alert alert-success">
                            <i class="fa fa-check-square"></i> &nbsp; POP successfully Approved!
                         </div>
                <?php }elseif(isset($_GET['Flagged'])){?>
                        <div class="alert alert-success">
                            <i class="fa fa-check-square"></i> &nbsp; <span style="color: red;">POP has been Flagged!</span> The Payee has been returned to pubilcation list to receive Pledge.
                         </div>
                <?php }?>
                   <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                
                <div class="panel panel-default">
                        <div class="panel-heading">
                             Matched Donors
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                            		<div class="row">
                                        <!--<div class="col-md-1">
                                           <button type="submit" name="unBlock" 
                                           class="btn btn-success btn-sm">Un-Block</button>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="submit" name="block" 
                                            class="btn btn-warning  btn-sm">Block</button>
                                        </div>-->
                                        <div class="col-md-1">
                                            <button type="submit" name="delete" 
                                            class="btn btn-danger  btn-sm">Delete</button>
                                        </div>
                                    </div>
                                    <br>
                                <table class="table table-striped table-bordered table-hover" 
                                id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checkAll" /></th>
                                            <th>Payer</th>                      
                                            <th>Payee</th>        
                                            <th>Amount</th>
                                            <th>Action</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>



                    	<?php 
                            if (!empty($matchedDonors)) {
                                foreach($matchedDonors as $donor) {
                                    $donatn = $auth_user->mDonors($donor["donating_id"]);
                                    $payerName = $auth_user->userInfo($donor["payer_id"]);
                                    $payeeName = $auth_user->userInfo($donor["payee_id"]);
                                    try {
                                        $stmt = $auth_user->runQuery("SELECT * FROM payment_proof 
                                            WHERE match_id=:matchedID limit 1");
                                        $stmt->execute(array(':matchedID'=>$donor["match_id"]));
                                        $POP = $stmt->fetch(PDO::FETCH_ASSOC);             
                                    }
                                    catch(PDOException $e) {
                                        echo $e->getMessage();
                                    }       
                                ?>
    						<tr>
                                <td><input name="tick[]" type="checkbox" value="<?php echo $donor["match_id"];?>"></td>
                                <td><a href="<?php echo BASE_URL.'administrator/user-details?id='.$donor["payer_id"];?>"><?php echo $payerName["username"];?></a></td>
                                <td><a href="<?php echo BASE_URL.'administrator/user-details?id='.$donor["payee_id"];?>"><?php echo $payeeName["username"];?></a></td>
                                <?php if($donor["paymt_method"] == "Bank"){?>
                                    <td>₦<?php echo number_format($donor["m_amount"]);?></td>
                                <?php }else{?>
                                    <td>$<?php echo number_format($donor["m_amount"]);?></td>
                                <?php }?>
                                <td align="center">
                                    <?php if(isset($POP["proof"]) AND $POP["proof"] != "" 
                                            AND $POP["pay_status"] == "Paid"){?>
                                        <span style="color: green;">Approved</span><br> 
                                        <a target="_blank" href="<?php echo $POP["proof"] ?>">
                                        View POP</a>
                                    <?php }elseif(isset($POP["proof"]) AND $POP["proof"] != "" 
                                            AND $POP["pay_status"] != "Paid"){?>
                                            <a target="_blank" href="<?php echo $POP["proof"] ?>">
                                        View POP</a><br>
                                        <form method="post" action="">
                                            <input type="hidden" name="matchID" value="<?php echo $donor["match_id"];?>">

                                            <input type="hidden" name="payerID" value="<?php echo  $donor["payer_id"];?>">

                                            <input type="hidden" name="payeeID" value="<?php echo  $donor["payee_id"];?>">

                                            <input type="hidden" name="donatingID" value="<?php echo $donor["donating_id"];?>">

                                            <input type="hidden" name="recievingDID" value="<?php echo $donor["match_to_donor_id"];?>">

                                            <input type="hidden" name="mAmount" value="<?php echo $donor["m_amount"];?>">

                                            <input type="hidden" name="curtRecdAmount" value="<?php echo $donor["received_amt"];?>">

                                            <input type="hidden" name="requestAmout" value="<?php echo $donor["request_amt"];?>">

                                            <input type="hidden" name="matchStatus" value="<?php echo $donor["match_status"];?>">

                                            <button type="submit" name="approve" style="font-size: 12px; padding: 0px 20px; color: green;">Approve</button>
                                        </form>
                                        <form method="post" action="">
                                            <input type="hidden" name="matchID" value="<?php echo $donor["match_id"];?>">

                                            <input type="hidden" name="donatingID" value="<?php echo $donor["donating_id"];?>">

                                            <input type="hidden" name="recievingPID" value="<?php echo $donor["match_to_donor_id"];?>">

                                            <button type="submit" name="flag" style="font-size: 8px; padding: 0px 10px; color: red;">Flag as Fake</button>
                                        </form>
                                    <?php }else{?>
                                        <form method="post" action="" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="paymentProof" class="btn btn-success">
                                                Click to Upload POP</label>
                                                <input style="display: none;" type="file" name="paymentProof" id="paymentProof" onchange="this.form.submit()";>
                                                <input type="hidden" name="uploadID" value="<?php echo  $donor["match_id"];?>">
                                            </div>
                                        </form>
                                    <?php }?>
                                </td>
                                <td><?php echo $donor["match_status"];?></td>
                                <td><?php echo timeAgo($donor["date_matched"]); ?></td>
                            </tr>
                        <?php  } }?>



                                    </tbody>
                                </table>
                            </form>
                            <script type="text/javascript">
								$('.checkAll').change(function(){
									var state = this.checked;
									state? $(':checkbox').prop('checked',true):$(':checkbox').prop('checked',false);
									state? $(this).next('b').text('Uncheck All') :$(this).next('b').text('Check All')
								});	
							</script>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
                    
                </div>
                <!-- /. ROW  -->
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>