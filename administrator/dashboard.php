<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");
	
	$countUsers = $auth_user->countUsers();
	$allTrans = $auth_user->allTrans();
	$btc_allTrans = $auth_user->btc_allTrans();
	$allDonations = $auth_user->allDonations();
	$allawaitingDonors = $auth_user->allawaitingDonors();

	$Offered = $auth_user->recentlyOffered();
	$received = $auth_user->recentlyreceived();
	$comfirmedPayments = $auth_user->comfirmedPayments();
	$recentTestimonies = $auth_user->recentTestimonies();
	$countAdminUsers = $auth_user->get_countAdminUsers();
	$countAdminLoggedIN = $auth_user->get_countAdminLoggedIN();
	$recentAdminAccess = $auth_user->get_recentAdminAccess();
	$adminNotes = $auth_user->get_adminNote();
		
	/// Add dote	
	if(isset($_POST['adminNote'])) {				 
		$adminNote = strip_tags($_POST['adminNote']);
		if($adminNote == ""){
			$errorMSG = 'You can not add empty note';
		}
		if(!isset($errorMSG)){
			// Insert into admin notes
			try	{
				$stmt = $auth_user->runQuery("INSERT INTO admin_notes (admin, note, note_date)
						VALUES (:username, :adminNote, now())");
				
				$stmt->execute(array(':username'=>$userInfo["username"], ':adminNote'=>$adminNote));
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
			$auth_user->redirect(BASE_URL.'administrator');
			exit();
		}
	}
	
	//Delete note
	if(isset($_GET["noteDelete"])){
		$noteID = intval($_GET["noteDelete"]);
		try	{
			$stmt = $auth_user->runQuery("DELETE FROM admin_notes WHERE id=:noteID LIMIT 1");

			$stmt->execute(array(':noteID'=>$noteID));
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
		$auth_user->redirect(BASE_URL.'administrator');
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
                            <small>Dashboard</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->

                <div class="row">
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-brown">
                            <div class="panel-body">
                                <i class="fa fa-users fa-5x"></i>
                                <h3><?php echo number_format($countUsers); ?></h3>
                            </div>
                            <div class="panel-footer back-footer-brown">
                                No. of Active Users
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-red">
                            <div class="panel-body">
                                <i class="fa fa-bar-chart-o fa-5x"></i>
                                <h3><?php echo number_format($allawaitingDonors); ?></h3>
                            </div>
                            <div class="panel-footer back-footer-red">
                                No.of Awaiting Donations
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-dollar fa-5x"></i>
                                <h3>$<?php echo number_format($btc_allTrans); ?></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Total Transactions
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="panel panel-primary text-center no-boder bg-color-green">
                            <div class="panel-body">
                                <i class="fa fa-money fa-5x"></i>
                                <h3>₦<?php echo number_format($allTrans); ?></h3>
                            </div>
                            <div class="panel-footer back-footer-green">
                                Total Transactions
                            </div>
                        </div>
                    </div>
                </div>  
                <br>             
                <!-- /. ROW  -->
                   
                    <div class="row">
                    	<div class="col-md-4 col-sm-12 col-xs-12">
                    		<div class="row">
                    		                    			
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											Recent Testimonies
										</div>
										<div class="panel-body">
			<div class="list-group">
			 	<?php if(!empty($recentTestimonies)){
							foreach($recentTestimonies as $testimony){
						$checkPaymethd = $auth_user->testimonyPayMethodCheck($testimony['pay_id']);?>
				 <a href="#" class="list-group-item" style="padding: 0px; border: none; padding-bottom: 10px;">
					<?php echo $testimony["message"];?>
					<br>
					<span class="badge" style="background:#063D64;color:#FFF; padding: 3px 10px; text-align: right; border-radius: 6px;"><?php echo $testimony["member"];?></span><br>
						<?php if($checkPaymethd['paymt_method'] == 'Bank'){?>
							<span class="badge" style="background:#109E55;color:#FFF; padding: 3px 10px; text-align: right; border-radius: 6px;">₦<?php echo number_format($testimony["amount"]);?></span>
						<?php }else{?>
							<span class="badge" style="background:#109E55;color:#FFF; padding: 3px 10px; text-align: right; border-radius: 6px;">$<?php echo number_format($testimony["amount"]);?></span>
						<?php }?>
					<br>
					<hr>
				</a>
				<?php }}?>
			</div>
											<div class="text-right">
												<a href="testimonies">View all Testimonies <i class="fa fa-arrow-circle-right"></i></a>
											</div>
										</div>
									</div>
								</div>
                   			</div>
                    	</div>
                    	<div class="col-md-8 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
										   Recently Confirmed Payments
										</div> 
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Date</th>
															<th>Member</th>
															<th>Amount</th>
													  </tr>
													</thead>
													<tbody>
					    <?php 
							if (!empty($comfirmedPayments)) {
								foreach($comfirmedPayments as $comfirmed) {?> 
							<tr>
								<td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($comfirmed["date_paid"]));?></td>
								<td><?php echo $comfirmed["username"];?>*******</td>

								<td>
									<?php if($comfirmed["paymt_method"] == "Bank"){?>
										<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">₦<?php echo number_format($comfirmed["m_amount"]);?></span>
									<?php }else{?>
										<span style="background:#1B8455; color: #FFF; padding: 3px 10px;border-radius: 5px;">$<?php echo number_format($comfirmed["m_amount"]);?></span>
									<?php }?>
								</td>
							</tr>
						<?php }}?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											Recent Admin Activity
										</div> 
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Admin</th>
															<th>IP Address</th>
															<th>Last Access</th>
													  </tr>
													</thead>
													<tbody>
														<?php if (!empty($recentAdminAccess)) {
																	foreach($recentAdminAccess as $AdminAccess) {?>
														<tr>
															<td><?php echo $AdminAccess["admin"];?></td>
															<td><?php echo $AdminAccess["ip"];?></td>
															<td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($AdminAccess["last_access"]));?></td>
														</tr>
														<?php }}?>

													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
                   		</div>
                    </div>
                    
                </div>
                <!-- /. ROW  -->
                <div class="row">
                   <div class="col-md-12 col-sm-12 col-xs-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											TO-DO-List
										</div>
										<div class="panel-body">
											<div class="list-group">
												<?php if (!empty($adminNotes)) {
														foreach($adminNotes as $note) {?>
													<span class="list-group-item">
														<span class="badge"><?php echo strftime("%d/%m/%Y %I:%M", strtotime($note["note_date"]));?></span>
														<a style="color:#8B2021;" href="<?php echo BASE_URL.'administrator/dashboard?noteDelete='.$note["id"];?>"><i class="fa fa-fw fa-times"></i></a> 
														<?php echo $note["note"]; ?> ...<em style="color:#999;">
														<?php echo $note["admin"];?></em>
													</span>
												<?php }}?>
												<?php 
													/*$adminNotes = get_adminNote();
													if (!empty($adminNote)) {
														foreach($adminNote as $note) {
															echo adminNote_view($note);
														}
													}*/
												 ?>

												<form role="form" method="post" action="" class="hiddenWraper" style="width: 90%; margin: auto; margin-top: 30px;display:none;">
													<div class="form-group">
													  <textarea class="form-control" name="adminNote" id="adminNote" 
														rows="3"></textarea>
													</div>
													<div style="width: 20%; margin: auto;">
														<button style="width: 100%;" type="submit" class="btn btn-success btn-lg">Add Note</button>
													</div>
												</form>
											</div>
											<div class="text-right">
											   <button class="btn btn-success btn-small" id="addList">Add to List</button>

											</div>
										</div>
									</div>
								</div>
                   
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                            <div class="row">
                            	<div class="col-md-12 col-sm-12 col-xs-12">
                                <legend>Vendor's Info</legend>
                                <p style="padding:0px; margin:0px;">
                                    <b>Developer:</b> CreativeWeb Nigeria <br/>
                                    <b>Website:</b> <a target="_blank" href="http://www.creativeweb.com.ng">
                                    www.creativeweb.com.ng </a><br />
                                    <b> Date Released:</b> 15-01-2017<br />
                                    <b>Current Version:</b> 1.1 <br />
                                    <br />
                                </p>
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
                                <legend>Client Info</legend>
                                <p style="padding:0px; margin:0px;">
                                	<b>Client Name: Naija Helpers
                                 </p>
                                 <p style="padding:0px; margin:0px;">
                                 	<b>Server Name:</b> www.naijahelpers.com<br />
                                 	<?php $serverIP = gethostbyname('www.mlm.com.ng'); ?>
                                 	<b>Server IP:</b> <?php echo $serverIP;?><br />
                                   


                                    <b>No. Of Admin Users:</b> <?php echo $countAdminUsers; ?><br />
                                    <b>No. of Users Logged in:</b> <?php echo $countAdminLoggedIN; ?> 
                                </p>
                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#addList").click(function(){
        $(".hiddenWraper").show(300);
    });
});
</script>
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>