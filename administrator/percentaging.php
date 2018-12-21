<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");

	//Update  donation percentage %
	if(isset($_POST['btc'])){
		$btc = strip_tags($_POST['btc']);	
		$local = strip_tags($_POST['local']);		
		try	{				
			$stmt = $auth_user->runQuery("UPDATE percentaging 
				SET btc_donation=:btc,
					local_donation=:local, 
					last_updated=now() 
				WHERE id='1'");
			$stmt->execute(array(':btc'=>$btc, ':local'=>$local));

			$auth_user->redirect(BASE_URL.'administrator/percentaging?Updated');
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}	
	}

	//Update Signup bonuses
	if(isset($_POST['btc1'])){
		$btc1 = strip_tags($_POST['btc1']);	
		$btc2 = strip_tags($_POST['btc2']);	
		$btc3 = strip_tags($_POST['btc3']);	
		$local1 = strip_tags($_POST['local1']);	
		$local2 = strip_tags($_POST['local2']);	
		$local3 = strip_tags($_POST['local3']);		
		try	{				
			$stmt = $auth_user->runQuery("UPDATE percentaging 
				SET bonus_btc_o=:btc1,
					bonus_btc_t=:btc2,
					bonus_btc_th=:btc3,
					bonus_local_o=:local1,
					bonus_local_t=:local2,
					bonus_local_th=:local3, 
					last_updated=now() 
				WHERE id='1'");
			$stmt->execute(array(':btc1'=>$btc1, ':btc2'=>$btc2, ':btc3'=>$btc3, ':local1'=>$local1, ':local2'=>$local2, ':local3'=>$local3));
			$auth_user->redirect(BASE_URL.'administrator/percentaging?Updated');
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}	
	}

	//Update Referral Bonus
	if(isset($_POST['refBonus'])){
		$refBonus = strip_tags($_POST['refBonus']);
		$relfTimes = strip_tags($_POST['relfTimes']);	
		try	{				
			$stmt = $auth_user->runQuery("UPDATE percentaging 
				SET referral_bonus=:refBonus, 
					ref_times=:relfTimes, 
					last_updated=now() 
				WHERE id='1'");
			$stmt->execute(array(':refBonus'=>$refBonus, ':relfTimes'=>$relfTimes));
			$auth_user->redirect(BASE_URL.'administrator/percentaging?Updated');
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}	
	}

	//percentage
	try	{
		$stmt = $auth_user->runQuery("SELECT * FROM percentaging WHERE id='1'");
		$stmt->execute();
		$penctg = $stmt->fetch(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Percentaging</small>
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
					elseif(isset($_GET['Updated'])){
						 ?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Account updated successfully!
						 </div>
						 <?php
					}
				?>

                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                            <div class="row">
                            	
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                <legend>Donation Percentage (%)</legend>
                                <form method="post" action="">
									  <div class="row">
										
										<div class="col-md-12">
											<div class="form-group">
											  <label for="btc">BTC %</label>
											  <input type="number" class="form-control" name="btc" id="btc" required
											  value="<?php if(isset($btc)){echo $btc;}else{echo $penctg["btc_donation"];}?>">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
											  <label for="local">NGN %</label>
											  <input type="number" class="form-control" name="local" id="local" required
											  value="<?php if(isset($local)){echo $local;}else{echo $penctg["local_donation"];}?>">
											</div>
										</div>
									</div>
									<button type="submit" class="btn btn-success btn-small">Update</button>
								</form>
                                </div>
                               </div>
                             </div>
                            </div>

                            <div class="panel panel-default">
                            <div class="panel-body">
                            <div class="row">
                            	
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                <legend>Referral bonus percentage</legend>
                                <form method="post" action="">
									  <div class="row">										
										<div class="col-md-12">
											<div class="form-group">
											  <label for="refBonus" style="font-size:11px;">
											  Percentage (%) of amount donated by referee
											  </label>
											  <input type="number" class="form-control" name="refBonus" id="refBonus" required
											  value="<?php if(isset($refBonus)){echo $refBonus;}else{echo $penctg["referral_bonus"];}?>">
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
											  <label for="refBonus" style="font-size:11px;">
											  Number of time to give bonus from a single referral %</label>
											  <input type="number" class="form-control" name="relfTimes" id="relfTimes" required
											  value="<?php if(isset($relfTimes)){echo $relfTimes;}else{echo $penctg["ref_times"];}?>">
											</div>
										</div>
									</div>
									<button type="submit" class="btn btn-success btn-small">Update</button>
								</form>
                                </div>
                               </div>
                             </div>
                            </div>


                            </div>



                            <div class="col-md-6 col-sm-12 col-xs-12">
                            <div class="panel panel-default">
                            <div class="panel-body">
                            <div class="row">
                            	
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                <legend>Signup Bonuses</legend>
                                <form method="post" action="" >
									  <div class="row">
										<div class="col-md-6">
											<div class="form-group">
											  <label for="btc1">BTC $50 - $499</label>
											  <input type="number" class="form-control" name="btc1" id="btc1" required
											  value="<?php if(isset($btc1)){echo $btc1;}else{echo $penctg["bonus_btc_o"];}?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
											  <label for="btc2">BTC $500 - $999</label>
											  <input type="number" class="form-control" name="btc2" id="btc2" required
											  value="<?php if(isset($btc2)){echo $btc2;}else{echo $penctg["bonus_btc_t"];}?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
											  <label for="btc3">BTC $1000 - $1000+</label>
											  <input type="number" class="form-control" name="btc3" id="btc3" required
											  value="<?php if(isset($btc3)){echo $btc3;}else{echo $penctg["bonus_btc_th"];}?>">
											</div>
										</div>
										<div style="clear: both;"></div>
											<hr>
										<div class="col-md-6">
											<div class="form-group">
											  <label for="local1">NGN ₦10,000 - ₦49,999</label>
											  <input type="number" class="form-control" name="local1" id="local1" required
											  value="<?php if(isset($local1)){echo $local1;}else{echo $penctg["bonus_local_o"];}?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
											  <label for="local2">NGN ₦50,000 - ₦99,999</label>
											  <input type="number" class="form-control" name="local2" id="local2" required
											  value="<?php if(isset($local2)){echo $local2;}else{echo $penctg["bonus_local_t"];}?>">
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
											  <label for="local3">NGN ₦100,000 - ₦100,000+</label>
											  <input type="number" class="form-control" name="local3" id="local3" required
											  value="<?php if(isset($local3)){echo $local3;}else{echo $penctg["bonus_local_th"];}?>">
											</div>
										</div>                                    
										
										
									</div>
									<button type="submit" class="btn btn-success btn-small">Update</button>
								</form>
                                </div>
                               </div>
                             </div>
                            </div>
                            </div>

                    </div>          
                <!-- /. ROW  -->
                    
       </div>
<br> <br><br><br>   <br> <br><br><br><br> <br>      
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>