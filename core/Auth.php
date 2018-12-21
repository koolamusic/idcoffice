<?php
	session_start(); 
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	//require("../includes/config.php");
	//-------------------------------------------------------------------------------------------------------	
	
	include(ROOT_PATH ."includes/db_connect.php"); 
	// Initialize some vars
	$user_ok = false;
	$log_userID = ""; 
	$log_userEmail = ""; 
	$log_userPassword = "";
	$log_firstName = ""; 
	
	// Verify function
	function evalLogged($db_connect,$userID, $userEmail,$userPassword){ 
		$sql = "SELECT last_login_ip FROM user_login WHERE id='$userID' AND email='$userEmail' AND password='$userPassword' LIMIT 1"; 
		$query = mysqli_query($db_connect, $sql); 
		$numrows = mysqli_num_rows($query); 
		if($numrows > 0){ 
			return true; 
		} 
	} 

	// sessions retrieved
	if(isset($_SESSION["myID"]) && 
		isset($_SESSION["myEmail"]) &&
		isset($_SESSION["myPassword"]) &&
		isset($_SESSION["my_firstName"])) { 
		$log_userID = preg_replace('#[^0-9]#', '', $_SESSION['myID']); 
		$log_userEmail = preg_replace('#[^a-z0-9.@-_]#i', '', $_SESSION['myEmail']); 
		$log_userPassword = preg_replace('#[^a-z0-9]#i', '', $_SESSION['myPassword']); 
		$log_firstName = preg_replace('#[^a-z0-9]#i', '', $_SESSION['my_firstName']); 
		
		// Verify the member
		$user_ok = evalLogged(
					$db_connect,
					$log_userID,
					$log_userEmail,
					$log_userPassword,
					$log_firstName
					);
	} else if(isset($_COOKIE["myID"]) && 
				isset($_COOKIE["myEmail"]) && 
				isset($_COOKIE["myPassword"]) &&
				isset($_COOKIE["my_firstName"])) {
		$_SESSION['myID'] = preg_replace('#[^0-9]#', '', $_COOKIE['myID']); 
		$_SESSION['myEmail'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['myEmail']);
		$_SESSION['myPassword'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['myPassword']);
		$_SESSION['firstName'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['firstName']);  
		
		$log_userID = $_SESSION['myID']; 
		$log_userEmail = $_SESSION['myEmail'];  
		$log_userPassword = $_SESSION['myPassword'];  
		$log_firstName = $_SESSION['firstName'];
		
		// Verify the user
		$user_ok = evalLogged(
					$db_connect,
					$log_userID,
					$log_userEmail,
					$log_userPassword,
					$log_firstName
					); 
		if($user_ok == true){ 
			// Update user lastlogin datetime field
			$sql = "UPDATE user_login SET last_login=now() WHERE id='$log_userID' LIMIT 1"; 
			$query = mysqli_query($db_connect, $sql); 
		} 
	}
	
	// Set favourite
	if(isset($_POST['pid'])) {	
		if(isset($_SESSION['myID'])){		 
			$adID = intval(mysqli_real_escape_string($db_connect, $_POST['pid']));
			//Check if user already added this ad
			$sql = "SELECT ad_id FROM favourites WHERE ad_id='$adID' LIMIT 1"; 
			$query = mysqli_query($db_connect, $sql); 
			$count = mysqli_num_rows($query);
			if($count > 0) {
				// Delete from favourite if already added
				$sql = "DELETE FROM favourites WHERE ad_id='$adID'"; 
				$query = mysqli_query($db_connect, $sql);
			}else{
				// Insert into table if not already added
				$sql = "INSERT INTO favourites (login_id, ad_id, date_added)
					VALUES('".$_SESSION['myID']."', '$adID', now())"; 
				$query = mysqli_query($db_connect, $sql);
			}
		}else{
			header("Location: ".BASE_URL."login");
			exit();
		}
	}
?>