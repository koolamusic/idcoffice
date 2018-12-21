<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");  
    require_once(ROOT_PATH . "user/includes/agree.php");

	$testimonies = $auth_user->testimonies();
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Payment Testimonials</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                <?php if(isset($_GET['testified'])){
                         ?>
                         <div class="alert alert-success">
                              <i class="fa fa-check-square"></i> &nbsp; <strong>Thanks you!</strong> Your testimony have been submitted successfully!
                         </div>
                         <?php
                    }
                ?>
                
             <div class="row">
			   <div class="col-md-12">

			   </div>
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Testimonies from logged in user's country (Nigeria)  
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" 
            id="dataTables-example">
                <thead>
                    <tr>
                        <th width="15%">Member</th>
                        <th>Amount</th>
                        <th>Message</th>
                        <th width="12%">Date</th>
                    </tr>
                </thead>
                <tbody style="font-size: 12px; font-weight: 800;">
					<?php 
                        if (!empty($testimonies)) {
                            foreach($testimonies as $testimony) {
                    $checkPaymethd = $auth_user->testimonyPayMethodCheck($testimony['pay_id']);?>
                    	 <tr>
                       <td><?php echo $testimony["member"];?></td>
                        <td>
                        <?php if($checkPaymethd['paymt_method'] == 'Bank'){?>
                        <span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">₦<?php echo number_format($testimony["amount"]);?></span>
                        <?php }else{?>
                        <span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">$<?php echo number_format($testimony["amount"]);?></span>
                        <?php }?>
                        </td>
                        <td><?php echo $testimony["message"];?></td>
                        <td><?php echo strftime("%b %d, %Y", strtotime($testimony["date_added"]));?></td>
                         </tr>
                    <?php  } }?>
                </tbody>
            </table>
                        </div>
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