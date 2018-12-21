<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
    require_once(ROOT_PATH . "user/includes/write-testimony.php");
	
	$allNews = $auth_user->allNews();
?>
<?php include(ROOT_PATH."user/includes/header.php") ?>
<?php include(ROOT_PATH."user/includes/navMenu.php") ?>        
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            <small>Dashboard / All News</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             All News 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" 
                                id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th width="10%">Sender</th>
                                            <th width="80%">News Body</th>
                                            <th width="10%">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px; font-weight: 800;">
                                    	<?php 
                                            if (!empty($allNews)) {
                                                foreach($allNews as $news) {?>
										<tr>
                                            <td>Admin</td>
                                            <td><?php echo substr($news["note"], 0, 180);?>... 
                                            <a href="<?php echo BASE_URL.'user/single?id='.$news["id"] ?>">read more!</a></td>
                                            <td><?php echo timeAgo($news["date_added"]);?></td>
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
<?php include(ROOT_PATH."user/includes/footer.php") ?>