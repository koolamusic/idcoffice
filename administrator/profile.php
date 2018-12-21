<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
	
	if(isset($_POST['phone'])){
		$phn = strip_tags($_POST['phone']);
		$email = strip_tags($_POST['email']);	
		
		//checking phone
		$phne = str_replace('+', '', $phn);
		$phn2 = preg_replace('#[^0-9.]#', '', $phne);
		$part = substr($phn2,0,3);
		if($part == "234"){
			$phone = $phn2;
		}else{
			$firt = substr($phn2,0,1);
			if($firt == "0") {
				$phone = preg_replace("/^".$firt."/", "234", $phn2);
			}else{
				$phone = "234".$phn2;
			}
		}
		
		//Php Validation
		if($phone == "")	{
			$error[] = "Please enter valid Phone Number!";	
		}	
		else if($email == "")	{
			$error[] = "Please enter valid Phone Number!";
		}
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL))	{
			$error[] = 'Please enter a valid email address !';
		}
		else{
			try	{
				$stmt = $auth_user->runQuery("SELECT email, phone FROM users WHERE email=:email OR phone=:phone");
				$stmt->execute(array(':email'=>$email, ':phone'=>$phone));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);				
				if($row['phone'] == $phone AND $row['email'] == $email) {
					if($row['phone'] == $phone) {
						$error[] = "Sorry, Phone Number already in use in the system!";
					}else if($row['email'] == $email) {
						$error[] = "Sorry, Email Address already in use in the system!";
					}
				}else{
					$stmt = $auth_user->runQuery("UPDATE `users` SET `email`=:email,`phone`=:phone,`last_updated`=now() 
							WHERE `login_id`=:loginID");
					$stmt->execute(array(':email'=>$email, ':phone'=>$phone, ':loginID'=>$loginID));
					$auth_user->redirect(BASE_URL.'user/profile?updated');
				}
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}	
	}

	if(isset($_POST['uploadBtn'])){
		// Upload product Images
		if(isset($_FILES['photo']['name']) AND $_FILES['photo']['name'] != "") {
			try	{
				$stmt = $auth_user->runQuery("SELECT photo FROM users WHERE login_id=:loginID");
				$stmt->execute(array(':loginID'=>$loginID));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
			}catch(PDOException $e) {
				echo $e->getMessage();
			}

			$toDelete = ROOT_PATH.str_replace('../', '', $row['photo']);
			if(file_exists($toDelete)){
				unlink($toDelete);
			}

			$target_path = "../user/img/";
			$validextensions = array("jpeg", "jpg", "png");
			$ext = explode('.', basename($_FILES['photo']['name'])); 
			$file_extension = end($ext); 
			$target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
			if (($_FILES["photo"]["size"] < 300000)
			&& in_array($file_extension, $validextensions)) {
				if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
					//
					$stmt = $auth_user->runQuery("SELECT credibility_score, photo FROM users WHERE login_id=:loginID");
					$stmt->execute(array(':loginID'=>$loginID));
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					if($row["photo"] == ""){						
						$newScore = $row["credibility_score"] + 10;
					}else{						
						$newScore = $row["credibility_score"];
					}
					// Insert into IMAGES table
					$stmt = $auth_user->runQuery("UPDATE `users` SET `photo`=:photo, `credibility_score`=:newScore, `last_updated`=now() WHERE `login_id`=:loginID");
					$stmt->execute(array(':photo'=>$target_path, ':newScore'=>$newScore, ':loginID'=>$loginID));
					$auth_user->redirect(BASE_URL.'user/profile?updated');

				} else {     //  If File Was Not Moved.
					$error[] = '). please try again!.<br/>';
				}
			} else {     //   If File Size And File Type Was Incorrect.
				$error[] = '). ***Invalid file Size or Type***<br/>';
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
                            <small>Dashboard / My Account / Account Details</small>
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
					elseif(isset($_GET['updated'])){
						 ?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Profile Successfully Updated!
						 </div>
						 <?php
					}
				?>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Account Details</h1>
                        <h4>Once updated, information cannot be changed without contacting support</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
                <div class="row">
                <div class="col-md-8">
                    <form role="form" method="post" action="" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12">
								<b>Full Name:</b> <?php if(isset($userInfo["full_name"])){echo $userInfo["full_name"]; } ?>
							  <br><br>
						  </div> 						  
							<div class="col-md-12">
								<b>Country:</b> <?php if(isset($userInfo["country"])){echo $userInfo["country"]; } ?>
								<br><br>
							</div> 
							<div class="col-md-12">
							  <div class="row">
									<div class="col-md-6">
										<div class="form-group">
										  <label for="phone">Phone Number</label>
										  <input type="text" class="form-control" name="phone" 
										  id="phone" value="<?php if(isset($userInfo["phone"])){echo $userInfo["phone"]; } ?>">
										</div>
									</div>
							  </div>
							</div>
						  <div class="col-md-12">
							<div class="row">
									<div class="col-md-6">
										<div class="form-group">
										  <label for="email">Email Address</label>
										  <input type="text" class="form-control" name="email" id="email" 
										  value="<?php if(isset($userInfo["email"])){echo $userInfo["email"]; } ?>">
										</div>
									</div> 
							</div>
						  </div>
					  </div>
						<button type="submit" class="btn btn-success btn-small">Update</button>
					</form>
            	</div>
            	<div class="col-md-4">
                 	<?php if(isset($userInfo['photo']) AND $userInfo['photo'] != ""){?>
                 		<img src="<?php echo BASE_URL.str_replace('../', '', $userInfo['photo']);?>" width="200" height="200" alt="Profile Picture"/>
					<?php }else{?>
                  		<img src="<?php echo BASE_URL;?>img/no_profile.png" width="200" height="200" alt=""/>
                  	<?php }?>
                  	<form role="form" method="post" action="" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								  <label for="photo">Upload/Change Profile Picture</label>
								  <input type="file" class="form-control" name="photo" id="photo">
								</div>
							</div>
					 	</div>
						<button type="submit" name="uploadBtn" class="btn btn-success btn-small">Upload</button>
						<p>Recommended size : 200px * 200px </p>
					</form>
                </div>
            
            	
        </div>
                    
   </div>
<!-- /. ROW  -->
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>