<?php 

declare(strict_types=1);
namespace App\SMTP;

require __DIR__ . '/../../../app/Configs/Env/env.php';

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;
use App\configLogs\LogConfig;
use Exception;

$logger = new LogConfig();

class SendEmail {

    // * Attributes:
    private PHPMailer $mail;
    private array $dict_ENV;


    // * Constructor:
    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->dict_ENV = require __DIR__ . '/../../../app/Helpers/LoadEnvironments.php';
    }


    function send_email(string $to, string $subject_email, mixed $body_email) {

        global $logger; // obs: Também poderia passar como dependency_injection, ai o B.O é resolvido.
    
        try {

            // * Config SMTP server
            $this->mail->isSMTP();
            $this->mail->Host = (string) $this->dict_ENV['SMTP_HOST'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = (string) $this->dict_ENV['SMTP_SENDER'];
            $this->mail->Password = (string) $this->dict_ENV['SMTP_PASS']; // * The pass for low security app.
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = (int) $this->dict_ENV['SMTP_PORT'];
    
            // * sender and email_destiny
            $this->mail->setFrom($this->dict_ENV['SMTP_SENDER'], 'API_PHP_System');
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

