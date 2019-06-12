<?php
use App\Kernel\App;

require 'kernel.php';

session_start();

$app = new App([
    'settings' => require config_path() . '/app.php'
]);
// errors

error_reporting(- 1);

ini_set('error_log', storage_path() . '/logs/php-SlimV3' . date('Y-m-d') . '.log');

$app->registerServices();

$app->registerAppMiddlewares();

require app_path() . '/Routes/app.php';

$app->run();
