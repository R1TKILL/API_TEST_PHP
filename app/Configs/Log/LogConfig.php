<?php

namespace App\configLogs;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use App\SMTP\SendEmail;
use Exception;

class LogConfig { 

    // * Attributes:
    private Logger $logger;
    private SendEmail $email;


    // * Construct:
    public function __construct() {

        $this->email = new SendEmail();
        $this->logger = new Logger('app');
        $this->logger->pushHandler(
            new RotatingFileHandler(__DIR__ . "/../../../app/Logs/app.log", 180, $this->logger::DEBUG),
        );

    }


    // * Methods:
    public function alertDevTeam(string $type, string $message, string $error_line, string $date_time, string $detailedMessage): void {

        try {

            match ($type) {
                'ERROR' => $this->logger->error($detailedMessage),
                'CRITICAL' => $this->logger->critical($detailedMessage),
                default => $this->logger->error($detailedMessage),
            };
    
            $body_email = file_get_contents(__DIR__ . '/../../../app/Utils/email_pages/error_notify.html');
            $body_email = str_replace(
                // * fields, keys, insert_file
                ['{{mensagem_erro}}', '{{linha_erro}}', '{{data_hora}}'], 
                [$message, $error_line, $date_time], 
                $body_email
            );
    
            $this->email->send_email('antoniojunio402@gmail.com', 'Alerta da API PHP', $body_email);

        } catch (Exception $ex) {
            $this->appLogMsg('ERROR', $ex->getMessage());
        }

    }


    public function appLogMsg(string $type, string $message): void {

        try {

            // * Get the file and the line that called it from the log.
            $backtrace = debug_backtrace();
            $file = $backtrace[0]['file'];
            $line = $backtrace[0]['line'];
            $date_time = date("d/m/Y H:i:s");

            $detailedMessage = "{$message} | File: {$file} | Line: {$line}";

            match ($type) {
                // * Detalhes para depuração
                'DEBUG' => $this->logger->debug($detailedMessage),
                // * Informação sobre o processo
                'INFO' => $this->logger->info($detailedMessage),
                // * Atenção: uso elevado de memória
                'NOTICE' => $this->logger->notice($detailedMessage),
                // * Aviso: possível problema de configuração
                'WARNING' => $this->logger->warning($detailedMessage),
                // * Erro ao conectar ao banco de dados
                'ERROR' => $this->alertDevTeam($type, $message, $line, $date_time, $detailedMessage),
                // * Falha grave: serviço de autenticação inativo
                'CRITICAL' => $this->alertDevTeam($type, $message, $line, $date_time, $detailedMessage),
                // * Alerta: falha de segurança detectada
                'ALERT' => $this->logger->alert($detailedMessage),
                // * Emergência: sistema inoperante
                'EMERGENCY' => $this->logger->emergency($detailedMessage),
                default => $this->logger->error($detailedMessage)
            };

        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
        }

    }

}
