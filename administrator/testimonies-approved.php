<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");

	$testimonies = $auth_user->approvedTestimonies();

	if(isset($_POST["approve"])) {
		if(!isset($_POST['tick'])) {
			$auth_user->redirect(BASE_URL.'administrator/testimonies?warning');
			exit();
		}
		$cnt = array();
		$cnt = count($_POST['tick']);		
		$pid = count($_POST['pid']);
		for($i = 0; $i < $cnt; $i++) {
			$rowID = $_POST['tick'][$i];
			try	{
				$stmt = $auth_user->runQuery("UPDATE testimonies SET status='Approved' WHERE id=:rowID limit 1");
				$stmt->execute(array(':rowID'=>$rowID));				
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}			
		}
		$auth_user->redirect(BASE_URL.'administrator/testimonies?Approved');
	}elseif(isset($_POST["delete"])) {
		if(!isset($_POST['tick'])) {
			$auth_user->redirect(BASE_URL.'administrator/testimonies?warning');
			exit();
		}
		$cnt = array();
		$cnt = count($_POST['tick']);
		$pid = count($_POST['pid']);
		for($i = 0; $i < $cnt; $i++) {
			$rowID = $_POST['tick'][$i];
			try	{
				$stmt = $auth_user->runQuery("DELETE FROM testimonies WHERE id=:rowID limit 1");
				$stmt->execute(array(':rowID'=>$rowID));				
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
		$auth_user->redirect(BASE_URL.'administrator/testimonies?Deleted');
	}
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Testimonials / Approved</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <?php if(isset($_GET['Approved'])){?>
						 <div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Approved successfully!
						 </div>
				<?php }elseif(isset($_GET['Deleted'])){?>
              			<div class="alert alert-success">
							  <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Deleted successfully!
						 </div>
               <?php }elseif(isset($_GET['warning'])){?>
               			<div class="alert alert-danger">
							<i class="fa fa-exclamation-triangle"></i> &nbsp; Please select a row to approve/delete!
						 </div>
                <?php }?>
                   <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                
                <div class="panel panel-default">
                        <div class="panel-heading">
                             Approved Testimonies
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                            		<div class="row">
                                        <div class="col-md-1">
                                           <button type="submit" name="approve" 
                                           class="btn btn-success btn-sm">Approve</button>
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
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px; font-weight: 200;">
                    	<?php 
                            if (!empty($testimonies)) {
                                foreach($testimonies as $testimony) {
$checkPaymethd = $auth_user->testimonyPayMethodCheck($testimony['pay_id']);?>
						<tr>
                            <td><input name="tick[]" type="checkbox" value="<?php echo $testimony["id"];?>"></td>
                            <td><?php echo $testimony["member"];?></td>
                            <?php if($checkPaymethd['paymt_method'] == 'Bank'){?>
                            <td>₦<?php echo  $testimony["amount"];?></td>
                            <?php }else{?>
                            <td>$<?php echo  $testimony["amount"];?></td>
                            <?php }?>
                            <td><?php echo  $testimony["message"];?></td>
                            <td><?php echo strftime("%b %d, %Y", strtotime($testimony["date_added"]));?></td>
                            <?php if($testimony["status"] == "Approved" ){?>
                            	<td style="color: #0CA034">Approved</td>
                            <?php }else{?>
                            	<td style="color: #8F3739"><?php echo $testimony["status"] ?></td>
                            <?php }?>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("#addList").click(function(){
        $(".hiddenWraper").show(300);
    });
});
</script>
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>