<?php
	$session = new USER();
	
	// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
	// put this file within secured pages that users (users can't access without login)
	
	if(!$session->is_loggedin()){
		// session no set redirects to login page
		$session->redirect(BASE_URL.'login');
		exit();
	}else{
		$auth_user = new USER();	
		$loginID = $_SESSION['user_session'];
		$stmt = $auth_user->runQuery("SELECT * FROM users WHERE login_id=:loginID");
		$stmt->execute(array(":loginID"=>$loginID));
		$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if($userInfo['status'] != 'Active'){
			unset($_SESSION['user_session']);
		}
		//Retrieve site info
    	$siteInfo = $auth_user->siteInfo();

    	$stmt = $auth_user->runQuery("SELECT * FROM admin_notice WHERE id='1'");
      	$stmt->execute();
     	$notice = $stmt->fetch(PDO::FETCH_ASSOC);
    	
		//include updator
		include(ROOT_PATH . "core/updator.php");
	}