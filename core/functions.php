<?php
	//Main ads view
	function ad_view($ad) {
		
		//Favourite
		$sql = "SELECT ad_id FROM favourites WHERE ad_id='".$ad["id"]."' LIMIT 1"; 
		$query = mysqli_query($db_connect, $sql); 
		$count = mysqli_num_rows($query);
		if($count > 0 ){
			$favourate = '<form method="post" action="" title="Favourite" style="padding-to:40px; position:absolute; z-index:100; top: 10%; left: 80%;">
			<input type="hidden" name="pid" value="'.$ad["id"].'">
			<button class="favorite"><i style="color:#008C69" class="fa fa-star"></i></button>
			</form>';
		}else{
			$favourate = '<form method="post" action="" title="Favourite" style="padding-to:40px; position:absolute; z-index:100; top: 10%; left: 80%;">
			<input type="hidden" name="pid" value="'.$ad["id"].'">
			<button class="favorite"><i class="fa fa-star"></i></button>
			</form>';
		}
		//
		$output = "";
		$output = $output . '<!--Single-->
                                <div class="col-md-12">
                                    <div class="listSingle">
                                    	<div class="row">
                                            <div class="col-md-3">
                                                <div class="imgWrapper">
                                                    <a href="'.BASE_URL.'detail?id='.$ad["id"].'&t='.str_replace(' ', '-', trim($ad["title"])).'"><img src="'.BASE_URL.'img/uploads/small/'.$url.'.'.$ext.'" alt="'.$ad["title"].'"/></a>
                                                </div>
                                            </div>                            
                                            <div class="col-md-9">                            
                                                <div class="infoWrapper">
                                                	<div class="row">
                                                        <div class="col-md-9 custom">
                                                            <h3> <a href="'.BASE_URL.'detail?id='.$ad["id"].'&t='.str_replace(' ', '-', trim($ad["title"])).'">'.$title.'</a></h3>
                                                            <p>'.$description.'</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <span class="price">₦'.number_format($ad["price"]).'</span> 
                                                        </div>
                                            			<div style="clear:both;"></div>
                                                        <div class="col-md-9">
                                                            <span> <i class="fa fa-map-marker"></i> 
                                                            '.$ad["city"].', '.$ad["state"].', '.timeAgo($ad["updated"]).' &mdash; '.$ad["sub_category"].' - '.$ad["category"].' &raquo; '.$ad["make"].'</span> 
                                                        </div>
                                                        <div class="col-md-3" align="right"> 
                                                            <span>
															'.$favourate.'
															</span>
															
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    	</div>
                                    </div>
                                </div>
                                <!--End single-->';
		
		return $output;
	}
	//Time ago function
	function timeAgo($dateTime){
		$dateTime = strtotime($dateTime);
		$cur_time   = time();
		$time_elapsed   = $cur_time - $dateTime;
		$seconds    = $time_elapsed ;
		$minutes    = round($time_elapsed / 60 );
		$hours      = round($time_elapsed / 3600);
		$days       = round($time_elapsed / 86400 );
		$weeks      = round($time_elapsed / 604800);
		$months     = round($time_elapsed / 2600640 );
		$years      = round($time_elapsed / 31207680 );
		// Seconds
		if($seconds <= 60){
			return '<span style="color:#096;font-style:italic;">Just now</span>';
		}
		//Minutes
		else if($minutes <= 60){
			if($minutes == 1){
				return '<span style="color:#096;font-style:italic;">1 minute ago</span>';
			}
			else{
				return "<span style=\"color:#096;font-style:italic;\">$minutes minutes ago</span>";
			}
		}
		//Hours
		else if($hours <= 24){
			if($hours == 1){
				return "<span style=\"color:#096;font-style:italic;\">an hour ago</span>";
			}else{
				return "<span style=\"color:#096;font-style:italic;\">$hours hrs ago </span>";
			}
		}
		//Days
		else if($days <= 7){
			if($days == 1){
				return "Yesterday";
			}else{
				return "$days days ago";
			}
		}
		//Weeks
		else if($weeks <= 4.3){
			if($weeks == 1){
				return "a week ago";
			}else{
				return "$weeks weeks ago";
			}
		}
		//Months
		else if($months <= 12){
			if($months == 1){
				return "a month ago";
			}else{
				return "$months months ago";
			}
		}
		//Years
		else{
			if($years == 1){
				return "1 year ago";
			}else{
				return "$years years ago";
			}
		}
	}
	
	function get_editAd_single($id) {
		require(ROOT_PATH . "includes/database.php" );
		try {
			$results = $db->prepare("SELECT * FROM ads WHERE id = ?");
			$results->bindParam(1, $id);
			$results->execute();
		} catch (Exception $e) {
			echo "Data could not be retrived from database.";
			exit;
		}
		$user = $results->fetch(PDO::FETCH_ASSOC);
		if ($user === false) return $user;		
		return $user;
	}
?>