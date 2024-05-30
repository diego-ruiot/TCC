<?php

namespace Tcc\App;
include(__DIR__."/router.php");

use \Tcc\App\Bases\BaseModel;
use \Tcc\App\Route;
use \Tcc\Controllers\TrabalhoController;
use Tcc\Controllers\UserController;

BaseModel::connectDb('mysql:host=127.0.0.1;dbname=repositorio_tcc', 'root', '');

Route::add('/', function() {
  TrabalhoController::index();
}, 'get');

Route::add('/results', function() {
  TrabalhoController::results();
}, 'get');

Route::add('/search', function() {
  TrabalhoController::advanced_search();
}, 'get');

Route::add('/arquivos/([A-Za-z0-9.]*)', function($file) {
  TrabalhoController::view_file($file);
}, 'get');

Route::add('/upload', function() {
  TrabalhoController::upload();
}, 'get');

Route::add('/upload', function() {
  TrabalhoController::insert();
}, 'post');

Route::add('/login', function() {
  UserController::login();
}, 'get');

Route::add('/login', function() {
  UserController::post_login();
}, 'post');

Route::add('/register', function() {
  UserController::register();
}, 'get');

Route::add('/register', function() {
  UserController::post_register();
}, 'post');

Route::add('/logout', function() {
  UserController::logout();
}, 'get');

Route::add('/my-files', function() {
  UserController::my_files();
}, 'get');

// Run the router
Route::run('/');