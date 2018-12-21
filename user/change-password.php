<?php 
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
	require_once(ROOT_PATH . "user/includes/agree.php");

	if(isset($_POST['currentPass'])){
		$currentPass = strip_tags($_POST['currentPass']);
		$password = strip_tags($_POST['password']);	
		$confirmPass = strip_tags($_POST['confirmPass']);	
		
		//Php Validation
		if($currentPass == "")	{
			$error[] = "Please enter your current Password!";	
		}	
		else if($password == "")	{
			$error[] = "Please enter new Password!";
		}
		else if($confirmPass == "")	{
			$error[] = "Please confirm new Password!";
		}
		else if(strlen($password) < 6){
			$error[] = "New Password must be atleast 6 characters";	
		}
		else if($confirmPass != $password)	{
			$error[] = "New Password does not match, please try again!";
		}
		else{
			try	{
				$new_password = password_hash($password, PASSWORD_DEFAULT);
				
				$stmt = $auth_user->runQuery("SELECT password FROM users WHERE login_id=:loginID");
				$stmt->execute(array(':loginID'=>$loginID));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				
				if(password_verify($currentPass, $row['password'])) {
					$stmt = $auth_user->runQuery("UPDATE `users` SET `password`=:password, `last_updated`=now() 
							WHERE `login_id`=:loginID");
					$stmt->execute(array(':password'=>$new_password, ':loginID'=>$loginID));
					$auth_user->redirect(BASE_URL.'user/profile?updated');
				}else{
					$error[] = "Current Password does not match record, please try again!";
				}
			}
			catch(PDOException $e) {
				echo $e->getMessage();
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
                            <small>Dashboard / My Account / Change Password</small>
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
                        <h1>Change Password</h1>
                        <h4>Fill the form below to change your password</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
                <div class="row">
                <div class="col-md-6">
                    <form role="form" method="post" action="" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
								  <label for="currentPass">Current Password</label>
								  <input type="password" class="form-control" name="currentPass" id="currentPass">
								</div>
							</div> 
							<div class="col-md-12">
								<div class="form-group">
								  <label for="password">New Password</label>
								  <input type="password" class="form-control" name="password" id="password">
								</div>
							</div> 
							<div class="col-md-12">
								<div class="form-group">
								  <label for="confirmPass">Confirm Password</label>
								  <input type="password" class="form-control" name="confirmPass" id="confirmPass">
								</div>
							</div> 
					  </div>
						<button type="submit" class="btn btn-success btn-small">Update Password</button>
					</form>
            	</div>
            	
            	
        </div>
      </div>
                <!-- /. ROW  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#addList").click(function(){
        $(".hiddenWraper").show(300);
    });
});
</script>
<?php include(ROOT_PATH."user/includes/footer.php") ?>