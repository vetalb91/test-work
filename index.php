<?php
// Включаем режим строгой типизации
declare(strict_types=1);

// Подключаем файл реализующий автозагрузку
require_once __DIR__ . '/System/autoload.php';

//error_reporting(0);

// Запускаем приложение
System\App::run();