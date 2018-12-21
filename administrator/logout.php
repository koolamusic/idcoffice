<?php
	require("../includes/config.php"); 
	require_once(ROOT_PATH . "core/class.admin.php");
	require_once(ROOT_PATH . "core/adminSession.php");

	$user_logout = new ADMIN();
	/*
	if($user_logout->is_loggedin() !="" )	{
		$user_logout->redirect(BASE_URL.'administrator');
		exit();
	}
*/
	try	{
		$stmt = $user_logout->runQuery("SELECT * FROM admin_logged_in_users 
			WHERE admin=:userName LIMIT 1");
		$stmt->execute(array(':userName'=>$userInfo["username"]));
		$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$stmt = $user_logout->runQuery("DELETE FROM admin_logged_in_users WHERE id=:idTOdelete");
		$stmt->execute(array(':idTOdelete'=>$userInfo["id"]));
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}

	$user_logout->doLogout();
	$user_logout->redirect(BASE_URL.'administrator/login');
