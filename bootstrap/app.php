<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Method\SettingMethod;

$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

$app = new \Slim\App(['settings' => SettingMethod::settingArr(getenv('settings'))]);

$container = $app->getContainer();

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection(SettingMethod::settingArr(getenv('db')));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

require_once __DIR__ . '/../app/routes.php';
