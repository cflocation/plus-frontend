<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);
	require_once 'lib/swift_required.php';

	if(isset($_GET['sender'])){

		$sender = $_GET['sender'];
		$attachment = $_GET['report'];
	}

		$message = "The ratecard that was emailed to ShowSeeker by $sender,  has been processed, here is the PDF report.";

		$transport = Swift_SmtpTransport::newInstance('smtpout.secureserver.net', 465, "ssl")->setUsername('web@vastadsales.com')->setPassword('Vastcf01');

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance('ShowSeeker - Ratecard Report')->setFrom(array('support@showseeker.com' => 'ShowSeeker Ratecard Processor'))->setTo(array($sender))->setBody($message)->attach(Swift_Attachment::fromPath('reports/'.$attachment));

		$result = $mailer->send($message);

		$good = true;
		
		// The message
		$message = "The ratecard that was emailed to ShowSeeker has been processed, here is the PDF report.";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		mail('support@showseeker.com', 'ShowSeeker - Ratecard Report', $message);

?>