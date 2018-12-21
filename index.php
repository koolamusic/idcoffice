<?php 
	require("includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");

	$auth_user = new USER();

	//include updator
	include(ROOT_PATH . "core/updator.php");
?>
<?php include(ROOT_PATH."includes/header.php"); ?>
<section id="featured">

<!-- Slider -->
	<div id="main-slider" class="flexslider">
		<ul class="slides">
		  <li>
			<img src="img/slides/photo11.jpeg" alt="Banner" />
			<div class="flex-caption" align="center">
			<h3>Get Help Worldwide</h3> 
			<p>A community of people providing each other financial help on the principle of gratuitousness, reciprocity and benevolence...</p>  
		  </div>
		  </li>
		</ul>
	</div>
<!-- end slider -->

</section>
<div class="featured_content">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-3 feature_grid1"> <i class="fa fa-trophy fa-3x"></i>
        <h3><a href="#">Access to helper community</a></h3>
        <p>We give you a technical basic program, which helps millions of participants worldwide to find those who NEED help, and those who are ready to PROVIDE help for FREE</p>
      </div>
      <div class="col-md-3 feature_grid1"> <i class="fa fa-picture-o fa-3x"></i>
        <h3><a href="#">no lenders & no debtors</a></h3>
        <p>There are no lenders and no debtors. Everything is very simple: one participant asks for help - another one helps.</p>
     </div>
      <div class="col-md-3 feature_grid1"> <i class="fa fa-flask fa-3x"></i>
        <h3><a href="#">30-50% Bonus Increment</a></h3>
        <p>When you provide help to a member, another will provide help to you with 30% Increase if help was provided in Local Currency and 50% increase if in Bitcoin in 30 days maximum. If you are willing to do so join us now</p>
      </div>
        
      <div class="col-md-3 feature_grid1"> <i class="fa fa-user fa-3x"></i>
        <h3><a href="#">Fully Automated System</a></h3>
        <p>All funds transferred to other participants are help given at your own good to another participant, absolutely gratis</p>
      </div>
    </div>
  </div>
</div>
 
<section id="content">
	<div class="container">
		<div class="row" style="border-bottom: 1px solid #EEE; padding-bottom: 20px;">
			<div class="col-md-6">
				<img src="img/ipad.png" alt=""/> 
			</div>
	  		<div class="col-md-6">
				<div class="panel-group" id="accordion-alt3">
				  <?php echo nl2br($content['middle_right']);?>
				</div>
			</div>
		</div>
	</div>
</section>
<section id="content">
	<div class="container">
		<h3 align="center">FREQUENTLY ASKED QUESTIONS</h3>
		<div class="row">
			<div class="col-md-6">
				<div class="panel-group" id="accordion-alt3">
				  <?php echo nl2br($content['home_bottom_left']);?>
				</div>
			</div>
  		
	  		<div class="col-md-6">
				<div class="panel-group" id="accordion-alt3">
				  <?php echo nl2br($content['home_bottom_right']);?>
				</div>				
			</div>
		</div>
	</div>
</section>

<?php include(ROOT_PATH."includes/footer.php"); ?>	