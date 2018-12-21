<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/write-testimony.php");

	$referrals = $auth_user->referral_bonus($userInfo['email']);
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / My Referral Bonuses</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>My Referral Bonuses</h1>
                        <h4>The records below are bonuses from offers placed by your referrals. Once full payments are made by them , value is automatically transferred to your "Request Help" amount</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
                   <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Referral Master List 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" 
                                id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th>Donation Amount</th>
                                            <th>My Bonus</th>
                                            <th>Status</th>
                                            <th>Date Used</th>
                                            <th>Donation Date</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px; font-weight: 800;">
                                        
										<?php 
                                            if (!empty($referrals)) {
                                                foreach($referrals as $referral) {?>
                                            <tr>
                                        	<td><?php echo $referral["member"];?></td>
                                            <?php if($referral["payment_method"] == "Bank"){?>
                                                <td>₦<?php echo number_format($referral["donation_amt"]);?></td>
                                                <td>₦<?php echo number_format($referral["bonus"]);?></td>
                                            <?php }else{?>
                                                <td>$<?php echo number_format($referral["donation_amt"]);?></td>
                                                <td>$<?php echo number_format($referral["bonus"]);?></td>
                                            <?php }?>
                                            <td><?php echo $referral["status"];?></td>

                                            <td align="center">
                                            <?php if($referral["date_used"] != ""){?>
                                            <?php echo strftime("%b %d, %Y", strtotime($referral["date_used"]));?>
                                            <?php }else{echo '--';}?>
                                            </td>

                                            <td><?php echo strftime("%b %d, %Y", strtotime($referral["date_added"]));?></td>
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