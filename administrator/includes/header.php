<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Administrtator - <?php if(isset($siteInfo['site_name'])){echo $siteInfo['site_name'];}?></title>

    <?php if(isset($siteInfo['favicon_url'])){ 
        $faviconURL = BASE_URL.str_replace('../', '', $siteInfo['favicon_url']);?>
    <link rel='shortcut icon' href='<?php echo $faviconURL;?>' type='image/x-icon'/ >
    <?php }?>
	<!-- Bootstrap Styles-->
    <link href="<?php echo BASE_URL;?>user/assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FontAwesome Styles-->
    <link href="<?php echo BASE_URL;?>user/assets/css/font-awesome.css" rel="stylesheet" />
     <!-- Morris Chart Styles-->
   
        <!-- Custom Styles-->
    <link href="<?php echo BASE_URL;?>user/assets/css/custom-styles.css" rel="stylesheet" />
     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
     <!-- TABLE STYLES-->
    <link href="<?php echo BASE_URL;?>user/assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php if(isset($siteInfo['logo_url'])){ 
                    $logoURL = BASE_URL.str_replace('../', '', $siteInfo['logo_url']);?>
                <a class="navbar-brand" href="<?php echo BASE_URL;?>">
                <img src="<?php echo $logoURL;?>" alt="logo"/></a>
                <?php }else{?>
                <a class="navbar-brand" href="<?php echo BASE_URL;?>"><img src="img/logo.png" alt="logo"/></a>
                <?php }?>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i>Welcome, <?php echo $userInfo["username"];  ?> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo BASE_URL;?>administrator/settings">
                        <i class="fa fa-gear fa-fw"></i> Admin Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo BASE_URL;?>administrator/logout">
                        <i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>