<?php
	require("../includes/config.php");  
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");
	
	$allNews = $auth_user->allNews();

    if(isset($_POST["delete"])) {
        if(!isset($_POST['tick'])) {
            $auth_user->redirect(BASE_URL.'administrator/pledges-basic?warning');
            exit();
        }
        $cnt = array();
        $cnt = count($_POST['tick']);
        for($i = 0; $i < $cnt; $i++) {
            $rowID = $_POST['tick'][$i];
            try {
                $stmt = $auth_user->runQuery("DELETE FROM news 
                    WHERE id=:rowID limit 1");
                $stmt->execute(array(':rowID'=>$rowID));                
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
        $auth_user->redirect(BASE_URL.'administrator/news?Deleted');
    }
?>
<?php include(ROOT_PATH."administrator/includes/header.php") ?>
<?php include(ROOT_PATH."administrator/includes/navMenu.php") ?>        
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
                <?php if(isset($_GET['Deleted'])){?>
                        <div class="alert alert-success">
                              <i class="fa fa-check-square"></i> &nbsp; Selected Row(s) have been Deleted successfully!
                         </div>
               <?php }?>
              <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                             All News 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <form role="form" method="post" action="" enctype="multipart/form-data">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                                    <div class="row">                                          
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
                                            <th width="2%"><input type="checkbox" class="checkAll" /></th>
                                            <th width="10%">Sender</th>
                                            <th width="75%">News Body</th>
                                            <th width="13%">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php 
                                            if (!empty($allNews)) {
                                                foreach($allNews as $news) {?>
										<tr>
                                            <td><input name="tick[]" type="checkbox" value="<?php echo $news["id"];?>"></td>
                                            <td><?php echo $news["admin"];?></td>
                                            <td><?php echo substr($news["note"], 0, 180);?>... 
                                            <a href="<?php echo BASE_URL.'administrator/single?id='.$news["id"] ?>">read more!</a></td>
                                            <td><?php echo timeAgo($news["date_added"]);?></td>
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
                        <br><br><br><br>
                    </div>
                    <!--End Advanced Tables -->
                </div>
            </div>
        </div>
                    
	</div>
	<!-- /. ROW  -->
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>