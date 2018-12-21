<?php
	//Checks if session is set
	if((!isset($_SESSION['agreed']) AND $notice['note'] == '') OR strlen($notice['note']) < 20 ){
	    $_SESSION['agreed'] = 'Agreed';
	}

	//Redirect to urgent message if not set
	if(!isset($_SESSION['agreed'])){
		$auth_user->redirect(BASE_URL.'user/urgent-notice');
		exit();
	}
?>