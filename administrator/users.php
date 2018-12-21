<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");

	$users = $auth_user->users();
    include(ROOT_PATH."administrator/includes/userAction.php");	
?>
<?php include(ROOT_PATH."administrator/includes/header.php")?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / Users</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <?php if(isset($_GET['unblock'])){?>
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
                <?php }elseif(isset($_GET['blocked'])){?>
               			<div class="alert alert-success">
							<i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Blocked successfully!
						 </div>
                <?php }?>
                   <div class="row">
                <div class="col-md-12">
                    <!-- Advanced Tables -->
                
                <div class="panel panel-default">
                        <div class="panel-heading">
                             All Users
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                            <form role="form" method="post" action="" enctype="multipart/form-data">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                            		<div class="row">
                                        <div class="col-md-1">
                                           <button type="submit" name="unBlock" 
                                           class="btn btn-success btn-sm">Un-Block</button>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="submit" name="block" 
                                            class="btn btn-warning  btn-sm">Block</button>
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
                                            <th>Username</th>
                                            <th>Full Name</th>
                                            <th>Phone Number</th>
                                            <th>Country</th>
                                            <th>Credibility Score</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px; font-weight: 200;">
                                    	<?php 
                                            if (!empty($users)) {
                                                foreach($users as $user) {?>
										<tr>
                                            <td><input name="tick[]" type="checkbox" value="<?php echo $user["login_id"];?>"></td>
                                            <td><?php echo $user["username"];?></td>
                                            <td><?php echo $user["full_name"];?></td>
                                            <td><?php echo  $user["phone"];?></td>
                                            <td><?php echo  $user["country"];?></td>
                                            <td><?php echo  $user["credibility_score"];?>%</td>
                                            <?php if($user["status"] == "Active" OR $user["status"] == ""){?>
                                            	<td style="color: #0CA034">Active</td>
                                            <?php }else{?>
                                            	<td style="color: #8F3739"><?php echo $user["status"] ?></td>
                                            <?php }?>
                                            <td><a class="btn btn-success btn-sm" href="<?php echo BASE_URL.'administrator/user-details?id='.$user["login_id"];?>">View</a></td>
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