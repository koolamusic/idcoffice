<?php
    require("../includes/config.php"); 
    require_once(ROOT_PATH . "core/class.user.php");
    require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
    require_once(ROOT_PATH . "user/includes/write-testimony.php");

    $referrals = $auth_user->referrals($userInfo["email"], $userInfo["username"]);
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / My Referrals</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>My Referrals</h1>
                        <h4>The list below shows all accounts registered under your account..</h4>
                        <h4 style="color:#AB0F12;">My Referral ID : http://yoursite.com/?ref_id=<?php echo $userInfo["username"];?></h4>
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
                             Referral List 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" 
                                id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Member</th>
                                            <th>Country</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Req. Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px; font-weight: 800;">
                                        <?php 
                                            if (!empty($referrals)) {
                                                foreach($referrals as $referral) {?>
                                                <tr>
                                                    <td><?php echo $referral["username"];?></td>
                                                    <td><?php echo $referral["country"];?></th>
                                                    <td><?php echo $referral["phone"];?></td>
                                                    <td><?php echo $referral["email"];?></td>
                                                    <td><?php echo strftime("%b %d, %Y", strtotime($referral["signup_date"]));?></td>
                                                    <td><?php echo $referral["status"];?></td>
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