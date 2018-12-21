<?php 
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");

    $writeTestimonies = $auth_user->writeTestimonies($loginID);
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
            <div class="col-md-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                    You are required to Write or Modify Testimony to continue
                </div> 
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <tbody>
                        <?php 
                            if (!empty($writeTestimonies)) {
                                foreach($writeTestimonies as $write) {
                        $testEdit = $auth_user->testimonyEdit($loginID, $write["match_id"]);?> 

                            <tr>
                            <?php if($testEdit["pay_id"] == $write["match_id"]){?>
                                <td><a href="<?php echo BASE_URL.'user/write-testimony?pid='.$write["match_id"];?>" class="btn btn-success btn-small">
                                <i class="fa fa-pencil-square-o"></i> Modify Testimony</a></td>
                                <td>
                                    <?php if($testEdit["status"] == "Approved"){?>
                                        <span style="color:green; font-size:18px;">Approved</span>
                                    <?php }else{?>
                                        <span style="color:red; font-size:18px;">Pending Approval</span>
                                    <?php }?>
                                </td>

                            <?php }else{?>
                                <td><a href="<?php echo BASE_URL.'user/write-testimony?pid='.$write["match_id"];?>" class="btn btn-success btn-small">
                                <i class="fa fa-pencil-square-o"></i> Write Testimony</a></td>
                                <td></td>
                            <?php }?>
                            </tr>
                        <?php }}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- /. ROW  -->
    <br> <br> <br> <br> <br> <br> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#addList").click(function(){
        $(".hiddenWraper").show(300);
    });
});
</script>
<?php include(ROOT_PATH."user/includes/footer.php") ?>