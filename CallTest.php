<?php

set_include_path(__DIR__);
require 'vendor/autoload.php';
header("Access-Control-Allow-Origin: *");
header("content-type: application/json; charset=UTF-8");

// php -S 127.0.0.1:8080 CallTest.php
// 127.0.0.1:8080/getbalance

// TODO переименовать
use AppName\BalanceProcessor;
// для хранения маршрутов-роутов
use AppName\Route;
// для обаботки маршрутов-роутов
use AppName\Dispatcher;

Route::get('/getlist', 'BalanceProcessor@dBselectListUsers');
Route::get('/getbalance', 'BalanceProcessor@dBselect');



$requestUriPath  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$dispatcher = new Dispatcher();
print_r($dispatcher->dispatch($requestUriPath));
