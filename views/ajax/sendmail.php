<?php

	$name = 	(strlen($_REQUEST["username"]) > 0) ? $_REQUEST["username"] : "Annonymous Coward";
	$email = 	$_REQUEST["email"];
	$message =	$_REQUEST["comment"];
	
	$pattern =	"^[a-z]+[a-z0-9_-]*(([.]{1})|([a-z0-9_-]*))[a-z0-9_-]+" .
				"[@]{1}[a-z0-9_-]+[.](([a-z]{2,3})|([a-z]{3}[.]{1}[a-z]{2}))$";

	$sent = 0;

	// Send mail IFF name, email and message are valid
	if (strlen($name) > 0 && eregi($pattern, $email) && strlen($message) > 0) {
		// attach headers
		$to      =  "tim.younger@bustedtubes.com";
		$subject = 	"IDF Feedback";
		$headers =  "From: $name <mailer@bustedtubes.com>" . "\r\n" .
		"Reply-To: 	$email" . "\r\n" .
		"X-Mailer: 	PHP/" . phpversion();
		if (mail($to,$subject,$message,$headers)) {
			$sent = 1;
		}
	}
	
	echo ($sent);
	
?>