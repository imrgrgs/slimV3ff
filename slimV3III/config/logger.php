<?php
return [
    'name' => 'slimV3',
    'path' => storage_path() . '/logs/slimV3.log',
    'mySql' => [
        'logLevel' => 100,
        'driver' => 'mysql',
        'hostname' => '200.184.77.167',
        'port' => 3306,
        'username' => 'iomar',
        'password' => '33iomar33',
        'database' => 'bitsystem',
        'table' => 'slimV3_log',
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix' => '',
        'autocommit' => true
    ],
    'email' => [
        'logLevel' => 400,
        'host' => 'mail.bitgroup.com.br',
        'to' => 'suportedev@bitgroup.com.br',
        'from' => 'BitVoip@bitgroup.com.br',
        'smtpPort' => 465,
        'smtpSecure' => 'tls',
        'userName' => 'app@bitgroup.com.br',
        'userPassword' => 'xxbitxxgroupxx33'
    ]
];
