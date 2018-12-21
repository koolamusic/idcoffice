<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");
    require_once(ROOT_PATH . "user/includes/agree.php");
	
	//Grab news id from URL query
	if(isset($_GET["id"]) AND $_GET["id"] != ""){
		$newsID = intval($_GET["id"]);
	}else{
		$auth_user->redirect(BASE_URL.'user/news');
		exit();
	}
	$news = $auth_user->newsSingle($newsID);
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
										<tr>
                                            <td>Admin</td>
                                            <td><p><?php echo nl2br($news["note"]);?></p> </td>
                                            <td><?php echo timeAgo($news["date_added"]);?></td>
                                        </tr>
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