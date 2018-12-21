<?php
  require("../includes/config.php"); 
  require_once(ROOT_PATH . "core/class.admin.php");
  require_once(ROOT_PATH . "core/adminSession.php");
  
  //
  if(isset($_POST['siteName'])) {
    $siteName = strip_tags($_POST['siteName']);
    $siteTitle = strip_tags($_POST['siteTitle']);
    $siteDesc = strip_tags($_POST['siteDesc']);
    try {
      $stmt = $auth_user->runQuery("UPDATE website_settings 
        SET site_name=:siteName, site_title=:siteTitle, site_description=:siteDesc 
        WHERE id='1'");     
      $stmt->execute(array(':siteName'=>$siteName, ':siteTitle'=>$siteTitle, ':siteDesc'=>$siteDesc));
    }
    catch(PDOException $e) {
      echo $e->getMessage();
    }
    $auth_user->redirect(BASE_URL.'administrator/site-settings?updated');
    exit();
  }

  //Update Favicon
  if(isset($_FILES['favicon']['name']) AND $_FILES['favicon']['name'] != "") {
    try {
      $stmt = $auth_user->runQuery("SELECT * FROM website_settings WHERE id='1'");
      $stmt->execute();
      $checkLogo = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e) {
      echo $e->getMessage();
    }

    if($checkLogo["favicon_url"] != ''){
      $toDelete = ROOT_PATH.str_replace('../', '', $checkLogo['favicon_url']);
      if(file_exists($toDelete)){
        unlink($toDelete);
      }
    }

    $target_path = "../img/";
    $validextensions = array("jpeg", "jpg", "png");
    $ext = explode('.', basename($_FILES['favicon']['name'])); 
    $file_extension = end($ext); 
    $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
    if (($_FILES["favicon"]["size"] < 300000000)
    && in_array($file_extension, $validextensions)) {
      if (move_uploaded_file($_FILES['favicon']['tmp_name'], $target_path)) {
        // Update
        $stmt = $auth_user->runQuery("UPDATE website_settings 
          SET favicon_url=:favicon WHERE id='1'");
        $stmt->execute(array(':favicon'=>$target_path));
        $auth_user->redirect(BASE_URL.'administrator/site-settings?updated');
        exit();
      } else {     //  If File Was Not Moved.
        $error[] = '). please try again!.<br/>';
      }
    } else {     //   If File Size And File Type Was Incorrect.
      $error[] = '). ***Invalid file Size or Type***<br/>';
    }     
  }

  //Update logo
  if(isset($_FILES['logo']['name']) AND $_FILES['logo']['name'] != "") {
    try {
      $stmt = $auth_user->runQuery("SELECT * FROM website_settings WHERE id='1'");
      $stmt->execute();
      $checkLogo = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e) {
      echo $e->getMessage();
    }

    if($checkLogo["logo_url"] != ''){
      $toDelete = ROOT_PATH.str_replace('../', '', $checkLogo['logo_url']);
      if(file_exists($toDelete)){
        unlink($toDelete);
      }
    }

    $target_path = "../img/";
    $validextensions = array("jpeg", "jpg", "png");
    $ext = explode('.', basename($_FILES['logo']['name'])); 
    $file_extension = end($ext); 
    $target_path = $target_path . md5(uniqid()) . "." . $ext[count($ext) - 1];
    if (($_FILES["logo"]["size"] < 300000000)
    && in_array($file_extension, $validextensions)) {
      if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
        // Update
        $stmt = $auth_user->runQuery("UPDATE website_settings 
          SET logo_url=:logo WHERE id='1'");
        $stmt->execute(array(':logo'=>$target_path));

        $auth_user->redirect(BASE_URL.'administrator/site-settings?updated');
        exit();
      } else {     //  If File Was Not Moved.
        $error[] = '). please try again!.<br/>';
      }
    } else {     //   If File Size And File Type Was Incorrect.
      $error[] = '). ***Invalid file Size or Type***<br/>';
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
                            <small>Dashboard / Website Settings</small>
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
                    <i class="fa fa-check-square"></i> &nbsp; Site info updated successfully!
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

                          <form role="form" method="post" action="" style="width: 90%; margin: auto; margin-top: 30px;" enctype="multipart/form-data">
                              <div class="row">
                                <div class="col-md-5 col-sm-12 col-xs-12">
                                  <div class="form-group">
                                      <label for="siteName">Site Name</label>
                                      <input type="text" class="form-control" 
                                      name="siteName" id="siteName" 
                                      value="<?php echo $siteInfo["site_name"];?>">
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                  <div class="form-group">
                                      <label for="siteTitle">Site Title</label>
                                      <input type="text" class="form-control" 
                                      name="siteTitle" id="siteTitle" 
                                      value="<?php echo $siteInfo["site_title"];?>">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                  <div class="form-group">
                                      <label for="siteDesc">Site Description</label>
                                      <input type="text" class="form-control" 
                                      name="siteDesc" id="siteDesc" 
                                      value="<?php echo $siteInfo["site_description"];?>">
                                    </div>
                                </div><div style="clear:both;"></div>
                                <div style="margin: 20px 0px 0px;">
                                  <button type="submit" class="btn btn-success btn-small">
                                  Update</button>
                                </div>
                              </div>
                            </form>
                             <form role="form" method="post" action="" style="width: 90%; margin: auto; margin-top: 30px;" enctype="multipart/form-data">
                              <div class="row">
                                <div style="clear:both;"></div>
                                <hr>
                                <div class="col-md-2 col-sm-12 col-xs-12" style="padding-top:20px;">
                                  <img src="<?php echo $siteInfo["favicon_url"];?>" alt="Favicon">
                                </div>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                  <div class="form-group">
                                      <label for="favicon">Change Favicon
                                        <span style="color:red;font-style:italic;">
                                          Favicon size most be 16px by 16px
                                        </span>
                                      </label>
                                      <input type="file" class="form-control" 
                                      name="favicon" id="favicon">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                  <div style="margin: 20px 0px 0px;">
                                  <button type="submit" class="btn btn-success btn-small">
                                  Upload Favicon</button>
                                </div>
                              </div>
                              </form>
                              <form role="form" method="post" action="" style="width: 90%; margin: auto; margin-top: 30px;" enctype="multipart/form-data">
                              <div class="row">
                                <div style="clear:both;"></div>
                                <hr>
                                <div class="col-md-5 col-sm-12 col-xs-12" style="padding-top:10px;">
                                  <img src="<?php echo $siteInfo["logo_url"];?>" alt="Logo">
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                  <div class="form-group">
                                      <label for="logo">Change Logo
                                        <span style="color:red;font-style:italic;">
                                          Logo size by height must be 40px</label>
                                      <input type="file" class="form-control" 
                                      name="logo" id="logo">
                                    </div>
                                </div>

                            </div>
                            <br><br>
                            <div style="margin: 0px 0px 0px 20px;">
                              <button type="submit" class="btn btn-success btn-small">
                              Upload Logo</button>
                            </div>
                            <hr>
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