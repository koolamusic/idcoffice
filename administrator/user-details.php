<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");

	//Grab user id from URL query
	if(isset($_GET["id"]) AND $_GET["id"] != ""){
		$userID = intval($_GET["id"]);
	}else{
		$auth_user->redirect(BASE_URL.'administrator/users');
		exit();
	}
	$user = $auth_user->userSingle($userID);
	$donationsMade = $auth_user->donationsMade($userID);
	$donationsReceived = $auth_user->donationsReceived($userID);

	//Grab bank details form data and insert into TB
	if(isset($_POST['bank'])){
		$bank = strip_tags($_POST['bank']);
		$accountName = strip_tags($_POST['accountName']);
		$accountNum = strip_tags($_POST['accountNum']);	
		$bitcoin = strip_tags($_POST['bitcoin']);	
		
		//Php Validation
		if($bank == "")	{
			$error[] = "Please enter Name of Bank!";	
		}	
		else if($accountName == "")	{
			$error[] = "Please enter Name on Account!";
		}
		else if($accountNum == "")	{
			$error[] = "Please enter Account Number!";
		}
		
		else{
			try	{				
				$stmt = $auth_user->runQuery("UPDATE account_details 
					SET account_number=:accountNum, 
						account_name=:accountName, 
						bank_name=:bank,
						bitcoin_id=:bitcoin,
						last_updated=now() 
					WHERE login_id=:userID");
				$stmt->execute(array(':userID'=>$userID, ':accountNum'=>$accountNum, ':accountName'=>$accountName, ':bank'=>$bank, ':bitcoin'=>$bitcoin));

				$auth_user->redirect(BASE_URL.'administrator/user-details?id='.$userID.'&Updated');
				exit();
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}	
	}

	//Update user profile
	if(isset($_POST['username'])){
		$username = strip_tags($_POST['username']);	
		$fullName = strip_tags($_POST['fullName']);	
		$email = strip_tags($_POST['email']);	
		$phone = strip_tags($_POST['phone']);	
		$country = strip_tags($_POST['country']);	
		$referral = strip_tags($_POST['referral']);	
		$status = strip_tags($_POST['status']);	
		$credit = strip_tags($_POST['credit']);
		
		//
		try	{				
			$stmt = $auth_user->runQuery("UPDATE users 
				SET username=:username,
					full_name=:fullName,
					email=:email,
					phone=:phone,
					country=:country,
					referral=:referral,
					status=:status,
					credibility_score=:credit
				WHERE login_id=:userID");
			$stmt->execute(array(':userID'=>$userID, ':username'=>$username, ':fullName'=>$fullName, ':email'=>$email, ':phone'=>$phone, ':country'=>$country, ':referral'=>$referral, ':status'=>$status, ':credit'=>$credit));

			$auth_user->redirect(BASE_URL.'administrator/user-details?id='.$userID.'&Updated');
			exit();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}	
	}

	//Grab user bank account details
	try	{
		$stmt = $auth_user->runQuery("SELECT * FROM account_details WHERE login_id=:userID");
		$stmt->execute(array(':userID'=>$userID));
		$account = $stmt->fetch(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
	
	/// Add 	
	if(isset($_POST['message'])) {
		$message = strip_tags($_POST['message']);
		if($message == ""){
			$errorMSG = 'You can not send empty message!';
		}

		if(!isset($errorMSG)){
			// Insert into admin notes
			try	{
				$stmt = $auth_user->runQuery("INSERT INTO messaging (sender_name, message, reciever_id, status, date_sent)
						VALUES ('admin', :message, :senderID, 'Unread', now())");
				
				$stmt->execute(array(':senderID'=>$userID, ':message'=>$message));
				$msgID = $auth_user->lastInsertId();
			
				//check files
				if(isset($_FILES['upload']['name']) AND $_FILES['upload']['name'] != "") {
				    $target_path = "../img/";
				    $validextensions = array("jpeg", "jpg", "png", "doc", "PDF", "txt");
				    $ext = explode('.', basename($_FILES['upload']['name'])); 
				    $file_extension = end($ext); 
				    $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
				    if (($_FILES["upload"]["size"] < 300000000)
				    && in_array($file_extension, $validextensions)) {
				      if (move_uploaded_file($_FILES['upload']['tmp_name'], $target_path)) {
				      	 	// Update
					        $stmt = $auth_user->runQuery("UPDATE messaging 
					          SET file=:file WHERE id=:msgID");
					        $stmt->execute(array(':file'=>$target_path, ':msgID'=>$msgID));
				      	} else {     //  If File Was Not Moved.
				        	$error[] = '). please try again!.<br/>';
				      	}
				    }else {     //   If File Size And File Type Was Incorrect.
				      $error[] = '). ***Invalid file Size or Type***<br/>';
				    }     
				}
				$auth_user->redirect(BASE_URL.'administrator/user-details?id='.$userID.'&message-sent');
				exit();

			}
			catch(PDOException $e) {
				echo $e->getMessage();
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
                            <small>Dashboard /  Users / Details</small>
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
					elseif(isset($_GET['Updated'])){
						 ?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Account updated successfully!
						 </div>
						 <?php
					}
					elseif(isset($_GET['message-sent'])){
						 ?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Message sent!
						 </div>
						 <?php
					}
				?>

                <div class="row">
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                            <div class="row">
                            	<div class="col-md-12 col-sm-12 col-xs-12">
                                <legend>Basic Information</legend>
                                <p style="padding:0px; margin:0px;">
                                	<?php if(isset($user['photo']) AND $user['photo'] != ""){?>
				                 		<img style="border-radius:10%;" src="<?php echo BASE_URL.str_replace('../', '', $user['photo']);?>" width="90" height="80" alt="Profile Picture"/>
									<?php }else{?>
				                  		<img src="<?php echo BASE_URL;?>img/no_profile.png" width="90" height="80" alt="Profile Picture"/>
				                  	<?php }?>
				                  	<br>
                                    <br>

                                    <form role="form" method="post" action="">
                                    <div class="row">
                                    	<div class="col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label for="username">Userame</label>
											  <input type="text" class="form-control" 
											  name="username" id="username" 
											  value="<?php if(isset($username)){echo $username;}else{echo $user["username"];}?>">
											</div>
										</div>
										<div class="col-md-6 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label for="fullName">Full Name</label>
											  <input type="text" class="form-control" 
											  name="fullName" id="fullName" 
											  value="<?php if(isset($fullName)){echo $fullName;}else{echo $user["full_name"];}?>">
											</div>
										</div>

										<div class="col-md-7 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label for="email">Email</label>
											  <input type="email" class="form-control" 
											  name="email" id="email" 
											  value="<?php if(isset($email)){echo $email;}else{echo $user["email"];}?>">
											</div>
										</div>
										<div class="col-md-5 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label for="phone">Phone</label>
											  <input type="text" class="form-control" 
											  name="phone" id="phone" 
											  value="<?php if(isset($phone)){echo $phone;}else{echo $user["phone"];}?>">
											</div>
										</div>

										<div class="col-md-4 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label for="country">Country</label>
											  <input type="text" class="form-control" 
											  name="country" id="country" 
											  value="<?php if(isset($country)){echo $country;}else{echo $user["country"];}?>">
											</div>
										</div>
										<div class="col-md-8 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label for="referral">Referral</label>
											  <input type="text" class="form-control" 
											  name="referral" id="referral" 
											  value="<?php if(isset($referral)){echo $referral;}else{echo $user["referral"];}?>">
											</div>
										</div>

										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label for="country">Status</label>
											  <select name="status" id="status" class="form-control">
											  	<option value="<?php echo $user["status"];?>">
											  	<?php echo $user["status"];?></option>
											  	<option value="Active">Active</option>
											  	<option value="Blocked">Block</option>
											  </select>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label>Signup Date:</label>
											  <input type="text" class="form-control" readonly 
											  value="<?php echo strftime("%b %d, %Y", strtotime($user["signup_date"]));?>">
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label>Last Login: <?php echo timeAgo($user["last_login"]);?></label>
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label>Last Updated: <?php if($user["last_updated"] != "0000-00-00 00:00:00"){echo timeAgo($user["last_updated"]);}?></label>
											</div>
										</div>

										<div class="col-md-8 col-sm-12 col-xs-12">
											<div class="form-group">
											  <label for="credit">Add / Remove Credibility</label>
											  <input type="number" class="form-control" 
											  name="credit" id="credit" 
											  value="<?php if(isset($credit)){echo htmlentities($credit);}else{echo $user["credibility_score"];}?>">
											</div>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12">
										<br>
											<button type="submit" class="btn btn-success btn-small">Update Information</button>
										</div>
									</div>
									</form>
                                </p>
                                </div>
                                
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                            <div class="row">
                            	
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                <legend>Payment Details</legend>
                                <form role="form" method="post" action="<?php echo BASE_URL.'administrator/user-details?id='.$userID;?>" enctype="multipart/form-data">
									  <div class="row">
										<div class="col-md-12">
											<div class="form-group">
											  <label for="bank">Name of Bank</label>
											  <input type="text" class="form-control" name="bank" id="bank" 
											  value="<?php if(isset($bank)){echo htmlentities($bank);}else{echo $account["bank_name"];}?>">
											</div>
										</div>                                    
										<div class="col-md-12">
											<div class="form-group">
											  <label for="accountName">Account Name</label>
											  <input type="text" class="form-control" name="accountName" id="accountName"
												value="<?php if(isset($accountName)){echo htmlentities($accountName);}else{echo $account["account_name"];}?>">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
											  <label for="accountNum">Account Number</label>
											  <input type="text" class="form-control" name="accountNum" id="accountNum" 
											  value="<?php if(isset($accountNum)){echo htmlentities($accountNum);}else{echo $account["account_number"];}?>">
											</div>
										</div>
										<div class="col-md-12">
											<hr>
											<div class="form-group">
											  <label for="accountNum">Bitcoin Address</label>
											  <input type="text" class="form-control" 
											  name="bitcoin" id="bitcoin" 
											  value="<?php if(isset($bitcoin)){echo htmlentities($bitcoin);}else{echo $account["bitcoin_id"];}?>">
											</div>
										</div>
									</div>
									<button type="submit" name="accountBtn" class="btn btn-success btn-small">Update Account</button>
								</form>
								<br><br><br><br><br>
                                </div>
                               </div>
                             </div>
                            </div>
                        </div>
                    </div>
                <br>          
                <!-- /. ROW  -->
               <div class="row">
               	<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="panel panel-default">
					<div align="center">
						<button class="btn btn-success btn-small" id="addList">
						Send Message</button>

					</div>
					<form role="form" method="post" action="" class="hiddenWraper" 
					style="width: 90%; margin: auto; margin-top: 30px;display:none;"
					 enctype="multipart/form-data">
						<div class="row">
               				<div class="col-md-12 col-sm-12 col-xs-12">
               					<div class="form-group">
								  <textarea class="form-control" name="message" id="message" 
									rows="3"></textarea>
								</div>
               				</div>
               				<div class="col-md-6 col-sm-12 col-xs-12">
               					<div class="form-group">
								  <input type="file" name="upload">
								</div>
               				</div>
               				<div class="col-md-6 col-sm-12 col-xs-12">
               					<div style="width: 20%; margin: auto;">
									<button type="submit" class="btn btn-success btn-sm">Send</button>
								</div>
               				</div>
					</form>
					</div>
				</div>
	               <div class="col-md-12 col-sm-12 col-xs-12">
						<div class="panel panel-default">
							<div class="panel-heading">
							   User Provide Help
							</div> 
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Date</th>
												<th>Recipient</th>
												<th>Amount Sent</th>
										  </tr>
										</thead>
										<tbody>
	   <?php 
			if (!empty($donationsMade)) {
				foreach($donationsMade as $donation) {?> 
			<tr>
				<td><?php echo timeAgo($donation["date_matched"]);?></td>
				<td><?php echo $donation["username"]?></td>
				<td>	
				<?php if($donation["paymt_method"] == "Bank"){?>
					<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">₦<?php echo number_format($donation["m_amount"]);?></span>
				<?php }else{?>
					<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">$<?php echo number_format($donation["m_amount"]);?></span>
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


					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="panel panel-default">
							<div class="panel-heading">
							   User Receive Help
							</div> 
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>Date</th>
												<th>Recipient</th>
												<th>Amount Received</th>
										  </tr>
										</thead>
										<tbody>
   <?php 
		if (!empty($donationsReceived)) {
			foreach($donationsReceived as $donation) {?> 
		<tr>
			<td><?php echo timeAgo($donation["date_matched"]);?></td>
			<td><?php echo $donation["username"]?></td>
			<td>
				<?php if($donation["paymt_method"] == "Bank"){?>
					<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">₦<?php echo number_format($donation["m_amount"]);?></span>
				<?php }else{?>
					<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">$<?php echo number_format($donation["m_amount"]);?></span>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#addList").click(function(){
        $(".hiddenWraper").show(300);
    });
});
</script>
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>