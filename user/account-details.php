<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
	require_once(ROOT_PATH . "user/includes/agree.php");
	require_once(ROOT_PATH . "user/includes/write-testimony.php");
	
	//Grab bank details form data and insert into TB
	if(isset($_POST['bank'])){
		$bank = strip_tags($_POST['bank']);
		$accountName = strip_tags($_POST['accountName']);
		$accountNum = strip_tags($_POST['accountNum']);	
		
		//Php Validation
		if($bank == "")	{
			$error[] = "Please enter Name of Bank!";	
		}	
		else if($accountName == "")	{
			$error[] = "Please enter Name on Account!";
		}
		else if($accountNum == "")	{
			$error[] = "Please enter Account Number!";
		}
		
		else{
			try	{
				$stmt = $auth_user->runQuery("SELECT COUNT(*) AS checkAcct FROM account_details WHERE login_id=:loginID");
				$stmt->execute(array(':loginID'=>$loginID));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if($row["checkAcct"] < 1){
					$stmt = $auth_user->runQuery("INSERT INTO account_details (login_id, account_number, account_name,bank_name, date_added) 

					VALUES(:loginID, :accountNum, :accountName, :bank, now())");
					$stmt->execute(array(':loginID'=>$loginID, ':accountNum'=>$accountNum, ':accountName'=>$accountName, ':bank'=>$bank));
					$auth_user->redirect(BASE_URL.'user/account-details?added');
				}else{
					$stmt = $auth_user->runQuery("UPDATE `account_details` SET `account_number`=:accountNum, `account_name`=:accountName, `bank_name`=:bank, `last_updated`=now() WHERE `login_id`=:loginID");
					$stmt->execute(array(':loginID'=>$loginID, ':accountNum'=>$accountNum, ':accountName'=>$accountName, ':bank'=>$bank));
					$auth_user->redirect(BASE_URL.'user/account-details?added');
				}
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}	
	}
	//Grab Bitcon form data and insert into TB
	if(isset($_POST['bitCoinID'])){
		$bitCoinID = strip_tags($_POST['bitCoinID']);
		
		//Php Validation
		if($bitCoinID == "")	{
			$error[] = "Please enter Bitcoin ID!";	
		}		
		else{
			try	{
				$stmt = $auth_user->runQuery("SELECT COUNT(*) AS checkAcct FROM account_details WHERE login_id=:loginID");
				$stmt->execute(array(':loginID'=>$loginID));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				if($row["checkAcct"] < 1){
					$stmt = $auth_user->runQuery("INSERT INTO account_details (login_id, bitcoin_id, date_added) 
					VALUES(:loginID, :bitCoinID, now())");
					$stmt->execute(array(':loginID'=>$loginID, ':bitCoinID'=>$bitCoinID));
					$auth_user->redirect(BASE_URL.'user/account-details?added');
				}else{
					$stmt = $auth_user->runQuery("UPDATE `account_details` SET `bitcoin_id`=:bitCoinID, `last_updated`=now() WHERE `login_id`=:loginID");
					$stmt->execute(array(':loginID'=>$loginID, ':bitCoinID'=>$bitCoinID));
					$auth_user->redirect(BASE_URL.'user/account-details?added');
				}
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}	
	}
	//Retrieve bank details from DB
	$stmt = $auth_user->runQuery("SELECT * FROM account_details WHERE login_id=:loginID");
	$stmt->execute(array(":loginID"=>$loginID));
	$bankInfo = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / My Account / Account Details</small>
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
					elseif(isset($_GET['added'])){
						 ?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Account details added successfully!
						 </div>
						 <?php
					}
				?>
                
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h1>Account Details</h1>
                        <h4>Once updated, information cannot be changed without contacting support</h4>
                        <br>
                        <br>
                    </div>
                    
                   
                    
                </div>
                <!-- /. ROW  -->
                <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            BitCoin 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <?php if(isset($bankInfo["bitcoin_id"]) AND $bankInfo["bitcoin_id"] != ""){?>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
											  <label for="bitCoinID">My BitCoin Address</label>
												<input type="text" readonly class="form-control" 
												value="<?php echo $bankInfo["bitcoin_id"]?>">
												<br><br> 
											</div>
										</div>
									</div>
                                <?php }else{?>
									<form role="form" method="post" action="" enctype="multipart/form-data">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
											  <label for="bitCoinID">My BitCoin Address</label>
												  <input type="text" class="form-control" name="bitCoinID" id="bitCoinID" 
												  value="<?php if(isset($bitCoinID)) echo htmlentities($bitCoinID)?>">
											</div>
										</div>  
									</div>
									<button type="submit" bitcoinBtn class="btn btn-success btn-lg">Submit</button>
								</form>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                    <!-- Advanced Tables -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             My Bank Account Details  
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                               <?php if(isset($bankInfo["account_name"]) AND $bankInfo["account_name"] != ""){?>
									  <div class="row">
										<div class="col-md-12">
											<div class="form-group">
											  <label for="bank">Name of Bank</label>
											  <input type="text" readonly class="form-control"
											  value="<?php echo $bankInfo["bank_name"]?>">
											</div>
										</div>                                    
										<div class="col-md-12">
											<div class="form-group">
											  <label for="accountName">Account Name</label>
											  <input type="text" readonly class="form-control"
											  value="<?php echo $bankInfo["account_name"]?>">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
											  <label for="accountNum">Account Number</label>
											  <input type="text" readonly class="form-control"
											  value="<?php echo $bankInfo["account_number"]?>">
											</div>
										</div>
									</div>
                               <?php }else{?>
                               		<form role="form" method="post" action="" enctype="multipart/form-data">
									  <div class="row">
										<div class="col-md-12">
											<div class="form-group">
											  <label for="bank">Name of Bank</label>
											  <input type="text" class="form-control" name="bank" id="bank" 
											  value="<?php if(isset($bank)) echo htmlentities($bank)?>">
											</div>
										</div>                                    
										<div class="col-md-12">
											<div class="form-group">
											  <label for="accountName">Account Name</label>
											  <input type="text" class="form-control" name="accountName" id="accountName"
												value="<?php if(isset($accountName)) echo htmlentities($accountName)?>">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
											  <label for="accountNum">Account Number</label>
											  <input type="text" class="form-control" name="accountNum" id="accountNum" 
											  value="<?php if(isset($accountNum)) echo htmlentities($accountNum)?>">
											</div>
										</div>
									</div>
									<button type="submit" name="accountBtn" class="btn btn-success btn-lg">Submit</button>
								</form>
                               <?php }?>
                                
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