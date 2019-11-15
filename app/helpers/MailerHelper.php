<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class MailerHelper {

    public function sendMail($recipient, $subject, $body) {
            $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 1;                      
            $mail->isSMTP();                       
            $mail->Host       = 'smtp.gmail.com';   
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'tls';                
            $mail->Username   = 'pswisaproject@gmail.com';  
            $mail->Password   = 'TvojaMama!';    
            $mail->SMTPSecure = "tls";  
            $mail->Port       = 587;       
            $mail->authenthication = false; 

            //Recipients
            $mail->setFrom('pswisaproject@gmail.com', 'PSW/ISA Project');
            $mail->addAddress($recipient);     // Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            // $mail->AltBody = $body;

            $mail->send();
            echo 'Mail has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}