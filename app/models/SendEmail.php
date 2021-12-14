<?php

namespace app\models;

use app\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail {

    public static function send($email, $token) {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = Config::MAIL_HOST; //Set the SMTP server to send through
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = Config::MAIL_USER; //SMTP username
            $mail->Password = Config::MAIL_PASS; //SMTP password
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port = Config::MAIL_PORT; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(Config::MAIL_OFFICIAL_EMAIL, 'Admin');
            $mail->addAddress($email['email']); //Add a recipient
            $mail->addReplyTo(Config::MAIL_OFFICIAL_EMAIL, 'Forgot Password');

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = 'Forgot Password';
            $mail->Body = 
                        '
                            <h2>Hi <b>' . $email['username'] .'</b> !</h2>
                            <p>A request was called to reset the password on this email <b>' . $email['email'] .'</b>.</p>

                            <p>If you didn\'t request a password reset link, please ignore this email.</p>
                            <p>To reset your account password, click on the link below.</p>

                            <a href="' . ROOT_PATH . '/auth/' . $token . '/reset-password">Reset Password</a>
                        ';

            $mail->AltBody = 
                        '
                            Hi ' . $email['username'] .' !
                            A request was called to reset the password on this email ' . $email['email'] .'.

                            If you didn\'t request a password reset link, please ignore this email.
                            To reset your account password, click on the link below.

                            <a href="' . ROOT_PATH . '/auth/' . $token . '/reset-password">Reset Password</a>
                        ';

            $mail->send();
        } catch (Exception $e) {
            throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 500);
        }
    }
}