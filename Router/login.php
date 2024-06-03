<?php
namespace Router;

use App\Controller\Login as ControllerLogin;
use App\Controller\Pages\Pacientes;
use App\Http\Response;
use App\Http\Router;

class login
{

    /**
     * Prefixo da URL da rota
     *
     * @var string
     */
    private static $preUlr = null;

    /**
     * Inicializa as rotas relacionadas ao login.
     *
     * @param  Router $obRouter Objeto Router para definir as rotas.
     * @param  string $preUlr Prefixo da URL das rotas.
     * @return void
     */
    public static function init($obRouter, $preUlr = null)
    {
        // Define a rota POST para o login
        $obRouter->post(self::$preUlr . '/login', [
            'middlewares' => [
                "required-logout" // Middleware que exige logout para acessar a rota
            ],
            function ($request) {
                // Define a resposta para o endpoint de login
                return new Response(200, ControllerLogin\Login::setLogin($request));
            }
        ]);
        
        // Define a rota GET para o logout
        $obRouter->get(self::$preUlr . '/logoff', [
            'middlewares' => [
                "required-login" // Middleware que exige login para acessar a rota
            ],
            function ($request) {
                // Define a resposta para o endpoint de logout
                return new Response(200, ControllerLogin\Login::setLogout($request));
            }
        ]);
    }
}
