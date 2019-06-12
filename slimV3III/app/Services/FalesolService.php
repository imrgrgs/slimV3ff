<?php
namespace App\Services;

use App\Kernel\ServiceInterface;
use LessQL\Database;

class FalesolService implements ServiceInterface
{

    public function name()
    {
        return 'falesol';
    }

    public function register()
    {
        return function ($container) {
            $settings = $container->settings['falesol'];
            $dbconf = $settings["mySql"];

            $pdo = "";

            // Create PDO
            if ('' != $dbconf['table']) {

                $driver = $dbconf["driver"];
                $host = $dbconf["hostname"];
                $port = $dbconf["port"];
                $user = $dbconf["username"];
                $pass = $dbconf["password"];
                $dbname = $dbconf["database"];
                $autocommit = $dbconf['autocommit'];
                $charset = $dbconf["charset"];
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
            }
            $falesol = new Database($pdo);

            unset($container, $settings);

            return $falesol;
        };
    }
}

