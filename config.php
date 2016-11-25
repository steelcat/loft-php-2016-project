<?php
// Получаем разделитель директорий
define('DS', '/');

// Получаем базовую папку сайта
define('BASE', str_replace(DIRECTORY_SEPARATOR, DS, __DIR__ . DS));

// Определяем папку для аплоада файлов пользователей
define('DIR_UPLOAD', BASE . 'upload' . DS);

// Определяем папки для шаблонов и кеша Twig
define('DIR_TEMPLATES', BASE . 'templates' . DS);
define('DIR_CACHE', BASE . 'cache' . DS);

// Загружаем библиотеки через Composer
require BASE . 'vendor' . DS . 'autoload.php';

// Устанавливаем режим дебага
define('APP_DEBUG', true);

// Устанавливаем режим кеширования
define('APP_CACHE', false);

// Устанавливаем параметры подключения к базе данных
const APP_DB = ['db_name' => 'project', 'db_user' => 'root', 'db_password' => ''];
