<?php 
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");

    if(isset($_GET['pid'])){
        $matchID = intval($_GET['pid']);
    }else{
        $auth_user->redirect(BASE_URL.'user/payment-testimonies');
        exit();
    }

    try {
        //Grab amount from match_donation
        $stmt = $auth_user->runQuery("SELECT * FROM match_donations WHERE match_id=:matchID");
        $stmt->execute(array(':matchID'=>$matchID));
        $rowFound = $stmt->fetch(PDO::FETCH_ASSOC);

         //Grab message for modification
        $stmt = $auth_user->runQuery("SELECT * FROM testimonies WHERE pay_id=:matchID");
        $stmt->execute(array(':matchID'=>$matchID));
        $updateTmny = $stmt->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $e) {
        echo $e->getMessage();
    }

	
	if(isset($_POST['msg'])){
		$msg = strip_tags($_POST['msg']);
		
		//Php Validation
		if($msg == "")	{
			$error[] = "You can not submit an empty form!";	
		}	
		else if(strlen($msg) < 50)	{
			$error[] = "You must enter at least 50 character!";
		}
		else{
			try	{
                //
                if(!isset($updateTmny['message']) OR $updateTmny['message'] == ""){
    				$stmt = $auth_user->runQuery("INSERT INTO testimonies (login_id, pay_id, member, amount, message, status, date_added)

    				VALUES(:loginID, :matchID, :username, :amount, :msg, 'Pending', now())");

    				$stmt->execute(array(':loginID'=>$loginID, ':matchID'=>$matchID, ':username'=>$userInfo["username"], ':amount'=>$rowFound["m_amount"], ':msg'=>$msg));
                }else{                    
                    $stmt = $auth_user->runQuery("UPDATE testimonies 
                        SET message=:msg, status='Pending', date_added=now()
                        WHERE pay_id=:matchID LIMIT 1");

                    $stmt->execute(array(':matchID'=>$matchID, ':msg'=>$msg));
                }
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
			$auth_user->redirect(BASE_URL.'user/payment-testimonies?testified');
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
                            <small>Dashboard / Receive Help / Write Testimony</small>
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
					elseif(isset($_GET['testified'])){
						 ?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; <strong>Thanks you!</strong> Your testimony have been submitted successfully!
						 </div>
						 <?php
					}
				?>
                
             <?php if(isset($_GET["payID"])){?>
             		
             <?php }?>
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Write Testimony  
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                      <textarea class="form-control" name="msg" id="msg" rows="7" placeholder="Start writing..."
                                      ><?php if(isset($msg)){echo $msg;}else{echo $updateTmny['message'];} ?></textarea>
                                    </div>
                                </div> 
                            </div>
                          </div>
                      </div>
                        <button type="submit" class="btn btn-success btn-small">Submit</button>
                        <br><br><br><br>
                    </form>
                        </div>
                    <!--End Advanced Tables -->
                </div>
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