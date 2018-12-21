<?php 
  require("../includes/config.php"); 
  require_once(ROOT_PATH . "core/class.admin.php");
  require_once(ROOT_PATH . "core/adminSession.php");
	//Update Account
	if(isset($_POST["username"])) {
    $username = strip_tags($_POST['username']);
    $firstName = strip_tags($_POST['firstName']);
    $lastName = strip_tags($_POST['lastName']);
    $email = strip_tags($_POST['email']);

		//Validate
		if($username == "" || $firstName == "" || $lastName == "" || $email == "") {
			$errormessage = "All fileds are required, please try it again!";
		}
		if(!isset($errormessage)) {
      try {
  			//Update 
        $stmt = $auth_user->runQuery("UPDATE admin 
            SET username=:username, 
                email=:email, 
                first_name=:firstName, 
                last_name=:lastName, 
                updated_date=now()
            WHERE id=:adminID");

          $stmt->execute(array(':username'=>$username, ':email'=>$email, ':firstName'=>$firstName, ':lastName'=>$lastName, ':adminID'=>$userInfo["id"]));
  			
        $auth_user->redirect(BASE_URL.'administrator/settings/?status=updated');
  			exit();
      }      
      catch(PDOException $e) {
        echo $e->getMessage();
      }
		}
	}
	
  //
	if(isset($_POST['currentPass'])){
    $currentPass = strip_tags($_POST['currentPass']);
    $password = strip_tags($_POST['newPass']); 
    $confirmPass = strip_tags($_POST['confirmPass']); 
    
    //Php Validation
    if($currentPass == "")  {
      $errormessage = "Please enter your current Password!"; 
    } 
    else if($password == "")  {
      $errormessage = "Please enter new Password!";
    }
    else if($confirmPass == "") {
      $errormessage = "Please confirm new Password!";
    }
    else if(strlen($password) < 6){
      $errormessage = "New Password must be atleast 6 characters"; 
    }
    else if($confirmPass != $password)  {
      $errormessage = "New Password does not match, please try again!";
    }
    else{
      try {
        $new_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $auth_user->runQuery("SELECT password FROM admin WHERE id=:adminID");
        $stmt->execute(array(':adminID'=>$userInfo["id"]));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(password_verify($currentPass, $row['password'])) {
          $stmt = $auth_user->runQuery("UPDATE admin SET password=:password, updated_date=now() 
              WHERE id=:adminID");

          $stmt->execute(array(':password'=>$new_password, ':adminID'=>$userInfo["id"]));
          $auth_user->redirect(BASE_URL.'administrator/settings/?status=changed');
          exit();
        }else{
          $errormessage = "Current Password does not match record, please try again!";
        }
      }
      catch(PDOException $e) {
        echo $e->getMessage();
      }
    } 
  }

?>
<?php
	$section = "table"; 
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>      
          <div id="page-wrapper" >
            <div id="page-inner">
			 <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                             <small>Admin Acoount Settings </small>
                        </h1>
                    </div>
</div> <!-- /. ROW  -->
                <?php if(isset($errormessage)) {?>
                     <div class="alert alert-danger">
                        <strong>Oh snap!</strong> <?php echo $errormessage; ?>
                     </div>
                 <?php } ?>
                 <?php if(isset($_GET["status"]) AND $_GET["status"] == "updated") {?>
                     <div class="alert alert-success">
                        <p><strong>Success: </strong> Your profile is updated successfully!</p>
                     </div>
                 <?php } ?>
                 <?php if(isset($_GET["status"]) AND $_GET["status"] == "changed") {?>
                     <div class="alert alert-success">
                        <strong>Success: </strong> Password is changed successfully!
                     </div>
                 <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
<div class="panel-heading">
                             Update Account Information
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                            	<div class="row">
                                	<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="username">Username</label>
                                          <input type="text" class="form-control" name="username" 
                                          id="username" value="<?php if(isset($username)){ 
											echo $username;}else{echo $userInfo["username"];}?>">
                                        </div>
                                    </div>                                    
                                	<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="email">Email Address</label>
                                          <input type="email" class="form-control" name="email" id="email"
                                            value="<?php if(isset($email)){ 
											echo $email;}else{echo $userInfo["email"];}?>">
                                        </div>
                                    </div>
                					<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="firstName">First Name</label>
                                          <input type="text" class="form-control" name="firstName" 
                                          id="firstName" value="<?php if(isset($firstName)){
											echo $firstName;}else{echo $userInfo["first_name"];}?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="lastName">Last Name</label>
                                          <input type="text" class="form-control" name="lastName" 
                                          id="lastName" value="<?php if(isset($lastName)){ 
											echo $lastName;}else{echo $userInfo["last_name"];}?>">
                                        </div>
                                    </div>
								</div>
							<button type="submit" class="btn btn-success btn-small">Update</button>
                            </form>
                            
                            <br><br>
                            <h3>Change Password</h3>
                            <br>
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                            	<div class="row">
                                	<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="currentPass">Current Password</label>
                                          <input type="password" class="form-control" name="currentPass" id="currentPass">
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>                                   
                                	<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="newPass">New Password</label>
                                          <input type="password" class="form-control" name="newPass" 
                                          id="newPass">
                                        </div>
                                    </div>
                					<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="confirmPass">Confirm Password</label>
                                          <input type="password" class="form-control" name="confirmPass" 
                                          id="confirmPass">
                                        </div>
                                    </div>
								</div>
							             <button type="submit" class="btn btn-success btn-small">
                            Change Password</button>
                            </form>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>