<?php 
	require("includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");

	$auth_user = new USER();

	//Grab email from the form and checks if exist in the system
	if(isset($_POST['email'])){
		$email = strip_tags($_POST['email']);
		if($email == ""){
			$error[] = 'Please enter a valid Email Address!';
		}else{
			try	{
				$stmt = $auth_user->runQuery("SELECT * FROM users 
					WHERE email=:email OR username=:email");
				$stmt->execute(array(':email'=>$email));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if($row['email'] == $email OR $row['username'] == $email){
					//Send password reset email
					include(ROOT_PATH . "emailTemplates/reset.php");
					$auth_user->redirect(BASE_URL.'password-reset?link-sent');
					exit();
				}else{
					$error[] = 'Opps! Email or Username does not exist in the system!';
				}
			}catch(PDOException $e) {
				echo $e->getMessage();
			}
		}	
	}

	//Grab login ID from verification link and check if exist in the system
	if(isset($_GET['true']) AND $_GET['true'] != ""){
		$loginID = intval($_GET['true']);
		try	{
			$stmt = $auth_user->runQuery("SELECT * FROM users WHERE login_id=:loginID");
			$stmt->execute(array(':loginID'=>$loginID));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if($row['login_id'] == $loginID AND $row['login_id'] != 0){
				$auth_user->redirect(BASE_URL.'password-reset?id='.$loginID.'&change-password');
				exit();
			}else{
				$error[] = 'Opps! Something went wrong, please try again!';
			}
		}catch(PDOException $e) {
			echo $e->getMessage();
		}	
	}


	//Update new password
	if(isset($_POST['loginID'])){
		$loginID = intval($_POST['loginID']);
		$password = strip_tags($_POST['password']);
		$confirmPass = strip_tags($_POST['confirmPass']);
		
		if($password == ""){
			$error[] = 'Please enter new password!';
		}elseif(strlen($password) < 6){
			$error[] = "Password must be at least 6 characters";	
		}elseif($confirmPass != $password){
			$error[] = "Password does not match, please try again!";
		}else{
			try	{
				$new_password = password_hash($password, PASSWORD_DEFAULT);

				$stmt = $auth_user->runQuery("UPDATE users 
					SET password=:password, last_updated=now() 
					WHERE login_id=:loginID");
				$stmt->execute(array(':password'=>$new_password, ':loginID'=>$loginID));
				$auth_user->redirect(BASE_URL.'password-reset?success');
				exit();				
			}catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
	}
?>

<?php include(ROOT_PATH."includes/header2.php"); ?>
<body> 
<style type="text/css">
	.formWrapper {
		width: 40%;
		margin: auto;
	}
	@media (max-width: 767px) {
		.formWrapper {
			width: 70%;
			margin: auto;
		}
	}
	@media (max-width: 480px) {
		.formWrapper {
			width: 90%;
			margin: auto;
		}
	}
</style>

<div style="background: #F8F8F8; border-bottom: 1px solid #CCC; padding: 40px 0px 120px;">
<div class="featured_content" style="margin: 0px;">
	<div class="formWrapper">
		<div align="center" style="margin-bottom: 20px;">
			<?php if(isset($siteInfo['logo_url'])){ 
                $logoURL = BASE_URL.str_replace('../', '', $siteInfo['logo_url']);?>
            <a style="padding-bottom: 20px;" href="<?php echo BASE_URL;?>">
            <img src="<?php echo $logoURL;?>" alt="logo"/></a>
            <?php }else{?>
            <a style="padding-bottom: 20px;" href="<?php echo BASE_URL;?>"><img src="img/logo.png" alt="logo"/></a>
            <?php }?>
		</div>
		<div align="center" style="margin-bottom: 10px;">
			<span style="font-size: 28px;">	Password Reset</span>
		</div>
		<div style="background: #FFF; padding: 50px 20px 20px; border-radius: 5px;">
			<?php if(isset($_GET['success'])){?>
				<div class="alert alert-success">
					<i class="fa fa-check-square"></i> &nbsp; Password reset is successful, 
					<a href="<?php echo BASE_URL;?>login">click to login</a>
				</div>
			<?php }elseif(isset($_GET['change-password'])){?>
			<form id="contact-form" method="post" action="" role="form" novalidate>
				<?php if(isset($error)){
					foreach($error as $error){ ?>
					 <div class="alert alert-danger">
						<i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?>
					 </div>
				<?php } }elseif(isset($_GET['link-sent'])){?>
					 <div class="alert alert-success">
						  <i class="fa fa-check-square"></i> &nbsp; An email has been sent to you with a link to reset your Password.
					 </div>

				<?php } ?>
				<div class="form-group has-feedback">
					<label for="password">New Password*</label>
					<input type="password" class="form-control" id="password" name="password" required placeholder="Enter new password">
					<i class="fa fa-lock form-control-feedback"></i>
					<input type="hidden" name="loginID" value="<?php echo $_GET['id'];?>">
				</div>
				<div class="form-group has-feedback">
					<label for="confirmPass">Confirm Password*</label>
					<input type="password" class="form-control" id="confirmPass" name="confirmPass" required placeholder="Confirm password">
					<i class="fa fa-lock form-control-feedback"></i>
				</div>
				<br>
				<input type="submit" style="width: 100%; padding: 20px;border-radius: 5px;" 
				value="Submit" class="btn btn-success btn-small">
			</form>
			<?php }else{?>
				<form id="contact-form" method="post" action="" role="form" novalidate>
				<?php if(isset($error)){
					foreach($error as $error){ ?>
					 <div class="alert alert-danger">
						<i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?>
					 </div>
				<?php } }elseif(isset($_GET['link-sent'])){?>
					 <div class="alert alert-success">
						  <i class="fa fa-check-square"></i> &nbsp; An email has been sent to you with a link to reset your Password.
					 </div>

				<?php } ?>
				<div class="form-group has-feedback">
					<label for="email">Enter username or Email Address*</label>
					<input type="email" class="form-control" id="email" name="email" required
					placeholder="Enter your Username Or Email">
					<i class="fa fa-user form-control-feedback"></i>
				</div>
				<br>
				<input type="submit" style="width: 100%; padding: 20px;border-radius: 5px;" 
				value="Submit" class="btn btn-success btn-small">
			</form>
			<?php }?>

		</div>
		<br><br><br><br><br>
	</div>
</div>