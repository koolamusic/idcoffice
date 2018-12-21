<?php
	require("includes/config.php"); 
	require_once(ROOT_PATH . "core/class.user.php");
	require_once(ROOT_PATH . "core/session.php");

	$user_logout = new USER();
	
	if($user_logout->is_loggedin() !="" )	{
		$user_logout->redirect(BASE_URL.'user');
	}
	if(isset($_GET['true'])) {
		$user_logout->doLogout();
		$user_logout->redirect(BASE_URL.'login');
	}
