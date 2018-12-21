<?php
    require("../includes/config.php"); 
    require_once(ROOT_PATH . "core/class.admin.php");
    require_once(ROOT_PATH . "core/adminSession.php");

    $flaggedDonors = $auth_user->flaggedDonors();

    
    //Accept flag as fake and update related Tables
    if(isset($_POST['approve'])){
        $matchID = strip_tags($_POST['matchID']);
        $payerID = strip_tags($_POST['payerID']);
        $payeeID = strip_tags($_POST['payeeID']);
        $donatingID = strip_tags($_POST['donatingID']);
        $recievingDID = strip_tags($_POST['recievingDID']);
        $mAmount = strip_tags($_POST['mAmount']);
        $requestAmount = strip_tags($_POST['requestAmout']);
        $curtRecdAmount = strip_tags($_POST['curtRecdAmount']);
        
        //check for the fake upload and delete
        try {
            $stmt = $auth_user->runQuery("SELECT * FROM payment_proof WHERE match_id=:matchID");
            $stmt->execute(array(':matchID'=>$matchID));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            echo $e->getMessage();
        }
        if($row['proof'] != ""){
            $toDelete = ROOT_PATH.str_replace('../', '', $row['proof']);
            if(file_exists($toDelete)){
                unlink($toDelete);
            }
        }

        try {
            //  
            $stmt = $auth_user->runQuery("DELETE FROM payment_proof WHERE match_id=:matchID");
            $stmt->execute(array(':matchID'=>$matchID));
            //
            $stmt = $auth_user->runQuery("DELETE FROM match_donations WHERE match_id=:matchID");
            $stmt->execute(array(':matchID'=>$matchID));
            //
            $stmt = $auth_user->runQuery("DELETE FROM donations WHERE donor_id=:donatingID");
            $stmt->execute(array(':donatingID'=>$donatingID));
            //
            $stmt = $auth_user->runQuery("UPDATE donations SET donor_status='Matured'
                WHERE donor_id=:recievingDID");
            $stmt->execute(array(':recievingDID'=>$recievingDID));

            //Add to payer credibility score
             $stmt = $auth_user->runQuery("UPDATE users 
                SET credibility_score=credibility_score - 30
                WHERE login_id=:payerID");
            $stmt->execute(array(':payerID'=>$payerID));
            
            $auth_user->redirect(BASE_URL.'administrator/flagged?accepted');
            exit();

        }catch(PDOException $e) {
            echo $e->getMessage();
        }   
    }

    //Delete
    if(isset($_POST["delete"])) {
        if(!isset($_POST['tick'])) {
            $auth_user->redirect(BASE_URL.'administrator/matched-donors?warning');
            exit();
        }
        $cnt = array();
        $cnt = count($_POST['tick']);
        for($i = 0; $i < $cnt; $i++) {
            $rowID = $_POST['tick'][$i];
            try {
                $stmt = $auth_user->runQuery("DELETE FROM match_donations 
                    WHERE match_id=:rowID limit 1");
                $stmt->execute(array(':rowID'=>$rowID));                
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        $auth_user->redirect(BASE_URL.'administrator/matched-donors?Deleted');
        exit();
    }
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Flagged POP</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <?php
                    if(isset($error)){
                        foreach($error as $error){ ?>
                         <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?>
                         </div>
                <?php } }elseif(isset($_GET['unblock'])){?>
                         <div class="alert alert-success">
                              <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Un-Blocked successfully!
                         </div>
                <?php }elseif(isset($_GET['Deleted'])){?>
                        <div class="alert alert-success">
                              <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Deleted successfully!
                         </div>
               <?php }elseif(isset($_GET['warning'])){?>
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i> &nbsp; Please select a row to Block / Un-blocked
                         </div>
                <?php }elseif(isset($_GET['accepted'])){?>
                        <div class="alert alert-success">
                            <i class="fa fa-check-square"></i> &nbsp; The Payer donation request has been removed and 30% credibility deducted. Meanwhile, the payee is now available to receive donation.
                         </div>
                <?php }?>
                   <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                
                <div class="panel panel-default">
                        <div class="panel-heading">
                             All Flagged POP
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                                    <div class="row">
                                        <!--<div class="col-md-1">
                                           <button type="submit" name="unBlock" 
                                           class="btn btn-success btn-sm">Un-Block</button>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="submit" name="block" 
                                            class="btn btn-warning  btn-sm">Block</button>
                                        </div>-->
                                        <div class="col-md-1">
                                            <button type="submit" name="delete" 
                                            class="btn btn-danger  btn-sm">Delete</button>
                                        </div>
                                    </div>
                                    <br>
                                <table class="table table-striped table-bordered table-hover" 
                                id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checkAll" /></th>
                                            <th>Payer</th>                      
                                            <th>Payee</th>        
                                            <th>Amount</th>
                                            <th>Action</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>



                        <?php 
                            if (!empty($flaggedDonors)) {
                                foreach($flaggedDonors as $donor) {
                                    try {
                                        //
                                        $stmt = $auth_user->runQuery("SELECT * FROM users 
                                            WHERE login_id=:loginID limit 1");
                                        $stmt->execute(array(':loginID'=>$donor["payer_id"]));
                                        $payerName = $stmt->fetch(PDO::FETCH_ASSOC);
                                        //
                                         $stmt = $auth_user->runQuery("SELECT * FROM users 
                                            WHERE login_id=:loginID limit 1");
                                        $stmt->execute(array(':loginID'=>$donor["payee_id"]));
                                        $payeeName = $stmt->fetch(PDO::FETCH_ASSOC);
                                        //
                                        $stmt = $auth_user->runQuery("SELECT * FROM payment_proof 
                                            WHERE match_id=:matchedID limit 1");
                                        $stmt->execute(array(':matchedID'=>$donor["match_id"]));
                                        $POP = $stmt->fetch(PDO::FETCH_ASSOC);             
                                    }
                                    catch(PDOException $e) {
                                        echo $e->getMessage();
                                    }       
                                ?>
                            <tr>
                                <td><input name="tick[]" type="checkbox" value="<?php echo $donor["match_id"];?>"></td>
                                <td><a href="<?php echo BASE_URL.'administrator/user-details?id='.$donor["payer_id"];?>"><?php echo $payerName["username"];?></a></td>
                                <td><a href="<?php echo BASE_URL.'administrator/user-details?id='.$donor["payee_id"];?>"><?php echo $payeeName["username"];?></a></td>
                                <?php if($donor["payment_method"] == "Bank"){?>
                                    <td>₦<?php echo number_format($donor["m_amount"]);?></td>
                                <?php }else{?>
                                    <td>$<?php echo number_format($donor["m_amount"]);?></td>
                                <?php }?>
                                <td align="center">
                                <a target="_blank" href="<?php echo $POP["proof"] ?>">
                                View POP</a><br>
                                <form method="post" action="">
                                    <input type="hidden" name="matchID" value="<?php echo $donor["match_id"];?>">

                                    <input type="hidden" name="payerID" value="<?php echo  $donor["payer_id"];?>">

                                    <input type="hidden" name="payeeID" value="<?php echo  $donor["payee_id"];?>">

                                    <input type="hidden" name="donatingID" value="<?php echo $donor["donating_id"];?>">

                                    <input type="hidden" name="recievingDID" value="<?php echo $donor["match_to_donor_id"];?>">

                                    <input type="hidden" name="mAmount" value="<?php echo $donor["m_amount"];?>">

                                    <input type="hidden" name="curtRecdAmount" value="<?php echo $donor["received_amt"];?>">

                                    <input type="hidden" name="requestAmout" value="<?php echo $donor["request_amt"];?>">

                                    <button type="submit" name="approve" style="font-size: 12px; padding: 0px 20px; color: green;">Accept As Fake</button>
                                </form>
                                </td>
                                <td><?php echo $donor["match_status"];?></td>
                            </tr>
                        <?php  } }?>



                                    </tbody>
                                </table>
                            </form>
                            <script type="text/javascript">
                                $('.checkAll').change(function(){
                                    var state = this.checked;
                                    state? $(':checkbox').prop('checked',true):$(':checkbox').prop('checked',false);
                                    state? $(this).next('b').text('Uncheck All') :$(this).next('b').text('Check All')
                                }); 
                            </script>
                        </div>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
                    
                </div>
                <!-- /. ROW  -->
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>