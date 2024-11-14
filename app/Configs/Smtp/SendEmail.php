<?php 

namespace App\SMTP;

require './app/Configs/Env/env.php';

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;
use App\configLogs\LogConfig;
use Exception;

$logger = new LogConfig();

class SendEmail {

    // * Attributes:
    private PHPMailer $mail;


    // * Constructor:
    public function __construct() {
        $this->mail = new PHPMailer(true);
    }


    function send_email(string $to, string $subject_email, $body_email) {

        global $dict_ENV;
        global $logger; // obs: também poderia passar como dependecy_injection, ai resolve o b.o
    
        try {

            // * Config SMTP server
            $this->mail->isSMTP();
            $this->mail->Host = (string) $dict_ENV['SMTP_HOST'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = (string) $dict_ENV['SMTP_SENDER'];
            $this->mail->Password = (string) $dict_ENV['SMTP_PASS'];
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = (int) $dict_ENV['SMTP_PORT'];
    
            // * sender and email_destiny
            $this->mail->setFrom($dict_ENV['SMTP_SENDER'], 'API_PHP_System');
            $this->mail->addAddress($to);
    
            // * Email content
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject_email;
            $this->mail->Body = $body_email;
    
            // * Send
            $this->mail->send();
            $logger->appLogMsg('INFO', 'Email service worked successfully.');

        } catch (PHPMailerException $mail_ex) {
            $logger->appLogMsg('ERROR', $mail_ex->getMessage());
        } catch (Exception $ex) {
            $logger->appLogMsg('ERROR', $ex->getMessage());
        }
    }

}

