<?php
  require("../includes/config.php"); 
  require_once(ROOT_PATH . "core/class.admin.php");
  require_once(ROOT_PATH . "core/adminSession.php");

  try {
      $stmt = $auth_user->runQuery("SELECT * FROM contents WHERE c_id='1'");
      $stmt->execute();
      $content = $stmt->fetch(PDO::FETCH_ASSOC);
  }catch(PDOException $e) {
      echo $e->getMessage();
  }
  
  //
  if(isset($_POST['middleRight'])) {
    $middleRight = $_POST['middleRight'];
    $bottomLeft = $_POST['bottomLeft'];
    $bottomRight = $_POST['bottomRight'];
    try {
      $stmt = $auth_user->runQuery("UPDATE contents 
        SET middle_right=:middleRight,
            home_bottom_left=:bottomLeft,
            home_bottom_right=:bottomRight
        WHERE c_id='1'");     
      $stmt->execute(array(':middleRight'=>$middleRight, ':bottomLeft'=>$bottomLeft,':bottomRight'=>$bottomRight));
      //check files
      if(isset($_FILES['upload']['name']) AND $_FILES['upload']['name'] != "") {
          $target_path = "../img/";
          $validextensions = array("jpeg", "jpg", "png");
          $ext = explode('.', basename($_FILES['upload']['name'])); 
          $file_extension = end($ext); 
          $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
          if (($_FILES["upload"]["size"] < 300000000)
          && in_array($file_extension, $validextensions)) {
            if (move_uploaded_file($_FILES['upload']['tmp_name'], $target_path)) {
                // Update
                $stmt = $auth_user->runQuery("UPDATE contents 
                  SET middle_left_img=:img WHERE c_id='1'");
                $stmt->execute(array(':img'=>$target_path));
              } else {     //  If File Was Not Moved.
                $error[] = '). please try again!.<br/>';
              }
          }else {     //   If File Size And File Type Was Incorrect.
            $error[] = '). ***Invalid file Size or Type***<br/>';
          }     
      }
    }
    catch(PDOException $e) {
      echo $e->getMessage();
    }
    $auth_user->redirect(BASE_URL.'administrator/content-home-page?updated');
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
                            <small>Dashboard / Contents / Home Page Contents</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
            <?php
                    if(isset($error)){
                        foreach($error as $error){?>
                         <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?>
                         </div>
                <?php } }elseif(isset($_GET['updated'])){?>
                 <div class="alert alert-success">
                    <i class="fa fa-check-square"></i> &nbsp; Content updated successfully!
                 </div>
            <?php }?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">                
                <div class="panel-body">
                  <div class="row">

                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div style="background: green; height: 10px; border-radius: 10px 10px 0px 0px;">
                      </div>
                      <div style="border:1px solid #CCC; min-height: 250px; padding: 30px 20px;">
                        <div class="row">

                          <form role="form" method="post" action="" style="width: 95%; margin: auto; margin-top: 30px;" enctype="multipart/form-data">
                              <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                  <?php if($content['middle_left_img'] != ""){?>
                                  <img src="<?php echo $content['middle_left_img'];?>" width="400" height="300">
                                  <?php }?>
                                  <div class="form-group">
                                      <label>Home - Middle Left - Image </label>
                                      <input type="file" name="upload">
                                  </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                   <div class="form-group">
                                      <label for="middleRight">Home - Middle Right</label>
                                      <textarea class="form-control" name="middleRight" id="middleRight" rows="12"
                                      ><?php if(isset($middleRight)){echo $middleRight;}else{echo $content['middle_right'];}?></textarea>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                                <hr>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                   <div class="form-group">
                                      <label for="bottomLeft">Home - Bottom Left</label>
                                      <textarea class="form-control" name="bottomLeft" id="bottomLeft" rows="20"
                                      ><?php if(isset($bottomLeft)){echo $bottomLeft;}else{echo $content['home_bottom_left'];}?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                   <div class="form-group">
                                      <label for="bottomRight">Home - Bottom Right</label>
                                      <textarea class="form-control" name="bottomRight" id="bottomRight" rows="20"
                                      ><?php if(isset($bottomRight)){echo $bottomRight;}else{echo $content['home_bottom_right'];}?></textarea>
                                    </div>
                                </div>
                            </div>
                            <br><br>
                            <div style="margin: 0px 0px 0px 20px;">
                              <button type="submit" class="btn btn-success btn-small">
                              Update</button>
                            </div>
                          </form>

                      </div>

                    </div>
                    <br><br><br>
                  </div>
                </div>
              </div>
            </div>
          </div>
<?php include(ROOT_PATH."administrator/includes/footer.php") ?>