<?php

use Exception;
use Library\PHPMailer\PHPMailer;

/**
 * Description of Mail
 *
 * @author Satjan
 */
class LvgMail {

    CONST MAIL_HOST = 'smtp.gmail.com';
    CONST MAIL_FROM = 'mail.mn';
    CONST MAIL_SMTP_SECURE = 'tls';
    CONST MAIL_USERNAME = 'gmail@gmail.com';
    CONST MAIL_PASSWORD = 'password';

    public static function send($emails, $title, $body) {

        require($_SERVER['DOCUMENT_ROOT'] . "/../Library/PHPMailer/class.phpmailer.php");

        try {

            $mail = new PHPMailer();

            $mail->IsSMTP();
            $mail->Host = self::MAIL_HOST;
            $mail->Port = 587;
            $mail->SMTPSecure = self::MAIL_SMTP_SECURE;
            $mail->SMTPAuth = true;
            $mail->Username = self::MAIL_USERNAME;
            $mail->Password = self::MAIL_PASSWORD;
            $mail->From = self::MAIL_USERNAME;
            $mail->FromName = self::MAIL_FROM;
            $mail->SingleTo = true;

            foreach ($emails as $address) {
                if (Validator::isEmail($address)) {
                    $mail->AddAddress($address);
                }
            }

            $mail->WordWrap = 350;
            $mail->IsHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 600;

            $mail->Subject = $title;
            $mail->Body = $body;
            $mail->AltBody = "";

            if (!$mail->Send()) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }

}
