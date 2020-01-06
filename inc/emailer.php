<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

	// include_once('services/database.php');
	require_once '../lib/swift_required.php';


	if(isset($_POST['email'])){



		$email = $_POST['email'];
		$first = trim (ucfirst($_POST['first']));
		$last = trim($_POST['last']);
		$company = $_POST['company'];
		$website = $_POST['website'];
		$title = $_POST['title'];
		$phone = $_POST['phone'];
		$source = $_POST['source'];
		$name = $first ." ". $last;



	$smtp = Swift_SmtpTransport::newInstance('smtpout.secureserver.net', 465, "ssl")->setUsername('web@vastadsales.com')->setPassword('Vastcf01');
	$mailer = Swift_Mailer::newInstance($smtp);
	$message = Swift_Message::newInstance('ShowSeeker - More Information');

	$message
	  ->setTo(array(
		$email,
	  ))
	  ->setEncoder(Swift_Encoding::get8BitEncoding())
	  ->setCharset('utf-8')
	  ->setFrom(array('support@showseeker.com' => 'ShowSeeker - Support'))
	  ->setCc(array('support@showseeker.com' => 'ShowSeeker - Support'))
	  ->setBody(
		'<div style="font-family:Arial, Helvetica, sans-serif; font-size:13px;">Hello '.$first.', <br>Thank you for your interest in ShowSeeker<sup>®</sup></strong><br /><br /><strong>Someone will be in touch with you soon.</strong><br /><br />Here is the information that you have provided: <br />Name: '.$name.' <br />Email: '.$email.' <br />Phone #: '.$phone.' <br />Company: '.$company.' <br />Website: '.$website.' <br />Title: '.$title.' <br />Source: '.$source.' <br /><br />Thank you, <br>The ShowSeeker Team</strong></div>', 'text/html' );





	if ($mailer->send($message))
	{
			header( 'Location: ../thank-you.php' ) ;
	}
	else
	{
			header( 'Location: ../thank-you.php' ) ;
	}


}
?>