<?php 
    //Grab referral ID
    if(isset($_GET["ref_id"])){
        //Set session
        $_SESSION["referral"] = $_GET["ref_id"];
        //set cookie
        setcookie("referral", $_GET["ref_id"], strtotime( '+30 days' ), "/", "", "", TRUE);
        //retreive cookie and load to session
        if(isset($_COOKIE['referral'])){
            $_SESSION['referral'] = $_COOKIE['referral'];
        } 
    }
    //Retrieve site info
    $siteInfo = $auth_user->siteInfo();
    $content = $auth_user->contents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php if(isset($siteInfo['site_name'])){echo $siteInfo['site_title'].' | '.$siteInfo['site_name'];}else{echo 'Welcome';}?></title>

<?php if(isset($siteInfo['favicon_url'])){ 
    $faviconURL = BASE_URL.str_replace('../', '', $siteInfo['favicon_url']);?>
<link rel='shortcut icon' href='<?php echo $faviconURL;?>' type='image/x-icon'/ >
<?php }?>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="<?php if(isset($siteInfo['site_description'])){echo $siteInfo['site_description'].' | '.$siteInfo['site_name'];}else{echo 'Welcome';}?>" />
<meta name="author" content="http://creativeweb.com.ng" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="js/owl-carousel/owl.carousel.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet" />

</head>
<body>
<div id="wrapper">
    <!-- start header -->
    <header>
        <div class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
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
                <div class="navbar-collapse collapse ">
                    <ul class="nav navbar-nav">
                        <li><a href="how-it-works">How it Works</a></li>
                        <li><a href="legality">Legality</a></li>
                        <li><a href="privacy">Privacy Policy</a></li>
                        <li><a href="register">Create Account</a></li>
                        <li><a class="btn btn-default" style="border-radius: 5px; color: #FFF;" href="login">Login</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- end header -->