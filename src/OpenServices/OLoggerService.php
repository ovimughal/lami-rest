<?php

namespace Lamirest\OpenServices;

use GlobalProcedure\Service\EmailService;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\LogglyFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\Curl\Util;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\MailHandler;
use Monolog\Handler\MissingExtensionException;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\Slack\SlackRecord;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Utils;
use Lamirest\DI\ServiceInjector;
use Psr\Log\LogLevel;
use Throwable;

class OLoggerService
{
    private static Logger $logger;
    private static string $to = 'omughal@stc.in';
    private static string $from = 'omughal@stc.in';
    private static string $subject = 'Opulent Log';
    private static bool $sendMailFlag = false;
    private static bool $notifyTeams = true;
    private static bool $verifyHostSSL = true;
    private static string $teamsWebhook;
    private static bool$isJSON = true;
    private static array $logs;

    private static function initLogger()
    {
        self::$logs = ServiceInjector::oFileManager()->getOconfigManager()['logs'];
        $logChannel = self::$logs['log_channel']; //'opulent-logs';
        self::$logger = new Logger($logChannel);
       

        self::$to = self::$logs['log_notification_email_to'];
        self::$from = self::$logs['log_notification_email_from'];
        self::$subject = self::$logs['log_notification_email_subject'];
        self::$sendMailFlag = self::$logs['enable_email_notification'];
        self::$notifyTeams = self::$logs['enable_teams_notification'];
        self::$teamsWebhook = self::$logs['teams_channel_webhook'];
        self::$isJSON = self::$logs['log_enable_json_format'];
        self::$verifyHostSSL = self::$logs['verify_ssl_host'];

    }

    private static function LogStreamAndMailHandler(int $logLevel, ?array $extraInfo)
    {
        self::initLogger();

        $logFilename = self::$logs['log_filename'];
        $logFilePath = self::$logs['log_file_path'];
        $isAbsolutePath = self::$logs['is_absolute_path']; 
        $logPath = $isAbsolutePath ? $logFilePath . $logFilename : getcwd() . $logFilePath . $logFilename;
        $handler = new StreamHandler($logPath, $logLevel);
        $formatter = self::$isJSON ? new JsonFormatter() : new LineFormatter();
        $handler->setFormatter($formatter);

        if (self::$sendMailFlag) {
            $emailHandler = new LaminasMailHandler(self::$to, self::$subject, self::$from, $logLevel);
            // $emailHandler = new NativeMailerHandler(self::$to, self::$subject, self::$from, $logLevel);
            self::$logger->pushHandler($emailHandler);
        }

        if(self::$notifyTeams){
            $teamsHandler = new TeamsHandler(self::$teamsWebhook, $logLevel, true, self::$verifyHostSSL);
            self::$logger->pushHandler($teamsHandler);
        }

        self::$logger->pushHandler($handler);

        $oOrmService = ServiceInjector::oOrm();
        $em = $oOrmService->getDoctObjMngr();
        $db = $em->getConnection()->getDatabase();
        $extraInfo['dbContext'] = $db;
        // If exception occures at Login level then we won't have the organization id
        $extraInfo['organization'] = ServiceInjector::oJwtizer()->organizationId();
        $extraInfo['user'] = ServiceInjector::oJwtizer()->getUserInfo()['userName'];

        self::$logger->pushProcessor(function ($record) use ($extraInfo) {
            $record['extra'] = $extraInfo;
            return $record;
        });
    }

    public static function DEBUG(string $logMsg = 'Default Debug Msg', array $logContext = ['context' => 'Default Empty'], array $extraInfo = ['info' => 'Info Empty'])
    {
        try {
            self::LogStreamAndMailHandler(Logger::DEBUG, $extraInfo);
            self::$logger->debug($logMsg, $logContext);
        } catch (Throwable $th) {
            echo $th->getMessage();
            throw $th;
        }
        self::$logger->reset();
    }

    public static function INFO(string $logMsg = 'Default Info Msg', array $logContext = ['context' => 'Default Empty'], array $extraInfo = ['info' => 'Info Empty'])
    {
        try {
            self::LogStreamAndMailHandler(Logger::INFO, $extraInfo);
            self::$logger->info($logMsg, $logContext);
        } catch (Throwable $th) {
            echo $th->getMessage();
            throw $th;
        }
        self::$logger->reset();
    }

