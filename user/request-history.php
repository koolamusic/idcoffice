<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
    require_once(ROOT_PATH . "user/includes/write-testimony.php");
	
	$requests = $auth_user->requests($loginID);
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Receive Help / Request History</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>My Request History</h1>
                        <h4>Show all requests for donations you have placed on the portal</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             Request history
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
    <thead>
        <tr>
            <th>Request Date</th>
            <th>Pay by</th>
            <th>Total Amount</th>
            <th>Amount Paid</th>
            <th>Amount Outstanding</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody style="font-size: 12px; font-weight: 800;">
    	<?php 
            if (!empty($requests)) {
                foreach($requests as $request) {
    $payerInfo = $auth_user->userInfo($request["payer_id"]);?>
		<tr>
            <td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($request["date_matched"])) ;?></td>

            <td><?php echo $payerInfo["username"] ;?></td>

            <?php if($request["pay_method"] == 'Bank'){?>
                <td>₦<?php echo number_format($request["request_amt"]);?>.00</td>
                <td>₦<?php echo  number_format($request["received_amt"]);?>.00</td>
                <td>₦<?php $bal = $request["request_amt"] - $request["received_amt"]; echo  number_format($bal);?>.00</td>
            <?php }else{?>
                <td>$<?php echo number_format($request["request_amt"]);?>.00</td>
                <td>$<?php echo  number_format($request["received_amt"]);?>.00</td>
                <td>$<?php $bal = $request["request_amt"] - $request["received_amt"]; echo  number_format($bal);?>.00</td>
            <?php }?>

            <td><?php if($request["match_status"] == "Paid"){?>
            		<span style="color: #04A626;">Received</span>
            	<?php }else{?>
            		<span style="color:#A32F31"><?php echo $request["match_status"];?></span>
            	<?php }?>
            </td>
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