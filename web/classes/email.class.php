<?php

		// Prepare basedir
		if (substr($global_basedir,0,-4) !='web/') {
			$global_basedir = $global_basedir.'web/';
		}
		
		// Initiate dates
		$pdate = date($general['dateformat'],strtotime($_SESSION['selectedDate']));
		$sdate = '';
		$txt_date = $pdate;
		
		// background color for email
		$bgcolor = ($general['contactform_color_scheme'] == '') ? "#545454" : $general['contactform_color_scheme'];
		
		// Get property details
		$property = querySQL('property_info');
		
	if ( $_POST['recurring_dbdate']!="" && strtotime($_POST['recurring_dbdate'])>strtotime($_SESSION['selectedDate']) ) {
		$sdate = date($general['dateformat'],strtotime($_POST['recurring_dbdate']));
		$txt_date .= " - ".$sdate;
	}

	// Email sender & receiver
	$to_guest = $_POST['reservation_guest_email'];
	$to_admin = $_SESSION['selOutlet']['confirmation_email'];
	$from = html_entity_decode($property['name'])." <".$_SESSION['selOutlet']['confirmation_email'].">";
	//$bcc = html_entity_decode($property['name'])." <".$_SESSION['selOutlet']['confirmation_email'].">";

	// To send HTML mail, the Content-type header must be set
	$header_guest  = 'MIME-Version: 1.0' . "\r\n";
	$header_guest .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$header_guest .= 'From: ' . $from . "\r\n";
	// Additional headers
	$header_admin = 'From: ' . $from . "\r\n";
	
	//$headers .= 'Bcc: ' . $bcc . "\r\n";

	// Subject of email
        if ( $_POST['email_type'] == 'en' ) {
		$subject = html_entity_decode(_email_subject_en." ".$_SESSION['selOutlet']['outlet_name']);
	}else{
		$subject = html_entity_decode(_email_subject." ".$_SESSION['selOutlet']['outlet_name']);
	}
	
	//Salutation
	if ( $_POST['email_type'] == 'en' ) {
		switch ($_POST['reservation_title']) {
			case 'W':
				$salut = _dear_mrs_en." ".$_POST['reservation_guest_name'];
				break;	
			case 'F':
				$salut = _dear_family_en." ".$_POST['reservation_guest_name'];
				break;
			case 'C':
				$salut = _dear_sirs_and_madams_en." ".$_POST['reservation_guest_name'];
				break;
			default:
				$salut = _dear_mr_en." ".$_POST['reservation_guest_name'];	
		}
	}else{
		switch ($_POST['reservation_title']) {
			case 'W':
				$salut = _dear_mrs." ".$_POST['reservation_guest_name'];
				break;	
			case 'F':
				$salut = _dear_family." ".$_POST['reservation_guest_name'];
				break;
			case 'C':
				$salut = _dear_sirs_and_madams." ".$_POST['reservation_guest_name'];
				break;
			default:
				$salut = _dear_mr." ".$_POST['reservation_guest_name'];	
		}
	}
	
	// prepare logo file
	$logo = ($property['logo_filename']=='') ? 'logo.png' : $property['logo_filename'];
	$logo = substr($global_basedir,0,-4).'uploads/logo/'.$logo;
	
	// prepare text
	if ( $_POST['email_type'] == 'en' ) {
		$text = _email_confirmation_en;
		$descr = 'outlet_description_en';
	}else{
		$text = _email_confirmation;
		$descr = 'outlet_description';
	}
	
	$plain_text  = $salut.",\r\n\r\n";
	$plain_text .= sprintf( $text , $_SESSION['selOutlet']['outlet_name'], $_POST['reservation_pax'], $txt_date, formatTime($_POST['reservation_time'],$general['timeformat']), $_SESSION['booking_number'], $prop_name." Team"  );
	$plain_text  = nl2br($plain_text);
	
	$message  = $salut.",<br/><br/>";
	$message .= sprintf( $text , $_SESSION['selOutlet']['outlet_name'], $_POST['reservation_pax'], $txt_date, formatTime($_POST['reservation_time'],$general['timeformat']), '<strong>'.$_SESSION['booking_number'].'</strong>', $property['name']." Team"  );
	$res_details = formatTime($_POST['reservation_time'],$general['timeformat'])." "._for_." "._phone.": ".$_POST['reservation_guest_phone']." /"._note.": \"".$_POST['reservation_notes']."\"";
	$message .= sprintf( $text , $_SESSION['selOutlet']['outlet_name'], $_POST['reservation_pax'], $txt_date, formatTime($_POST['reservation_time'],$general['timeformat']), '<strong>'.$_SESSION['booking_number'].'</strong>', $property['name']." Team"  );
	
	// ===============
	// Email template
	// ===============
	$html_text = '
		<html>
		<head>

			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<title> '._confirmation_email.' </title>



		</head><body style="font-size: 12px; margin: 0; padding: 0; line-height: 22px; font-family: Arial, sans-serif; color: #555555; width: 100%;" bgcolor="'.$bgcolor.'">

		<!-- WRAPPER TABLE -->
		<table cellspacing="0" style="font-size: 12px; line-height: 22px; font-family: Arial, sans-serif; color: #555555; table-layout: fixed;" width="100%" cellpadding="0"><tr><td style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" bgcolor="#545454"> 	
		<!-- ///////////////////////////////////// NEWSLETTER CONTENT  /////////////////////////////////// -->		

			<br>	

			<p align="center" style="font-size: 12px; margin: 0 0 12px; padding: 0; line-height: 22px; font-family: Arial, sans-serif; color: #999999; text-align: center;"></p>

		<!-- ////////////////////////////////// START MAIN CONTENT WRAP ////////////////////////////////// -->	
			<table rules="none" cellspacing="0" border="0" frame="border" align="center" style="border-color: #E4E2E4 #E4E2E4 #E4E2E4 #E4E2E4; font-size: 12px; border-collapse: collapse; background-color: #ffffff; line-height: 22px; font-family: Arial, sans-serif; color: #555555; border-spacing: 0; border-style: solid solid solid solid; border-width: 10px 0px 0px 0px;-moz-border-radius:7px;-webkit-border-radius:7px;" width="600" cellpadding="0" bgcolor="#ffffff">
			<tr><td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

		<!-- ////////////////////////////////// START HEADER ////////////////////////////////////////////  -->

				<br><br>

				<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="540" cellpadding="0">
					<tr>
						<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="260">

