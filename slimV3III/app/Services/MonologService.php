<?php
namespace App\Services;

use Monolog\Logger;
use App\Kernel\ServiceInterface;
use Monolog\Processor\UidProcessor;
use Monolog\Handler\StreamHandler;
use MySQLHandler\MySQLHandler;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Handler\FingersCrossedHandler;

class MonologService implements ServiceInterface
{

    /**
     * Service register name
     */
    public function name()
    {
        return 'logger';
    }

    /**
     * Register new service on dependency container
     */
    public function register()
    {
        return function ($container) {
            $settings = $container->settings['logger'];
            $dbconf = $settings["mySql"];
            $emailconf = $settings['email'];

            $logger = new Logger($settings['name']);

            $logger->pushProcessor(new UidProcessor());

            $logger->pushHandler(new StreamHandler($settings['path'], Logger::DEBUG));

            // Create MysqlHandler
            if ('' != $dbconf['table']) {

                $driver = $dbconf["driver"];
                $host = $dbconf["hostname"];
                $port = $dbconf["port"];
                $user = $dbconf["username"];
                $pass = $dbconf["password"];
                $dbname = $dbconf["database"];
                $charset = $dbconf["charset"];
                $table = $dbconf['table'];
                $level = $dbconf['logLevel'];
                $autocommit = $dbconf['autocommit'];
                $dsn = "$driver:host=$host;port=$port;dbname=$dbname";
                $options = array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,

                    \PDO::ATTR_AUTOCOMMIT => $autocommit
                );
                if (version_compare(PHP_VERSION, '5.3.6', '<')) {
                    if (defined('\PDO::MYSQL_ATTR_INIT_COMMAND')) {
                        $options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . $charset;
                    }
                } else {
                    $dsn .= ';charset=' . $charset;
                }

                $pdo = new \PDO($dsn, $user, $pass, $options);

                $mySQLHandler = new MySQLHandler($pdo, $table, array(
                    'username',
                    'userid'
                ), $level);
                $logger->pushHandler($mySQLHandler);
            }

            /*
             * |===============================================================
             * | Email config setup logger
             * |===============================================================
             */

            if ('' != $emailconf['to']) {
                $transport = Swift_SmtpTransport::newInstance($emailconf['host'], $emailconf['smtpPort'], $emailconf['smtpSecure'])->setUsername($emailconf['userName'])
                    ->setPassword($emailconf['userPassword'])
                    ->setStreamOptions(array(
                    'ssl' => array(
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    )
                ));

                $mailer = Swift_Mailer::newInstance($transport);
                $project = 'SlimV3';
                $appName = 'App Slim V3';

                $message = Swift_Message::newInstance('[' . $project . ' Something wrong occurred!]')->setFrom(array(
                    $emailconf['from'] => $appName
                ))
                    ->setTo(array(
                    $emailconf['to'] => 'System'
                ))
                    ->setContentType("text/html");
                // $message->setBody('', 'text / html');
                $htmlFormatter = new HtmlFormatter();
                $mailHandler = new SwiftMailerHandler($mailer, $message, $emailconf['logLevel']);
                $mailHandler->setFormatter($htmlFormatter);

                $fingerCrossHandler = new FingersCrossedHandler($mailHandler, $emailconf['logLevel']);
                $logger->pushHandler($fingerCrossHandler);
            } // FIM Email config setup logger

            unset($container, $settings);

            return $logger;
        };
    }
}
