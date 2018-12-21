<?php 
	require("../includes/config.php"); 
  require_once(ROOT_PATH . "core/class.user.php");
  require_once(ROOT_PATH . "core/session.php");
  require_once(ROOT_PATH . "user/includes/agree.php");
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Agreement</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Fake Proof of Payment Attachments</h1>
                        <h4 style="color:#AB0F12;">PLEASE READ EVERY LINE CAREFULLY!! </h4>
                        <p>Hello Participants </p>
                        <p>We have noticed with displeasure, the high rate of fake Proof of Payment (POP) Attachments & members exploiting the system by stopping the penalty time with fake attachments.</p>
                        <p>GHW is based on trust and actions like this must be dealt with immediately to avoid corrosion of the system
Hence forth, we will ruthlessly deal with cases of fake proof of payment attachments by enforcing the following penalties. </p>
                   		<ul class="listItemCust">
                   			<li>Once found guilty of uploading a fake POP, a total of 25% of your active referrals will be permanently removed from your account (this action will be immediate and CANNOT be reversed).</li>
                   			<li>Your next Provide Help will have a maturity rate of 60days.</li>
                   			<li>All pending referral bonuses in your account will be cancelled</li>
                   			<li>If you default for the third time, your account will be blocked and deleted from the system (no reactivation)</li>
                   		</ul>
                   		<p>If there is need for time extension; you are strongly advised to contact your match receipient for time extension. </p>
                   		<h4 style="color:#AB0F12; line-height: 25px;">By clicking on "I AGREE" below, you are signifying that you have read, <br>
                   		and accepted the above listed terms and conditions.</h4>
                   		
                   		<br>
                   		<br>
                   		<a class="btn btn-success btn-lg" href="#">YES, I AGREE</a>
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