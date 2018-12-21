<?php
	require("../includes/config.php");  
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");
	
	$enquirys = $auth_user->enquirys();

    if(isset($_GET["deleteID"])){
        $id = $_GET["deleteID"];
        try {
            $stmt = $auth_user->runQuery("DELETE FROM messaging WHERE id=:id");             
            $stmt->execute(array(':id'=>$id));
            $auth_user->redirect(BASE_URL.'administrator/supports?Deleted');
        }
        catch(PDOException $e) {
            echo $e->getMessage();
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
                            <small>Dashboard / All Enquiry</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                <?php if(isset($_GET['Deleted'])){?>
                     <div class="alert alert-success">
                          <i class="fa fa-check-square"></i> &nbsp; Row Successfully Deleted!
                     </div>
               <?php } ?>
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             All Enquiry 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" 
                                id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th width="15%">Sender</th>
                                            <th width="60%">Subject</th>
                                            <th width="15%">Date</th>
                                            <th width="10%">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px; font-weight: 200;">
                                    	<?php 
                                            if (!empty($enquirys)) {
                                                foreach($enquirys as $enquiry) {?>
										<tr>
                                            <td><?php echo $enquiry["sender_name"];?></td>
                                            <td>
                                            <?php if($enquiry["status"] == "Unread"){?><a style="font-weight: 800" href="<?php echo BASE_URL.'administrator/support-details?id='.$enquiry["id"].'&sender='.$enquiry["sender_id"]; ?>"><?php echo $enquiry["subject"];?>... </a>
                                            <?php }else{?>
                                            	<a href="<?php echo BASE_URL.'administrator/support-details?id='.$enquiry["id"].'&sender='.$enquiry["sender_id"];?>"><?php echo $enquiry["subject"];?>... </a>
                                            <?php }?>
                                            </td>
                                            <td><?php echo timeAgo($enquiry["date_sent"]);?></td>
                                            <td><a href="<?php echo BASE_URL.'administrator/supports?deleteID='.$enquiry["id"] ?>">Delete</a></td>
                                        </tr>
                                        <?php  } }?>
                                    </tbody>
                                </table>
                        </div>
                        <br><br><br><br>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
                    
	</div>
	<!-- /. ROW  -->
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>