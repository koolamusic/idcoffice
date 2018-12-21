<?php 
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");

	$auth_user = new ADMIN();

	//Site settings
	$stmt = $auth_user->runQuery("SELECT * FROM website_settings WHERE id='1'");
	$stmt->execute();
	$siteInfo = $stmt->fetch(PDO::FETCH_ASSOC);

	if($auth_user->is_loggedin() != ""){
		$auth_user->redirect(BASE_URL.'administrator');
	}

	if(isset($_POST['loginBtn'])){
		$username = strip_tags($_POST['userID']);
		$password = strip_tags($_POST['password']);
		
		if($auth_user->doLogin($username, $password)){
			$auth_user->redirect(BASE_URL.'administrator');
		}else{
			$error = "Email Address or Password does not match, please try again!";
		}	
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php if(isset($siteInfo['site_name'])){echo $siteInfo['site_title'].' | '.$siteInfo['site_name'];}else{echo 'Welcome';}?></title>

<?php if(isset($siteInfo['favicon_url'])){ 
    $faviconURL = BASE_URL.str_replace('../', '', $siteInfo['favicon_url']);?>
<link rel='shortcut icon' href='<?php echo $faviconURL;?>' type='image/x-icon'/ >
<?php }?>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="" />
<meta name="author" content="http://creativeweb.com.ng" />
<!-- css -->
<link href="<?php echo BASE_URL;?>css/bootstrap.min.css" rel="stylesheet" />
<link href="<?php echo BASE_URL;?>css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="<?php echo BASE_URL;?>css/jcarousel.css" rel="stylesheet" />
<link href="<?php echo BASE_URL;?>css/flexslider.css" rel="stylesheet" />
<link href="<?php echo BASE_URL;?>js/owl-carousel/owl.carousel.css" rel="stylesheet">
<link href="<?php echo BASE_URL;?>css/style.css" rel="stylesheet" />
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

</head>

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
			<span style="font-size: 28px;">Adminstrators only</span><br>
			<span>Secure admin access</span>
		</div>
		<div style="background: #FFF; padding: 50px 20px 20px; border-radius: 5px;">
			<?php
				if(isset($error))
				{
					?>
					<div class="alert alert-danger">
					   <i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?> !
					</div>
					<?php
				}
			?>
			<form id="contact-form" method="post" action="" role="form" novalidate>
				<div class="form-group has-feedback">
					<label for="email">Email Address*</label>
					<input type="text" class="form-control" id="userID" name="userID" required
					placeholder="Enter your Email Or Username">
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
		
	</div>
</div>
<?php include(ROOT_PATH."includes/footer.php"); ?>	