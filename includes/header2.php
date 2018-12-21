<?php 
	//Retrieve site info
    $siteInfo = $auth_user->siteInfo(); 
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
<meta name="description" content="" />
<meta name="author" content="http://creativeweb.com.ng" />
<!-- css -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<link href="css/fancybox/jquery.fancybox.css" rel="stylesheet">
<link href="css/jcarousel.css" rel="stylesheet" />
<link href="css/flexslider.css" rel="stylesheet" />
<link href="js/owl-carousel/owl.carousel.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet" />
<style type="text/css">
	.formWrapper {
		width: 40%;
		margin: auto;
	}
	@media (max-width: 767px) {
		.formWrapper {
			width: 70%;
			margin: auto;
		}
	}
	@media (max-width: 480px) {
		.formWrapper {
			width: 90%;
			margin: auto;
		}
	}
</style>

</head>