<?php 
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
	require_once(ROOT_PATH . "user/includes/agree.php");

	/// Add News	
	if(isset($_POST['message'])) {				 
		$message = strip_tags($_POST['message']);
		$subject = strip_tags($_POST['subject']);
		if($message == ""){
			$errorMSG = 'You can not send empty message!';
		}if($subject == ""){
			$errorMSG = 'Please enter subject!';
		}
		if(!isset($errorMSG)){
			// Insert into admin notes
			try	{
				$stmt = $auth_user->runQuery("INSERT INTO messaging (sender_id, sender_name,   subject, message, status, date_sent)
						VALUES (:senderID, :senderName, :subject, :message, 'Unread', now())");
				
				$stmt->execute(array(':senderID'=>$loginID, ':senderName'=>$userInfo["username"], ':subject'=>$subject, ':message'=>$message));
				$msgID = $auth_user->lastInsertId();

				///
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
				
				$auth_user->redirect(BASE_URL.'user/contact-us?thanks');
				exit();
				
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
                            <small>Dashboard / Contact Us</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                 <?php
					if(isset($_GET["thanks"])){?>
						 <div class="alert alert-success">
							 &nbsp; Message sent!
						 </div>
				<?php }?>
               <?php
					if(isset($errorMSG)){?>
						 <div class="alert alert-danger">
							 &nbsp; <?php echo $errorMSG; ?>
						 </div>
				<?php }?>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Contact Support</h1>
                        <p>For Fast resolution to your issues / questions, please use the form below to contact us;<br> 
                        </p>
                   		
                   		<br>
                    </div>
                    <form role="form" method="post" action="" style="width: 90%; margin: auto; margin-top: 30px;" enctype="multipart/form-data">
						<div class="form-group">
						  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject">
						</div>
						<div class="form-group">
						  <textarea class="form-control" name="message" id="message" placeholder="Write message..." 
							rows="7"></textarea>
						</div>
						<div class="form-group">
						  <input type="file" name="upload" class="form-control">
						</div>
						<div style="width: 20%; margin: auto;">
							<button style="width: 100%;" type="submit" class="btn btn-success btn-lg">
							Send Message</button>
						</div>
					</form>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
                    
                </div>
                <!-- /. ROW  -->
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<?php include(ROOT_PATH."user/includes/footer.php") ?>