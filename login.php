<?php 
	require("includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");

	$auth_user = new USER();

	//include updator
	include(ROOT_PATH . "core/updator.php");

	if($auth_user->is_loggedin() != ""){
		$auth_user->redirect(BASE_URL.'user');
	}

	if(isset($_POST['loginBtn'])){
		$userID = strip_tags($_POST['userID']);
		$password = strip_tags($_POST['password']);
		
		//check if USER login (ID) is phone Number
		$userid = str_replace('+', '', $userID);
		$userPhone = preg_replace('#[^0-9.]#', '', $userid);
		if($userPhone == $userid) {
			//Its phone, now let see if its has default 234
			$part = substr($userPhone,0,3);
			if($part == "234"){
				$userLoginID = $userPhone;
			}else{
				$firt = substr($userPhone,0,1);
				if($firt == "0") {
					$userLoginID = preg_replace("/^".$firt."/", "234", $userPhone);
				}else{
					$userLoginID = "234".$userPhone;
				}
			}
		}else{
			$userLoginID = $userID;
		}
		
		if($auth_user->doLogin($userLoginID, $password)){
			$auth_user->redirect(BASE_URL.'user');
		}
		else{
			$error[] = "Username, Email or Password does not match, Or account not active!";
		}	
	}
?>
<?php include(ROOT_PATH."includes/header2.php"); ?>
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
			<span style="font-size: 28px;">Sign In</span><br>
			<span>Secure access portal</span>
		</div>
		<div style="background: #FFF; padding: 50px 20px 20px; border-radius: 5px;">
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
				elseif(isset($_GET['joined'])){
					 ?>
					 
					 <div class="alert alert-success">
						  <i class="fa fa-check-square"></i> &nbsp; Your account is successfully created!<br> Please check your email to verify your account and get started!
					 </div>

					 <?php
				}
				?>
			<form id="contact-form" method="post" action="" role="form" novalidate>
				<div class="form-group has-feedback">
					<label for="email">Email Address*</label>
					<input type="text" class="form-control" id="userID" name="userID" required
					placeholder="Enter your Email Or Phone Number">
					<i class="fa fa-envelope form-control-feedback"></i>
				</div>
				<div class="form-group has-feedback">
					<label for="password">Password*</label>
					<input type="password" class="form-control" id="password" name="password" required placeholder="Password">
					<i class="fa fa-lock form-control-feedback"></i>
				</div>
				<br>
				<input type="submit" style="width: 100%; padding: 20px;border-radius: 5px;" 
				value="Login" name="loginBtn" class="btn btn-default">
			</form>
		</div>
		<div class="row">
			<div class="col-md-12" align="center">
				<span style="font-size: 12px;">
				<a style="color: #666;" href="register">
				 <i class="fa fa-user"></i> New User? Register</a></span>
			</div>
			<div class="col-md-12" align="center">
				<span style="font-size: 12px;">
				<a style="color: #666;" href="password-reset">
				<i class="fa fa-lock"></i> Forgot Password</a></span>
			</div>
		</div>		
	</div>
</div>
<?php include(ROOT_PATH."includes/footer.php"); ?>	