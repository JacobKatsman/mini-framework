<?php

set_include_path(__DIR__);
require 'vendor/autoload.php';
header("Access-Control-Allow-Origin: *");

// http://127.0.0.1:8080/showimage?file=photo/981243762.jpg
// http://127.0.0.1:8080/getform?file=https%3A%2F%2Fmayak.travel%2F

use AppName\BalanceProcessor;
// для хранения маршрутов-роутов
use AppName\Route;
// для обаботки маршрутов-роутов
use AppName\Dispatcher;

if (isset ($_REQUEST["file"]))
{
    $par = $_REQUEST["file"];
} else {
    $par = "";
}

// ****
// картиночный краулер. Собирает картинки с предложенного url
// не обрабатывает на своей стороне JS
// ****

Route::get('/getform', 'BalanceProcessor@requestForm' , $par);
Route::get('/showimage', 'RenderFileProcessor@getImagefile' , $par);
Route::get('/', 'BalanceProcessor@getUrl' , $par);

$requestUriPath  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$dispatcher = new Dispatcher();

print_r($dispatcher->dispatch($requestUriPath));


