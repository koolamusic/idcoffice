<?php
	require("../includes/config.php"); 
    require_once(ROOT_PATH . "core/class.user.php");
    require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
	
	//Grab news id from URL query
	if(isset($_GET["id"]) AND $_GET["id"] != ""){
		$id = intval($_GET["id"]);
		$senderID = intval($_GET["sender"]);
		try	{
			$stmt = $auth_user->runQuery("UPDATE messaging SET status='Read' 
				WHERE id=:id");
			$stmt->execute(array(':id'=>$id));
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}else{
		$auth_user->redirect(BASE_URL.'user/supports');
		exit();
	}
	$conversations = $auth_user->msgDetails($id);


	/// Add 	
	if(isset($_POST['message'])) {				 
		$message = strip_tags($_POST['message']);
		if($message == ""){
			$errorMSG = 'You can not send empty message!';
		}
		if(!isset($errorMSG)){
			// Insert into admin notes
			try	{
				$stmt = $auth_user->runQuery("INSERT INTO messaging (sender_name, message, reciever_id, status, date_sent)
						VALUES (:userName, :message, :loginID, 'Unread', now())");
				
				$stmt->execute(array(':loginID'=>$loginID, ':userName'=>$userInfo['username'], ':message'=>$message));
				$msgID = $auth_user->lastInsertId();
				
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
				$auth_user->redirect(BASE_URL.'user/support-details?id='.$id.'&sender='.$senderID.'&thanks');
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
                            <small>Dashboard / Message</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Conversation
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" 
                                id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th width="20%">Sender</th>
                                            <th width="60%">Message Body</th>
                                            <th width="20%">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px; font-weight: 800;">
                                    <?php 
                                            if (!empty($conversations)) {
                                                foreach($conversations as $conva) {?>
						<tr>
                            <td><?php echo $conva["sender_name"];?></td>
                            <td><p><?php echo nl2br($conva["message"]);?>
                            	<?php if($conva["file"] != ''){?>
                            	<br><a target="_blank" href="<?php echo $conva["file"];?>"> View Attachment</a>
                            	<?php }?>
                            </p> </td>
                            <td><?php echo timeAgo($conva["date_sent"]);?></td>
                        </tr>
                                        <?php }}?>
                                    </tbody>
                                </table>
                        </div>
                        <form role="form" method="post" action="" style="width: 90%; margin: auto; margin-top: 30px;" enctype="multipart/form-data">
						<div class="form-group">
						  <textarea class="form-control" name="message" id="message" placeholder="Reply message..." 
							rows="7"></textarea>
						</div>
						<div class="form-group">
						  <input type="file" name="upload">
						</div>
						<div style="width: 20%; margin: auto;">
							<button style="width: 100%;" type="submit" class="btn btn-success btn-sm">
							Send Message</button>
						</div>
					</form>
                        <br><br><br><br>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
                    
	</div>
	<!-- /. ROW  -->
<?php include(ROOT_PATH."user/includes/footer.php") ?>