<div style="background-image: url('.$logo.'); display: block; background-position:center center; background-repeat: no-repeat; width: 250px; height: 80px; display: block;"></div>

						</td>
						<td style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="20">&nbsp;</td><!-- spacer -->
						<td align="right" valign="top" style="font-size: 12px; line-height: 16px; font-family: Arial, sans-serif; color: #555555;" width="260">

							'._website.'
							<br><b style="font-weight: bold;"><a href="'.$property['website'].'" style="color: #3279BB; text-decoration: underline;">'.$property['website'].'</a></b>

						</td>
					</tr>
				</table>

				<img src="'.$global_basedir.'images/email/divider-600x61.gif" border="0" height="61" alt="" style="border: none; display: block;" width="600" />

		<!-- ////////////////////////////////// END HEADER /////////////////////////////////////////////// -->

			</td></tr>	
			<tr><td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

		<!-- ////////////////////////////////// START MAIN CONTENT. ADD MODULES BELOW //////////////////// -->

				<!-- Module #1 | 1 col, 540px -->
				<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="540" cellpadding="0">
					<tr>

						<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

							<br/>'.$message.'<br/><br/>

						</td>

					</tr>	
				</table>
				<!-- End Module #1 -->

		<!-- ////////////////////////////////// END MAIN CONTENT ///////////////////////////////////////// -->

			</td></tr>
			<tr><td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">

		<!-- ////////////////////////////////// START FOOTER ///////////////////////////////////////////// -->

				<img src="'.$global_basedir.'images/email/divider-600x31-2.gif" border="0" height="31" alt="" style="border: none; display: block;" width="600" />

				<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="600" cellpadding="0" bgcolor="#f0f0f0">
					<tr>
						<td valign="top" style="font-size: 12px; border-top-color: #D4D6D4; line-height: 22px; font-family: Arial, sans-serif; color: #555555; border-top-style: solid; border-top-width: 1px;">

							<img src="'.$global_basedir.'images/email/footer-divider-600x16.gif" border="0" height="16" alt="" style="border: none; display: block;" width="600" />

							<!-- company info + subscription -->
							<table cellspacing="0" align="center" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;" width="540" cellpadding="0">
								<tr>
									<td valign="top" style="color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 22px;">
									
										   <b style="font-weight: bold;">© '.$property['name'].'</b>, '.$property['street'].', '.$property['city'].', '.$property['zip'].'<br/> T: '.$property['phone'].' |
											E: <a href="mailto:'.$property['email'].'" style="color: #3279BB; text-decoration: underline;">'.$property['email'].'</a> | 
										<a href="http://www.myseat.us/terms.php" style="color: #3279BB; text-decoration: underline;">Terms & Conditions</a>
										<a href="http://www.myseat.us/privacy.php" style="color: #3279BB; text-decoration: underline;">Privacy Policy</a>
									</td>
								</tr>	
							</table>
							<!-- end company info + subscription -->

							<img src="'.$global_basedir.'images/email/footer-divider-600x31-2.gif" border="0" height="31" alt="" style="border: none; display: block;" width="600" />

						</td>
					</tr>
				</table>

		<!-- ////////////////////////////////// END FOOTER /////////////////////////////////////////////// -->

			</td></tr>
			</table>

		<!-- ////////////////////////////////// END MAIN CONTENT WRAP //////////////////////////////////// -->

			<br><br><br>

		<!-- ///////////////////////////////////// END NEWSLETTER CONTENT  /////////////////////////////// -->			
		</td></tr></table><!-- END WRAPPER TABLE -->

		</body>
		</html>';
	
	//*** Outlet Admin notification email text
	// if the line breaks are not working please try "\r\n" instead of "\n"
	$notification_text  = _new_entry.".\n\n";
	$notification_text  .= _booknum.": ".$_SESSION['booking_number']."\n";
	$notification_text  .= _outlets.": ".$_SESSION['selOutlet']['outlet_name']."\n";
	$notification_text  .= _date.": ".date($general['dateformat'],strtotime($_POST['reservation_date']))."\n";
	$notification_text  .= _time.": ".formatTime($_POST['reservation_time'],$general['timeformat'])."\n";
	$notification_text  .= _guest_name.": ".$_POST['reservation_guest_name']."\n";
	$notification_text  .= _pax.": ".$_POST['reservation_pax']."\n";
	$notification_text  .= _phone_room.": ".$_POST['reservation_guest_phone']."\n";
	$notification_text  .= _email.": ".$_POST['reservation_guest_email']."\n";
	$notification_text  .= _author.": ".$_POST['reservation_booker_name']."\n";
	$notification_text  .= _note.": ".$_POST['reservation_notes']."\n\n";
	$notification_text  .= _created.": ".date($general['dateformat'],time())."\n";


	//***
	//SEND OUT MAIL

	//switch LOCAL/SMTP email use
	if($settings['emailSMTP'] == 'SMTP'){
		
		//PHPMailer
		$mail = new PHPMailer();					   // Instantiate your new class
		$mail->IsSMTP(); 							   // telling the class to use SMTP
		$mail->Host          = $settings['emailHost'];
		$mail->SMTPAuth      = true;                   // enable SMTP authentication
		$mail->SMTPKeepAlive = true;                   // SMTP connection will not close after each email sent
		$mail->CharSet  	 = 'utf-8'; 			   // sets Encoding
		//$mail->SMTPSecure	 = 'ssl'; 				   //  Used instead of TLS when only POP mail is selected
		

		$mail->Host          = $settings['emailHost']; // sets the SMTP server
		$mail->Port          = $settings['emailPort']; // set the SMTP port
		$mail->Username      = $settings['emailUser']; // SMTP account username
		$mail->Password      = $settings['emailPass']; // SMTP account password

		// email message
		$mail->SetFrom($to_admin, html_entity_decode($property['name']));
		$mail->AddReplyTo($to_admin, html_entity_decode($property['name']));
		$mail->Subject       = $subject;
		$mail->AltBody       = $plain_text; // optional, comment out and test.
		$mail->MsgHTML($html_text);
		$mail->AddAddress($to_guest, _pax);

		  if(!$mail->Send()) {
		    echo "Mailer Error (" . str_replace("@", "&#64;", $row["email"]) . ') ' . $mail->ErrorInfo . '<br />';
		  }
		  // Clear all addresses and attachments for next loop
		  $mail->ClearAddresses();
		
		  // Outlet Admin notification email
		 $mail->SetFrom($to_admin, html_entity_decode($property['name']));
		 $mail->AddReplyTo($to_admin, html_entity_decode($property['name']));
		 $mail->Subject = $subject;
		 $mail->Body 	= $notification_text;
		 $mail->AddAddress($to_admin, _reservations);
		  
		 if(!$mail->Send()) {
		    echo "Mailer Error (" . str_replace("@", "&#64;", $row["email"]) . ') ' . $mail->ErrorInfo . '<br />';
		 }
		 // Clear all addresses and attachments for next loop
		 $mail->ClearAddress();	
		
	} else{
		//PHP native
		mail( $to_guest, $subject, $html_text, $header_guest);
		// Outlet Admin notification email
		mail( $to_admin, $subject, $notification_text, $header_admin);
	}
		?>