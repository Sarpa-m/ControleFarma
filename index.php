<?php
require_once __DIR__ . "/vendor/autoload.php";

// Carrega as variáveis de ambiente
App\Utils\Environment::load(__DIR__);

// Configuração para exibição de erros em ambiente de desenvolvimento
if ($_SERVER["REMOTE_HOST"] == "192.168.41.4") {
    error_reporting(E_ALL);
}

ini_set("display_errors", 1);

use App\Utils\View;
use App\Http\Middleware;
use App\Http\Router as HttpRouter;
use App\Utils\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;
use App\Model\LdapAD\LDAPModel as AdLDAPConnection;

// Define a URL base do projeto
define("URL", getenv("URL"));
define("DIR", __DIR__);

// Inicia o Router
$obRouter = new HttpRouter(URL);

// Define a URL onde o usuário está
define('URLc', $obRouter->getcurrentUrl());

// Configura as classes de rota
$obRouter->setRoutes([
    Router\pages::class,
    Router\login::class,
    Router\pacientes::class,
], $obRouter);

// Configura o banco de dados
Database::config(
    getenv("DB_HOST"),
    getenv("DB_NAME"),
    getenv("DB_USER"),
    getenv("DB_PASS"),
    getenv("DB_PORT")
);

// Configura a conexão LDAP
AdLDAPConnection::Config(
    getenv("LDAP_AD_HOST"),
    getenv("LDAP_AD_PORT"),
    getenv("LDAP_AD_USER"),
    getenv("LDAP_AD_PASS")
);

// Define o valor padrão das variáveis da View
View::init([
    "URL"   => URL,
    'URLc'  => URLc,
    'title' => 'Controle Farma'
]);

// Mapeia os middlewares
MiddlewareQueue::setMap([
    'Maintenance'       => Middleware\Maintenance::class,
    'required-logout'   => Middleware\RequireLogout::class,
    'required-login'    => Middleware\Requirelogin::class,
    'Cache'             => Middleware\Cache::class,
    'Api'               => Middleware\Api::class,
]);

// Define os middlewares padrões para todas as rotas
MiddlewareQueue::setDefault([
    'Maintenance'
]);

// Executa e envia a resposta da rota
$obRouter->run()->sendResponse();
