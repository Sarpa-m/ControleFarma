<?php
require_once __DIR__ . "/vendor/autoload.php";

//CARRAA AS VARIAVES DE AMBIENTE
App\Utils\Environment::load(__DIR__);

if ($_SERVER["REMOTE_HOST"] == "192.168.41.4") {
   // if ($_SERVER["REMOTE_HOST"] == "192.168.255.2") {
    error_reporting(E_ALL); 
 
}
ini_set("display_errors", 1);

use App\Utils\View;
use App\Http\Middleware;
use App\Http\Router as HttpRouter;
use App\Utils\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;

use App\Model\LdapAD\LDAPModel as AdLDAPConnection;

//DEFINE A URL BASE DO PROJETO
define("URL", getenv("URL"));
define("DIR", __DIR__);


//INICIA O ROUTER
$obRouter = new HttpRouter(URL);

//DEFINE A URL ONDE O USÚARIO ESTA
define('URLc', $obRouter->getcurrentUrl());

//CONFIGURA AS CLASS DE ROTA
$obRouter->setRoutes([
    Router\pages::class,
    Router\login::class,
    Router\pacientes::class,
 
], $obRouter);

//CONFIGURA O BANCO DE DADOS
Database::config(
    getenv("DB_HOST"),
    getenv("DB_NAME"),
    getenv("DB_USER"),
    getenv("DB_PASS"),
    getenv("DB_PORT"),
);

AdLDAPConnection::Config(
    getenv("LDAP_AD_HOST"),
    getenv("LDAP_AD_PORT"),
    getenv("LDAP_AD_USER"),
    getenv("LDAP_AD_PASS"),
);


//DEFINE O VALOR PADÃO DAS VARIAVES DA VIEW
View::init([
    "URL"  => URL,
    'URLc' => URLc,
    'title' => 'Controle Farma'
]);

//MAPEIA OS MIDDLEWARE
MiddlewareQueue::setMap([
    'mentenance'            => Middleware\Mentenance::class,
    'required-logout' => Middleware\RequireLogout::class,
    'required-login'  => Middleware\Requirelogin::class,
    'Cache'                 => Middleware\Cache::class,

]);

//DEFINE OS MIDDLEWARES PADOES PARA TODAS AS ROTAS
MiddlewareQueue::setDefault([
    'mentenance'
]);


//EXECUTA E INFRUME A ROTA
$obRouter->run()->sendResponse();
