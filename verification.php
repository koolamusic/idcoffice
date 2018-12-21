<?php 
	require("includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");

	$auth_user = new USER();

	if(isset($_GET['true']) AND $_GET['true'] != ""){
		$userID = intval($_GET['true']);
		try	{
			$stmt = $auth_user->runQuery("UPDATE users SET status='Active' 
				WHERE login_id=:userID LIMIT 1");
			$stmt->execute(array(":userID"=>$userID));	
			$auth_user->redirect(BASE_URL.'verification?verified');
			exit();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
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
			<span style="font-size: 28px;">	Email Verification</span><br>
			<span>Secure access portal</span>
		</div>
		<div style="background: #FFF; padding: 50px 20px 20px; border-radius: 5px;">
			<?php
				if(isset($_GET["verified"])){?>
					<div class="alert alert-success" style="font-size:20px;">
					   <b>Great!</b> &nbsp; Your account verified successfully!
					</div>
					<div align="center">						
					   <a style="font-size:18px;" class="btn btn-default" href="<?php echo BASE_URL;?>login">Sign in</a>
					</div>
			<?php }else{?>
					<div class="alert alert-danger">
					   <i class="fa fa-exclamation-triangle"></i> &nbsp; Account verification failed, please try again!
					</div>
			<?php }?>

		</div>
		<br><br><br><br><br>
	</div>
</div>