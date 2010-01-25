<?php

	$name = 	(strlen($_REQUEST['username']) > 0) ? $_REQUEST['username'] : 'Annonymous Coward';
	$email = 	$_REQUEST['email'];
	$message =	$_REQUEST['comment'];
	
	$status = array('sent' => 0, 'error' => array());
	
	// Send mail IFF name, email and message are valid
	if (strlen($name) < 1) {
		$status['error'][] = 'Name is required.';
	}
	
	$pattern =	"^[a-z]+[a-z0-9_-]*(([.]{1})|([a-z0-9_-]*))[a-z0-9_-]+" .
				"[@]{1}[a-z0-9_-]+[.](([a-z]{2,3})|([a-z]{3}[.]{1}[a-z]{2}))$";
	if (strlen($email) < 1 || !eregi($pattern, $email)) {
		$status['error'][] = 'Your e-mail address is in an invalid format.';
	}
	
	if (strlen($message) < 1) {
		$status['error'][] = 'Your comment cannot be empty.';
	}
	
	if (count($status['error']) > 0) {
		$status['error'] = implode('<br/>', $status['error']);
	}
	else {
		// attach headers
		$to = 'tim.younger@bustedtubes.com';
		$subject = 'IDF Feedback from '.$name;
		$headers = 'From: '.$name.' <mailer@bustedtubes.com>\r\n Reply-To: '.$email.'\r\n X-Mailer: PHP/' . phpversion();
		if (mail($to,$subject,$message,$headers)) {
			$status['sent'] = 1;
		}
	}
	
	echo json_encode($status);
	exit;
	
?>
