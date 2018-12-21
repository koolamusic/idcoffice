<?php 
  require("../includes/config.php"); 
  require_once(ROOT_PATH . "core/class.admin.php");
  require_once(ROOT_PATH . "core/adminSession.php");

  $adminLists = $auth_user->adminLists();

	//form data
	if(isset($_POST["role"])) {
		$role = strip_tags( $_POST["role"]);
		$username = strip_tags($_POST["username"]);
		$firstName = strip_tags($_POST["firstName"]);
		$lastName = strip_tags($_POST["lastName"]);
		$email = strip_tags($_POST["email"]);		
		$newPass = $_POST["newPass"];
		$confirmPass = $_POST["confirmPass"];
		$ip = strip_tags(getenv('REMOTE_ADDR')); 
		
		//Validate
		if($username == "" || $firstName == "" || $lastName == "" || $email == "" || $role == "") {
			$errormessage = "All fileds are required, please try it again!";
		}elseif($newPass != $confirmPass) {
			$errormessage = "Your Password does not match.";
		}
		elseif($newPass == "") {
			$errormessage = "Please enter your New Password";
		}
		elseif($confirmPass == "") {
			$errormessage = "Please confirm your new password";
		}else{
      try {
        $stmt = $auth_user->runQuery("SELECT * FROM admin 
          WHERE email=:email OR username=:username");
        $stmt->execute(array(':email'=>$email, ':username'=>$username));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row['email'] == $email) {
          $error[] = "Sorry, Email Address already in use in the system!";
        }
        else if($row['username'] == $username) {
          $error[] = "Sorry, Username already in use in the system!";
        }else{
          $new_password = password_hash($newPass, PASSWORD_DEFAULT);
          
          $stmt = $auth_user->runQuery("INSERT INTO admin (role, username, password, email, first_name, last_name, status, ip,  signup_date) 

          VALUES (:role, :username, :password, :email, :firstName, :lastName, 'Active', :ip, now()) ");

          $stmt->execute(array(':password'=>$new_password, ':role'=>$role, ':username'=>$username, ':email'=>$email, ':firstName'=>$firstName, ':lastName'=>$lastName, ':ip'=>$ip));
          $auth_user->redirect(BASE_URL.'administrator/add-subadmin/?status=added');
          exit();
        }
      }
      catch(PDOException $e) {
        echo $e->getMessage();
      }
		}
	}

  if(isset($_GET["id"])) {
    $id = $_GET["id"];
    try {
      $stmt = $auth_user->runQuery("DELETE FROM admin WHERE id=:id LIMIT 1");
      $stmt->execute(array(':id'=>$id));        
    }
    catch(PDOException $e) {
      echo $e->getMessage();
    }
    $auth_user->redirect(BASE_URL.'administrator/add-subadmin?Deleted');
  }
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
          <div id="page-wrapper" >
            <div id="page-inner">
			 <div class="row">


<div class="col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                 Administrators
              </div> 
              <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Role</th>
                        <th>Username</th>
                        <th>Date Added</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
   <?php 
    if (!empty($adminLists)) {
      foreach($adminLists as $admin) {?> 
    <tr>
      <td><?php echo $admin["role"];?></td>
      <td><?php echo $admin["username"]?></td>
      <td><?php echo timeAgo($admin["signup_date"]);?></td>
      <td><?php if($admin["role"] == "Administrator"){?>
          <span style="">None</span>
        <?php }else{?>
          <a href="?id=<?php echo $admin["id"];?>">Delete</a>
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

</div> <!-- /. ROW  -->
                <?php if(isset($errormessage)) {?>
                     <div class="alert alert-danger">
                        <strong>Oh snap!</strong> <?php echo $errormessage; ?>
                     </div>
                 <?php } ?>
                 <?php if(isset($_GET["status"]) AND $_GET["status"] == "added") {?>
                     <div class="alert alert-success">
                        <strong>Success: </strong> New Admin added successfully!
                     </div>
                 <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
<div class="panel-heading">
                             Add new admin
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                            	<div class="row">
                                	<div class="col-md-4">
                                      <div class="form-group">
                                        <label for="role">User Role</label>
                                          <select required class="form-control" name="role" id="role">
                                          	<option value="">--Select Role---</option>
                                          	<option value="Editor">Editor</option>
                                          	<option value="Accounting">Accounting</option>
                                          </select>
                                        </div>
                                    </div> 
                                    <div style="clear: both;"></div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="username">Username</label>
                                          <input required type="text" class="form-control" name="username" 
                                          id="username" value="<?php if(isset($username)) 
											echo htmlentities($username)?>">
                                        </div>
                                    </div>                                    
                                	<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="email">Email Address</label>
                                          <input required type="email" class="form-control" name="email" id="email"
                                            value="<?php if(isset($email)) 
											echo htmlentities($email)?>">
                                        </div>
                                    </div>
                					<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="firstName">First Name</label>
                                          <input required type="text" class="form-control" name="firstName" 
                                          id="firstName" value="<?php if(isset($firstName)) 
											echo htmlentities($firstName)?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="lastName">Last Name</label>
                                          <input required type="text" class="form-control" name="lastName" 
                                          id="lastName" value="<?php if(isset($lastName)) 
											echo htmlentities($lastName)?>">
                                        </div>
                                    </div>
								</div>
							
                            	<div class="row">
                                    <div style="clear:both;"></div>                                   
                                	<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="newPass">New Password</label>
                                          <input required type="password" class="form-control" name="newPass" 
                                          id="newPass">
                                        </div>
                                    </div>
                					<div class="col-md-6">
                                        <div class="form-group">
                                          <label for="confirmPass">Confirm Password</label>
                                          <input required type="password" class="form-control" name="confirmPass" 
                                          id="confirmPass">
                                        </div>
                                    </div>
								</div>
							<button type="submit" class="btn btn-success btn-lg">Add Admin</button>
                            </form>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>


            
        </div>
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>