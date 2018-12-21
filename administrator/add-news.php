<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");
	
	/// Add News	
	if(isset($_POST['note'])) {				 
		$note = strip_tags($_POST['note']);
		if($note == ""){
			$errorMSG = 'You can not Post empty news';
		}
		if(!isset($errorMSG)){
			// Insert into admin notes
			try	{
				$stmt = $auth_user->runQuery("INSERT INTO news (admin, note, date_added)
						VALUES (:username, :note, now())");
				
				$stmt->execute(array(':username'=>$userInfo["username"], ':note'=>$note));
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
			$auth_user->redirect(BASE_URL.'administrator/add-news?posted');
			exit();
		}
	}
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Add News</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                <?php
					if(isset($_GET["posted"])){?>
						 <div class="alert alert-success">
							 &nbsp; News successfully posted!
						 </div>
				<?php }?>
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Add News 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form role="form" method="post" action="" style="width: 90%; margin: auto; margin-top: 30px;">
								<div class="form-group">
								  <textarea class="form-control" name="note" id="note" 
									rows="7"></textarea>
								</div>
								<div style="width: 20%; margin: auto;">
									<button style="width: 100%;" type="submit" class="btn btn-success btn-lg">
									Post News</button>
								</div>
							</form>
                        </div>
                        <br><br><br><br>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
                    
	</div>
	<!-- /. ROW  -->
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>