<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
    require_once(ROOT_PATH . "user/includes/write-testimony.php");
	
	$makeRequests = $auth_user->makeRequests($loginID);
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Receive Help / Request for Help</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Request for Help</h1>
                        <h4>Records shows all matured/pending collections availiable to your account, please select a matured donation to have the money remitted to your account</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Request for help pool  
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" 
    id="dataTables-example">
        <thead>
            <tr>
                <th>Mature Date</th>
                <th>Donation Amount</th>
                <th>Yield Amount</th>
                <th>Bonuses</th>
                <th>Request Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px; font-weight: 800;">
        	<?php 
                if (!empty($makeRequests)) {
                    foreach($makeRequests as $req) {
                $status = $auth_user->mrTrnStatus($req["donor_status"]);
						?>
			<tr>
                <td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($req["matured_date"])) ;?></td>
                <?php if($req["payment_method"] == "Bank"){?>
                    <td>₦<?php echo number_format($req["amount"]);?>.00</td>
                    <td>₦<?php echo number_format($req["yield_amt"]);?>.00</td>
                    <td>₦<?php echo number_format($req["bonus"]);?>.00</td>
                    <td>₦<?php echo number_format($req["request_amt"]);?>.00</td>
                <?php }else{?>
                    <td>$<?php echo number_format($req["amount"]);?>.00</td>
                    <td>$<?php echo number_format($req["yield_amt"]);?>.00</td>
                    <td>$<?php echo number_format($req["bonus"] + $req["referral_bonus"]);?>.00</td>
                    <td>$<?php echo number_format($req["request_amt"]);?>.00</td>
                <?php }?>
                <td><?php echo $status; ?></td>
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