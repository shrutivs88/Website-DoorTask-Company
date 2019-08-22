<?php

{
	    $name=$_POST['name'];
		$email=$_POST['email'];
		$phone=$_POST['phone'];
		$msg=$_POST['msg'];
		$email2='info@doortask.com';
		$recipient="info@doortask.com"; // Receiver Email ID, Replace with your email ID
		$subject='Contact Query from Website';
		$body1="Dear Admin,"."\n\n"."A user ".$name." is submitted contact form at DoorTask Technologies Website. Below are the details:"."\n";
		$body2="\n\n"."Thanks and Regards,"."\n"."DoorTask Technologies Pvt Ltd"."\n"."Phone: +91-7401010151"."\n"."Email:info@doortask.com";
		$message=$body1."\n"."Name : ".$name."\n"."Phone : ".$phone."\n"."E-mail : ".$email."\n"."Query :".$msg."\n".$body2;
		$headers="From: ".$email2;
		$subject2='Thank you for contacting us | DoorTask Technologies Pvt Ltd';
		$body11="Dear ".$name.","."\n\n"."Thank you for contacting us."."\n\n"."Your contact request has been received and our support team will get back to you soon. Your submitted details are :"."\n";
		$message2=$body11."\n"."Name : ".$name."\n"."Phone : ".$phone."\n"."E-mail : ".$email."\n"."Query :" .$msg."\n".$body2;

			if(mail($recipient, $subject, $message, $headers))
            {
				mail($email, $subject2, $message2, $headers);
                 header("Location:contact.php");
            }
}
?>