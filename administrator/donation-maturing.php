<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");

	$donations = $auth_user->maturingDonations();
	
	if(isset($_POST["cancel"])) {
		if(!isset($_POST['tick'])) {
			$auth_user->redirect(BASE_URL.'administrator/donations?warning');
			exit();
		}
		$cnt = array();
		$cnt = count($_POST['tick']);	
		for($i = 0; $i < $cnt; $i++) {
			$rowID = $_POST['tick'][$i];
			try	{
				$stmt = $auth_user->runQuery("UPDATE donations SET donor_status='Canceled' 
                    WHERE donor_id=:rowID limit 1");
				$stmt->execute(array(':rowID'=>$rowID));				
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}			
		}
		$auth_user->redirect(BASE_URL.'administrator/donations?Canceled');
		exit();
	}elseif(isset($_POST["delete"])) {
		if(!isset($_POST['tick'])) {
			$auth_user->redirect(BASE_URL.'administrator/donations?warning');
			exit();
		}
		$cnt = array();
		$cnt = count($_POST['tick']);
		for($i = 0; $i < $cnt; $i++) {
			$rowID = $_POST['tick'][$i];
			try	{
				$stmt = $auth_user->runQuery("DELETE FROM new_donation WHERE donor_id=:rowID limit 1");
				$stmt->execute(array(':rowID'=>$rowID));				
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
		$auth_user->redirect(BASE_URL.'administrator/donations?Deleted');
	}
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Donations</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <?php if(isset($_GET['Canceled'])){?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Canceled successfully!
						 </div>
				<?php }elseif(isset($_GET['Deleted'])){?>
              			<div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Deleted successfully!
						 </div>
               <?php }elseif(isset($_GET['warning'])){?>
               			<div class="alert alert-danger">
							<i class="fa fa-exclamation-triangle"></i> &nbsp; Please select a row to Cancel / Delete
						 </div>
                <?php }?>
                   <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                
                <div class="panel panel-default">
                        <div class="panel-heading">
                             All Donations
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                            		<div class="row">
                                        <!--<div class="col-md-1">
                                           <button type="submit" name="unBlock" 
                                           class="btn btn-success btn-sm">Un-Block</button>
                                        </div>-->
                                        <div class="col-md-1">
                                            <button type="submit" name="cancel" 
                                            class="btn btn-warning  btn-sm">Cancel</button>
                                        </div>
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
                <th>Member</th>
                <th>Amount</th>
                <th>Yield</th>
                <th>Bonus</th>
                <th>Request Amount</th>
                <th>Mature Date</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px; font-weight: 200;">
        	<?php 
                if (!empty($donations)) {
                    foreach($donations as $donor) {
						if($donor["donor_status"] == "Canceled"){
							$status = '<span style="background:#999;color: #FFF;padding: 3px 5px;border-radius:5px;">'.$donor["donor_status"].'</span>';
						}elseif($donor["donor_status"] == "In Progress"){
							$status = '<span style="background:#4CB3CD;color: #FFF;padding: 3px 5px;border-radius:5px;">'.$donor["donor_status"].'</span>';
						}elseif($donor["donor_status"] == "Matured"){
							$status = '<span style="background:#279E40;color: #FFF;padding: 3px 5px;border-radius:5px; text-decoration:none">Matured</span>';
						}else{
							$status = '<span style="background:#279E40;color: #FFF;padding: 3px 5px;border-radius:5px;">'.$donor["donor_status"].'</span>';
							//
							
						}?>
			<tr>
                <td><input name="tick[]" type="checkbox" value="<?php echo $donor["donor_id"];?>"></td>
            <td>
            <a href="<?php echo BASE_URL.'administrator/user-details?id='.$donor["login_id"]?>"><?php echo $donor["username"];?></a></td>
            <?php if($donor["payment_method"] == "Bank"){?>
                <td>₦<?php echo  number_format($donor["amount"]);?>.00</td>
                <td>₦<?php echo  number_format($donor["yield_amt"]);?>.00</td>
                <td>₦<?php echo  number_format($donor["bonus"]);?>%.00</td>
                <td>₦<?php echo  number_format($donor["request_amt"]);?>.00</td>
            <?php }else{?>
                <td>$<?php echo  number_format($donor["amount"]);?>.00</td>
                <td>$<?php echo  number_format($donor["yield_amt"]);?>.00</td>
                <td>$<?php echo  number_format($donor["bonus"]);?>%.00</td>
                <td>$<?php echo  number_format($donor["request_amt"]);?>.00</td>
            <?php }?>
                
                <td><?php echo strftime("%d/%m/%Y %I:%M", strtotime($donor["matured_date"])) ;?></td>
                <td><?php echo strftime("%d/%m/%Y", strtotime($donor["date_added"])) ;?></td>
                <td style="color: #8F3739"><?php echo $status; ?></td>
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