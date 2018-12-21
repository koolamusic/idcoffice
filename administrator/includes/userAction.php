<?php
	if(isset($_POST["unBlock"])) {
		if(!isset($_POST['tick'])) {
			$auth_user->redirect(BASE_URL.'administrator/users?warning');
			exit();
		}
		$cnt = array();
		$cnt = count($_POST['tick']);
		for($i = 0; $i < $cnt; $i++) {
			$rowID = $_POST['tick'][$i];
			try	{
				$stmt = $auth_user->runQuery("UPDATE users SET status='Active', user_timer=now() 
					WHERE login_id=:rowID limit 1");
				$stmt->execute(array(':rowID'=>$rowID));				
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}			
		}
		$auth_user->redirect(BASE_URL.'administrator/users?unblocked');
		exit();
	}elseif(isset($_POST["block"])) {
		if(!isset($_POST['tick'])) {
			$auth_user->redirect(BASE_URL.'administrator/users?warning');
			exit();
		}
		$cnt = array();
		$cnt = count($_POST['tick']);	
		for($i = 0; $i < $cnt; $i++) {
			$rowID = $_POST['tick'][$i];
			try	{
				$stmt = $auth_user->runQuery("UPDATE users SET status='Blocked' 
					WHERE login_id=:rowID limit 1");
				$stmt->execute(array(':rowID'=>$rowID));				
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}			
		}
		$auth_user->redirect(BASE_URL.'administrator/users?blocked');
		exit();
	}elseif(isset($_POST["delete"])) {
		if(!isset($_POST['tick'])) {
			$auth_user->redirect(BASE_URL.'administrator/users?warning');
			exit();
		}
		$cnt = array();
		$cnt = count($_POST['tick']);
		for($i = 0; $i < $cnt; $i++) {
			$rowID = $_POST['tick'][$i];
			try	{
                $stmt = $auth_user->runQuery("UPDATE donations SET donor_status='Canceled' 
                    WHERE login_id=:rowID 
                    AND received_amt < request_amt");
                $stmt->execute(array(':rowID'=>$rowID));

                $stmt = $auth_user->runQuery("DELETE FROM match_donations 
                    WHERE match_status='Pending' 
                    AND (payer_id=:rowID OR payee_id=:rowID)");
                $stmt->execute(array(':rowID'=>$rowID));
                
				$stmt = $auth_user->runQuery("DELETE FROM users WHERE login_id=:rowID limit 1");
				$stmt->execute(array(':rowID'=>$rowID));				
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}
		$auth_user->redirect(BASE_URL.'administrator/users?Deleted');
	}
?>