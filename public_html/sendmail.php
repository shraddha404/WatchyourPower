<?php session_start(); 
if(isset($_POST['email'])) {

	
		$email_to = "esmi@prayaspune.org";
		
		$email_from = $_POST['email'];
		
		$email_subject = "Contact Us - Prayas, Energy Group";
		$email_message .= "Full Name: ".$_POST['name']."\n";

		$email_message .= "Email Address: ".$_POST['email']."\n";

		$email_message .= "Purpose: ".$_POST['purpose']."\n";
		$email_message .= "Comment: ".$_POST['message']."\n";
		
		// create email headers
		$headers = 'From: '.$email_from."\r\n".
		'Reply-To: '.$email_from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		mail($email_to, $email_subject, $email_message, $headers); 
	
		header("location:thanks.php");
	

}
?>
