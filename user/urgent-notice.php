<?php 
	require("../includes/config.php"); 
  require_once(ROOT_PATH . "core/class.user.php");
  require_once(ROOT_PATH . "core/session.php");

  if(isset($_POST['agree'])) {
    $_SESSION['agreed'] = 'Agreed';
    $auth_user->redirect(BASE_URL.'user/');
    exit();
  }
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Urgent Message</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Urgent Message</h1>
                        <h4 style="color:#AB0F12;">PLEASE READ EVERY LINE CAREFULLY!! </h4>
                        <p><?php echo nl2br($notice['note']);?></p>
                   		
                   		<br>
                   		<br>
                      <form action="" method="post">
                        <button class="btn btn-success btn-lg" type="submit" name="agree">
                        YES, I AGREE</button>
                      </form>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
                    
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