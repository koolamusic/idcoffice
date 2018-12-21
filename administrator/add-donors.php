<?php
  require("../includes/config.php"); 
  require_once(ROOT_PATH . "core/class.admin.php");
  require_once(ROOT_PATH . "core/adminSession.php");

  //
  if(isset($_POST['username'])){
    $username = strip_tags($_POST['username']);
    $amount = strip_tags($_POST['amount']);
    $paymentMethod = strip_tags($_POST['paymentMethod']);
    
    //Php Validation
    if($username == "") {
      $error[] = "Please enter Payee username";  
    } 
    else if($paymentMethod == "") {
      $error[] = "Please select payment method!";
    }
    else if($amount == "") {
      $error[] = "Please enter Amount to receive by Payee!";
    }else{
      //
      try {
        $stmt = $auth_user->runQuery("SELECT * FROM users 
          WHERE (username=:username) AND status='Active'");
        $stmt->execute(array(':username'=>$username));
        $userFound = $stmt->fetch(PDO::FETCH_ASSOC);

        //Check if user adds account
         $stmt = $auth_user->runQuery("SELECT COUNT(*) AS checkAcct FROM account_details 
          WHERE login_id=:loginID");
        $stmt->execute(array(':loginID'=>$userFound['login_id']));
        $acctChecks = $stmt->fetch(PDO::FETCH_ASSOC);

        if($acctChecks['checkAcct'] < 1 OR $acctChecks['checkAcct'] == ""){
          $error[] = "Payee record found but has not added Payment Information!";
        }elseif(!isset($userFound["username"]) OR $userFound["username"] != $username){
          $error[] = "Payee record not found or account not active!";
        }else{ 
          $maturedDate = date('Y-m-d H:i:s',strtotime('-30 days',strtotime(date("Y-m-d H:i:s"))));

          $stmt = $auth_user->runQuery("INSERT INTO donations (login_id, amount, request_amt, donor_status, payment_status, matured_date, date_paid, date_added, payment_method)
          VALUES(:loginID, :amount, :amount, 'In Progress', 'Paid', :maturedDate, now(), now(), :paymentMethod)");
          
          $stmt->execute(array(':loginID'=>$userFound["login_id"], ':amount'=>$amount, ':maturedDate'=>$maturedDate, ':paymentMethod'=>$paymentMethod));
          $donorID = $auth_user->lastInsertId();

          //
          $stmt = $auth_user->runQuery("INSERT INTO match_donations (match_to_donor_id, donating_id, match_status, date_matched, date_paid, paymt_method)
          VALUES(:donorID, :donorID, 'Paid', now(), now(), :paymentMethod)");
          
          $stmt->execute(array(':donorID'=>$donorID, ':paymentMethod'=>$paymentMethod));

          $auth_user->redirect(BASE_URL.'administrator/add-donors?added');
          exit();
        }
      }
      catch(PDOException $e) {
        echo $e->getMessage();
      }
    } 
  }
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Add to Payee</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
            <?php
                    if(isset($error)){
                        foreach($error as $error){?>
                         <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?>
                         </div>
                <?php } }elseif(isset($_GET['added'])){?>
                 <div class="alert alert-success">
                    <i class="fa fa-check-square"></i> &nbsp; User added to receive Donations
                 </div>
            <?php }?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">                
                <div class="panel-body">
                  <div class="row">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div style="background: green; height: 10px; border-radius: 10px 10px 0px 0px;">
                      </div>
                      <div style="border:1px solid #CCC; min-height: 250px; padding: 30px 20px;">
                        <div class="row">

                          <form role="form" method="post" action="" style="width: 90%; margin: auto; margin-top: 30px;"
                     enctype="multipart/form-data">
                              <div class="row">
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                  <div class="form-group">
                                    <label for="username">Payee Username</label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required value="<?php if(isset($username)){echo $username;} ?>">
                                  </div>
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                      <label for="amount">Amount</label>
                                      <input type="number" class="form-control" name="amount" id="amount" required placeholder="Enter Amount"
                                      value="<?php if(isset($amount)){echo $amount;} ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                      <label for="paymentMethod">Payment Method</label>
                                     <select class="form-control" name="paymentMethod" id="paymentMethod" required>
                                        <option value="">---Select Payment Method---</option>
                                        <option value="Bank">Bank Deposit (₦)</option>
                                        <option value="BitCoin">BitCoin Payment ($)</option>
                                      </select>
                                    </div>
                                </div>
                              
                            </div>
                            <br><br>
                            <div class="col-md-2 col-sm-12 col-xs-12">
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                               <button style="width: 100%;" type="submit" class="btn btn-success btn-small">
                              Continue</button>
                            </div>
                          </form>

                      </div>

                    </div>
                    <br><br><br>
                  </div>
                </div>
              </div>
            </div>
          </div>
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>