<?php 
	require("includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");

	$auth_user = new USER();

	//include updator
	include(ROOT_PATH . "core/updator.php");

	if($auth_user->is_loggedin()!=""){
		$auth_user->redirect(BASE_URL.'user');
	}

	if(isset($_POST['register'])){
		$username = strip_tags($_POST['username']);
		$fullname = strip_tags($_POST['fullname']);
		$email = strip_tags($_POST['email']);	
		$emailCobfirm = strip_tags($_POST['emailCobfirm']);
		$phn = strip_tags($_POST['phone']);
		$password = strip_tags($_POST['password']);
		$passwordConfirm = strip_tags($_POST['passwordConfirm']);
		$referrer = strip_tags($_POST['referrer']);
		$country = strip_tags($_POST['country']);
		
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
		if($username == "")	{
			$error[] = "Please choose a username!";	
		}elseif(strlen($username) < 5 ){
			$error[] = "Username must be at least 5 charaters!";	
		}elseif($fullname == "")	{
			$error[] = "Your Full Name is required!";	
		}else if($email == "")	{
			$error[] = "Your Email Address is required!";	
		}
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL))	{
			$error[] = 'Please enter a valid email address !';
		}
		else if($emailCobfirm == "")	{
			$error[] = "Please confirm your email address !";
		}
		else if($emailCobfirm != $email)	{
			$error[] = "Email Address does not match, please check and re-enter!";
		}
		else if($phone == "")	{
			$error[] = "Please enter your Phone Number!";
		}
		else if(strlen($password) < 6){
			$error[] = "Password must be atleast 6 characters";	
		}
		else if($passwordConfirm != $password)	{
			$error[] = "Password does not match, please try again!";
		}		
		else if($country == "")	{
			$error[] = "Please select your Country!";
		}
		else{
			try	{
				$stmt = $auth_user->runQuery("SELECT email, username, phone FROM users 
					WHERE email=:email OR phone=:phone OR username=:username");
				$stmt->execute(array(':email'=>$email,':phone'=>$phone,':username'=>$username));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if($row['email'] == $email) {
					$error[] = "Sorry, Email Address already in use in the system!";
				}
				else if($row['username'] == $username) {
					$error[] = "Sorry, Username already in use in the system!";
				}			
				else if($row['phone'] == $phone) {
					$error[] = "Sorry, Phone Number already in use in the system!";
				}
				else{
					if($auth_user->register($username, $fullname, $email, $phone, $password, $referrer, $country)){	
						$auth_user->redirect(BASE_URL.'login?joined');
						exit();
					}
				}
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
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
			<span style="font-size: 28px;">Registration</span><br>
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
						  <i class="fa fa-check-square"></i> &nbsp; Successfully registered 
						  <a href='<?php echo BASE_URL; ?>login'>login</a> here
					 </div>
					 <?php
				}
				?>
			<form id="contact-form" method="post" action="" role="form" novalidate>
				<div class="form-group has-feedback">
					<label for="username">Username*</label>
					<input type="text" class="form-control" id="username" name="username" required 
					placeholder="Choose Username" value="<?php if(isset($username)){echo $username;}?>" >
					<i class="fa fa-user form-control-feedback"></i>
				</div>
				<div class="form-group has-feedback">
					<label for="fullname">Full Name*</label>
					<input type="text" class="form-control" id="fullname" name="fullname" required 
					placeholder="Full Name..." value="<?php if(isset($fullname)){echo $fullname;}?>" >
					<i class="fa fa-user form-control-feedback"></i>
				</div>
				<div class="form-group has-feedback">
					<label for="email">Email Address*</label>
					<input type="email" class="form-control" id="email" name="email" required placeholder="Email"
					value="<?php if(isset($email)){echo $email;}?>">
					<i class="fa fa-envelope form-control-feedback"></i>
				</div>

				<div class="form-group has-feedback">
					<label for="emailCobfirm">Confirm Email Address*</label>
					<input type="email" class="form-control" id="emailCobfirm" required name="emailCobfirm" 
					placeholder="Confirm Email" value="<?php if(isset($emailCobfirm)){echo $emailCobfirm;}?>">
					<i class="fa fa-envelope form-control-feedback"></i>
				</div>
				<div class="form-group has-feedback">
					<label for="phone">Phone Number*</label>
					<input type="text" class="form-control" id="phone" name="phone" required 
					placeholder="Phone Number (include country code)" value="<?php if(isset($phone)){echo $phone;}?>">
					<i class="fa fa-phone-square form-control-feedback"></i>
				</div>
				<div class="form-group has-feedback">
					<label for="password">Password*</label>
					<input type="password" class="form-control" id="password" name="password" required 
					placeholder="Password" value="<?php if(isset($password)){echo $password;}?>">
					<i class="fa fa-key form-control-feedback"></i>
				</div>
				<div class="form-group has-feedback">
					<label for="passwordConfirm">Confirm Password*</label>
					<input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" required
					 placeholder="Confirm Password" value="<?php if(isset($passwordConfirm)){echo $passwordConfirm;}?>">
					<i class="fa fa-key form-control-feedback"></i>
				</div>
				<div class="form-group has-feedback">
					<label for="referrer">Referrer Email <em>(Optional)</em></label>
					<input type="text" class="form-control" id="referrer" name="referrer" placeholder="Referrer"
					value="<?php if(isset($referrer)){echo $referrer;}elseif(isset($_SESSION['referral'])){echo $_SESSION['referral'];}?>">
					<i class="fa fa-briefcase form-control-feedback"></i>
				</div>
				<div class="form-group has-feedback">
					<label for="password">Country*</label>
					<select class="form-control" name="country" id="country" required>
						<?php if(isset($country)){ ?>
						<option value="<?php echo $country; ?>"><?php echo $country;?></option>
						<?php }else{?>
						<option value="">Select Country</option>
						<?php }?>
						<?php include(ROOT_PATH."includes/countries.php");?>
					</select>
				</div>
				<br>
				<input type="submit" style="width: 100%; padding: 20px;border-radius: 5px;" 
				 name="register" value="Register" class="btn btn-default">
			</form>
		</div>
		<div class="row">
			<div class="col-md-12">
				<span style="font-size: 12px; padding-left: 10px;">
				Already Have account? <a style="color: #666;" href="login"><i class="fa fa-lock"></i> Login</a></span>
			</div>
		</div>		
	</div>
</div>
<?php include(ROOT_PATH."includes/footer.php"); ?>	