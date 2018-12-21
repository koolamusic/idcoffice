<?php
	$session = new ADMIN();
	
	// if user session is not active(not loggedin) this page will help 'home.php and profile.php' to redirect to login page
	// put this file within secured pages that users (users can't access without login)
	
	if(!$session->is_loggedin()){
		// session no set redirects to login page
		$session->redirect(BASE_URL.'administrator/login');
		exit();
	}else{
		$auth_user = new ADMIN();	
		$adminID = $_SESSION['admin_session'];
		$stmt = $auth_user->runQuery("SELECT * FROM admin WHERE id=:adminID");
		$stmt->execute(array(":adminID"=>$adminID));
		$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

		//Site settings
		$stmt = $auth_user->runQuery("SELECT * FROM website_settings WHERE id='1'");
		$stmt->execute();
		$siteInfo = $stmt->fetch(PDO::FETCH_ASSOC);

		//include updator
		include(ROOT_PATH . "core/updator.php");
	}