    public static function NOTICE(string $logMsg = 'Default Notice Msg', array $logContext = ['context' => 'Default Empty'], array $extraInfo = ['info' => 'Info Empty'])
    {
        try {
            self::LogStreamAndMailHandler(Logger::NOTICE, $extraInfo);
            self::$logger->notice($logMsg, $logContext);
        } catch (Throwable $th) {
            echo $th->getMessage();
            throw $th;
        }
        self::$logger->reset();
    }

    public static function WARNING(string $logMsg = 'Default Warning Msg', array $logContext = ['context' => 'Default Empty'], array $extraInfo = ['info' => 'Info Empty'])
    {
        try {
            self::LogStreamAndMailHandler(Logger::WARNING, $extraInfo);
            self::$logger->warning($logMsg, $logContext);
        } catch (Throwable $th) {
            echo $th->getMessage();
            throw $th;
        }
        self::$logger->reset();
    }

    public static function ERROR(string $logMsg = 'Default Error Msg', array $logContext = ['context' => 'Default Empty'], array $extraInfo = ['info' => 'Info Empty'])
    {
        try {
            self::LogStreamAndMailHandler(Logger::ERROR, $extraInfo);
            self::$logger->error($logMsg, $logContext);
        } catch (Throwable $th) {
            echo $th->getMessage();
            throw $th;
        }
        self::$logger->reset();
    }

    public static function CRITICAL(string $logMsg = 'Default Critical Msg', array $logContext = ['context' => 'Default Empty'], array $extraInfo = ['info' => 'Info Empty'])
    {
        try {
            self::LogStreamAndMailHandler(Logger::CRITICAL, $extraInfo);
            self::$logger->critical($logMsg, $logContext);
        } catch (Throwable $th) {
            echo $th->getMessage();
            throw $th;
        }
        self::$logger->reset();
    }

    public static function ALERT(string $logMsg = 'Default Alert Msg', array $logContext = ['context' => 'Default Empty'], array $extraInfo = ['info' => 'Info Empty'])
    {
        try {
            self::LogStreamAndMailHandler(Logger::ALERT, $extraInfo);
            self::$logger->alert($logMsg, $logContext);
        } catch (Throwable $th) {
            echo $th->getMessage();
            throw $th;
        }
        self::$logger->reset();
    }

    public static function EMERGENCY(string $logMsg = 'Default Emergency Msg', array $logContext = ['context' => 'Default Empty'], array $extraInfo = ['info' => 'Info Empty'])
    {
        try {
            self::LogStreamAndMailHandler(Logger::EMERGENCY, $extraInfo);
            self::$logger->emergency($logMsg, $logContext);
        } catch (Throwable $th) {
            echo $th->getMessage();
            throw $th;
        }
        self::$logger->reset();
    }
}

class LaminasMailHandler extends MailHandler
{
    private string $to;
    private string $subject;
    private string $from;

    public function __construct(string $to, string $subject, string $from, int $level)
    {
        parent::__construct($level);
        $this->to = $to;
        $this->subject = $subject;
        $this->from = $from;
    }

    public function send(string $content, array $records): void
    {
        $emailTo = $this->to;
        $emailCc = null;
        $emailSubject = $this->subject;
        $mailBodyTemplate = $content;
        $replyTo = null;
        $attachments = [];
        $fileName = null;
        $emailBcc = null;
        $emailFrom = $this->from;
        $fromName = 'OLogger Service';

        EmailService::sendEmail(
            $emailTo,
            $emailCc,
            $emailSubject,
            $mailBodyTemplate,
            $replyTo,
            $attachments,
            $fileName,
            $emailBcc,
            $emailFrom,
            $fromName
        );
    }
}

class TeamsHandler extends AbstractProcessingHandler
{
    private string $webhookUrl;
    private bool $verifyHostSSL;

    public function __construct(string $webhookUrl, $level = Logger::DEBUG, bool $bubble = true, $verifyHostSSL = true)
    {
        $this->setFormatter(new \Monolog\Formatter\HtmlFormatter());
        $this->webhookUrl = $webhookUrl;
        $this->verifyHostSSL = $verifyHostSSL;
        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        $ch = curl_init($this->webhookUrl);

        
        $payload = json_encode([
            'text' => $record['formatted'],
        ]);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        // This will supress the unwanted output of curl which is
        // either 1(true) or 0(false)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(!$this->verifyHostSSL){
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }

        // You might want to add more cURL options based on your needs

        curl_exec($ch);
        curl_close($ch);
    }
}
