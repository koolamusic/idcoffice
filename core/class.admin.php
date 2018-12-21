<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("Africa/Lagos");

	require_once(ROOT_PATH.'includes/db_connect.php');

	// Class
	class ADMIN {	

		private $connect;

		public function __construct(){
			$database = new Database();
			$db = $database->dbConnection();
			$this->connect = $db;
		}

		public function runQuery($sql){
			$stmt = $this->connect->prepare($sql);
			return $stmt;
		}

		public function lastInsertId(){
			return $this->connect->lastInsertId();
		}

		//Grab site basic info
		public function siteInfo(){
			
			try {
				$results = $this->connect->prepare("SELECT * FROM website_settings 
					WHERE id='1'");
				$results->execute();
			} catch (Exception $e) {
				echo "Data could not be retrived from database.";
				exit;
			}
			$matches = $results->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//Grab contents
		public function contents(){
			
			try {
				$results = $this->connect->prepare("SELECT * FROM contents WHERE c_id='1'");
				$results->execute();
			} catch (Exception $e) {
				echo "Data could not be retrived from database.";
				exit;
			}
			$matches = $results->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//Count Active Users
		public function countUsers(){
			try	{
				$stmt = $this->connect->prepare("SELECT COUNT(*) FROM users
					WHERE status='Active' OR status=''");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			return intval($stmt->fetchColumn(0));
		}

		//
		public function testimonyPayMethodCheck($matchID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations 
					WHERE match_id=:matchID");
				$stmt->execute(array(':matchID'=>$matchID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//Local Currency - Total Transaction
		public function allTrans(){
			try	{
				$stmt = $this->connect->prepare("SELECT SUM(m_amount) FROM match_donations 
					WHERE paymt_method='Bank' AND match_status='Paid'");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			return intval($stmt->fetchColumn(0));
		}

		//Bitcoin - Total Transaction
		public function btc_allTrans(){
			try	{
				$stmt = $this->connect->prepare("SELECT SUM(m_amount) FROM match_donations 
					WHERE paymt_method='BitCoin' AND match_status='Paid'");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			return intval($stmt->fetchColumn(0));
		}

		//All completed Donations
		public function allDonations(){
			try	{
				$stmt = $this->connect->prepare("SELECT COUNT(*) FROM donations 
					WHERE donor_status='Fully Paid'");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			return intval($stmt->fetchColumn(0));
		}
		
		//All Awaiting donations
		public function allawaitingDonors(){
			try	{
				$stmt = $this->connect->prepare("SELECT COUNT(*) FROM donations 
					WHERE donor_status='Available' OR donor_status='Part Payment'");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			return intval($stmt->fetchColumn(0));
		}

		//Display All Matched Pledges
		public function matchedDonors(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations
					ORDER BY date_matched DESC");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//
		public function mDonors($donorID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations
					WHERE donor_id=:donorID");
				$stmt->execute(array(':donorID'=>$donorID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		

		//All admins
		public function adminLists(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM admin ORDER BY id DESC");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//Display All Flaggeds Donations
		public function flaggedDonors(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, donations
					WHERE match_donations.match_to_donor_id=donations.donor_id
					AND (match_donations.match_status='Flagged' 
						OR donations.donor_status='Flagged')
					ORDER BY match_donations.date_matched ASC");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}


		//Display USER Recent Pledge Made
		public function donationsMade($userID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, users
					WHERE match_donations.payer_id=:userID 
					AND match_donations.payee_id=users.login_id
					AND match_donations.match_status='Paid'
					ORDER BY match_donations.date_matched DESC");
				$stmt->execute(array(':userID'=>$userID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//Display USER Recent Pledge Received
		public function donationsReceived($userID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, users
					WHERE match_donations.payee_id=:userID 
					AND match_donations.payer_id=users.login_id
					AND match_donations.match_status='Paid'
					ORDER BY match_donations.date_matched DESC");
				$stmt->execute(array(':userID'=>$userID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}


		//Display Customer Enquiry
		public function enquirys(){
			try	{
				$stmt = $this->connect->prepare("SELECT *
					FROM messaging WHERE NOT sender_name='Admin'
					AND id IN (SELECT MAX(id) FROM messaging 
					GROUP BY sender_id, reciever_id)
					ORDER BY date_sent DESC");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Display Single Enquiry  
		public function msgDetails($senderID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM messaging 
					WHERE reciever_id=:senderID OR sender_id=:senderID
					ORDER BY date_sent ASC");
				$stmt->execute(array(':senderID'=>$senderID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Display lastest news on dashboard
		public function allNews(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM news ORDER BY id DESC");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Single news
		public function newsSingle($newsID){
			
			try {
				$results = $this->connect->prepare("SELECT * FROM news WHERE id = ?");
				$results->bindParam(1, $newsID);
				$results->execute();
			} catch (Exception $e) {
				echo "Data could not be retrived from database.";
				exit;
			}
			$matches = $results->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}
		
		//Testimonies Display - Dashboard
		public function recentTestimonies(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM testimonies 
					WHERE status='Approved' ORDER BY id DESC");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Confirmed Payments
		public function comfirmedPayments(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, users 
					WHERE match_donations.payer_id=users.login_id 
					AND match_donations.match_status='Paid'
					ORDER BY match_donations.date_paid DESC LIMIT 15");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Recently Received Help
		public function recentlyreceived(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM make_payment, users 
					WHERE make_payment.status='Confirmed' AND make_payment.receiver_id=users.login_id LIMIT 2");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
			
		//Recently Offered Help
		public function recentlyOffered(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM make_payment, users 
					WHERE make_payment.status='Confirmed' AND make_payment.login_id=users.login_id LIMIT 2");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Maturing Donation
		public function usersMaturingDonations(){
			$currentTime = date("Y-m-d H:i:s");
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM new_donation 
					WHERE matured_date<:currentTime AND status='In Progress'");
				$stmt->execute(array(':currentTime'=>$currentTime));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Maturing Donations Display
		public function maturingDonations(){
			$currentTime = date("Y-m-d H:i:s");
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations, users 
						WHERE donations.login_id=users.login_id
						AND donations.matured_date>:currentTime 
						AND NOT donor_status='Canceled'");
				$stmt->execute(array(':currentTime'=>$currentTime));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Sum Matching amount
		public function matchingDonations($donorID){
			try	{
				$stmt = $this->connect->prepare("SELECT SUM(m_amount) 
					FROM match_donations WHERE match_to_donor_id=:donorID");
				$stmt->execute(array(':donorID'=>$donorID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			return intval($stmt->fetchColumn(0));
		}

		//Grab User info
		public function userInfo($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM users 
					WHERE login_id=:loginID LIMIT 1");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//List of receiving donations
		public function payeeLists(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations
              WHERE payment_status='Paid'
              AND NOT (donor_status='Canceled' OR donor_status='Frozen')
              AND (request_amt > received_amt
              OR request_amt > (SELECT SUM(m_amount) FROM match_donations
                    WHERE match_to_donor_id=donor_id))");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//List of Donation to send out
		public function payerLists(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations, users
					WHERE NOT donations.donor_status='Canceled'
					AND users.login_id=donations.login_id
					AND donations.donor_id 
						NOT IN (SELECT donating_id 
						FROM match_donations)");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//Matured Donations Display
		public function maturedDonations(){
			$currentTime = date("Y-m-d H:i:s");
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations, users
					WHERE donations.login_id=users.login_id
					AND donations.matured_date<:currentTime");
				$stmt->execute(array(':currentTime'=>$currentTime));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Donations Display
		public function donations(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations, users
					WHERE donations.login_id=users.login_id");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		
		//Testimonies Display
		public function testimonies(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM testimonies");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Approved Testimonies Display
		public function approvedTestimonies(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM testimonies WHERE status='Approved'");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Approved Testimonies Display
		public function pendingTestimonies(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM testimonies WHERE status='pending' OR status=''");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Users Display
		public function users(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM users");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Active Users Display
		public function activeUsers(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM users WHERE status='Active' OR status=''");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Blocked Users Display
		public function blockedUsers(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM users WHERE status='Blocked'");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		// User Single
		public function userSingle($id){
			
			try {
				$results = $this->connect->prepare("SELECT * FROM users WHERE login_id = ?");
				$results->bindParam(1, $id);
				$results->execute();
			} catch (Exception $e) {
				echo "Data could not be retrived from database.";
				exit;
			}
			$matches = $results->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}
		
		//Recent Admin Activity Display		
		public function get_recentAdminAccess(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM admin_login_activity ORDER BY last_access DESC LIMIT 6");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Recent Admini Activity Display
		public function get_adminNote(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM admin_notes ORDER BY note_date DESC LIMIT 5");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//Count admin Users
		public function get_countAdminUsers(){
			try	{
				$stmt = $this->connect->prepare("SELECT COUNT(*) AS adminUsers FROM admin WHERE status='Active'");
				$stmt->execute();
				$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $userRow["adminUsers"];
			
			return $matches;
		}
		
		
		//Count admin Logged Users
		public function get_countAdminLoggedIN(){
			try	{
				$stmt = $this->connect->prepare("SELECT COUNT(*) AS loggedIN FROM admin_logged_in_users");
				$stmt->execute();
				$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $userRow["loggedIN"];
			
			return $matches;
		}
		
		
		public function doLogin($username, $password){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM admin WHERE email=:email OR username=:username ");
				$stmt->execute(array(':email'=>$username, ':username'=>$username));
				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)				{
					if(password_verify($password, $userRow['password'])) {
						$_SESSION['admin_session'] = $userRow['id'];
						
						//INSERT INTO LOGIN ACTIVITY
						$ip = getenv('REMOTE_ADDR');
						$stmt = $this->connect->prepare("INSERT INTO admin_login_activity (admin, ip, last_access)
									VALUES (:userName, :ip, now())");
						$stmt->execute(array(':userName'=>$userRow["username"], ':ip'=>$ip));

						//INSERT INTO logged in USER TABLE
						$stmt = $this->connect->prepare("INSERT INTO admin_logged_in_users (admin, logged_in_time)
									VALUES (:userName, now())");
						$stmt->execute(array(':userName'=>$userRow["username"]));
						
						return true;
					}else{
						return false;
					}
				}
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
		}
		
		public function is_loggedin(){
			if(isset($_SESSION['admin_session'])) {
				return true;
			}
		}

		public function redirect($url){
			header("Location: $url");
		}

		public function doLogout(){
			session_destroy();
			unset($_SESSION['admin_session']);
			return true;
		}
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
?>