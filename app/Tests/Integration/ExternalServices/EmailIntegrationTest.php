<?php

namespace App\Tests\Integration\ExternalServices;

require './app/Configs/Env/env.php';

use PHPUnit\Framework\TestCase;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailIntegrationTest extends TestCase {

    private $mailer;

    protected function setUp(): void {

        // * Config the PHPMailer instance:
        $this->mailer = new PHPMailer(true);

        // * Basics config of SMTP server:
        $this->mailer->isSMTP();
        $this->mailer->Host = (string) $_ENV['SMTP_HOST']; // * False service of SMTP for tests.
        $this->mailer->SMTPAuth = true;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = (int) $_ENV['SMTP_PORT'];

    }


    public function testEmailSendSuccess() {   
        try {

            // * Specifics configs of test: 
            $this->mailer->Username = (string) $_ENV['SMTP_SENDER'];
            $this->mailer->Password = (string) $_ENV['SMTP_PASS'];
            $this->mailer->addAddress((string) $_ENV['SMTP_DEV_TEAM']);
            $this->mailer->setFrom((string) $_ENV['SMTP_SENDER'], 'API_PHP_System');
            $this->mailer->Subject = 'Test Email';
            $this->mailer->Body = 'This is a test email.';

            // * Send and verify:
            $result = $this->mailer->send();
            $this->assertTrue($result, 'Send Email with success!');

        } catch (Exception $e) {
            $this->fail('Fail in trying of send email: ' . $e->getMessage());
        }
    }


    public function testEmailSendFailure() {

        // * Specifics configs for simulate the fail:
        $this->mailer->Username = (string) $_ENV['SMTP_SENDER'];
        $this->mailer->Password = '1234'; // * intentional error!
        $this->mailer->addAddress((string) $_ENV['SMTP_DEV_TEAM']);
        $this->mailer->setFrom((string) $_ENV['SMTP_SENDER'], 'API_PHP_System');
        $this->mailer->Subject = 'Test Email Failure';
        $this->mailer->Body = 'This is a failed email test.';

        // * Test the fail in send:
        $this->expectException(Exception::class);
        $this->mailer->send();

    }

}
