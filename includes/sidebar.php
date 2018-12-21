<div class="featureSide">
	<?php if(isset($session) AND $session == "My-account"){?>
    	<div>
        	<div>
            	<div class="photoSidebar-sec">
                	<div class="photoSidebar">
                    	<img src="<?php echo BASE_URL;?>img/user/photo.jpg" alt="">
            		</div>
                </div>
                <!--<div style="clear:both;"></div>-->
                <div class="infoSidebar-sec">
					<div class="title-holder">
						<h5 style="font-size:16px;">
						<a href="<?php echo BASE_URL;?>My-Account/settings">
						<?php echo $firstName.' '.$lastName;?></a>
                        </h5>
                        <h4><?php echo $role;?> Advertsiser</h4>
					</div>
					<div class="text-holder">
						<p>
                        	<span><i class="fa fa-map-marker"></i>
								<?php if($state != ""){?> 
                                <?php echo $city.', '.$state;?>
                                <?php }else{?>
                                No location set
                                <?php }?>
                            </span> 
                            <span><b><?php echo $userAds;?></b> Ad(s)</span> 
						</p>
					</div>
				</div>
            </div>
        </div>
    <?php }else{?>
	<h3>Category</h3>
	<ul>
    	<?php if($category == "Vehicles" OR 
				$category == "vehicles" OR 
				$category == "Cars" OR 
				$category == "cars" OR 
				$category == "Trucks" OR 
				$category == "trucks" OR 
				$category == "Vehicle-Parts-and-Accessories" OR 
				$category == "Motorcycles-and-Scooters" OR 
				$category == "Heavy-Equipments" OR 
				$category == "Watercrafts"){ ?>
    	<li class="parent"><a class="head" href="<?php echo BASE_URL;?>Vehicles">Vehicles</a>
        	<ul>
                <li><a href="<?php echo BASE_URL;?>Cars">-  &nbsp; Cars</a></li>
                <li><a href="<?php echo BASE_URL;?>Trucks">-  &nbsp; Trucks</a></li>
                <li><a href="<?php echo BASE_URL;?>Vehicle-Parts-and-Accessories">-  &nbsp; Vehicle Parts and Accessories</a></li>
                <li><a href="<?php echo BASE_URL;?>Motorcycles-and-Scooters">-  &nbsp; Motorcycles and Scooters</a></li>
                <li><a href="<?php echo BASE_URL;?>Heavy-Equipments">-  &nbsp; Heavy Equipments</a></li>
                <li><a href="<?php echo BASE_URL;?>Watercrafts">- &nbsp; Watercrafts</a></li>
            </ul>
        </li>
        <?php }?>
    	<li><a href="<?php echo BASE_URL;?>Phones-and-Tablets">Mobile Phones and Tablets</a></li>
    	<li><a href="<?php echo BASE_URL;?>Jobs">Jobs</a></li>
    	<li><a href="<?php echo BASE_URL;?>Electronics">Electronics</a></li>
    	<li><a href="<?php echo BASE_URL;?>Vehicles">Vehicles</a></li>
    	<li><a href="<?php echo BASE_URL;?>Home-and-Garden">Home and Garden</a></li>
    	<li><a href="<?php echo BASE_URL;?>Fashion">Fashion</a></li>
    	<li><a href="<?php echo BASE_URL;?>Health-and-Beauty">Health and Beauty</a></li>
    	<li><a href="<?php echo BASE_URL;?>Property">Property</a></li>
    	<li><a href="<?php echo BASE_URL;?>Hobbies-Art-Sport">Hobbies - Art - Sport</a></li>
    	<li><a href="<?php echo BASE_URL;?>Babies-and-Kids">Babies and Kids</a></li>
    	<li><a href="<?php echo BASE_URL;?>Animals-and-Pets">Animals and Pets</a></li>
    	<li><a href="<?php echo BASE_URL;?>Services">Services</a></li>
    	<li><a href="<?php echo BASE_URL;?>Others">Other Categories</a></li>
    </ul>
    <?php }?>
</div>
<br>
<div class="featureSide2">
    <!--<br><br>
    <p>Google Adsense <br>here</p>-->
</div>