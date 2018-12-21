<?php 
	require("includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");

	$auth_user = new USER();

	//include updator
	include(ROOT_PATH . "core/updator.php");
?>
<?php include(ROOT_PATH."includes/header.php"); ?>
	<section id="inner-headline">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h2 class="pageTitle">LEGALITY</h2>
			</div>
		</div>
	</div>
	</section>
	<section id="content">
		<div class="container content">	
			<div class="row"> 
				<div class="col-md-12">
					<div class="about-logo">
						<?php echo nl2br($content['legality']);?>
					</div> 
				</div>
			</div>		
        

        
    </div>
    </section>
<?php include(ROOT_PATH."includes/footer.php"); ?>	