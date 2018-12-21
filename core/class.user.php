<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	date_default_timezone_set("Africa/Lagos");

	require_once(ROOT_PATH.'includes/db_connect.php');

	// Class
	class USER {	

		private $connect;

		public function __construct(){
			$database = new Database();
			$db = $database->dbConnection();
			$this->connect = $db;
		}
		
		public function lastInsertId(){
			return $this->connect->lastInsertId();
		}

		public function runQuery($sql){
			$stmt = $this->connect->prepare($sql);
			return $stmt;
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
		
		//Display Customer Enquiry
		public function supports($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT *
					FROM messaging WHERE sender_id=:loginID OR reciever_id=:loginID
					AND id IN (SELECT MAX(id) FROM messaging 
					GROUP BY subject)
					ORDER BY date_sent DESC");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Display Single Enquiry  
		public function msgDetails($msgID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM messaging 
					WHERE id=:msgID
					ORDER BY date_sent ASC");
				$stmt->execute(array(':msgID'=>$msgID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//Referrals Display
		public function referrals($email, $username){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM users 
					WHERE referral=:email OR referral=:username");
				$stmt->execute(array(':email'=>$email, ':username'=>$username));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Display lastest news on dashboard
		public function dashboardNews(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM news ORDER BY id DESC LIMIT 1");
				$stmt->execute();
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

		//Grab percenates
		public function percentage(){			
			try {
				$results = $this->connect->prepare("SELECT * FROM percentaging WHERE id='1'");
				$results->execute();
			} catch (Exception $e) {
				echo "Data could not be retrived from database.";
				exit;
			}
			$matches = $results->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
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

		//Count user donation for multiple referral bonus offer
		public function countMYdonations($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT COUNT(*) AS cnt FROM donations 
					WHERE login_id=:loginID AND NOT donor_status='Canceled'");
				$stmt->execute(array(':loginID'=>$loginID));
				$dnts = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			return $dnts['cnt'];
		}

		//Count pending donation requests
		public function countMYpendingDos($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT COUNT(*) AS cnt FROM donations 
					WHERE login_id=:loginID AND donor_status='In Progress'");
				$stmt->execute(array(':loginID'=>$loginID));
				$dnts = $stmt->fetch(PDO::FETCH_ASSOC);
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			return $dnts['cnt'];
		}
		
		//Maturing Donation
		public function maturingDonations($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations 
					WHERE login_id=:loginID 
					AND NOT (donor_status='Completed' OR donor_status='Canceled')
					ORDER BY donor_id DESC LIMIT 10");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//Receiving Donation Single
		public function receivingDonateSingle($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, donations 
					WHERE match_donations.payee_id=:loginID
					AND NOT match_donations.match_status='Paid' 
					AND NOT match_donations.match_to_donor_id='0'
					AND NOT donations.donor_status='Canceled' 
					AND match_donations.match_to_donor_id=donations.donor_id 
					ORDER BY match_donations.date_matched DESC LIMIT 1");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//Sending donations Single
		public function myDonationsSingle($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, donations
					WHERE match_donations.payer_id=:loginID
					AND NOT match_donations.match_status='Paid'
					AND NOT match_donations.match_to_donor_id='0' 
					AND match_donations.donating_id=donations.donor_id 
					ORDER BY match_donations.date_matched DESC LIMIT 1");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}

			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		
		//Recently Offered Help
		public function recentlyOffered(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations  
					WHERE NOT amount='0' ORDER BY date_added DESC LIMIT 2");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		
		//Recently Received Help
		public function recentlyrecvd(){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations
					WHERE match_status='Paid' AND NOT m_amount='0' 
					ORDER BY date_paid DESC LIMIT 2");
				$stmt->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Make payment Display
		public function makePayments($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, users 
					WHERE match_donations.payer_id=:loginID
					AND match_donations.payee_id=users.login_id
					AND NOT match_donations.match_status='Paid'");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//Recent GET HELP User dashboard
		public function recentHelps($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, users 
					WHERE match_donations.payee_id=:loginID
					AND match_donations.payer_id=users.login_id
					ORDER BY period_timer DESC LIMIT 5");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
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


		//Recent PROVIDE HELP User dashboard
		public function recentProvideHelps($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, users 
					WHERE match_donations.payer_id=:loginID
					AND match_donations.payee_id=users.login_id
					ORDER BY period_timer DESC LIMIT 5");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//
		public function grabDonorInfo($matchID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM payment_proof, match_donations, donations 
					WHERE payment_proof.match_id=:matchID
					AND match_donations.match_id=:matchID
					AND match_donations.donating_id=donations.donor_id");
				$stmt->execute(array(':matchID'=>$matchID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}
		
		//Confirm payment Display
		public function confirmPayments($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM payment_proof, match_donations, donations 
						WHERE payment_proof.payee_id=:loginID
						AND NOT payment_proof.pay_status='Paid'
						AND payment_proof.match_id=match_donations.match_id
						AND match_donations.match_to_donor_id=donations.donor_id");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//
		public function payInfo($matchID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM payment_proof, users 
					WHERE payment_proof.match_id=:matchID
					AND payment_proof.payer_id=users.login_id");
				$stmt->execute(array(':matchID'=>$matchID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}
		
		//Request History Display
		public function requests($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM payment_proof, match_donations, donations
					WHERE match_donations.payee_id=:loginID
					AND payment_proof.match_id=match_donations.match_id
					AND donations.donor_id=match_donations.match_to_donor_id");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Referrals Bonus Display
		public function referral_bonus($username){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM referral_bonus 
					WHERE referral_username=:username");
				$stmt->execute(array(':username'=>$username));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
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
		
		//Testimonies Display
		public function testimonies(){
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
		
		//Testimonies Display
		public function writeTestimonies($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations
		            WHERE payee_id=:loginID 
		            ORDER BY date_paid DESC");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}

		//Grab user testimonies
		public function testimonyEdit($loginID, $matchID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM testimonies
					WHERE login_id=:loginID AND pay_id=:matchID");
				$stmt->execute(array(':loginID'=>$loginID, ':matchID'=>$matchID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//Testimonies Display
		/*public function writeTestimonies($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM testimonies WHERE login_id=:loginID");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}*/

		//Proof of payment
		public function checkPaymt($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM payment_proof, match_donations
					WHERE payment_proof.match_id=match_donations.match_id
					AND payment_proof.payer_id=:payerID
					AND payment_proof.pay_status='Paid'");
				$stmt->execute(array(':payerID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//Proof of payment
		public function ifPaid($donorID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM match_donations, donations
					WHERE donations.donor_id=:donorID
					AND match_donations.donating_id=:donorID");
				$stmt->execute(array(':donorID'=>$donorID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}


		//Grab User info
		public function userInfo($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM users WHERE login_id=:loginID");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//Grab User info
		public function acctInfo($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM account_details 
					WHERE login_id=:loginID");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($matches === false) return $matches;		
			return $matches;
		}

		//Grab User info
		public function checkPOP($matchID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM payment_proof 
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

		//Chech Status
		public function trnStatus($paymentStatus, $trnID){
			if($paymentStatus == "Canceled"){
				$trnStatus = '<span style="background:#999;color: #FFF;padding: 3px 5px;border-radius:5px;">'.$paymentStatus.'</span>';
			}elseif($paymentStatus != "Paid"){
				$trnStatus = '<a style="background:#AC2224;color: #FFF;padding: 3px 5px;border-radius:5px;" href="'.BASE_URL.'user/transaction-history?trn='.$trnID.'">Cancel my Offer</a>';
			}else{
				$trnStatus = '<span style="background:#279E40;color: #FFF;padding: 3px 5px;border-radius:5px;">'.$paymentStatus.'</span>';
			}
			
			return $trnStatus;
		}
		
		//Chech Status - Make request
		public function mrTrnStatus($status){
			if($status == "Canceled"){
				$trnStatus = '<span style="background:#999;color: #FFF;padding: 3px 5px;border-radius:5px;">'.$status.'</span>';
			}elseif($status == "In Progress"){
				$trnStatus = '<span style="background:#4CB3CD;color: #FFF;padding: 3px 5px;border-radius:5px;">'.$status.'</span>';
			}elseif($status == "Matured"){
				$trnStatus = '<span style="background:#279E40;color: #FFF;padding: 3px 5px;border-radius:5px; text-decoration:none">Matured, Awaiting match</span>';
			}else{
				$trnStatus = '<span style="background:#279E40;color: #FFF;padding: 3px 5px;border-radius:5px;">'.$status.'</span>';
			}
			
			return $trnStatus;
		}

		//Transaction History Display
		public function transactionHistory($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations 
					WHERE login_id=:loginID AND NOT donor_status='Canceled'");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		//Make Request Display
		public function makeRequests($loginID){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM donations 
					WHERE login_id=:loginID 
					AND (donor_status='In Progress' 
						OR donor_status='Matured' 
						OR donor_status='Matched')");
				$stmt->execute(array(':loginID'=>$loginID));
			}
			catch(PDOException $e){
				echo $e->getMessage();
			}
			
			$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $matches;
		}
		
		
				
		//Registration Function
		public function register($username, $fullname, $email, $phone, $password, $referrer, $country){
			try	{
				$new_password = password_hash($password, PASSWORD_DEFAULT);		
				$stmt = $this->connect->prepare("INSERT INTO users(username, email, phone, password, full_name, referral, country, signup_date, last_login, last_updated, credibility_score, status, user_timer) 
												
				VALUES(:username, :email, :phone, :password, :fullname, :referrer, :country, now(), now(), now(), '100', 'Pending', now())");

				$stmt->bindparam(":username", $username);
				$stmt->bindparam(":fullname", $fullname);
				$stmt->bindparam(":email", $email);
				$stmt->bindparam(":phone", $phone);
				$stmt->bindparam(":password", $new_password);
				$stmt->bindparam(":referrer", $referrer);
				$stmt->bindparam(":country", $country);	
				$stmt->execute();
				$loginID = $this->connect->lastInsertId();
				
				//include welcome Email File
				include(ROOT_PATH."emailTemplates/email-verify.php");
				
				return $stmt;	
			}
			catch(PDOException $e)	{
				echo $e->getMessage();
			}				
		}


		public function doLogin($userLoginID, $password){
			try	{
				$stmt = $this->connect->prepare("SELECT * FROM users 
					WHERE status='Active'
					AND (email=:email OR phone=:phone  OR username=:email) ");
				$stmt->execute(array(':email'=>$userLoginID, ':phone'=>$userLoginID));
				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)				{
					if(password_verify($password, $userRow['password'])) {
						$_SESSION['user_session'] = $userRow['login_id'];
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
			if(isset($_SESSION['user_session'])) {
				return true;
			}
		}

		public function redirect($url){
			header("Location: $url");
		}

		public function doLogout(){
			session_destroy();
			unset($_SESSION['user_session']);
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